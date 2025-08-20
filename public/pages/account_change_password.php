<?php
require_role('employee', 'admin', 'manager');
$pdo = getPDO();
$user = current_user();
$error = null;
$success = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
  $old = post('old_password');
  $new = post('new_password');
  $confirm = post('confirm_password');
  if ($new === '' || strlen($new) < 6) {
    $error = 'Password minimal 6 karakter';
  } elseif ($new !== $confirm) {
    $error = 'Konfirmasi tidak cocok';
  } else {
    $st = $pdo->prepare('SELECT password FROM users WHERE id=? LIMIT 1');
    $st->execute([$user['id']]);
    $row = $st->fetch();
    if (!$row) {
      $error = 'User tidak ditemukan';
    } elseif (!password_verify($old, $row['password']) && $row['password'] !== $old) {
      $error = 'Password lama salah';
    } else {
      $hash = password_hash($new, PASSWORD_DEFAULT);
      $up = $pdo->prepare('UPDATE users SET password=?, must_change_password=0 WHERE id=?');
      $up->execute([$hash, $user['id']]);
      $_SESSION['user']['must_change_password'] = 0;
      $success = 'Password berhasil diubah';
    }
  }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-semibold">Change Password</h5>
      </div>
      <div class="card-body p-4">
        <?php if ($error): ?><div class="alert alert-danger py-2 mb-3"><?= esc($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success py-2 mb-3"><?= esc($success) ?></div><?php endif; ?>
        <form method="post">
          <?= csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label small fw-medium">Password Lama</label>
            <input type="password" name="old_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-medium">Password Baru</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>
          <div class="mb-4">
            <label class="form-label small fw-medium">Konfirmasi Password Baru</label>
            <input type="password" name="confirm_password" class="form-control" required>
          </div>
          <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="index.php?page=dashboard" class="btn btn-outline-secondary">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>