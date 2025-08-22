<?php
// Allow both employee and admin to create/register trips
require_role('employee', 'admin');
$pdo = getPDO();
$__trips_fixed = false;
try {
  $col = $pdo->query("SHOW COLUMNS FROM trips LIKE 'id'")->fetch(PDO::FETCH_ASSOC);
  if ($col && stripos(($col['Extra'] ?? ''), 'auto_increment') === false) {
    // Ensure primary key then modify to AUTO_INCREMENT
    try {
      $hasPk = $pdo->query("SHOW INDEX FROM trips WHERE Key_name='PRIMARY'")->fetch();
      if (!$hasPk) {
        $pdo->exec("ALTER TABLE trips ADD PRIMARY KEY (id)");
      }
    } catch (Throwable $e) {
    }
    try {
      $pdo->exec("ALTER TABLE trips MODIFY id INT NOT NULL AUTO_INCREMENT");
    } catch (Throwable $e) {
    }
    try {
      $next = (int)$pdo->query("SELECT MAX(id)+1 FROM trips")->fetchColumn();
      if ($next < 1) $next = 1;
      $pdo->exec("ALTER TABLE trips AUTO_INCREMENT=" . $next);
    } catch (Throwable $e) {
    }
    $__trips_fixed = true;
  }
} catch (Throwable $e) { /* ignore */
}
$user = current_user();

// Unified dropdown (searchable) always includes LOCAL employees from employees table.
// Japan employees pulled from japanemployees (preferred) or employees_japan if available.
// employeemaster (if exists) only used for better name/DeptCode correction, not to exclude locals.
$employeesLocal = [];
$employeesJapan = [];
$hasJapan = false;
$fromMaster = false;
try {
  $fromMaster = (bool)$pdo->query("SHOW TABLES LIKE 'employeemaster'")->fetch();
} catch (Exception $e) {
}

