<?php
require_role('admin');
$pdo = getPDO('btms_db');
// Ensure auto increment for id
try { $col=$pdo->query("SHOW COLUMNS FROM users LIKE 'id'")->fetch(); if($col && stripos(($col['Extra']??''),'auto_increment')===false){ try { $pdo->exec("ALTER TABLE users MODIFY id INT NOT NULL AUTO_INCREMENT"); } catch(Throwable $e){} } } catch(Throwable $e){}
$msg=null; $err=null;
$identifier = trim($_POST['empno'] ?? ''); // can be username or EmpNo
if(isset($_POST['do']) && $identifier!==''){
  try {
    // find by username
    $stmt=$pdo->prepare('SELECT id, username, role FROM users WHERE username=? LIMIT 1');
    $stmt->execute([$identifier]);
    $user=$stmt->fetch();
    if(!$user){
      // try provision if employee exists
      $emp=null; $empId=null;
      if($identifier==='admin') {
        // Create admin user
        $hash=password_hash('admin',PASSWORD_DEFAULT); // default same string 'admin'
        $pdo->prepare("INSERT INTO users(username,password,role,must_change_password) VALUES('admin',?,'admin',1)")->execute([$hash]);
        $msg='User admin dibuat. Password = admin (harap segera diganti).';
      } else {
        try { $s=$pdo->prepare('SELECT id, EmpNo FROM employees WHERE EmpNo=? LIMIT 1'); $s->execute([$identifier]); $emp=$s->fetch(); if($emp) $empId=$emp['id']; } catch(Throwable $e){}
        if(!$emp){ try { $s=$pdo->prepare('SELECT EmpNo FROM employeemaster WHERE EmpNo=? LIMIT 1'); $s->execute([$identifier]); $emp=$s->fetch(); } catch(Throwable $e){} }
        if($emp){
          $hash=password_hash($identifier,PASSWORD_DEFAULT);
          $ins=$pdo->prepare('INSERT INTO users(username,password,role,employee_id,must_change_password) VALUES(?,?,"employee",?,1)');
          $ins->execute([$identifier,$hash,$empId]);
          $msg='User baru dibuat & password diset ke EmpNo.';
        } else {
          $err='Identifier tidak ditemukan (bukan user & bukan EmpNo).';
        }
      }
    } else {
      $newPlain = ($user['role']==='admin') ? 'admin' : $identifier; // reset admin to 'admin'
      $hash=password_hash($newPlain,PASSWORD_DEFAULT);
      $up=$pdo->prepare('UPDATE users SET password=?, must_change_password=1 WHERE id=?');
      $up->execute([$hash,$user['id']]);
      $msg='Password berhasil di-reset ke ' . htmlspecialchars($newPlain) . '.';
    }
  } catch(Throwable $e){ $err=$e->getMessage(); }
}
?>
<div class="content-section fade-in">
  <h4 class="mb-3">Reset Password User</h4>
  <p class="text-muted small">Atur ulang password menjadi sama dengan EmpNo (akan di-hash) dan wajib ganti saat login berikutnya.</p>
  <?php if($msg): ?><div class="alert alert-success py-2 small"><?= esc($msg) ?></div><?php endif; ?>
  <?php if($err): ?><div class="alert alert-danger py-2 small"><?= esc($err) ?></div><?php endif; ?>
  <form method="post" class="row g-2" onsubmit="return confirm('Reset password untuk EmpNo ini?');">
    <div class="col-auto">
  <input type="text" name="empno" class="form-control" placeholder="Username atau EmpNo (misal: admin / 2001785)" required />
    </div>
    <div class="col-auto">
      <button class="btn btn-warning" name="do" value="1"><i class="fas fa-redo me-1"></i> Reset</button>
      <a href="index.php?page=employees/index" class="btn btn-outline-secondary ms-1">Kembali</a>
    </div>
  </form>
</div>
