<?php
require_role('admin');
$pdo = getPDO();
// Ensure users.id auto increment
try { $col=$pdo->query("SHOW COLUMNS FROM users LIKE 'id'")->fetch(); if($col && stripos(($col['Extra']??''),'auto_increment')===false){ try { $pdo->exec("ALTER TABLE users MODIFY id INT NOT NULL AUTO_INCREMENT"); } catch(Throwable $e){} } } catch(Throwable $e){}
$msg=null; $err=null;
if($_SERVER['REQUEST_METHOD']==='POST' && verify_csrf()){
  $username=trim($_POST['username']??'');
  $password=trim($_POST['password']??'');
  $role=trim($_POST['role']??'employee');
  if(!in_array($role,['employee','manager','admin'],true)) $role='employee';
  $empNoMap = trim($_POST['empno_map']??''); // EmpNo to map to employee_id (optional)
  if($username==='' || $password===''){
    $err='Username & password wajib diisi';
  } else {
    try {
      // check existing
      $st=$pdo->prepare('SELECT id FROM users WHERE username=? LIMIT 1');
      $st->execute([$username]);
      if($st->fetch()){
        $err='Username sudah ada.';
      } else {
        $employeeId=null;
        if($empNoMap!==''){
          try { $s=$pdo->prepare('SELECT id FROM employees WHERE EmpNo=? LIMIT 1'); $s->execute([$empNoMap]); $row=$s->fetch(); if($row){ $employeeId=(int)$row['id']; } } catch(Throwable $e){}
        }
        $hash=password_hash($password,PASSWORD_DEFAULT);
        $ins=$pdo->prepare('INSERT INTO users(username,password,role,employee_id,must_change_password) VALUES(?,?,?,?,0)');
        $ins->execute([$username,$hash,$role,$employeeId]);
        $msg='User berhasil dibuat. Password sudah di-hash.';
      }
    } catch(Throwable $e){ $err=$e->getMessage(); }
  }
}
?>
<div class="content-section fade-in">
  <h4 class="mb-3">Tambah User Manual</h4>
  <p class="text-muted small">Gunakan form ini untuk menambahkan user spesifik dengan password yang langsung di-hash. Opsional: mapping ke karyawan melalui EmpNo.</p>
  <?php if($msg): ?><div class="alert alert-success py-2 small"><?= esc($msg) ?></div><?php endif; ?>
  <?php if($err): ?><div class="alert alert-danger py-2 small"><?= esc($err) ?></div><?php endif; ?>
  <form method="post" class="row g-3" autocomplete="off">
    <?= csrf_field(); ?>
    <div class="col-md-4">
      <label class="form-label small">Username <span class="text-danger">*</span></label>
      <input type="text" name="username" class="form-control form-control-sm" required />
    </div>
    <div class="col-md-4">
      <label class="form-label small">Password <span class="text-danger">*</span></label>
      <input type="text" name="password" class="form-control form-control-sm" required />
      <div class="form-text small">Akan di-hash dengan bcrypt.</div>
    </div>
    <div class="col-md-4">
      <label class="form-label small">Role</label>
      <select name="role" class="form-select form-select-sm">
        <option value="employee">Employee</option>
        <option value="manager">Manager</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label small">EmpNo (optional)</label>
      <input type="text" name="empno_map" class="form-control form-control-sm" placeholder="Isi untuk link ke employee" />
      <div class="form-text small">Jika diisi & ditemukan di tabel employees, akan set employee_id.</div>
    </div>
    <div class="col-12">
      <button class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan</button>
      <a href="index.php?page=employees/index" class="btn btn-outline-secondary btn-sm ms-2">Kembali</a>
    </div>
  </form>
</div>
