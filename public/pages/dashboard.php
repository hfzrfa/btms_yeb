<?php
require_role(current_user()['role'] ?? 'employee');
$user = current_user();
$displayName = $user['username'];
if (isset($user['employee_id']) && $user['employee_id']) {
  try {
    $pdoName = getPDO();
    $stn = $pdoName->prepare('SELECT Name FROM employees WHERE id=? LIMIT 1');
    $stn->execute([$user['employee_id']]);
    $rN = $stn->fetch(PDO::FETCH_ASSOC);
    if ($rN && $rN['Name']) $displayName = $rN['Name'];
  } catch (Exception $e) {
  }
}
if ($user['role'] === 'employee'):
?>
  <div class="container-fluid fade-in">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-6">
        <div class="card card-clean border-0">
          <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                  <i class="fas fa-user text-primary" style="font-size: 20px;"></i>
                </div>
              </div>
              <div>
                <h4 class="mb-1 fw-semibold">Welcome, <?= esc($displayName) ?></h4>
                <p class="text-muted mb-0 small">Business Trip Management System</p>
              </div>
            </div>
          </div>
          <div class="card-body p-4 text-center">
            <div class="mb-4">
              <h5 class="text-muted mb-3">Quick Actions</h5>
              <p class="text-muted mb-4">Manage your business trip requests efficiently</p>
            </div>
            <div class="d-grid gap-2 d-md-block">
              <a class="btn btn-primary btn-modern btn-lg px-4 me-2" href="index.php?page=trips/create">
                <i class="fas fa-plus me-2"></i>Create New Trip Request
              </a>
              <!-- <a class="btn btn-outline-secondary btn-modern btn-lg px-4" href="index.php?page=trips/index">
                <i class="fas fa-list me-2"></i>View My Trips
              </a> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
  return; // stop rendering further admin/manager widgets
endif;

// Admin & manager dashboard (retain previous metrics)
$pdo = getPDO();
$employeeCount = 0;
$source = 'employees';
try {
  $cols = $pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_COLUMN, 0);
  if ($cols) {
    $employeeCount = (int)$pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
  }
} catch (Exception $e) {
}
if ($employeeCount === 0) {
  try {
    $t = $pdo->query("SHOW TABLES LIKE 'employeemaster'")->fetch();
    if ($t) {
      $source = 'employeemaster';
      $employeeCount = (int)$pdo->query("SELECT COUNT(*) FROM employeemaster")->fetchColumn();
    }
  } catch (Exception $e) {
  }
}
$tripPending = $tripApproved = $tripRejected = 0;
try {
  $row = $pdo->query("SELECT SUM(status='pending') pending, SUM(status='approved') approved, SUM(status='rejected') rejected FROM trips")->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    $tripPending = (int)$row['pending'];
    $tripApproved = (int)$row['approved'];
    $tripRejected = (int)$row['rejected'];
  }
} catch (Exception $e) {
}
$recentTrips = [];
try {
  $recentTrips = $pdo->query("SELECT t.id,t.tujuan,t.tanggal,t.status,e.Name,e.EmpNo FROM trips t LEFT JOIN employees e ON e.id=t.employee_id ORDER BY t.id DESC LIMIT 5")->fetchAll();
} catch (Exception $e) {
}
?>
<div class="container-fluid fade-in">
  <div class="row">
    <div class="col-12">
      <div class="d-flex align-items-center mb-4">
        <div class="me-3">
          <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="fas fa-chart-bar text-primary" style="font-size: 20px;"></i>
          </div>
        </div>
        <div>
          <h4 class="mb-1 fw-semibold">Dashboard Overview</h4>
          <p class="text-muted mb-0 small">System statistics and recent activities</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
      <div class="card card-clean border-0 btms-stat h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="me-3">
              <div class="bg-info bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="fas fa-users text-info" style="font-size: 16px;"></i>
              </div>
            </div>
            <div>
              <div class="mini-label">Total Employees</div>
              <div class="h4 mb-0 fw-bold"><?= esc($employeeCount) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card card-clean border-0 btms-stat h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="me-3">
              <div class="bg-warning bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="fas fa-clock text-warning" style="font-size: 16px;"></i>
              </div>
            </div>
            <div>
              <div class="mini-label">Pending Trips</div>
              <div class="h4 mb-0 fw-bold"><?= esc($tripPending) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card card-clean border-0 btms-stat h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="me-3">
              <div class="bg-success bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="fas fa-check text-success" style="font-size: 16px;"></i>
              </div>
            </div>
            <div>
              <div class="mini-label">Approved Trips</div>
              <div class="h4 mb-0 fw-bold"><?= esc($tripApproved) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card card-clean border-0 btms-stat h-100">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="me-3">
              <div class="bg-danger bg-opacity-15 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="fas fa-times text-danger" style="font-size: 16px;"></i>
              </div>
            </div>
            <div>
              <div class="mini-label">Rejected Trips</div>
              <div class="h4 mb-0 fw-bold"><?= esc($tripRejected) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Trips Table -->
  <div class="card card-clean border-0">
    <div class="card-header bg-white border-bottom py-3">
      <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">Recent Trip Requests</h6>
        <a class="btn btn-outline-primary btn-modern btn-sm" href="index.php?page=trips/index" style="border-radius: 6px;">View All Trips</a>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-professional btms-table-modern mb-0">
          <thead>
            <tr>
              <th class="border-0 py-3 px-4">ID</th>
              <th class="border-0 py-3">Employee</th>
              <th class="border-0 py-3">Employee No</th>
              <th class="border-0 py-3">Destination</th>
              <th class="border-0 py-3">Date</th>
              <th class="border-0 py-3">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$recentTrips): ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-5">No recent trip requests found</td>
              </tr>
              <?php else: foreach ($recentTrips as $t): ?>
                <tr>
                  <td class="px-4 py-3"><?= esc($t['id']) ?></td>
                  <td class="py-3"><?= esc($t['Name']) ?></td>
                  <td class="py-3"><?= esc($t['EmpNo']) ?></td>
                  <td class="py-3"><?= esc($t['tujuan']) ?></td>
                  <td class="py-3"><?= esc($t['tanggal']) ?></td>
                  <td class="py-3">
                    <?php
                    $badge = 'secondary';
                    $icon = 'fas fa-circle';
                    $statusClass = 'status-inactive';
                    if ($t['status'] === 'approved') {
                      $badge = 'success';
                      $icon = 'fas fa-check-circle';
                      $statusClass = 'status-active';
                    } elseif ($t['status'] === 'pending') {
                      $badge = 'warning';
                      $icon = 'fas fa-clock';
                      $statusClass = 'status-pending';
                    } elseif ($t['status'] === 'rejected') {
                      $badge = 'danger';
                      $icon = 'fas fa-times-circle';
                      $statusClass = 'status-inactive';
                    }
                    ?>
                    <span class="badge bg-<?= $badge ?> text-white px-2 py-1">
                      <?= ucfirst(esc($t['status'])) ?>
                    </span>
                    </span>
                  </td>
                </tr>
            <?php endforeach;
            endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>