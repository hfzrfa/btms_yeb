<?php
if (get('logout')) {
  logout_user();
  redirect('index.php?page=login');
}
$error = null;
require_once __DIR__ . '/../../lib/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!verify_csrf()) {
    $error = 'Invalid CSRF token';
  } else {
    $ok = login_attempt(trim(post('username')), (string)post('password'));
    if ($ok) {
      redirect('index.php?page=dashboard');
    } else {
      // Distinguish throttle vs wrong silently
      $remain = login_block_remaining();
      if ($remain > 0) {
        $error = 'Terlalu banyak percobaan. Coba lagi dalam ' . ceil($remain / 60) . ' menit.';
      } else {
        $error = 'Username atau password salah';
      }
    }
  }
}
?>
<div class="row justify-content-center">
  <div class="col-md-5 col-lg-4">
    <div class="card border-0 shadow-lg mt-5">
      <div class="card-header bg-primary border-0 py-4">
        <div class="text-center">
          <div class="mb-3">
            <div class="d-flex justify-content-center">
              <div class="bg-white rounded-circle p-3 shadow-sm" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                <div class="text-primary fw-bold" style="font-size: 24px; font-family: Arial, sans-serif;">
                  YEB
                </div>
              </div>
            </div>
          </div>
          <h4 class="text-white mb-1 fw-bold">Business Trip Management System</h4>
          <p class="text-white text-opacity-85 mb-0 small">Secure access to your account</p>
        </div>
      </div>
      <div class="card-body p-5">
        <?php if ($error): ?>
          <div class="alert alert-danger border-0 py-3 mb-4" style="border-radius: 8px;">
            <div class="d-flex align-items-center">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <span><?= esc($error) ?></span>
            </div>
          </div>
        <?php endif; ?>

        <form method="post" autocomplete="off" novalidate>
          <?= csrf_field(); ?>

          <div class="mb-4">
            <label class="form-label text-muted small fw-semibold mb-2">EMP NO / USERNAME</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0" style="border-radius: 8px 0 0 8px;">
                <i class="fas fa-user text-muted"></i>
              </span>
              <input type="text" name="username" class="form-control border-start-0 py-3" placeholder="EmpNo or username" required style="border-radius: 0 8px 8px 0;">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label text-muted small fw-semibold mb-2">PASSWORD</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0" style="border-radius: 8px 0 0 8px;">
                <i class="fas fa-lock text-muted"></i>
              </span>
              <input type="password" name="password" class="form-control border-start-0 py-3" placeholder="Enter your password" required style="border-radius: 0 8px 8px 0;">
            </div>
          </div>

          <div class="d-grid gap-2 mb-4">
            <button type="submit" class="btn btn-primary py-3 fw-semibold" style="border-radius: 8px;">
              <i class="fas fa-sign-in-alt me-2"></i>
              Sign In to Dashboard
            </button>
          </div>

          <div class="text-center">
            <p class="text-muted mb-0 small">Contact admin if you need access.</p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>