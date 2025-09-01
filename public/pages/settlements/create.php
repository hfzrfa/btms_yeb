<?php
require_role('employee', 'admin', 'manager');
$pdo = getPDO();
// Self-heal settlements.id AUTO_INCREMENT (prevents 1364 errors)
try {
  $col = $pdo->query("SHOW COLUMNS FROM settlements LIKE 'id'")->fetch(PDO::FETCH_ASSOC);
  if ($col && stripos(($col['Extra'] ?? ''), 'auto_increment') === false) {
    try {
      $hasPk = $pdo->query("SHOW INDEX FROM settlements WHERE Key_name='PRIMARY'")->fetch();
      if (!$hasPk) {
        $pdo->exec("ALTER TABLE settlements ADD PRIMARY KEY (id)");
      }
    } catch (Throwable $e) {
    }
    try {
      $pdo->exec("ALTER TABLE settlements MODIFY id INT NOT NULL AUTO_INCREMENT");
    } catch (Throwable $e) {
    }
    try {
      $next = (int)$pdo->query("SELECT MAX(id)+1 FROM settlements")->fetchColumn();
      if ($next < 1) $next = 1;
      $pdo->exec("ALTER TABLE settlements AUTO_INCREMENT=" . $next);
    } catch (Throwable $e) {
    }
  }
} catch (Throwable $e) { /* ignore */
}
$user = current_user();
// Ensure reg_no columns/tables exist for stable registration number flow
try {
  $pdo->exec("ALTER TABLE trips ADD COLUMN reg_no INT NULL");
} catch (Throwable $e) {
}
try {
  $pdo->exec("ALTER TABLE settlements ADD COLUMN reg_no INT NULL");
} catch (Throwable $e) {
}
try {
  $pdo->exec("CREATE TABLE IF NOT EXISTS trip_registrations (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
} catch (Throwable $e) {
}
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
    // Fetch reg_no from trip (generate if missing)
    $regNoTrip = null;
    try {
      $stReg = $pdo->prepare('SELECT reg_no FROM trips WHERE id=?');
      $stReg->execute([$trip_id]);
      $regNoTrip = (int)$stReg->fetchColumn();
    } catch (Throwable $e) {
    }
    if (!$regNoTrip) {
      try {
        $pdo->exec("INSERT INTO trip_registrations() VALUES()");
        $regNoTrip = (int)$pdo->lastInsertId();
      } catch (Throwable $e) {
      }
      if ($regNoTrip) {
        try {
          $pdo->prepare('UPDATE trips SET reg_no=? WHERE id=?')->execute([$regNoTrip, $trip_id]);
        } catch (Throwable $e) {
        }
      }
    }
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
    try {
      $pdo->exec("ALTER TABLE settlements ADD COLUMN remaining_cash DECIMAL(14,2) DEFAULT 0");
    } catch (Exception $e) {
    }
    try {
      $pdo->prepare('INSERT INTO settlements(trip_id,reg_no,total_realisasi,bukti_file,variance,status,remaining_cash,created_at) VALUES(?,?,?,?,?,?,?,NOW())')
        ->execute([$trip_id, $regNoTrip ?: null, $total_idr, implode('|', $fileNames), $variance, 'submitted', $remaining_cash]);
    } catch (Throwable $e) {
      // Fallback for tables without AUTO_INCREMENT on id
      if (strpos($e->getMessage(), '1364') !== false || stripos($e->getMessage(), "doesn't have a default value") !== false) {
        try {
          $next = (int)$pdo->query('SELECT COALESCE(MAX(id),0)+1 FROM settlements')->fetchColumn();
          if ($next < 1) $next = 1;
        } catch (Throwable $e2) {
          $next = 1;
        }
        $pdo->prepare('INSERT INTO settlements(id,trip_id,reg_no,total_realisasi,bukti_file,variance,status,remaining_cash,created_at) VALUES(?,?,?,?,?,?,?,?,NOW())')
          ->execute([$next, $trip_id, $regNoTrip ?: null, $total_idr, implode('|', $fileNames), $variance, 'submitted', $remaining_cash]);
      } else {
        throw $e;
      }
    }
    redirect('index.php?page=settlements/index');
  }
  // Detailed / modal mode (multi-currency capable)
  $items = json_decode(post('items_json') ?? '[]', true) ?: [];
  // Fetch trip currencies to derive implicit rates (IDR per foreign)
  $trip = $pdo->prepare('SELECT temp_payment_idr,temp_payment_sgn,temp_payment_yen, reg_no FROM trips WHERE id=?');
  $trip->execute([$trip_id]);
  $tripRow = $trip->fetch(PDO::FETCH_ASSOC) ?: ['temp_payment_idr' => 0, 'temp_payment_sgn' => 0, 'temp_payment_yen' => 0, 'reg_no' => null];
  if (empty($tripRow['reg_no'])) {
    // Generate and persist reg_no if missing
    try {
      $pdo->exec("INSERT INTO trip_registrations() VALUES()");
      $newReg = (int)$pdo->lastInsertId();
    } catch (Throwable $e) {
      $newReg = null;
    }
    if ($newReg) {
      try {
        $pdo->prepare('UPDATE trips SET reg_no=? WHERE id=?')->execute([$newReg, $trip_id]);
      } catch (Throwable $e) {
      }
      $tripRow['reg_no'] = $newReg;
    }
  }
  $adv_idr_full = (float)($tripRow['temp_payment_idr'] ?? 0);
  $adv_sgd_full = (float)($tripRow['temp_payment_sgn'] ?? 0);
  $adv_yen_full = (float)($tripRow['temp_payment_yen'] ?? 0);
  $rate_sgd = ($adv_idr_full > 0 && $adv_sgd_full > 0) ? ($adv_idr_full / $adv_sgd_full) : 0;
  $rate_yen = ($adv_idr_full > 0 && $adv_yen_full > 0) ? ($adv_idr_full / $adv_yen_full) : 0;
  $total_idr = 0;
  $sumInputIdr = 0;
  $sumSgd = 0;
  $sumYen = 0;
  foreach ($items as &$it) {
    $idr = (float)($it['amount_idr'] ?? 0);
    $sgd = (float)($it['amount_sgd'] ?? 0);
    $yen = (float)($it['amount_yen'] ?? 0);
    $sumInputIdr += $idr;
    $sumSgd += $sgd;
    $sumYen += $yen;
    $total_idr += $idr + ($rate_sgd > 0 ? $sgd * $rate_sgd : 0) + ($rate_yen > 0 ? $yen * $rate_yen : 0);
  }
  unset($it);
  // Variance kept for legacy purposes (difference vs advance IDR only)
  $variance = $total_idr - $advance_idr;
  // Remaining cash = advance - total (if positive)
  $remaining_cash = $advance_idr > $total_idr ? ($advance_idr - $total_idr) : 0;
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
  try {
    $pdo->exec("ALTER TABLE settlements ADD COLUMN remaining_cash DECIMAL(14,2) DEFAULT 0");
  } catch (Exception $e) {
  }
  $usedManualId = null;
  try {
    $pdo->prepare('INSERT INTO settlements(trip_id,reg_no,total_realisasi,bukti_file,variance,status,remaining_cash,created_at) VALUES(?,?,?,?,?,?,?,NOW())')
      ->execute([$trip_id, $tripRow['reg_no'] ?? null, $total_idr, implode('|', $fileNames), $variance, 'submitted', $remaining_cash]);
  } catch (Throwable $e) {
    if (strpos($e->getMessage(), '1364') !== false || stripos($e->getMessage(), "doesn't have a default value") !== false) {
      try {
        $next = (int)$pdo->query('SELECT COALESCE(MAX(id),0)+1 FROM settlements')->fetchColumn();
        if ($next < 1) $next = 1;
      } catch (Throwable $e2) {
        $next = 1;
      }
      $usedManualId = $next;
      $pdo->prepare('INSERT INTO settlements(id,trip_id,reg_no,total_realisasi,bukti_file,variance,status,remaining_cash,created_at) VALUES(?,?,?,?,?,?,?,?,NOW())')
        ->execute([$next, $trip_id, $tripRow['reg_no'] ?? null, $total_idr, implode('|', $fileNames), $variance, 'submitted', $remaining_cash]);
    } else {
      throw $e;
    }
  }
  try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS settlement_items (id INT AUTO_INCREMENT PRIMARY KEY, settlement_id INT, category VARCHAR(50), description VARCHAR(255), amount_idr DECIMAL(15,2), amount_sgd DECIMAL(15,2) NULL, amount_yen DECIMAL(15,2) NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
  } catch (Exception $e) {
  }
  // Ensure settlement_items.id is AUTO_INCREMENT if table already existed without it
  try {
    $col = $pdo->query("SHOW COLUMNS FROM settlement_items LIKE 'id'")->fetch(PDO::FETCH_ASSOC);
    if ($col && stripos(($col['Extra'] ?? ''), 'auto_increment') === false) {
      try {
        $hasPk = $pdo->query("SHOW INDEX FROM settlement_items WHERE Key_name='PRIMARY'")->fetch();
        if (!$hasPk) {
          $pdo->exec("ALTER TABLE settlement_items ADD PRIMARY KEY (id)");
        }
      } catch (Throwable $e) {
      }
      try {
        $pdo->exec("ALTER TABLE settlement_items MODIFY id INT NOT NULL AUTO_INCREMENT");
      } catch (Throwable $e) {
      }
      try {
        $next = (int)$pdo->query("SELECT MAX(id)+1 FROM settlement_items")->fetchColumn();
        if ($next < 1) $next = 1;
        $pdo->exec("ALTER TABLE settlement_items AUTO_INCREMENT=" . $next);
      } catch (Throwable $e) {
      }
    }
  } catch (Throwable $e) { /* ignore */
  }
  // Ensure new columns exist (idempotent)
  try {
    $pdo->exec('ALTER TABLE settlement_items ADD COLUMN amount_sgd DECIMAL(15,2) NULL, ADD COLUMN amount_yen DECIMAL(15,2) NULL');
  } catch (Exception $e) {
  }
  $settleId = (int)$pdo->lastInsertId();
  if (!$settleId && $usedManualId) {
    $settleId = (int)$usedManualId;
  }
  $ins = $pdo->prepare('INSERT INTO settlement_items(settlement_id,category,description,amount_idr,amount_sgd,amount_yen) VALUES (?,?,?,?,?,?)');
  foreach ($items as $it) {
    try {
      $ins->execute([$settleId, $it['category'] ?? '', substr($it['description'] ?? '', 0, 255), (float)($it['amount_idr'] ?? 0), (float)($it['amount_sgd'] ?? 0), (float)($it['amount_yen'] ?? 0)]);
    } catch (Throwable $e) {
      if (strpos($e->getMessage(), '1364') !== false || stripos($e->getMessage(), "doesn't have a default value") !== false) {
        // fallback insert with explicit id for settlement_items
        try {
          $nextIt = (int)$pdo->query('SELECT COALESCE(MAX(id),0)+1 FROM settlement_items')->fetchColumn();
          if ($nextIt < 1) $nextIt = 1;
        } catch (Throwable $e2) {
          $nextIt = 1;
        }
        $pdo->prepare('INSERT INTO settlement_items(id,settlement_id,category,description,amount_idr,amount_sgd,amount_yen) VALUES (?,?,?,?,?,?,?)')
          ->execute([$nextIt, $settleId, $it['category'] ?? '', substr($it['description'] ?? '', 0, 255), (float)($it['amount_idr'] ?? 0), (float)($it['amount_sgd'] ?? 0), (float)($it['amount_yen'] ?? 0)]);
      } else {
        throw $e;
      }
    }
  }
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