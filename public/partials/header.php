<?php
$user = current_user();
// determine current page for active state
$current = $page ?? ($_GET['page'] ?? 'dashboard');
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Business Trip Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
  <link href="assets/css/theme.css" rel="stylesheet" />
  <style>
    body {
      padding-bottom: 40px
    }
  </style>
</head>

<body class="btms-has-sidebar">
  <div class="d-flex btms-app">
    <aside class="btms-sidebar" id="btmsSidebar">
      <div class="brand">
        <div class="logo-mark">YEB</div>
        <div>
          <div>Business Trip Management</div>
          <small style="font-size:.65rem;opacity:.7;letter-spacing:.15em;">PT. Yoshikawa Electronic Bintan</small>
        </div>
      </div>
      <?php if ($user): ?>
        <nav class="nav flex-column">
          <div class="section-label">Overview</div>
          <a class="nav-link<?= $current === 'dashboard' ? ' active' : '' ?>" href="index.php?page=dashboard">
            <i class="fas fa-chart-pie"></i> Dashboard
          </a>

          <div class="section-label">Trip Management</div>
          <?php if (in_array($user['role'], ['employee', 'manager'], true)): ?>
            <a class="nav-link<?= $current === 'trips/create' ? ' active' : '' ?>" href="index.php?page=trips/create">
              <i class="fas fa-plus-circle"></i> New Trip Request
            </a>
          <?php endif; ?>

          <?php if (in_array($user['role'], ['admin', 'manager'], true)): ?>
            <a class="nav-link<?= ($current === 'trips/index') ? ' active' : '' ?>" href="index.php?page=trips/index">
              <i class="fas fa-clipboard-check"></i> Review & Approve
            </a>

            <a class="nav-link<?= $current === 'settlements/index' ? ' active' : '' ?>" href="index.php?page=settlements/index">
              <i class="fas fa-receipt"></i> Settlements
            </a>
            
            <a class="nav-link<?= $current === 'admin/edit_approvals' ? ' active' : '' ?>" href="index.php?page=admin/edit_approvals">
              <i class="fas fa-edit"></i> Edit Approvals
            </a>
          <?php endif; ?>


          <?php if ($user['role'] === 'admin'): ?>
            <div class="section-label">Administration</div>
            <a class="nav-link<?= $current === 'employees/index' ? ' active' : '' ?>" href="index.php?page=employees/index">
              <i class="fas fa-users"></i> Employee Data
            </a>
            <a class="nav-link<?= $current === 'users/create' ? ' active' : '' ?>" href="index.php?page=users/create">
              <i class="fas fa-user-plus"></i> Add User Manual
            </a>
            <a class="nav-link<?= $current === 'users/reset' ? ' active' : '' ?>" href="index.php?page=users/reset">
              <i class="fas fa-key"></i> Reset Password
            </a>
            <!-- <a class="nav-link<?= $current === 'users/sync' ? ' active' : '' ?>" href="index.php?page=users/sync">
              <i class="fas fa-users-cog"></i> Sync Users
            </a>
            <a class="nav-link<?= $current === 'users/rebuild' ? ' active' : '' ?>" href="index.php?page=users/rebuild">
              <i class="fas fa-recycle"></i> Rebuild Users
            </a> -->
            <!-- <a class="nav-link<?= $current === 'admin/circural' ? ' active' : '' ?>" href="index.php?page=admin/circural">
              <i class="fas fa-database"></i> Edit Circural
            </a> -->
          <?php endif; ?>
        </nav>
        <div class="mt-auto btms-userbox">
          <div class="fw-semibold mb-3">
            <i class="fas fa-user-circle me-2"></i><?= esc($user['username']) ?>
            <span class="badge bg-primary ms-1" style="font-size:.55rem;"><?= esc(ucfirst($user['role'])) ?></span>

          </div>
          <div class="medium mb-3">
            <a href="index.php?page=login&logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
      <?php else: ?>
        <nav class="nav flex-column px-4">
          <a class="nav-link<?= $current === 'login' ? ' active' : '' ?>" href="index.php?page=login">Login</a>
        </nav>
      <?php endif; ?>
    </aside>
    <main class="btms-content w-100">
      <div class="btms-topbar px-3 py-2 d-lg-none d-flex justify-content-between align-items-center">
        <button class="btn-toggle-sidebar btn-toggle-sidebar" type="button" onclick="document.getElementById('btmsSidebar').classList.toggle('show')">☰</button>

      </div>
      <div class="container py-4">