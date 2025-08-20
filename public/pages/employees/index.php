<?php
require_role('admin');
$pdo = getPDO();

// Get filter parameter
$filter = $_GET['filter'] ?? 'all'; // 'all', 'regular', 'japan'

// Determine which schema we have: original normalized employees vs raw master-style columns
$hasEmpNo = false;
$useEmployeeMasterTable = false;
$rows = [];
try {
  $cols = $pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_COLUMN, 0);
  $colsLower = array_map('strtolower', $cols);
  if (in_array('empno', $colsLower)) {
    $hasEmpNo = true;
  }
} catch (Exception $e) {
  // employees table might not exist yet
}

if (!$hasEmpNo) {
  // Check alternate table employeemaster
  try {
    $t = $pdo->query("SHOW TABLES LIKE 'employeemaster'")->fetch();
    if ($t) {
      $useEmployeeMasterTable = true;
    }
  } catch (Exception $e) {
  }
}

// Fetch data based on filter
if ($hasEmpNo) {
  if ($filter === 'regular') {
    $stmt = $pdo->query("SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'regular' as employee_type FROM employees ORDER BY Name");
  } elseif ($filter === 'japan') {
    // Check if japanemployees table exists
    try {
      $japanTable = $pdo->query("SHOW TABLES LIKE 'japanemployees'")->fetch();
      if ($japanTable) {
        $stmt = $pdo->query("SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'japan' as employee_type FROM japanemployees ORDER BY Name");
      } else {
        $stmt = $pdo->query("SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'japan' as employee_type FROM employees WHERE DeptCode IN ('JP', 'JG', 'JD') ORDER BY Name");
      }
    } catch (Exception $e) {
      $stmt = $pdo->query("SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'japan' as employee_type FROM employees WHERE DeptCode IN ('JP', 'JG', 'JD') ORDER BY Name");
    }
  } else { // all
    try {
      $japanTable = $pdo->query("SHOW TABLES LIKE 'japanemployees'")->fetch();
      if ($japanTable) {
        $stmt = $pdo->query("
                    SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'regular' as employee_type FROM employees
                    UNION ALL
                    SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'japan' as employee_type FROM japanemployees
                    ORDER BY Name
                ");
      } else {
        $stmt = $pdo->query("SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 
                    CASE WHEN DeptCode IN ('JP', 'JG', 'JD') THEN 'japan' ELSE 'regular' END as employee_type 
                    FROM employees ORDER BY Name");
      }
    } catch (Exception $e) {
      $stmt = $pdo->query("SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 
                CASE WHEN DeptCode IN ('JP', 'JG', 'JD') THEN 'japan' ELSE 'regular' END as employee_type 
                FROM employees ORDER BY Name");
    }
  }
  $mode = 'raw-employees';
  $rows = $stmt->fetchAll();
} elseif ($useEmployeeMasterTable) {
  if ($filter === 'regular') {
    $stmt = $pdo->query("SELECT EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'regular' as employee_type FROM employeemaster WHERE DeptCode NOT IN ('JP', 'JG', 'JD') ORDER BY Name");
  } elseif ($filter === 'japan') {
    $stmt = $pdo->query("SELECT EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'japan' as employee_type FROM employeemaster WHERE DeptCode IN ('JP', 'JG', 'JD') ORDER BY Name");
  } else { // all
    $stmt = $pdo->query("SELECT EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 
            CASE WHEN DeptCode IN ('JP', 'JG', 'JD') THEN 'japan' ELSE 'regular' END as employee_type 
            FROM employeemaster ORDER BY Name");
  }
  $mode = 'employee-master';
  $rows = $stmt->fetchAll();
} else {
  // Legacy normalized structure
  $stmt = $pdo->query('SELECT e.id,e.name,d.name department,g.name team,ds.name designation, "regular" as employee_type
      FROM employees e
      LEFT JOIN departments d ON d.id=e.department_id
      LEFT JOIN `groups` g ON g.id=e.group_id
      LEFT JOIN designations ds ON ds.id=e.designation_id
      ORDER BY e.name');
  $mode = 'normalized';
  $rows = $stmt->fetchAll();
}

// Count statistics for badges
$stats = ['all' => 0, 'regular' => 0, 'japan' => 0];
if ($mode !== 'normalized') {
  foreach ($rows as $row) {
    $type = $row['employee_type'] ?? 'regular';
    $stats[$type]++;
    $stats['all']++;
  }
} else {
  $stats['all'] = count($rows);
  $stats['regular'] = count($rows);
}
?>
<div class="content-section fade-in">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="mb-2">Employee Management</h4>
      <p class="text-muted mb-0 small">Manage regular and Japanese employees</p>
    </div>
    <a href="index.php?page=employees/create" class="btn btn-primary btn-modern">
      <i class="fas fa-plus me-2"></i>Add Employee
    </a>
  </div>

  <!-- Filter Tabs -->
  <div class="card card-clean border-0 mb-4">
    <div class="card-body p-3">
      <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="text-muted small fw-semibold me-2">Filter by Type:</span>
        <div class="btn-group" role="group" aria-label="Employee filter">
          <input type="radio" class="btn-check" name="employee-filter" id="filter-all" <?= $filter === 'all' ? 'checked' : '' ?>>
          <label class="btn btn-outline-primary btn-sm" for="filter-all" onclick="filterEmployees('all')">
            <i class="fas fa-users me-1"></i>All Employees
            <?php if ($stats['all'] > 0): ?>
              <span class="badge bg-primary bg-opacity-20 text-primary ms-1"><?= $stats['all'] ?></span>
            <?php endif; ?>
          </label>

          <input type="radio" class="btn-check" name="employee-filter" id="filter-regular" <?= $filter === 'regular' ? 'checked' : '' ?>>
          <label class="btn btn-outline-success btn-sm" for="filter-regular" onclick="filterEmployees('regular')">
            <i class="fas fa-user me-1"></i>Regular Employees
            <?php if ($stats['regular'] > 0): ?>
              <span class="badge bg-success bg-opacity-20 text-success ms-1"><?= $stats['regular'] ?></span>
            <?php endif; ?>
          </label>

          <input type="radio" class="btn-check" name="employee-filter" id="filter-japan" <?= $filter === 'japan' ? 'checked' : '' ?>>
          <label class="btn btn-outline-info btn-sm" for="filter-japan" onclick="filterEmployees('japan')">
            <i class="fas fa-flag me-1"></i>Japanese Employees
            <?php if ($stats['japan'] > 0): ?>
              <span class="badge bg-info bg-opacity-20 text-info ms-1"><?= $stats['japan'] ?></span>
            <?php endif; ?>
          </label>
        </div>

        <?php if ($mode !== 'normalized'): ?>
          <div class="ms-auto">
            <span class="badge badge-modern bg-light text-dark">
              <i class="fas fa-database me-1"></i>Mode: <?= esc($mode) ?>
            </span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="card card-clean border-0">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-professional btms-table-modern datatable mb-0">
        <thead>
          <?php if ($mode === 'normalized'): ?>
            <tr>
              <th>Name</th>
              <th>Department</th>
              <th>Group</th>
              <th>Designation</th>
              <th width=120>Action</th>
            </tr>
          <?php else: ?>
            <tr>
              <th>Type</th>
              <th>EmpNo</th>
              <th>Name</th>
              <th>DeptCode</th>
              <th>ClasCode</th>
              <th>DestCode</th>
              <th>Sex</th>
              <th>Resigned</th>
              <th>GrpCode</th>
              <th width=160>Action</th>
            </tr>
          <?php endif; ?>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <?php if ($mode === 'normalized'): ?>
                <td data-label="Name"><?= esc($r['name']) ?></td>
                <td data-label="Department"><?= esc($r['department']) ?></td>
                <td data-label="Group"><?= esc($r['team']) ?></td>
                <td data-label="Designation"><?= esc($r['designation']) ?></td>
                <td data-label="Action">
                  <div class="btn-action-group">
                    <a class="btn btn-warning btn-action" href="index.php?page=employees/edit&id=<?= $r['id'] ?>">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a class="btn btn-danger btn-action" onclick="return confirm('Delete?')" href="index.php?page=employees/delete&id=<?= $r['id'] ?>">
                      <i class="fas fa-trash"></i>
                    </a>
                  </div>
                </td>
              <?php else: ?>
                <td data-label="Type">
                  <?php
                  $empType = $r['employee_type'] ?? 'regular';
                  if ($empType === 'japan'): ?>
                    <span class="badge badge-modern bg-info text-white">
                      <i class="fas fa-flag me-1"></i>Japan
                    </span>
                  <?php else: ?>
                    <span class="badge badge-modern bg-success text-white">
                      <i class="fas fa-user me-1"></i>Regular
                    </span>
                  <?php endif; ?>
                </td>
                <td data-label="EmpNo">
                  <span class="fw-semibold"><?= esc($r['EmpNo']) ?></span>
                </td>
                <td data-label="Name">
                  <div class="fw-semibold"><?= esc($r['Name']) ?></div>
                </td>
                <td data-label="DeptCode">
                  <?php if (!empty($r['DeptCode'])): ?>
                    <span class="badge badge-modern bg-light text-dark"><?= esc($r['DeptCode']) ?></span>
                  <?php endif; ?>
                </td>
                <td data-label="ClasCode"><?= esc(isset($r['ClasCode']) ? $r['ClasCode'] : '') ?></td>
                <td data-label="DestCode"><?= esc(isset($r['DestCode']) ? $r['DestCode'] : '') ?></td>
                <td data-label="Sex">
                  <?php if (!empty($r['Sex'])): ?>
                    <span class="badge badge-modern <?= $r['Sex'] === 'M' ? 'bg-primary' : 'bg-pink' ?> text-white">
                      <i class="fas fa-<?= $r['Sex'] === 'M' ? 'mars' : 'venus' ?> me-1"></i><?= $r['Sex'] === 'M' ? 'Male' : 'Female' ?>
                    </span>
                  <?php endif; ?>
                </td>
                <td data-label="Resigned">
                  <?php if (!empty($r['Resigned'])): ?>
                    <span class="badge badge-modern bg-warning text-dark">
                      <i class="fas fa-exclamation-triangle me-1"></i>Yes
                    </span>
                  <?php else: ?>
                    <span class="badge badge-modern bg-success text-white">
                      <i class="fas fa-check me-1"></i>Active
                    </span>
                  <?php endif; ?>
                </td>
                <td data-label="GrpCode"><?= esc(isset($r['GrpCode']) ? $r['GrpCode'] : '') ?></td>
                <td data-label="Action">
                  <div class="btn-action-group">
                    <?php if ($mode === 'raw-employees'): ?>
                      <a class="btn btn-warning btn-action" href="index.php?page=employees/edit&id=<?= $r['id'] ?>">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-danger btn-action btn-delete" data-mode="raw" data-id="<?= $r['id'] ?>" data-empno="<?= esc($r['EmpNo']) ?>">
                        <i class="fas fa-trash"></i>
                      </button>
                    <?php elseif ($mode === 'employee-master'): ?>
                      <button type="button" class="btn btn-danger btn-action btn-delete" data-mode="master" data-empno="<?= esc($r['EmpNo']) ?>">
                        <i class="fas fa-trash"></i>
                      </button>
                    <?php endif; ?>
                  </div>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white py-2">
        <h6 class="modal-title">Konfirmasi Hapus</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2 small">Masukkan <strong>EmpNo</strong> berikut untuk konfirmasi penghapusan:</p>
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="badge bg-secondary" id="delEmpNoBadge"></span>
          <span class="small text-muted" id="delInfoMode"></span>
        </div>
        <input type="text" class="form-control" id="confirmEmpNoInput" placeholder="Ketik ulang EmpNo" autocomplete="off">
        <div class="form-text" id="confirmHint">Ketik persis sama untuk mengaktifkan tombol hapus.</div>
        <div class="alert alert-warning small mt-3 mb-0" id="warnRaw" style="display:none;">Data akan dihapus permanen dari tabel sumber.</div>
      </div>
      <div class="modal-footer py-2">
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger btn-sm" id="btnExecuteDelete" disabled>Hapus</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Filter function
  function filterEmployees(type) {
    const url = new URL(window.location);
    url.searchParams.set('filter', type);
    window.location.href = url.toString();
  }

  window.addEventListener('load', function() {
    if (typeof bootstrap === 'undefined') return console.warn('Bootstrap JS not loaded');
    const modalEl = document.getElementById('deleteModal');
    if (!modalEl) return;
    let target = {
      mode: null,
      id: null,
      empno: null
    };
    const empNoBadge = document.getElementById('delEmpNoBadge');
    const infoMode = document.getElementById('delInfoMode');
    const input = document.getElementById('confirmEmpNoInput');
    const btnDelete = document.getElementById('btnExecuteDelete');
    const warnRaw = document.getElementById('warnRaw');
    const bsModal = new bootstrap.Modal(modalEl);
    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', () => {
        target.mode = btn.getAttribute('data-mode');
        target.id = btn.getAttribute('data-id');
        target.empno = btn.getAttribute('data-empno');
        empNoBadge.textContent = target.empno || '';
        infoMode.textContent = target.mode === 'master' ? '(Employee Master)' : '(Employees)';
        input.value = '';
        btnDelete.disabled = true;
        warnRaw.style.display = 'block';
        bsModal.show();
        setTimeout(() => input.focus(), 250);
      });
    });
    input.addEventListener('input', () => {
      const ok = input.value.trim() === (target.empno || '');
      btnDelete.disabled = !ok;
      input.classList.toggle('is-valid', ok);
      input.classList.toggle('is-invalid', !ok && input.value.length > 0);
    });
    btnDelete.addEventListener('click', () => {
      if (btnDelete.disabled) return;
      let url = 'index.php?page=employees/delete';
      if (target.mode === 'raw' && target.id) {
        url += '&id=' + encodeURIComponent(target.id) + '&confirm_empno=' + encodeURIComponent(target.empno);
      } else if (target.mode === 'master') {
        url += '&empno=' + encodeURIComponent(target.empno) + '&master=1&confirm_empno=' + encodeURIComponent(target.empno);
      }
      window.location.href = url;
    });
  });
</script>

<style>
  .bg-pink {
    background-color: #e91e63 !important;
  }

  .text-pink {
    color: #e91e63 !important;
  }
</style>