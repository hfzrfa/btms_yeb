<?php
require_role('admin');
$pdo = getPDO();
$id = (int)get('id');
$emp = $pdo->prepare('SELECT * FROM employees WHERE id=?');
$emp->execute([$id]);
$emp = $emp->fetch(PDO::FETCH_ASSOC);
if (!$emp) {
  echo '<div class="alert alert-danger">Not found</div>';
  return;
}

// Load reference lookups (tolerant if tables missing)
$departments = $groups = $designations = $clasCodes = $destCodes = [];
try {
  $departments = $pdo->query('SELECT id,name FROM departments ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}
try {
  $groups = $pdo->query('SELECT id,name FROM `groups` ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}
try {
  $designations = $pdo->query('SELECT id,name FROM designations ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}
try {
  $clasCodes = $pdo->query('SELECT Code, Classification FROM clasificcode ORDER BY Code')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}
try {
  $destCodes = $pdo->query('SELECT Code, Designation FROM desigcode ORDER BY Code')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
  $stmt = $pdo->prepare('UPDATE employees SET Name=?, DeptCode=?, ClasCode=?, DestCode=?, GrpCode=?, Sex=?, Resigned=?, department_id=?, designation_id=? WHERE id=?');
  $stmt->execute([
    trim(post('Name')),
    post('DeptCode') !== '' ? post('DeptCode') : null,
    post('ClasCode') !== '' ? post('ClasCode') : null,
    post('DestCode') !== '' ? post('DestCode') : null,
    post('GrpCode') !== '' ? post('GrpCode') : null,
    post('Sex') !== '' ? post('Sex') : null,
    trim(post('Resigned')) !== '' ? trim(post('Resigned')) : null,
    post('department_id') !== '' ? post('department_id') : null,
    post('designation_id') !== '' ? post('designation_id') : null,
    $id
  ]);
  redirect('index.php?page=employees/index');
}

function sel($a, $b)
{
  return (string)$a === (string)$b ? 'selected' : '';
}
?>
<h4 class="mb-3">Edit Employee</h4>
<form method="post" class="row g-3">
  <?= csrf_field(); ?>
  <div class="col-md-3">
    <label class="form-label">Emp No</label>
    <input class="form-control" value="<?= esc($emp['EmpNo']) ?>" readonly>
  </div>
  <div class="col-md-5">
    <label class="form-label">Name</label>
    <input name="Name" class="form-control" value="<?= esc($emp['Name']) ?>" required>
  </div>
  <div class="col-md-2">
    <label class="form-label">Sex</label>
    <select name="Sex" class="form-select">
      <option value="">-</option>
      <option value="M" <?= sel('M', $emp['Sex']) ?>>M</option>
      <option value="F" <?= sel('F', $emp['Sex']) ?>>F</option>
    </select>
  </div>
  <div class="col-md-2">
    <label class="form-label">DeptCode</label>
    <input name="DeptCode" class="form-control" value="<?= esc($emp['DeptCode']) ?>">
  </div>
  <div class="col-md-2">
    <label class="form-label">ClasCode</label>
    <select name="ClasCode" class="form-select">
      <option value="">-</option>
      <?php foreach ($clasCodes as $c): ?>
        <option value="<?= esc($c['Code']) ?>" <?= sel($c['Code'], $emp['ClasCode']) ?>><?= esc($c['Code']) ?> - <?= esc($c['Classification']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">DestCode (Designation Code)</label>
    <select name="DestCode" class="form-select">
      <option value="">-</option>
      <?php foreach ($destCodes as $d): ?>
        <option value="<?= esc($d['Code']) ?>" <?= sel($d['Code'], $emp['DestCode']) ?>><?= esc($d['Code']) ?> - <?= esc($d['Designation']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2">
    <label class="form-label">Group Code</label>
    <input name="GrpCode" class="form-control" value="<?= esc($emp['GrpCode']) ?>">
  </div>
  <div class="col-md-4">
    <label class="form-label">Department ( FK )</label>
    <select name="department_id" class="form-select">
      <option value="">-</option>
      <?php foreach ($departments as $d): ?>
        <option value="<?= $d['id'] ?>" <?= sel($d['id'], $emp['department_id']) ?>><?= esc($d['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Designation ( FK )</label>
    <select name="designation_id" class="form-select">
      <option value="">-</option>
      <?php foreach ($designations as $d): ?>
        <option value="<?= $d['id'] ?>" <?= sel($d['id'], $emp['designation_id']) ?>><?= esc($d['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Resigned Note</label>
    <input name="Resigned" class="form-control" placeholder="e.g. 2024-01-31" value="<?= esc($emp['Resigned']) ?>">
  </div>
  <div class="col-12">
    <hr>
    <button class="btn btn-success">Update</button>
    <a href="index.php?page=employees/index" class="btn btn-secondary">Back</a>
  </div>
</form>
<style>
  form .form-label {
    font-weight: 600;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #355471;
  }
</style>