// Department name map (dept_code => name)
$deptNameMap = [];
try {
  $deps = $pdo->query("SELECT dept_code,name FROM departments")->fetchAll(PDO::FETCH_ASSOC);
  foreach ($deps as $d) {
    $deptNameMap[$d['dept_code']] = $d['name'];
  }
} catch (Exception $e) { /* ignore if table missing */
}
// Build designation description maps for Position field
$empColsLower = [];$hasEmpDest=false;$hasEmpDesignationId=false;
try { $empColsLower = array_map('strtolower',$pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_COLUMN,0)); } catch(Exception $e){}
if($empColsLower){ $hasEmpDest = in_array('destcode',$empColsLower,true); $hasEmpDesignationId = in_array('designation_id',$empColsLower,true); }
$designationById = [];
$designationByCode = [];
// Prefer desigcode table (maps code->Description)
try {
  $hasDesig = (bool)$pdo->query("SHOW TABLES LIKE 'desigcode'")->fetch();
  if($hasDesig){
    try { foreach($pdo->query("SELECT DesigCode AS code, Description FROM desigcode") as $r){ if($r['code']!==''){ $designationByCode[$r['code']] = $r['Description'] ?: $r['code']; } } }
    catch(Throwable $ie){ try { foreach($pdo->query("SELECT Code AS code, Description FROM desigcode") as $r){ if($r['code']!==''){ $designationByCode[$r['code']] = $r['Description'] ?: $r['code']; } } } catch(Throwable $ie2){} }
  }
} catch(Exception $e){}
// designations table fallback (id/code -> description/name)
try {
  $hasDesignations = (bool)$pdo->query("SHOW TABLES LIKE 'designations'")->fetch();
  if($hasDesignations){
    $dcols = $pdo->query('SHOW COLUMNS FROM designations')->fetchAll(PDO::FETCH_COLUMN,0);
    $hasCode = in_array('code',$dcols,true);
    $hasDesc = in_array('description',$dcols,true);
    if($hasDesc){ foreach($pdo->query("SELECT id, description FROM designations") as $r){ $designationById[(int)$r['id']] = (string)$r['description']; } }
    else { foreach($pdo->query("SELECT id, name FROM designations") as $r){ $designationById[(int)$r['id']] = (string)$r['name']; } }
    if($hasCode){
      if($hasDesc){ foreach($pdo->query("SELECT code, description FROM designations") as $r){ if($r['code']!==''){ $designationByCode[$r['code']] = $r['description'] ?: ($designationByCode[$r['code']] ?? $r['code']); } } }
      else { foreach($pdo->query("SELECT code, name FROM designations") as $r){ if($r['code']!==''){ $designationByCode[$r['code']] = $r['name'] ?: ($designationByCode[$r['code']] ?? $r['code']); } } }
    } else {
      foreach($pdo->query("SELECT name FROM designations") as $r){ $designationByCode[$r['name']] = $r['name']; }
    }
  }
} catch(Exception $e){}
// Detect employees columns for designation and build designation maps
$empColsLower = [];$hasEmpDest=false;$hasEmpDesignationId=false;
try { $empColsLower = array_map('strtolower',$pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_COLUMN,0)); } catch(Exception $e){}
if($empColsLower){ $hasEmpDest = in_array('destcode',$empColsLower,true); $hasEmpDesignationId = in_array('designation_id',$empColsLower,true); }
// designation maps
$designationById=[]; $designationByCode=[];
// Prefer desigcode table (DesigCode/Code -> Description)
try { $hasDesigCode=(bool)$pdo->query("SHOW TABLES LIKE 'desigcode'")->fetch(); if($hasDesigCode){
  try { foreach($pdo->query("SELECT DesigCode AS code, Description FROM desigcode") as $r){ if($r['code']!==''){ $designationByCode[$r['code']] = $r['Description'] ?: $r['code']; } } }
  catch(Throwable $e){ try { foreach($pdo->query("SELECT Code AS code, Description FROM desigcode") as $r){ if($r['code']!==''){ $designationByCode[$r['code']] = $r['Description'] ?: $r['code']; } } } catch(Throwable $e2){} }
} } catch(Exception $e){}
// designations table fallback (id/code -> description/name)
try { $hasDesignations=(bool)$pdo->query("SHOW TABLES LIKE 'designations'")->fetch(); if($hasDesignations){
  $dcols = $pdo->query('SHOW COLUMNS FROM designations')->fetchAll(PDO::FETCH_COLUMN,0);
  $hasCodeCol = in_array('code',$dcols,true);
  $hasDescCol = in_array('description',$dcols,true);
  if($hasDescCol){ foreach($pdo->query("SELECT id, description FROM designations") as $r){ $designationById[(int)$r['id']] = (string)$r['description']; } }
  else { foreach($pdo->query("SELECT id, name FROM designations") as $r){ $designationById[(int)$r['id']] = (string)$r['name']; } }
  if($hasCodeCol){
    if($hasDescCol){ foreach($pdo->query("SELECT code, description FROM designations") as $r){ if($r['code']!==''){ $designationByCode[$r['code']] = $r['description'] ?: ($designationByCode[$r['code']] ?? $r['code']); } } }
    else { foreach($pdo->query("SELECT code, name FROM designations") as $r){ if($r['code']!==''){ $designationByCode[$r['code']] = $r['name'] ?: ($designationByCode[$r['code']] ?? $r['code']); } } }
  } else {
    foreach($pdo->query("SELECT name FROM designations") as $r){ $designationByCode[$r['name']] = $r['name']; }
  }
} } catch(Exception $e){}

