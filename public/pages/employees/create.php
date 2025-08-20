<?php
require_role('admin');
$pdo = getPDO();

// Determine if raw columns exist; if not, we can't use this form
$hasRaw = false;
$targetTable = 'employees';
$tableCols = [];
$needNo = false;
$noAuto = true;
try {
  $desc = $pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_ASSOC);
  foreach ($desc as $d) {
    $tableCols[] = $d['Field'];
  }
  $colsLower = array_map('strtolower', $tableCols);
  if (in_array('empno', $colsLower)) {
    $hasRaw = true;
  }
  // detect 'no' requirement
  foreach ($desc as $d) {
    if (strtolower($d['Field']) === 'no') {
      $needNo = (strpos(strtolower($d['Extra']), 'auto_increment') === false && $d['Null'] === 'NO' && $d['Default'] === null);
      if (strpos(strtolower($d['Extra']), 'auto_increment') !== false) {
        $noAuto = true;
      } else {
        $noAuto = false;
      }
    }
  }
} catch (Exception $e) {
}

if (!$hasRaw) {
  // fallback to employeemaster
  $t = $pdo->query("SHOW TABLES LIKE 'employeemaster'")->fetch();
  if ($t) {
    $targetTable = 'employeemaster';
    // describe employeemaster
    try {
      $desc = $pdo->query("DESCRIBE employeemaster")->fetchAll(PDO::FETCH_ASSOC);
      foreach ($desc as $d) {
        $tableCols[] = $d['Field'];
      }
      foreach ($desc as $d) {
        if (strtolower($d['Field']) === 'no') {
          $needNo = (strpos(strtolower($d['Extra']), 'auto_increment') === false && $d['Null'] === 'NO' && $d['Default'] === null);
          if (strpos(strtolower($d['Extra']), 'auto_increment') !== false) {
            $noAuto = true;
          } else {
            $noAuto = false;
          }
        }
      }
    } catch (Exception $e) {
    }
  } else {
    echo '<div class="alert alert-warning">Tidak ada struktur raw (EmpNo...) baik di employees maupun employeemaster.</div>';
    echo '<a class="btn btn-secondary" href="index.php?page=employees/index">Back</a>';
    return;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
  $baseCols = ['EmpNo', 'Name', 'DeptCode', 'ClasCode', 'DestCode', 'Sex', 'Resigned', 'GrpCode'];
  $insertCols = [];
  $values = [];
  if ($needNo) {
    $noVal = post('no');
    if ($noVal === null || $noVal === '') {
      // generate sequential if possible
      $max = $pdo->query("SELECT COALESCE(MAX(no),0)+1 FROM $targetTable")->fetchColumn();
      $noVal = $max ?: 1;
    }
    $insertCols[] = 'no';
    $values[] = (int)$noVal;
  }
  foreach ($baseCols as $c) {
    $exists = false;
    foreach ($tableCols as $tc) {
      if (strtolower($tc) === strtolower($c)) {
        $exists = true;
        break;
      }
    }
    if ($exists) {
      $v = post($c);
      if ($v === null) $v = '';
      $insertCols[] = $c;
      $values[] = trim((string)$v);
    }
  }
  $placeholders = implode(',', array_fill(0, count($insertCols), '?'));
  $sql = "INSERT INTO $targetTable (" . implode(',', $insertCols) . ") VALUES ($placeholders)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($values);
  redirect('index.php?page=employees/index');
}
?>
<h4>Tambah Karyawan</h4>
<form method="post" class="row g-3">
  <?= csrf_field(); ?>
  <?php if ($needNo): ?>
    <div class="col-md-2">
      <label class="form-label">No</label>
      <input name="no" class="form-control" required>
    </div>
  <?php endif; ?>
  <div class="col-md-3">
    <label class="form-label">EmpNo</label>
    <input name="EmpNo" class="form-control" required>
  </div>
  <div class="col-md-5">
    <label class="form-label">Name</label>
    <input name="Name" class="form-control" required>
  </div>
  <div class="col-md-2">
    <label class="form-label">DeptCode</label>
    <input name="DeptCode" class="form-control">
  </div>
  <div class="col-md-2">
    <label class="form-label">ClasCode</label>
    <input name="ClasCode" class="form-control">
  </div>
  <div class="col-md-2">
    <label class="form-label">DestCode</label>
    <input name="DestCode" class="form-control">
  </div>
  <div class="col-md-2">
    <label class="form-label">Sex</label>
    <select name="Sex" class="form-select">
      <option value="">-</option>
      <option value="M">M</option>
      <option value="F">F</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">GrpCode</label>
    <input name="GrpCode" class="form-control">
  </div>
  <div class="col-md-7">
    <label class="form-label">Resigned (kosongkan jika masih aktif)</label>
    <input name="Resigned" class="form-control">
  </div>
  <div class="col-12">
    <button class="btn btn-success">Simpan</button>
    <a href="index.php?page=employees/index" class="btn btn-secondary">Kembali</a>
  </div>
</form>