<?php
$pdo = getPDO();
$user = current_user();

// For employees: go straight to create form (privacy: no table listing)
if ($user['role'] === 'employee') {
  redirect('index.php?page=trips/create');
  return;
}

// Detect extended columns safely
$cols = [];
try {
  $cols = $pdo->query("SHOW COLUMNS FROM trips")->fetchAll(PDO::FETCH_COLUMN, 0);
} catch (Exception $e) {
}
$hasPeriod = in_array('period_from', $cols) || in_array('period_to', $cols);
$hasRegNo = in_array('reg_no', $cols);
$hasDestDistrict = in_array('destination_district', $cols);
$hasDestCompany = in_array('destination_company', $cols);
$hasEmpSource = in_array('employee_source', $cols);
$hasEmpEmbedded = in_array('emp_no', $cols) && in_array('emp_name', $cols);
$hasCurrency = in_array('currency_entered', $cols) && in_array('temp_payment_idr', $cols);
$hasMultiTemp = in_array('temp_payment_sgn', $cols) && in_array('temp_payment_yen', $cols);
$hasPurpose = in_array('purpose', $cols);

// Build select list dynamically for admin/manager listing for richer view
if ($user['role'] === 'employee') {
  $stmt = $pdo->prepare('SELECT * FROM trips WHERE employee_id=? ORDER BY created_at DESC');
  $stmt->execute([$user['id']]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  // base select
  $select = 't.*';
  if (!$hasEmpEmbedded) {
    // fallback join to employees to display name
    $select .= ', e.Name employee_name, e.EmpNo employee_empno';
  }
  $sql = "SELECT $select FROM trips t LEFT JOIN employees e ON e.id=t.employee_id ORDER BY t.created_at DESC";
  $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
  <h4 class="mb-0 fw-semibold">Trips</h4>
  <div class="ms-auto" style="min-width:240px;">
    <div class="position-relative">
      <input type="text" id="tripQuickSearch" class="form-control form-control-sm ps-5" placeholder="Search trips..." autocomplete="off">
      <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted"><i class="fas fa-search"></i></span>
    </div>
  </div>
</div>
<div class="btms-table-responsive shadow-sm rounded border" style="background:#fff;">
  <table class="table table-hover align-middle btms-table-modern">
    <thead>
      <tr class="text-nowrap">
  <th>#</th>
  <?php if ($hasRegNo): ?><th>Reg No</th><?php endif; ?>
        <?php if ($hasEmpEmbedded): ?>
          <th>Emp No</th>
          <th>Name</th>
        <?php else: ?>
          <th>Employee</th>
        <?php endif; ?>
        <?php if ($hasEmpSource): ?><th>Src</th><?php endif; ?>
        <?php if ($hasPeriod): ?><th>Period</th><?php endif; ?>
        <?php if ($hasDestDistrict): ?><th>District</th><?php endif; ?>
        <?php if ($hasDestCompany): ?><th>Company</th><?php endif; ?>
        <th>Tujuan</th>
        <?php if ($hasCurrency): ?><th>Temp Payment</th><?php endif; ?>
        <th>Estimasi(IDR)</th>
        <?php if ($hasPurpose): ?><th>Purpose</th><?php endif; ?>
        <th>Status</th>
        <th style="width:160px">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1;
      foreach ($rows as $r): ?>
        <tr>
          <td data-label="#"><?= $i++ ?></td>
          <?php if ($hasRegNo): ?>
            <td data-label="Reg No"><span class="badge bg-dark"><?= (int)($r['reg_no'] ?? 0) ?></span></td>
          <?php endif; ?>
          <?php if ($hasEmpEmbedded): ?>
            <td data-label="Emp No"><?= esc($r['emp_no']) ?></td>
            <td data-label="Name"><?= esc($r['emp_name']) ?></td>
          <?php else: ?>
            <td data-label="Employee"><?= esc($r['employee_name'] ?? $user['username']) ?></td>
          <?php endif; ?>
          <?php if ($hasEmpSource): ?><td data-label="Src"><?= esc(substr($r['employee_source'], 0, 1)) ?></td><?php endif; ?>
          <?php if ($hasPeriod): ?><td data-label="Period"><?= esc(($r['period_from'] ?? '') . ' - ' . ($r['period_to'] ?? '')) ?></td><?php endif; ?>
          <?php if ($hasDestDistrict): ?><td data-label="District"><?= esc($r['destination_district']) ?></td><?php endif; ?>
          <?php if ($hasDestCompany): ?><td data-label="Company"><?= esc($r['destination_company']) ?></td><?php endif; ?>
          <td data-label="Tujuan"><?= esc($r['tujuan']) ?></td>
          <?php if ($hasCurrency): ?>
            <td data-label="Temp Payment">
              <?php if ($hasMultiTemp): ?>
                <div>IDR <?= number_format($r['temp_payment_idr'], 0) ?></div>
                <div>SGN <?= number_format($r['temp_payment_sgn'], 2) ?></div>
                <div>YEN <?= number_format($r['temp_payment_yen'], 0) ?></div>
              <?php else: ?>
                <?= esc($r['currency_entered']) ?> <?= number_format($r['temp_payment_idr'], 0) ?>
              <?php endif; ?>
            </td>
          <?php endif; ?>
          <td data-label="Estimasi(IDR)"><?= number_format($r['biaya_estimasi']) ?></td>
          <?php if ($hasPurpose): ?><td data-label="Purpose" class="text-truncate" style="max-width:220px;" title="<?= esc($r['purpose']) ?>"><?= esc($r['purpose']) ?></td><?php endif; ?>
          <td data-label="Status">
            <?php $badge = 'secondary';
            if ($r['status'] === 'approved') $badge = 'success';
            elseif ($r['status'] === 'pending') $badge = 'warning';
            elseif ($r['status'] === 'rejected') $badge = 'danger';
            elseif ($r['status'] === 'cancelled') $badge = 'dark'; ?>
            <span class="badge bg-<?= $badge ?>"><?= esc($r['status']) ?></span>
          </td>
          <td data-label="Action" class="text-nowrap">
            <div class="btn-action-group">
              <?php if (in_array($user['role'], ['manager', 'admin'], true) && $r['status'] === 'pending'): ?>
                <a class="btn btn-success btn-action" href="index.php?page=trips/approve&id=<?= $r['id'] ?>" title="Approve Trip">
                  <i class="fas fa-check"></i>
                </a>
              <?php endif; ?>

              <?php if ($r['status'] === 'approved'): ?>
                <a class="btn btn-primary btn-action" href="index.php?page=trips/pdf&id=<?= $r['id'] ?>" title="Download PDF" target="_blank">
                  <i class="fas fa-file-pdf"></i>
                </a>
              <?php endif; ?>

              <a class="btn btn-info btn-action" href="index.php?page=trips/view&id=<?= $r['id'] ?>" title="View Details">
                <i class="fas fa-eye"></i>
              </a>

              <?php if ($user['role'] === 'employee' && $r['status'] === 'pending'): ?>
                <a class="btn btn-danger btn-action" onclick="return confirm('Cancel trip?')" href="index.php?page=trips/cancel&id=<?= $r['id'] ?>" title="Cancel Trip">
                  <i class="fas fa-times"></i>
                </a>
              <?php endif; ?>

              <!-- <?php if (in_array($user['role'], ['manager', 'admin'], true)): ?>
            <a class="btn btn-warning btn-action" href="index.php?page=trips/edit&id=<?= $r['id'] ?>" title="Edit Trip">
              <i class="fas fa-edit"></i>
            </a>
          <?php endif; ?> -->

            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<script>
  // Quick client-side search
  (function() {
    const inp = document.getElementById('tripQuickSearch');
    const table = document.querySelector('.btms-table-modern tbody');
    if (!inp || !table) return;
    inp.addEventListener('input', () => {
      const q = inp.value.toLowerCase();
      table.querySelectorAll('tr').forEach(tr => {
        tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  })();
</script>