// Load local employees (include DestCode/designation_id if exists)
try {
  $extra = [];
  if($hasEmpDest) $extra[]='DestCode';
  if($hasEmpDesignationId) $extra[]='designation_id';
  $sel = 'id, EmpNo, Name, DeptCode' . ($extra? ', '.implode(',', $extra):'');
  $employeesLocal = $pdo->query("SELECT $sel FROM employees ORDER BY Name LIMIT 1500")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// If employeemaster exists, build a quick lookup to fix misaligned imported data (cases where Name holds numeric string etc.)
if ($fromMaster) {
  try {
    $masterRows = $pdo->query("SELECT EmpNo, Name, DeptCode FROM employeemaster")->fetchAll(PDO::FETCH_ASSOC);
    $masterMap = [];
    foreach ($masterRows as $mr) {
      $masterMap[$mr['EmpNo']] = $mr;
    }
    foreach ($employeesLocal as &$loc) {
      if (isset($masterMap[$loc['EmpNo']])) {
        // Overwrite display name & dept if master has it
        $loc['Name'] = $masterMap[$loc['EmpNo']]['Name'] ?: $loc['Name'];
        if (!empty($masterMap[$loc['EmpNo']]['DeptCode'])) {
          $loc['DeptCode'] = $masterMap[$loc['EmpNo']]['DeptCode'];
        }
      } else {
        // Heuristic correction: if Name looks like numeric EmpNo and DeptCode not numeric, maybe shifted import
        if (ctype_digit((string)$loc['Name']) && !ctype_digit((string)$loc['EmpNo'])) {
          $tmp = $loc['EmpNo'];
          $loc['EmpNo'] = $loc['Name'];
          $loc['Name'] = $tmp;
        }
      }
    }
    unset($loc);
  } catch (Exception $e) {
  }
}

// Load Japan employees: prefer japanemployees
try {
  $chkjapanemployees = $pdo->query("SHOW TABLES LIKE 'japanemployees'")->fetch();
  if ($chkjapanemployees) {
    $hasJapan = true;
    $rows = $pdo->query("SELECT EmpNo, Name, DeptCode FROM japanemployees ORDER BY Name LIMIT 500")->fetchAll(PDO::FETCH_ASSOC);
    // Attempt map to employees.id; if not found create a minimal placeholder so FK will not fail later.
    $mapStmt = $pdo->prepare("SELECT id, DeptCode FROM employees WHERE EmpNo=? LIMIT 1");
    $insertEmp = $pdo->prepare("INSERT INTO employees (EmpNo, Name, DeptCode) VALUES (?,?,?)");
    foreach ($rows as $r) {
      $id = null;
      try {
        $mapStmt->execute([$r['EmpNo']]);
        $m = $mapStmt->fetch(PDO::FETCH_ASSOC);
        if ($m) {
          $id = $m['id'];
          if (!$r['DeptCode'] && $m['DeptCode']) $r['DeptCode'] = $m['DeptCode'];
        } else {
          // create placeholder employee to satisfy FK
          try {
            $insertEmp->execute([$r['EmpNo'], $r['Name'] ?: $r['EmpNo'], $r['DeptCode']]);
            $id = (int)$pdo->lastInsertId();
          } catch (Exception $ie) { /* ignore on race condition */
            $mapStmt->execute([$r['EmpNo']]);
            $m2 = $mapStmt->fetch(PDO::FETCH_ASSOC);
            if ($m2) $id = $m2['id'];
          }
        }
      } catch (Exception $e) {
      }
      $r['id'] = $id;
      $employeesJapan[] = $r;
    }
  } else {
    // fallback employees_japan
    $chk = $pdo->query("SHOW TABLES LIKE 'employees_japan'")->fetch();
    if ($chk) {
      $hasJapan = true;
      $employeesJapan = $pdo->query("SELECT id, EmpNo, Name, DeptCode FROM employees_japan ORDER BY Name LIMIT 300")->fetchAll(PDO::FETCH_ASSOC);
    }
  }
} catch (Exception $e) {
}

// Simple currency rates (placeholder; ideally pulled from config table)
$rate_idr_to_sgn = 0.000085; // example
$rate_idr_to_yen = 0.0095;   // example

$isEmployeeUser = ($user['role'] === 'employee');
$lockedEmployeeId = $isEmployeeUser && !empty($user['employee_id']) ? (int)$user['employee_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
  // Collect posted fields
  if ($lockedEmployeeId) {
    $employeeSource = 'local';
    $selectedEmpId = $lockedEmployeeId;
  } else {
    $employeeSource = post('employee_source') === 'japan' ? 'japan' : 'local';
    $selectedEmpId = (int)post('employee_id');
  }
  $empRow = null;
  if ($selectedEmpId) {
    $table = $employeeSource === 'japan' && $hasJapan ? 'employees_japan' : 'employees';
    $st = $pdo->prepare("SELECT EmpNo, Name, DeptCode FROM $table WHERE id=?");
    $st->execute([$selectedEmpId]);
    $empRow = $st->fetch(PDO::FETCH_ASSOC);
  }
  $periode_from = post('period_from');
  $periode_to = post('period_to');
  $tujuanDistrict = trim(post('destination_district'));
  $tujuanCompany = trim(post('destination_company'));
  $purpose = trim(post('purpose'));
  $tempPayment = (float)post('temporary_payment');
  $currencyEntered = post('currency');
  if (!in_array($currencyEntered, ['IDR', 'SGN', 'YEN'])) $currencyEntered = 'IDR';
  // Convert amounts to all currencies for storage snapshot
  $amount_idr = $amount_sgn = $amount_yen = 0;
  switch ($currencyEntered) {
    case 'IDR':
      $amount_idr = $tempPayment;
      $amount_sgn = $tempPayment * $rate_idr_to_sgn;
      $amount_yen = $tempPayment * $rate_idr_to_yen;
      break;
    case 'SGN': // assume SGN is SGD? reverse convert using rate
      $amount_sgn = $tempPayment;
      $amount_idr = $rate_idr_to_sgn ? $tempPayment / $rate_idr_to_sgn : 0;
      $amount_yen = $amount_idr * $rate_idr_to_yen;
      break;
    case 'YEN':
      $amount_yen = $tempPayment;
      $amount_idr = $rate_idr_to_yen ? $tempPayment / $rate_idr_to_yen : 0;
      $amount_sgn = $amount_idr * $rate_idr_to_sgn;
      break;
  }

  // Insert minimal fields even if extended columns not yet added, by detecting columns
  $cols = $pdo->query("SHOW COLUMNS FROM trips")->fetchAll(PDO::FETCH_COLUMN, 0);
  $fieldMap = [
    // Use the selected employee (not the logged-in user) for FK integrity.
    'employee_id' => $selectedEmpId ?: $user['employee_id'] ?? $user['id'],
    'employee_source' => $employeeSource,
    'emp_no' => $empRow['EmpNo'] ?? null,
    'emp_name' => $empRow['Name'] ?? null,
    'emp_department' => $empRow['DeptCode'] ?? null,
    'emp_description' => post('emp_description'),
    'tujuan' => $tujuanDistrict, // store district into tujuan for backward compatibility
    'destination_district' => $tujuanDistrict,
    'destination_company' => $tujuanCompany,
    'tanggal' => $periode_from ?: date('Y-m-d'),
    'period_from' => $periode_from,
    'period_to' => $periode_to,
    'biaya_estimasi' => $amount_idr,
    'currency_entered' => $currencyEntered,
    'temp_payment_idr' => $amount_idr,
    'temp_payment_sgn' => $amount_sgn,
    'temp_payment_yen' => $amount_yen,
    'keperluan' => $purpose ?: 'Trip',
    'purpose' => $purpose,
    'data_for_collection' => post('data_for_collection')
  ];
  $present = array_intersect(array_keys($fieldMap), $cols);
  $insertCols = $present;
  $placeholders = array_fill(0, count($insertCols), '?');
  $values = [];
  foreach ($insertCols as $c) {
    $values[] = $fieldMap[$c];
  }
  $sql = 'INSERT INTO trips(' . implode(',', $insertCols) . ') VALUES(' . implode(',', $placeholders) . ')';
  $st = $pdo->prepare($sql);
  $st->execute($values);
  // Show popup confirmation after insert
  if ($isEmployeeUser) {
    $_SESSION['trip_created'] = 1; // flag for create page (since employee can't open index listing)
    redirect('index.php?page=trips/create&success=1');
  } else {
    $_SESSION['trip_created'] = 1; // flag for trips/index page
    redirect('index.php?page=trips/index');
  }
}
?>
<div class="container-fluid">
  <?php
  $showSuccess = isset($_GET['success']) || (!empty($_SESSION['trip_created_displayed']) && $_SESSION['trip_created_displayed'] == 0 && !empty($_SESSION['trip_created']));
  if (isset($_SESSION['trip_created'])) {
    $_SESSION['trip_created_displayed'] = 1;
    unset($_SESSION['trip_created']);
  }
  ?>
  <?php if ($showSuccess): ?>
    <div id="tripSuccessToast" class="alert alert-success shadow-sm position-fixed top-0 end-0 m-3" style="z-index:1055;min-width:260px;">
      <strong><i class="fas fa-check-circle me-1"></i> Success!</strong><br>Trip request has been submitted.
      <button type="button" class="btn-close ms-2 float-end" onclick="this.parentElement.remove()"></button>
    </div>
    <script>
      setTimeout(() => {
        var el = document.getElementById('tripSuccessToast');
        if (el) el.remove();
      }, 5000);
    </script>
  <?php endif; ?>
  <div class="row">
    <div class="col-lg-10 col-xl-8 mx-auto">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
          <div class="d-flex align-items-center">
            <div class="me-3">
              <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="fas fa-plane text-primary" style="font-size: 20px;"></i>
              </div>
            </div>
            <div>
              <h4 class="mb-1 fw-semibold">Create New Trip Request</h4>
              <p class="text-muted mb-0 small">Fill in the details for your business trip</p>
            </div>
          </div>
        </div>
        <div class="card-body p-4">
          <form method="post" id="tripForm">
            <?= csrf_field(); ?>

            <div class="mb-4">
              <h6 class="text-muted text-uppercase small fw-semibold mb-3 border-bottom pb-2">Employee Information</h6>
              <?php if ($lockedEmployeeId): ?>
                <?php $lr = null;
                try {
                  // Include designation-related columns if they exist for accurate Position display
                  $selColsLocked = 'EmpNo, Name, DeptCode';
                  if (!empty($hasEmpDest)) $selColsLocked .= ', DestCode';
                  if (!empty($hasEmpDesignationId)) $selColsLocked .= ', designation_id';
                  $st = $pdo->prepare("SELECT $selColsLocked FROM employees WHERE id=? LIMIT 1");
                  $st->execute([$lockedEmployeeId]);
                  $lr = $st->fetch(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                } ?>
                <input type="hidden" name="employee_source" value="local">
                <input type="hidden" name="employee_id" value="<?= (int)$lockedEmployeeId ?>">
                <div class="row g-3">
                  <div class="col-md-3 col-sm-6">
                    <label class="form-label small text-muted mb-1">Employee No</label>
                    <input class="form-control" value="<?= esc($lr['EmpNo'] ?? 'N/A') ?>" readonly>
                  </div>
                  <div class="col-md-4 col-sm-6">
                    <label class="form-label small text-muted mb-1">Name</label>
                    <input class="form-control" value="<?= esc($lr['Name'] ?? '') ?>" readonly>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <label class="form-label small text-muted mb-1">Department</label>
                    <input class="form-control" value="<?= isset($lr['DeptCode']) && isset($deptNameMap[$lr['DeptCode']]) ? esc($deptNameMap[$lr['DeptCode']]) : '-' ?>" readonly>
                  </div>
                  <div class="col-md-2 col-sm-6">
                    <label class="form-label small text-muted mb-1">Position</label>
                    <?php
                      $lockedDesigDesc='';
                      if(!empty($lr['designation_id']) && isset($designationById[(int)$lr['designation_id']])){ $lockedDesigDesc = $designationById[(int)$lr['designation_id']]; }
                      elseif(!empty($lr['DestCode']) && isset($designationByCode[$lr['DestCode']])){ $lockedDesigDesc = $designationByCode[$lr['DestCode']]; }
                    ?>
                    <input class="form-control" value="<?= esc($lockedDesigDesc) ?>" readonly>
                  </div>
                </div>
              <?php else: ?>
                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label fw-medium mb-2">Employee Source</label>
                    <div class="d-flex gap-4">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="employee_source" value="local" checked id="sourceLocal">
                        <label class="form-check-label fw-medium" for="sourceLocal">
                          <span class="badge bg-success bg-opacity-15 text-success me-2">●</span>Local Employee
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="employee_source" value="japan" id="sourceJapan">
                        <label class="form-check-label fw-medium" for="sourceJapan">
                          <span class="badge bg-info bg-opacity-15 text-info me-2">●</span>Japan Employee
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-5">
                    <label class="form-label fw-medium mb-2">Select Employee</label>
                    <div class="position-relative">
                      <input type="text" id="employeeFilter" class="form-control form-control-lg border-2 mb-2" placeholder="Search by Employee Number or Name..." autocomplete="off" style="border-radius: 8px;">
                      <select name="employee_id" id="employeeSelect" class="form-select border-2" size="6" required style="border-radius: 8px; font-size: 0.9rem; background: #fafafa;">
                        <option value="" class="text-muted">-- Select an employee --</option>
                        <?php foreach ($employeesLocal as $e): ?>
                          <?php $desigDescOpt=''; if(!empty($e['designation_id']) && isset($designationById[(int)$e['designation_id']])){ $desigDescOpt=$designationById[(int)$e['designation_id']]; } elseif(!empty($e['DestCode']) && isset($designationByCode[$e['DestCode']])) { $desigDescOpt=$designationByCode[$e['DestCode']]; } ?>
                          <option data-source="local" data-empno="<?= esc($e['EmpNo']) ?>" data-name="<?= esc($e['Name']) ?>" data-dept="<?= esc($e['DeptCode']) ?>" data-desig-desc="<?= esc($desigDescOpt) ?>" value="<?= $e['id'] ?>" style="padding: 8px;">
                            <span class="fw-medium"><?= esc($e['EmpNo']) ?></span> • <?= esc($e['Name']) ?>
                          </option>
                        <?php endforeach; ?>
                        <?php if ($hasJapan): ?>
                          <optgroup label="Japan Employees">
                            <?php foreach ($employeesJapan as $e): ?>
                              <option data-source="japan" data-empno="<?= esc($e['EmpNo']) ?>" data-name="<?= esc($e['Name']) ?>" data-dept="<?= esc($e['DeptCode']) ?>" data-desig-desc="" value="<?= $e['id'] ?>" style="padding: 8px;">
                                <span class="fw-medium"><?= esc($e['EmpNo']) ?></span> • <?= esc($e['Name']) ?>
                              </option>
                            <?php endforeach; ?>
                          </optgroup>
                        <?php endif; ?>
                      </select>
                      <div class="form-text"><small class="text-muted">Source: <?= $fromMaster ? 'Employee Master Database' : 'Standard Database'; ?> • Type to filter results</small></div>
                    </div>
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="form-label fw-medium mb-2">Employee Number</label>
                    <input name="emp_no" id="empNo" class="form-control border-2" readonly style="border-radius: 8px; background: #f8f9fa;">
                  </div>
                  <div class="col-lg-2 col-md-4 col-sm-6">
                    <label class="form-label fw-medium mb-2">Department</label>
                    <input name="emp_department" id="empDept" class="form-control border-2" readonly style="border-radius: 8px; background: #f8f9fa;">
                  </div>
                  <div class="col-lg-3 col-md-4 col-sm-6">
                    <label class="form-label fw-medium mb-2">Position</label>
                    <input id="empDeptName" class="form-control border-2" readonly style="border-radius: 8px; background:#f8f9fa;">
                  </div>
                  <div class="col-lg-3 col-md-8 col-sm-12">
                    <label class="form-label fw-medium mb-2">Description</label>
                    <input name="emp_description" id="empDesc" class="form-control border-2" placeholder="Optional description" style="border-radius: 8px;">
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <!-- Trip Details Section -->
            <div class="mb-4">
              <h6 class="text-muted text-uppercase small fw-semibold mb-3 border-bottom pb-2">Trip Schedule</h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-medium mb-2">Period From</label>
                  <input type="date" name="period_from" class="form-control form-control-lg border-2" required
                    style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-medium mb-2">Period To</label>
                  <input type="date" name="period_to" class="form-control form-control-lg border-2" required
                    style="border-radius: 8px;">
                </div>
              </div>
            </div>

            <!-- Destination Section -->
            <div class="mb-4">
              <h6 class="text-muted text-uppercase small fw-semibold mb-3 border-bottom pb-2">Destination Details</h6>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-medium mb-2">Destination District/City</label>
                  <input name="destination_district" class="form-control form-control-lg border-2" required
                    placeholder="e.g., Jakarta, Singapore, Tokyo"
                    style="border-radius: 8px;">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-medium mb-2">Company/Client Name</label>
                  <input name="destination_company" class="form-control form-control-lg border-2" required
                    placeholder="Enter company or client name"
                    style="border-radius: 8px;">
                </div>
              </div>
            </div>

            <!-- Purpose & Payment Section -->
            <div class="row g-4 mb-4">
              <div class="col-lg-6">
                <h6 class="text-muted text-uppercase small fw-semibold mb-3 border-bottom pb-2">Trip Purpose</h6>
                <label class="form-label fw-medium mb-2">Purpose of Visit</label>
                <textarea name="purpose" class="form-control border-2" rows="4" required
                  placeholder="Describe the main purpose of this business trip..."
                  style="border-radius: 8px; resize: vertical;"></textarea>
              </div>
              <div class="col-lg-6">
                <h6 class="text-muted text-uppercase small fw-semibold mb-3 border-bottom pb-2">Financial Details</h6>
                <label class="form-label fw-medium mb-2">Temporary Payment Advance</label>
                <div class="input-group input-group-lg">
                  <select class="form-select border-2" name="currency" id="currencySel"
                    style="max-width: 120px; border-radius: 8px 0 0 8px;">
                    <option value="IDR">IDR</option>
                    <option value="SGN">SGD</option>
                    <option value="YEN">JPY</option>
                  </select>
                  <input type="number" step="0.01" min="0" name="temporary_payment" id="tempPayment"
                    class="form-control border-2" required placeholder="0.00"
                    style="border-radius: 0 8px 8px 0;">
                </div>
                <div class="mt-2 p-2 bg-light rounded" id="convertedInfo" style="border-radius: 6px;">
                  <small class="text-muted">Currency conversion will appear here</small>
                </div>
              </div>
            </div>

            <!-- Additional Information Section -->
            <div class="mb-4">
              <h6 class="text-muted text-uppercase small fw-semibold mb-3 border-bottom pb-2">Additional Information</h6>
              <label class="form-label fw-medium mb-2">
                Data for Collection <span class="text-muted">(Optional)</span>
              </label>
              <textarea name="data_for_collection" class="form-control border-2" rows="3"
                placeholder="Any additional information, documents, or data to be collected during the trip..."
                style="border-radius: 8px; resize: vertical;"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="border-top pt-4 mt-4">
              <div class="d-flex justify-content-end gap-3">
                <a href="index.php?page=trips/index" class="btn btn-outline-secondary btn-lg px-4"
                  style="border-radius: 8px;">
                  Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-lg px-4"
                  style="border-radius: 8px;">
                  Submit Trip Request
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const empNo = document.getElementById('empNo');
    const empDept = document.getElementById('empDept');
    const empDeptName = document.getElementById('empDeptName');
    const desc = document.getElementById('empDesc');
    const sel = document.getElementById('employeeSelect');
    const filter = document.getElementById('employeeFilter');
    const deptMap = <?= json_encode($deptNameMap, JSON_UNESCAPED_UNICODE) ?>;
    if (sel && filter) {
      sel.addEventListener('change', () => {
        const opt = sel.options[sel.selectedIndex];
        empNo && (empNo.value = opt.getAttribute('data-empno') || '');
        if (empDept) {
          const code = opt.getAttribute('data-dept') || '';
          // Show department NAME (fallback to code if name missing)
          empDept.value = (code && deptMap[code]) ? deptMap[code] : (code || '');
        }
        if (empDeptName) {
          // Show Position = designation description provided in data-desig-desc
          empDeptName.value = opt.getAttribute('data-desig-desc') || '';
        }
        if (desc && !desc.value) desc.value = opt.getAttribute('data-name') || '';
        if (opt && opt.getAttribute('data-source')) {
          document.querySelectorAll('input[name="employee_source"]').forEach(r => {
            r.checked = (r.value === opt.getAttribute('data-source'));
          });
        }
        [empNo, empDept, empDeptName].forEach(input => {
          if (input && input.value) {
            input.classList.add('border-success');
            setTimeout(() => input.classList.remove('border-success'), 2000);
          }
        });
      });
      let filterTimeout;
      filter.addEventListener('input', () => {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
          const q = filter.value.toLowerCase().trim();
          let visibleCount = 0;
          for (const opt of sel.options) {
            if (!opt.value) continue;
            const empno = (opt.getAttribute('data-empno') || '').toLowerCase();
            const name = (opt.getAttribute('data-name') || '').toLowerCase();
            const isVisible = !q || empno.includes(q) || name.includes(q);
            opt.hidden = !isVisible;
            if (isVisible) visibleCount++;
          }
          filter.classList.toggle('border-success', visibleCount > 0 && q.length > 0);
          filter.classList.toggle('border-warning', visibleCount === 0 && q.length > 0);
        }, 200);
      });
    }

    // Enhanced currency conversion with better formatting
    let rateIdrToSgn = Number(<?= json_encode($rate_idr_to_sgn) ?>) || 0;
    let rateIdrToYen = Number(<?= json_encode($rate_idr_to_yen) ?>) || 0;
    const tempInput = document.getElementById('tempPayment');
    const currencySel = document.getElementById('currencySel');
    const info = document.getElementById('convertedInfo');

    function updateConverted() {
      if (!tempInput || !currencySel || !info) return;
      const valRaw = tempInput.value;
      const val = parseFloat(valRaw) || 0;
      const cur = currencySel.value;
      let idr = 0,
        sgd = 0,
        yen = 0;
      switch (cur) {
        case 'IDR':
          idr = val;
          sgd = val * rateIdrToSgn;
          yen = val * rateIdrToYen;
          break;
        case 'SGN': // SGD
          sgd = val;
          idr = rateIdrToSgn ? val / rateIdrToSgn : 0;
          yen = idr * rateIdrToYen;
          break;
        case 'YEN':
          yen = val;
          idr = rateIdrToYen ? val / rateIdrToYen : 0;
          sgd = idr * rateIdrToSgn;
          break;
      }
      info.dataset.rateIdrSgn = rateIdrToSgn;
      info.dataset.rateIdrYen = rateIdrToYen;
      info.innerHTML = `
      <div class="row g-2 small">
        <div class="col-4 text-center">
          <div class="fw-semibold text-primary">IDR</div>
          <div class="text-muted">${new Intl.NumberFormat('id-ID').format(Math.round(idr))}</div>
        </div>
        <div class="col-4 text-center">
          <div class="fw-semibold text-success">SGD</div>
          <div class="text-muted">${sgd.toFixed(2)}</div>
        </div>
        <div class="col-4 text-center">
          <div class="fw-semibold text-warning">JPY</div>
          <div class="text-muted">${Math.round(yen)}</div>
        </div>
      </div>
      <div class="text-center mt-1"><small class="text-muted">Base: ${cur || '-'} • Input: ${valRaw || '0'}</small></div>
    `;
    }

    if (tempInput) tempInput.addEventListener('input', () => {
      console.log('tempPayment input');
      updateConverted();
    });
    if (currencySel) currencySel.addEventListener('change', () => {
      console.log('currency change');
      updateConverted();
    });
    // Initial display
    console.log('init conversion');
    updateConverted();

    // Form validation enhancement
    const form = document.getElementById('tripForm');
    form.addEventListener('submit', (e) => {
      const requiredFields = form.querySelectorAll('[required]');
      let isValid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          field.classList.add('border-danger');
          isValid = false;
        } else {
          field.classList.remove('border-danger');
        }
      });

      if (!isValid) {
        e.preventDefault();
        // Scroll to first invalid field
        const firstInvalid = form.querySelector('.border-danger');
        if (firstInvalid) {
          firstInvalid.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
          });
          firstInvalid.focus();
        }
      }
    });

    // Initialize with today's date for period_from if empty
    const periodFrom = form.querySelector('[name="period_from"]');
    if (periodFrom && !periodFrom.value) {
      periodFrom.value = new Date().toISOString().split('T')[0];
    }
  });
</script>