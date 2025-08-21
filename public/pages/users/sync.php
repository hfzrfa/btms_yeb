<?php
require_role('admin');
$pdo = getPDO();
// Ensure users table auto increment
try { $col=$pdo->query("SHOW COLUMNS FROM users LIKE 'id'")->fetch(); if($col && stripos(($col['Extra']??''),'auto_increment')===false){ try { $pdo->exec("ALTER TABLE users MODIFY id INT NOT NULL AUTO_INCREMENT"); } catch(Throwable $e){} } } catch(Throwable $e){}
$created = 0; $skipped=0; $errors=[]; $total=0;
$do = isset($_POST['do_sync']);
if($do){
  try {
    // fetch all employee ids & EmpNo from preferred source (employees table if exists else employeemaster)
    $source='employees';
    try { $pdo->query("DESCRIBE employees"); } catch(Throwable $e){ $source=null; }
    if(!$source){
      try { $pdo->query("DESCRIBE employeemaster"); $source='employeemaster'; } catch(Throwable $e){ $source=null; }
    }
    if(!$source) throw new Exception('Tidak ada tabel employees / employeemaster');
    $rows = $pdo->query("SELECT id, EmpNo FROM `$source` WHERE EmpNo IS NOT NULL AND EmpNo<>''")->fetchAll();
    $total = count($rows);
    $userStmt = $pdo->prepare('SELECT id FROM users WHERE username=? LIMIT 1');
    $insStmt  = $pdo->prepare('INSERT INTO users(username,password,role,employee_id) VALUES(?, ?, "employee", ?)');
    foreach($rows as $r){
      $empno = trim($r['EmpNo']); if($empno===''){ $skipped++; continue; }
      $userStmt->execute([$empno]);
      $existing = $userStmt->fetch();
      if($existing){ $skipped++; continue; }
      $hash = password_hash($empno, PASSWORD_DEFAULT);
      try { $insStmt->execute([$empno,$hash,$r['id']]); $created++; } catch(Throwable $e){ $errors[] = $empno . ': ' . $e->getMessage(); }
    }
  } catch(Throwable $e){ $errors[]=$e->getMessage(); }
}
?>
<div class="content-section fade-in">
  <h4 class="mb-3">Sinkronisasi User dari Employee</h4>
  <p class="text-muted small">Membuat akun user (username & password = EmpNo) untuk setiap karyawan yang belum punya akun. Password di-hash otomatis.</p>
  <form method="post" class="mb-4" onsubmit="return confirm('Lanjutkan proses sinkronisasi?');">
    <input type="hidden" name="do_sync" value="1" />
    <button class="btn btn-primary"><i class="fas fa-users-cog me-2"></i> Jalankan Sinkronisasi</button>
    <a href="index.php?page=employees/index" class="btn btn-outline-secondary ms-2">Kembali</a>
  </form>
  <?php if($do): ?>
  <div class="card"><div class="card-body p-3">
    <h6 class="mb-2">Hasil:</h6>
    <ul class="small mb-2">
      <li>Total karyawan dicek: <strong><?= (int)$total ?></strong></li>
      <li>User baru dibuat: <strong class="text-success"><?= (int)$created ?></strong></li>
      <li>Sudah ada (skip): <strong class="text-muted"><?= (int)$skipped ?></strong></li>
    </ul>
    <?php if($errors): ?>
      <div class="alert alert-warning small mb-2">
        <div class="fw-semibold mb-1">Error (<?= count($errors) ?>):</div>
        <div style="max-height:150px;overflow:auto;"><pre class="mb-0 small"><?= esc(implode("\n", $errors)) ?></pre></div>
      </div>
    <?php endif; ?>
    <div class="alert alert-info small mb-0">Selesai.</div>
  </div></div>
  <?php endif; ?>
</div>
