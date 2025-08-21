<?php
require_role('admin');
$pdo = getPDO('btms_db');
// Ensure users table can auto increment
try { $col=$pdo->query("SHOW COLUMNS FROM users LIKE 'id'")->fetch(); if($col && stripos($col['Extra']??'', 'auto_increment')===false){ try { $pdo->exec("ALTER TABLE users MODIFY id INT NOT NULL AUTO_INCREMENT"); } catch(Throwable $e){} } } catch(Throwable $e){}
$ran = false; $summary = [];$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['confirm']) && $_POST['confirm']==='YA'){
  $ran = true;
  // Backup count old
  try { $oldCount = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(); } catch(Throwable $e){ $oldCount = 0; $errors[]='Gagal hitung user lama: '.$e->getMessage(); }
  try { $pdo->exec('DELETE FROM users'); $pdo->exec('ALTER TABLE users AUTO_INCREMENT=1'); } catch(Throwable $e){ $errors[]='Gagal menghapus user lama: '.$e->getMessage(); }
  // Collect all EmpNo
  $sourceRows = [];
  $hasEmployees=false; $hasMaster=false;
  try { $pdo->query('DESCRIBE employees'); $hasEmployees=true; } catch(Throwable $e){}
  try { $pdo->query('DESCRIBE employeemaster'); $hasMaster=true; } catch(Throwable $e){}
  if($hasEmployees){
    try { $sourceRows = $pdo->query("SELECT id, EmpNo FROM employees WHERE EmpNo IS NOT NULL AND EmpNo<>''")->fetchAll(PDO::FETCH_ASSOC); } catch(Throwable $e){ $errors[]='Gagal ambil employees: '.$e->getMessage(); }
  } elseif($hasMaster){
    try { $tmp = $pdo->query("SELECT EmpNo FROM employeemaster WHERE EmpNo IS NOT NULL AND EmpNo<>''")->fetchAll(PDO::FETCH_ASSOC); foreach($tmp as $r){ $sourceRows[]=['id'=>null,'EmpNo'=>$r['EmpNo']]; } } catch(Throwable $e){ $errors[]='Gagal ambil employeemaster: '.$e->getMessage(); }
  } else {
    $errors[]='Tidak ada tabel employees atau employeemaster';
  }
  $ins = $pdo->prepare('INSERT INTO users(username,password,role,employee_id,must_change_password) VALUES(?,?,"employee",?,1)');
  $created=0; $skipped=0;
  foreach($sourceRows as $r){
    $empno = trim($r['EmpNo']); if($empno===''){ $skipped++; continue; }
    $hash = password_hash($empno, PASSWORD_DEFAULT);
    try { $ins->execute([$empno,$hash,$r['id']]); $created++; } catch(Throwable $e){ $errors[]=$empno.': '.$e->getMessage(); }
  }
  // Admin
  try { $hashAdmin = password_hash('admin', PASSWORD_DEFAULT); $pdo->prepare('INSERT INTO users(username,password,role,must_change_password) VALUES("admin",?,"admin",1)')->execute([$hashAdmin]); } catch(Throwable $e){ $errors[]='Admin gagal dibuat: '.$e->getMessage(); }
  $summary = compact('oldCount','created','skipped');
}
?>
<div class="content-section fade-in">
  <h4 class="mb-3">Rebuild Semua User dari EmpNo</h4>
  <p class="text-muted small">Aksi ini akan MENGHAPUS semua user lama lalu membuat ulang user baru: username = EmpNo, password = EmpNo (hash). Ditambah akun admin (admin/admin). Wajib ganti password setelah login.</p>
  <?php if(!$ran): ?>
    <form method="post" onsubmit="return confirm('Yakin hapus semua user dan rebuild?');" class="mb-4">
      <div class="mb-2">
        <label class="form-label small">Ketik <strong>YA</strong> untuk konfirmasi:</label>
        <input type="text" name="confirm" class="form-control form-control-sm" required />
      </div>
      <button class="btn btn-danger"><i class="fas fa-recycle me-2"></i>Rebuild Users</button>
      <a href="index.php?page=employees/index" class="btn btn-outline-secondary ms-2">Kembali</a>
    </form>
  <?php else: ?>
    <div class="card"><div class="card-body p-3">
      <h6 class="mb-2">Hasil:</h6>
      <?php if($summary): ?>
        <ul class="small mb-2">
          <li>User lama (sebelum): <strong><?= (int)$summary['oldCount'] ?></strong></li>
          <li>User baru dibuat: <strong class="text-success"><?= (int)$summary['created'] ?></strong></li>
          <li>Skip (EmpNo kosong): <strong><?= (int)$summary['skipped'] ?></strong></li>
          <li>Akun admin ditambahkan: <strong>1</strong></li>
        </ul>
        <div class="alert alert-success small py-2">Selesai. Silakan login: admin / admin atau EmpNo / EmpNo.</div>
      <?php endif; ?>
      <?php if($errors): ?>
        <div class="alert alert-warning small mt-2 mb-0">
          <div class="fw-semibold mb-1">Error (<?= count($errors) ?>):</div>
          <div style="max-height:160px;overflow:auto;"><pre class="mb-0 small"><?= esc(implode("\n", $errors)) ?></pre></div>
        </div>
      <?php endif; ?>
      <a href="index.php?page=users/rebuild" class="btn btn-outline-primary btn-sm mt-3">Kembali</a>
    </div></div>
  <?php endif; ?>
</div>
