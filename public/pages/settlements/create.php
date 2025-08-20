<?php
require_role('employee', 'admin', 'manager');
$pdo = getPDO();
$user = current_user();
// Trips approved & not yet settled
if ($user['role'] === 'employee') {
  $stmt = $pdo->prepare('SELECT t.id, t.tujuan, t.tanggal, t.temp_payment_idr, t.temp_payment_sgn, t.temp_payment_yen FROM trips t LEFT JOIN settlements s ON s.trip_id=t.id WHERE t.employee_id=? AND t.status="approved" AND s.id IS NULL ORDER BY t.tanggal DESC');
  $stmt->execute([$user['id']]);
  $trips = $stmt->fetchAll();
} else {
  // Admin/manager: all approved trips without settlement, include employee name
  $trips = $pdo->query('SELECT t.id, t.tujuan, t.tanggal, t.temp_payment_idr, t.temp_payment_sgn, t.temp_payment_yen, e.Name employee_name, e.EmpNo emp_no FROM trips t LEFT JOIN settlements s ON s.trip_id=t.id LEFT JOIN employees e ON e.id=t.employee_id WHERE t.status="approved" AND s.id IS NULL ORDER BY t.tanggal DESC LIMIT 500')->fetchAll();
}
$uploadDir = __DIR__ . '/../../uploads';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
  $trip_id = (int)post('trip_id');
  $advance_idr = (float)post('advance_idr');
  // Simple mode: used_amount provided (no category items)
  $used_amount = (float)post('used_amount');
  if ($used_amount > 0) {
    $total_idr = $used_amount;
    $variance = $total_idr - $advance_idr; // positive = company owes employee
    $remaining_cash = max($advance_idr - $total_idr, 0);
    // Receipts handling (optional)
    $fileNames = [];
    if (!empty($_FILES['receipts']['name'][0])) {
      foreach ($_FILES['receipts']['name'] as $i => $n) {
        if (!$_FILES['receipts']['tmp_name'][$i]) continue;
        $ext = strtolower(pathinfo($n, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'pdf'])) continue;
        $fn = 'settle_' . time() . '_' . $i . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($_FILES['receipts']['tmp_name'][$i], $uploadDir . '/' . $fn);
        $fileNames[] = $fn;
      }
    }
    try { $pdo->exec("ALTER TABLE settlements ADD COLUMN remaining_cash DECIMAL(14,2) DEFAULT 0"); } catch (Exception $e) {}
    $pdo->prepare('INSERT INTO settlements(trip_id,total_realisasi,bukti_file,variance,status,remaining_cash,created_at) VALUES(?,?,?,?,?,?,NOW())')
        ->execute([$trip_id, $total_idr, implode('|', $fileNames), $variance, 'submitted', $remaining_cash]);
    redirect('index.php?page=settlements/index');
  }
  // Legacy detailed mode fallback (if used_amount not sent)
  $items = json_decode(post('items_json') ?? '[]', true) ?: [];
  $remaining_cash = (float)post('remaining_cash');
  $total_idr = 0;
  foreach ($items as $it) { $total_idr += (float)($it['amount_idr'] ?? 0); }
  $variance = $total_idr - $advance_idr;
  $fileNames = [];
  if (!empty($_FILES['receipts']['name'][0])) {
    foreach ($_FILES['receipts']['name'] as $i => $n) {
      if (!$_FILES['receipts']['tmp_name'][$i]) continue;
      $ext = strtolower(pathinfo($n, PATHINFO_EXTENSION));
      if (!in_array($ext, ['jpg', 'jpeg', 'png', 'pdf'])) continue;
      $fn = 'settle_' . time() . '_' . $i . '_' . rand(1000, 9999) . '.' . $ext;
      move_uploaded_file($_FILES['receipts']['tmp_name'][$i], $uploadDir . '/' . $fn);
      $fileNames[] = $fn;
    }
  }
  try { $pdo->exec("ALTER TABLE settlements ADD COLUMN remaining_cash DECIMAL(14,2) DEFAULT 0"); } catch (Exception $e) {}
  $pdo->prepare('INSERT INTO settlements(trip_id,total_realisasi,bukti_file,variance,status,remaining_cash,created_at) VALUES(?,?,?,?,?,?,NOW())')
      ->execute([$trip_id, $total_idr, implode('|', $fileNames), $variance, 'submitted', $remaining_cash]);
  try { $pdo->exec("CREATE TABLE IF NOT EXISTS settlement_items (id INT AUTO_INCREMENT PRIMARY KEY, settlement_id INT, category VARCHAR(50), description VARCHAR(255), amount_idr DECIMAL(15,2), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)"); } catch (Exception $e) {}
  $settleId = (int)$pdo->lastInsertId();
  $ins = $pdo->prepare('INSERT INTO settlement_items(settlement_id,category,description,amount_idr) VALUES (?,?,?,?)');
  foreach ($items as $it) { $ins->execute([$settleId, $it['category'] ?? '', substr($it['description'] ?? '', 0, 255), (float)($it['amount_idr'] ?? 0)]); }
  redirect('index.php?page=settlements/index');
}
?>
<h4 class="mb-3">New Settlement</h4>
<form method="post" enctype="multipart/form-data" id="settlementForm" class="mb-4">
  <?= csrf_field(); ?>
  <div class="row g-3 mb-3">
    <?php if ($user['role'] === 'employee'): ?>
      <div class="col-md-6">
        <label class="form-label">Trip</label>
        <select name="trip_id" id="tripSelect" class="form-select" required>
          <option value="">-- select trip --</option>
          <?php foreach ($trips as $t): ?>
            <option data-advance="<?= (float)$t['temp_payment_idr'] ?>" value="<?= $t['id'] ?>"><?= esc($t['tujuan']) ?> (<?= esc($t['tanggal']) ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php else: ?>
      <div class="col-12">
        <label class="form-label">Select Trip</label>
        <div class="mb-2 d-flex gap-2">
          <input type="text" id="tripSearch" class="form-control" placeholder="Search trip / employee ..." style="max-width:340px;">
          <div class="small text-muted align-self-center">Showing <?= count($trips) ?> records</div>
        </div>
        <div class="table-responsive border rounded" style="max-height:300px;overflow:auto;">
          <table class="table table-sm table-hover mb-0" id="tripPickTable">
            <thead class="table-light position-sticky top-0">
              <tr>
                <th style="width:40px"></th>
                <th>Trip</th>
                <th>Date</th>
                <th>Employee</th>
                <th class="text-end">Advance (IDR)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($trips as $t): ?>
                <tr data-id="<?= $t['id'] ?>" data-advance="<?= (float)$t['temp_payment_idr'] ?>">
                  <td><input type="radio" name="trip_radio" value="<?= $t['id'] ?>"></td>
                  <td><?= esc($t['tujuan']) ?></td>
                  <td><?= esc($t['tanggal']) ?></td>
                  <td><?= esc(($t['emp_no'] ?? '') . ' ' . $t['employee_name']) ?></td>
                  <td class="text-end"><?= number_format($t['temp_payment_idr'] ?? 0) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <input type="hidden" name="trip_id" id="tripSelect" required>
      </div>
    <?php endif; ?>
    <div class="col-md-3">
      <label class="form-label">Temporary (IDR)</label>
      <input type="text" id="advanceDisplay" class="form-control" readonly>
      <input type="hidden" name="advance_idr" id="advanceHidden">
    </div>
    <div class="col-md-3">
      <label class="form-label">Variance (IDR)</label>
      <input type="text" id="varianceDisplay" class="form-control" readonly>
    </div>
    <div class="col-md-3">
      <label class="form-label">Remaining Cash (IDR)</label>
      <input type="number" name="remaining_cash" id="remainingCash" class="form-control" min="0" step="0.01" placeholder="0">
    </div>
  </div>
  <div class="table-responsive mb-3">
    <table class="table table-bordered align-middle" id="itemsTable">
      <thead class="table-light">
        <tr>
          <th style="width:160px">Category</th>
          <th>Description</th>
          <th style="width:140px">Amount (IDR)</th>
          <th style="width:60px"></th>
        </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
        <tr>
          <td>
            <select class="form-select form-select-sm" id="newCat">
              <option value="Transport">Transport</option>
              <option value="Accommodation">Accommodation</option>
              <option value="Meal">Meal</option>
              <option value="Allowance">Allowance</option>
              <option value="Other">Other</option>
            </select>
          </td>
          <td><input type="text" id="newDesc" class="form-control form-control-sm" placeholder="Description"></td>
          <td><input type="number" id="newAmt" class="form-control form-control-sm" min="0" step="0.01"></td>
          <td><button type="button" id="btnAddItem" class="btn btn-sm btn-primary">Add</button></td>
        </tr>
        <tr>
          <th colspan="2" class="text-end">Total</th>
          <th><span id="totalCell">0</span></th>
          <th></th>
        </tr>
      </tfoot>
    </table>
  </div>
  <div class="mb-3">
    <label class="form-label">Upload Receipts (multiple)</label>
    <input type="file" name="receipts[]" multiple accept=".jpg,.jpeg,.png,.pdf" class="form-control">
    <div class="form-text">Accepted: jpg, png, pdf. Multiple allowed.</div>
  </div>
  <input type="hidden" name="items_json" id="itemsJson">
  <button class="btn btn-primary">Submit Settlement</button>
  <a href="index.php?page=settlements/index" class="btn btn-secondary">Back</a>
</form>
<script>
  (() => {
    const items = [];
    const tbody = document.querySelector('#itemsTable tbody');
    const totalCell = document.getElementById('totalCell');
    const varianceDisplay = document.getElementById('varianceDisplay');
    const remainingCash = document.getElementById('remainingCash');
    const advanceDisplay = document.getElementById('advanceDisplay');
    const advanceHidden = document.getElementById('advanceHidden');
    const tripSelect = document.getElementById('tripSelect');

    function fmt(n) {
      return new Intl.NumberFormat('id-ID').format(Math.round(n));
    }

    function recalc() {
      const total = items.reduce((s, i) => s + Number(i.amount_idr || 0), 0);
      totalCell.textContent = fmt(total);
      const adv = Number(advanceHidden.value || 0);
      const variance = total - adv; // positive: claim more, negative: return
      varianceDisplay.value = (variance >= 0 ? '+' : '') + fmt(variance);
      if (remainingCash) {
        // remaining cash = adv - total (only if employee returns money)
        const rem = adv - total;
        if (rem > 0) remainingCash.value = Math.round(rem);
        else if (!remainingCash.value) remainingCash.value = '0';
      }
      document.getElementById('itemsJson').value = JSON.stringify(items);
    }
    document.getElementById('btnAddItem').addEventListener('click', () => {
      const cat = document.getElementById('newCat').value;
      const desc = document.getElementById('newDesc').value.trim();
      const amt = parseFloat(document.getElementById('newAmt').value || '0');
      if (!amt) return;
      items.push({
        category: cat,
        description: desc,
        amount_idr: amt
      });
      const tr = document.createElement('tr');
      tr.innerHTML = `<td>${cat}</td><td>${desc||'-'}</td><td class='text-end'>${fmt(amt)}</td><td><button type='button' class='btn btn-sm btn-danger btn-del'>&times;</button></td>`;
      tr.querySelector('.btn-del').addEventListener('click', () => {
        const idx = [...tbody.children].indexOf(tr);
        if (idx > -1) {
          items.splice(idx, 1);
          tr.remove();
          recalc();
        }
      });
      tbody.appendChild(tr);
      document.getElementById('newDesc').value = '';
      document.getElementById('newAmt').value = '';
      recalc();
    });
    if (tripSelect.tagName === 'SELECT') {
      tripSelect.addEventListener('change', () => {
        const opt = tripSelect.options[tripSelect.selectedIndex];
        const adv = Number(opt?.dataset.advance || 0);
        advanceHidden.value = adv;
        advanceDisplay.value = fmt(adv);
        recalc();
      });
    } else {
      // admin table selection
      const table = document.getElementById('tripPickTable');
      table.addEventListener('click', e => {
        const tr = e.target.closest('tr[data-id]');
        if (!tr) return;
        tr.querySelector('input[type=radio]').checked = true;
        tripSelect.value = tr.dataset.id; // hidden input
        const adv = Number(tr.dataset.advance || 0);
        advanceHidden.value = adv;
        advanceDisplay.value = fmt(adv);
        recalc();
      });
      const search = document.getElementById('tripSearch');
      search.addEventListener('input', () => {
        const q = search.value.toLowerCase();
        table.querySelectorAll('tbody tr').forEach(r => {
          r.style.display = r.innerText.toLowerCase().includes(q) ? '' : 'none';
        });
      });
    }
  })();
</script>