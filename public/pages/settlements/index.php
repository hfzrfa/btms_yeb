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
<style>
  /* Ensure page and modal can scroll on smaller viewports */
  html,
  body {
    height: auto;
  }

  .modal-dialog.modal-dialog-scrollable .modal-content {
    max-height: 92vh;
  }

  /* Ensure vertical scroll within the modal body */
  .modal-dialog.modal-dialog-scrollable .modal-body {
    overflow-y: auto;
    max-height: calc(100vh - 160px);
    /* header+footer approx */
  }

  /* Wider but fluid modal width and viewport fit */
  @media (max-width: 1366px) {
    .modal-dialog {
      max-width: 96vw;
      margin: 0.5rem auto;
    }
  }

  @media (max-height: 700px) {
    .modal-dialog.modal-dialog-scrollable .modal-content {
      max-height: 88vh;
    }

    .modal-dialog.modal-dialog-scrollable .modal-body {
      max-height: calc(100vh - 140px);
    }
  }

  /* Prevent table container from growing off-screen if extremely tall */
  .btms-content {
    overflow: auto;
  }

  #settleTable {
    width: 100%;
  }

  /* Footer stays outside the scrollable body per Bootstrap; no sticky needed */
</style>
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
        <th>Reg No</th>
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
          <td><?= (int)($t['reg_no'] ?? 0) ?></td>
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
              <button class="btn btn-sm btn-primary" data-trip='<?= json_encode([
                                                                  'id' => $t['id'],
                                                                  'advance' => (float)$t['temp_payment_idr'],
                                                                  'advance_sgd' => (float)($t['temp_payment_sgn'] ?? 0),
                                                                  'advance_yen' => (float)($t['temp_payment_yen'] ?? 0),
                                                                  'tujuan' => $t['tujuan'],
                                                                  'period' => ($t['period_from'] ?? '') . ' - ' . ($t['period_to'] ?? '')
                                                                ]) ?>' onclick="openSettlement(this)"><i class="fas fa-plus-circle"></i></button>
            <?php else: ?>
              <a class="btn btn-sm btn-outline-secondary" target="_blank" href="index.php?page=settlements/pdf&id=<?= $t['settlement_id'] ?>" title="Print PDF"><i class="fas fa-file-pdf"></i></a>
              <!-- <a class="btn btn-sm btn-outline-success" href="index.php?page=settlements/export_excel&id=<?= $t['settlement_id'] ?>" title="Export to Excel"><i class="fas fa-file-excel"></i></a> -->
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
      <div class="modal-body">
        <form method="post" action="index.php?page=settlements/create" enctype="multipart/form-data" id="inlineSettleForm">
          <?= csrf_field(); ?>
          <input type="hidden" name="trip_id" id="mTripId">
          <input type="hidden" name="advance_idr" id="mAdvanceHidden">
          <input type="hidden" name="items_json" id="mItemsJson" value="[]">
          <div class="mb-3 small text-muted" id="mTripInfo"></div>
          <div class="row g-2 mb-3">
            <div class="col-md-3">
              <label class="form-label">Temporary (IDR)</label>
              <input class="form-control" id="mAdvanceDisp" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label">Total (IDR)</label>
              <input class="form-control" id="mTotalIdr" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label">Return To Acc</label>
              <input class="form-control" id="mReturnAcc" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label">Pay To Employee</label>
              <input class="form-control" id="mPayEmp" readonly>
            </div>
          </div>
          <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered align-middle" id="mExpTbl" style="min-width:760px;">
              <thead class="table-light">
                <tr class="text-center align-middle">
                  <th style="width:200px;">Type Of Expenses</th>
                  <th style="width:140px;">IDR</th>
                  <th style="width:120px;">SGD</th>
                  <th style="width:120px;">YEN</th>
                </tr>
              </thead>
              <tbody id="mExpBody"></tbody>
              <tfoot>
                <tr class="fw-semibold">
                  <td>Total Business Trip Expenses</td>
                  <td class="text-end" id="mTotalIdrCell">0</td>
                  <td class="text-end" id="mTotalSgdCell">0</td>
                  <td class="text-end" id="mTotalYenCell">0</td>
                </tr>
                <tr>
                  <td>Temporary Payment</td>
                  <td class="text-end" id="mAdvIdrCell">0</td>
                  <td class="text-end" id="mAdvSgdCell">-</td>
                  <td class="text-end" id="mAdvYenCell">-</td>
                </tr>
                <tr>
                  <td class="text-center">Return To ACC</td>
                  <td class="text-end" id="mReturnAccCell">0</td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td class="text-center">Pay To Employee</td>
                  <td class="text-end" id="mPayEmpCell">0</td>
                  <td></td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="mb-3">
            <label class="form-label">Receipts</label>
            <input type="file" name="receipts[]" multiple accept=".jpg,.jpeg,.png,.pdf" class="form-control form-control-sm">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" form="inlineSettleForm">Save Settlement</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
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
    document.getElementById('mAdvSgdCell').textContent = data.advance_sgd ? Number(data.advance_sgd).toFixed(2) : '-';
    document.getElementById('mAdvYenCell').textContent = data.advance_yen ? fmt(data.advance_yen) : '-';
    resetItems();
    if (typeof calcExp === 'function') {
      calcExp();
    }
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
  const EXP_CATEGORIES = [
    'Airplane', 'Train', 'Ferry / Speed', 'Bus', 'Taxi 1', 'Taxi 2', 'Taxi 3', 'Taxi 4', 'Taxi 5',
    'Other', 'Daily Allowance', 'Accommodation'
  ];

  function resetItems() {
    const body = document.getElementById('mExpBody');
    body.innerHTML = '';
    EXP_CATEGORIES.forEach(cat => {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td>${cat}</td>
        <td><input type="number" min="0" step="0.01" class="form-control form-control-sm exp-idr" data-cat="${cat}"></td>
        <td><input type="number" min="0" step="0.01" class="form-control form-control-sm exp-sgd" data-cat="${cat}"></td>
        <td><input type="number" min="0" step="0.01" class="form-control form-control-sm exp-yen" data-cat="${cat}"></td>`;
      body.appendChild(tr);
    });
    body.querySelectorAll('input').forEach(i => i.addEventListener('input', calcExp));
    calcExp();
  }

  function calcExp() {
    const adv = Number(document.getElementById('mAdvanceHidden').value || 0);
    // derive implicit rates from advance composition present in data attribute saved earlier
    const advBtn = document.querySelector('button[data-trip][data-bs-target]');
    // We'll cache rates globally after openSettlement sets advance_sgd/yen cells
    const advSgdCell = document.getElementById('mAdvSgdCell').textContent;
    const advYenCell = document.getElementById('mAdvYenCell').textContent;
    const advSgd = parseFloat(advSgdCell.replace(/[^0-9.]/g, '')) || 0;
    const advYen = parseFloat(advYenCell.replace(/[^0-9.]/g, '')) || 0;
    const rateSgd = (advSgd > 0 && adv > 0) ? (adv / advSgd) : 0;
    const rateYen = (advYen > 0 && adv > 0) ? (adv / advYen) : 0;
    let sumIdrInput = 0,
      sumSgd = 0,
      sumYen = 0;
    const items = [];
    document.querySelectorAll('#mExpBody tr').forEach(tr => {
      const cat = tr.cells[0].textContent.trim();
      const idr = parseFloat(tr.querySelector('.exp-idr').value) || 0;
      const sgd = parseFloat(tr.querySelector('.exp-sgd').value) || 0;
      const yen = parseFloat(tr.querySelector('.exp-yen').value) || 0;
      if (idr || sgd || yen) {
        items.push({
          category: cat,
          amount_idr: idr,
          amount_sgd: sgd,
          amount_yen: yen
        });
      }
      sumIdrInput += idr;
      sumSgd += sgd;
      sumYen += yen;
    });
    const converted = (rateSgd > 0 ? sumSgd * rateSgd : 0) + (rateYen > 0 ? sumYen * rateYen : 0);
    const totalIdr = sumIdrInput + converted;
    document.getElementById('mTotalIdrCell').textContent = fmt(totalIdr);
    document.getElementById('mTotalSgdCell').textContent = sumSgd ? sumSgd.toFixed(2) : '0';
    document.getElementById('mTotalYenCell').textContent = sumYen ? fmt(sumYen) : '0';
    document.getElementById('mTotalIdr').value = fmt(totalIdr);
    document.getElementById('mItemsJson').value = JSON.stringify(items);
    document.getElementById('mAdvIdrCell').textContent = fmt(adv);
    // Return To ACC = Temporary - Total (if positive)
    const returnAcc = adv > totalIdr ? (adv - totalIdr) : 0;
    // Pay To Employee shows total used (as per user request)
    const payEmp = totalIdr;
    document.getElementById('mReturnAccCell').textContent = returnAcc ? fmt(returnAcc) : '0';
    document.getElementById('mPayEmpCell').textContent = fmt(payEmp);
    document.getElementById('mReturnAcc').value = returnAcc ? fmt(returnAcc) : '0';
    document.getElementById('mPayEmp').value = fmt(payEmp);
  }
  // listeners removed (no manual rate inputs)
  document.getElementById('settleSearch').addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('#settleTable tbody tr').forEach(tr => {
      tr.style.display = tr.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  });
</script>