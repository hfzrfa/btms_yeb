<?php
$pdo = getPDO();
$user = current_user();
// Ensure settlement extensions
try {
  $pdo->exec("ALTER TABLE settlements ADD COLUMN variance DECIMAL(14,2) DEFAULT 0, ADD COLUMN status VARCHAR(20) DEFAULT 'submitted'");
} catch (Exception $e) {
}

// Fetch approved trips with optional existing settlement
if ($user['role'] === 'employee') {
  $stmt = $pdo->prepare('SELECT t.*, s.id AS settlement_id, s.total_realisasi, s.variance, s.status AS settlement_status FROM trips t LEFT JOIN settlements s ON s.trip_id=t.id WHERE t.employee_id=? AND t.status="approved" ORDER BY t.created_at DESC');
  $stmt->execute([$user['id']]);
  $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  $trips = $pdo->query('SELECT t.*, e.Name employee_name, e.EmpNo emp_no, s.id settlement_id, s.total_realisasi, s.variance, s.status settlement_status FROM trips t LEFT JOIN employees e ON e.id=t.employee_id LEFT JOIN settlements s ON s.trip_id=t.id WHERE t.status="approved" ORDER BY t.created_at DESC LIMIT 800')->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Approved Trips & Settlements</h4>
  <div class="ms-auto" style="min-width:260px;">
    <div class="position-relative">
      <input type="text" id="settleSearch" class="form-control form-control-sm ps-5" placeholder="Search trips...">
      <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted"><i class="fas fa-search"></i></span>
    </div>
  </div>
</div>
<div class="table-responsive shadow-sm border rounded bg-white">
  <table class="table table-hover align-middle mb-0" id="settleTable">
    <thead class="table-light">
      <tr class="text-nowrap">
        <?php if ($user['role'] !== 'employee'): ?><th>Emp No</th>
          <th>Employee</th><?php endif; ?>
        <th>Trip</th>
        <th>Period</th>
        <th>Temporary(IDR)</th>
        <th>Settlement</th>
        <th>Variance</th>
        <th>Remain</th>
        <th>Status</th>
        <th style="width:130px;">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($trips as $t): $settled = !empty($t['settlement_id']); ?>
        <tr>
          <?php if ($user['role'] !== 'employee'): ?><td><?= esc($t['emp_no']) ?></td>
            <td><?= esc($t['employee_name']) ?></td><?php endif; ?>
          <td><?= esc($t['tujuan']) ?></td>
          <td><?= esc(($t['period_from'] ?? '') . ' - ' . ($t['period_to'] ?? '')) ?></td>
          <td class="text-end"><?= number_format($t['temp_payment_idr'], 0) ?></td>
          <td class="text-end"><?= $settled ? number_format($t['total_realisasi'], 0) : '<span class="text-muted">-</span>' ?></td>
          <td class="text-end">
            <?php if ($settled) {
              $v = $t['variance'];
              $cls = $v > 0 ? 'text-danger' : ($v < 0 ? 'text-success' : 'text-muted');
              echo '<span class="' . $cls . '">' . ($v > 0 ? '+' : '') . number_format($v) . '</span>';
            } else {
              echo '<span class="text-muted">-</span>';
            } ?>
          </td>
          <td class="text-end"><?php if ($settled && isset($t['remaining_cash'])) echo number_format($t['remaining_cash'], 0);
                                else echo '<span class="text-muted">-</span>'; ?></td>
          <td>
            <?php if ($settled) {
              $st = $t['settlement_status'] ?: 'submitted';
              $badge = 'warning';
              if ($st === 'approved') $badge = 'success';
              echo '<span class="badge bg-' . $badge . '">' . esc($st) . '</span>';
            } else {
              echo '<span class="badge bg-secondary">Pending</span>';
            } ?>
          </td>
          <td class="text-nowrap">
            <?php if (!$settled): ?>
              <button class="btn btn-sm btn-primary" data-trip='<?= json_encode(['id' => $t['id'], 'advance' => $t['temp_payment_idr'], 'tujuan' => $t['tujuan'], 'period' => ($t['period_from'] ?? '') . ' - ' . ($t['period_to'] ?? '')]) ?>' onclick="openSettlement(this)"><i class="fas fa-plus-circle"></i></button>
            <?php else: ?>
              <a class="btn btn-sm btn-outline-secondary" target="_blank" href="index.php?page=settlements/pdf&id=<?= $t['settlement_id'] ?>" title="Print PDF"><i class="fas fa-file-pdf"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Settlement Modal -->
<div class="modal fade" id="settleModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Settlement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post" action="index.php?page=settlements/create" enctype="multipart/form-data" id="inlineSettleForm">
        <?= csrf_field(); ?>
        <input type="hidden" name="trip_id" id="mTripId">
  <input type="hidden" name="advance_idr" id="mAdvanceHidden">
  <input type="hidden" name="items_json" id="mItemsJson" value="[]">
        <div class="modal-body">
          <div class="mb-3 small text-muted" id="mTripInfo"></div>
          <div class="row g-2 mb-3">
            <div class="col-md-4">
              <label class="form-label">Temporary (IDR)</label>
              <input class="form-control" id="mAdvanceDisp" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label">Used (IDR)</label>
              <input class="form-control" name="used_amount" id="mUsedAmount" type="number" min="0" step="0.01" placeholder="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Remaining (IDR)</label>
              <input class="form-control" id="mDiffDisp" readonly>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Receipts</label>
            <input type="file" name="receipts[]" multiple accept=".jpg,.jpeg,.png,.pdf" class="form-control form-control-sm">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Settlement</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function openSettlement(btn) {
    const data = JSON.parse(btn.getAttribute('data-trip'));
    const modalEl = document.getElementById('settleModal');
    const m = new bootstrap.Modal(modalEl);
    document.getElementById('mTripId').value = data.id;
    document.getElementById('mAdvanceHidden').value = data.advance;
    document.getElementById('mAdvanceDisp').value = fmt(data.advance);
    document.getElementById('mTripInfo').innerHTML = `<strong>${escapeHtml(data.tujuan||'Trip')}</strong> <span class="text-muted">(${escapeHtml(data.period||'')})</span>`;
  resetItems();
  calcSimple();
  m.show();
  }

  function fmt(n) {
    return new Intl.NumberFormat('id-ID').format(Math.round(n || 0));
  }

  function escapeHtml(t) {
    return t.replace(/[&<>"']/g, s => ({
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      "\"": "&quot;",
      "'": "&#39;"
    } [s]));
  }
  function resetItems(){ /* simplified mode */ document.getElementById('mItemsJson').value='[]'; }
  function calcSimple(){
    const adv = Number(document.getElementById('mAdvanceHidden').value||0);
    const used = Number(document.getElementById('mUsedAmount').value||0);
    const diff = adv - used;
    document.getElementById('mDiffDisp').value = fmt(diff);
  }
  document.getElementById('mUsedAmount').addEventListener('input', calcSimple);
  document.getElementById('settleSearch').addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('#settleTable tbody tr').forEach(tr => {
      tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });
</script>