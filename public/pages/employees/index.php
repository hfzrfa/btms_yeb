<?php
require_role('admin');
$pdo = getPDO();

// Parameters
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['q'] ?? '');
// Pagination params
$pageNum = max(1, (int)($_GET['p'] ?? 1));
$pageSize = (int)($_GET['ps'] ?? 20);
if (!in_array($pageSize, [20, 25, 50, 75, 100], true)) $pageSize = 20;
$offset = ($pageNum - 1) * $pageSize;
$hasSearch = $search !== '';
$searchLike = '%' . $search . '%';

// Detect schema
$hasEmpNo = false;
$useEmployeeMasterTable = false;
$hasDesignationId = false; // employees.designation_id exists?
$rows = [];
try {
  $cols = $pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_COLUMN, 0);
  $colsLower = array_map('strtolower', $cols);
  if (in_array('empno', $colsLower)) $hasEmpNo = true;
  if (in_array('designation_id', $colsLower)) $hasDesignationId = true;
} catch (Exception $e) { /* ignore */
}
if (!$hasEmpNo) {
  try {
    $t = $pdo->query("SHOW TABLES LIKE 'employeemaster'")->fetch();
    if ($t) $useEmployeeMasterTable = true;
  } catch (Exception $e) { /* ignore */
  }
}

// total count helper closure (raw modes only)
$totalRows = 0;
if ($hasEmpNo) {
  if ($filter === 'regular') {
    $sql = "SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, " . ($hasDesignationId ? "designation_id, " : "NULL AS designation_id,") . " Sex, Resigned, GrpCode, 'regular' as employee_type FROM employees";
    $params = [];
    if ($hasSearch) {
      $sql .= " WHERE (Name LIKE ? OR EmpNo LIKE ?)";
      $params = [$searchLike, $searchLike];
    }
    $sql .= " ORDER BY Name";
    // Count
    $countSql = 'SELECT COUNT(*) FROM (' . $sql . ') c';
    $cStmt = $pdo->prepare($countSql);
    $cStmt->execute($params);
    $totalRows = (int)$cStmt->fetchColumn();
    $sql .= " LIMIT $pageSize OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
  } elseif ($filter === 'japan') {
    $params = [];
    try {
      $japanTable = $pdo->query("SHOW TABLES LIKE 'japanemployees'")->fetch();
    } catch (Exception $e) {
      $japanTable = null;
    }
    if ($japanTable) {
      $sql = "SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, " . ($hasDesignationId ? "designation_id, " : "NULL AS designation_id,") . " Sex, Resigned, GrpCode, 'japan' as employee_type FROM japanemployees";
    } else {
      $sql = "SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, " . ($hasDesignationId ? "designation_id, " : "NULL AS designation_id,") . " Sex, Resigned, GrpCode, 'japan' as employee_type FROM employees WHERE DeptCode IN ('JP','JG','JD')";
    }
    if ($hasSearch) {
      if (stripos($sql, 'WHERE') !== false) $sql .= " AND (Name LIKE ? OR EmpNo LIKE ?)";
      else $sql .= " WHERE (Name LIKE ? OR EmpNo LIKE ?)";
      $params = [$searchLike, $searchLike];
    }
    $sql .= " ORDER BY Name";
    $countSql = 'SELECT COUNT(*) FROM (' . $sql . ') c';
    $cStmt = $pdo->prepare($countSql);
    $cStmt->execute($params);
    $totalRows = (int)$cStmt->fetchColumn();
    $sql .= " LIMIT $pageSize OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
  } else { // all
    $params = [];
    $japanExists = false;
    try {
      $japanExists = (bool)$pdo->query("SHOW TABLES LIKE 'japanemployees'")->fetch();
    } catch (Exception $e) {
      $japanExists = false;
    }
    if ($japanExists) {
      if ($hasSearch) {
        $sql = "SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, " . ($hasDesignationId ? "designation_id, " : "NULL AS designation_id,") . " Sex, Resigned, GrpCode, 'regular' as employee_type FROM employees WHERE (Name LIKE ? OR EmpNo LIKE ?)\n        UNION ALL\n        SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, " . ($hasDesignationId ? "designation_id, " : "NULL AS designation_id,") . " Sex, Resigned, GrpCode, 'japan' as employee_type FROM japanemployees WHERE (Name LIKE ? OR EmpNo LIKE ?)\n        ORDER BY Name";
        $countSql = 'SELECT COUNT(*) FROM (' . $sql . ') c';
        $cStmt = $pdo->prepare($countSql);
        $cStmt->execute([$searchLike, $searchLike, $searchLike, $searchLike]);
        $totalRows = (int)$cStmt->fetchColumn();
        $sql .= " LIMIT $pageSize OFFSET $offset";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$searchLike, $searchLike, $searchLike, $searchLike]);
      } else {
        $sql = "SELECT * FROM ((SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, " . ($hasDesignationId ? "designation_id, " : "NULL AS designation_id,") . " Sex, Resigned, GrpCode, 'regular' as employee_type FROM employees)\n        UNION ALL\n        (SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, " . ($hasDesignationId ? "designation_id, " : "NULL AS designation_id,") . " Sex, Resigned, GrpCode, 'japan' as employee_type FROM japanemployees)) u";
        $countSql = 'SELECT COUNT(*) FROM (' . $sql . ') c';
        $totalRows = (int)$pdo->query($countSql)->fetchColumn();
        $sql .= " ORDER BY Name LIMIT $pageSize OFFSET $offset";
        $stmt = $pdo->query($sql);
      }
    } else {
      $sql = "SELECT id, EmpNo, Name, DeptCode, ClasCode, DestCode, " . ($hasDesignationId ? "designation_id, " : "NULL AS designation_id,") . " Sex, Resigned, GrpCode, CASE WHEN DeptCode IN ('JP','JG','JD') THEN 'japan' ELSE 'regular' END as employee_type FROM employees";
      if ($hasSearch) {
        $sql .= " WHERE (Name LIKE ? OR EmpNo LIKE ?)";
        $params = [$searchLike, $searchLike];
      }
      $countSql = 'SELECT COUNT(*) FROM (' . $sql . ') c';
      $cStmt = $pdo->prepare($countSql);
      $cStmt->execute($params);
      $totalRows = (int)$cStmt->fetchColumn();
      $sql .= " ORDER BY Name LIMIT $pageSize OFFSET $offset";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($params);
    }
  }
  $mode = 'raw-employees';
  $rows = $stmt->fetchAll();
  // Map designation names if possible
  if ($hasDesignationId) {
    $ids = [];
    foreach ($rows as $r) {
      if (!empty($r['designation_id'])) $ids[$r['designation_id']] = true;
    }
    if ($ids) {
      $in = implode(',', array_map('intval', array_keys($ids)));
      try {
        $desMap = $pdo->query("SELECT id,name FROM designations WHERE id IN ($in)")->fetchAll(PDO::FETCH_KEY_PAIR);
      } catch (Exception $e) {
        $desMap = [];
      }
      foreach ($rows as &$r) {
        if (!empty($r['designation_id']) && isset($desMap[$r['designation_id']])) $r['DesignationName'] = $desMap[$r['designation_id']];
      }
      unset($r);
    }
  }
} elseif ($useEmployeeMasterTable) {
  $params = [];
  if ($filter === 'regular') {
    $sql = "SELECT EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'regular' as employee_type FROM employeemaster WHERE DeptCode NOT IN ('JP','JG','JD')";
    if ($hasSearch) {
      $sql .= " AND (Name LIKE ? OR EmpNo LIKE ?)";
      $params = [$searchLike, $searchLike];
    }
  } elseif ($filter === 'japan') {
    $sql = "SELECT EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, 'japan' as employee_type FROM employeemaster WHERE DeptCode IN ('JP','JG','JD')";
    if ($hasSearch) {
      $sql .= " AND (Name LIKE ? OR EmpNo LIKE ?)";
      $params = [$searchLike, $searchLike];
    }
  } else {
    $sql = "SELECT EmpNo, Name, DeptCode, ClasCode, DestCode, Sex, Resigned, GrpCode, CASE WHEN DeptCode IN ('JP','JG','JD') THEN 'japan' ELSE 'regular' END as employee_type FROM employeemaster";
    if ($hasSearch) {
      $sql .= " WHERE (Name LIKE ? OR EmpNo LIKE ?)";
      $params = [$searchLike, $searchLike];
    }
  }
  $countSql = 'SELECT COUNT(*) FROM (' . $sql . ') c';
  $cStmt = $pdo->prepare($countSql);
  $cStmt->execute($params);
  $totalRows = (int)$cStmt->fetchColumn();
  $sql .= " ORDER BY Name LIMIT $pageSize OFFSET $offset";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $mode = 'employee-master';
  $rows = $stmt->fetchAll();
  // Attempt map designation by DestCode->designations.name (names created from codes during import)
  if ($rows) {
    $codes = [];
    foreach ($rows as $r) {
      if (!empty($r['DestCode'])) $codes[$r['DestCode']] = true;
    }
    if ($codes) {
      $placeholders = implode(',', array_fill(0, count($codes), '?'));
      try {
        $stmtD = $pdo->prepare("SELECT name FROM designations WHERE name IN ($placeholders)");
        $stmtD->execute(array_keys($codes));
        $names = $stmtD->fetchAll(PDO::FETCH_COLUMN);
        $nameSet = array_flip($names);
        foreach ($rows as &$r) {
          if (!empty($r['DestCode']) && isset($nameSet[$r['DestCode']])) $r['DesignationName'] = $r['DestCode'];
        }
        unset($r);
      } catch (Exception $e) {
      }
    }
  }
} else {
  if ($hasSearch) {
    $baseNorm = 'SELECT e.id,e.name,d.name department,g.name team,ds.name designation, "regular" as employee_type
      FROM employees e
      LEFT JOIN departments d ON d.id=e.department_id
      LEFT JOIN `groups` g ON g.id=e.group_id
      LEFT JOIN designations ds ON ds.id=e.designation_id';
    $countSql = $baseNorm . ' WHERE e.name LIKE ?';
    $cStmt = $pdo->prepare('SELECT COUNT(*) FROM (' . $countSql . ') c');
    $cStmt->execute([$searchLike]);
    $totalRows = (int)$cStmt->fetchColumn();
    $stmt = $pdo->prepare($baseNorm . ' WHERE e.name LIKE ? ORDER BY e.name LIMIT ' . $pageSize . ' OFFSET ' . $offset);
    $stmt->execute([$searchLike]);
  } else {
    $baseNorm = 'SELECT e.id,e.name,d.name department,g.name team,ds.name designation, "regular" as employee_type
      FROM employees e
      LEFT JOIN departments d ON d.id=e.department_id
      LEFT JOIN `groups` g ON g.id=e.group_id
      LEFT JOIN designations ds ON ds.id=e.designation_id';
    $countSql = 'SELECT COUNT(*) FROM (' . $baseNorm . ') c';
    $totalRows = (int)$pdo->query($countSql)->fetchColumn();
    $stmt = $pdo->query($baseNorm . ' ORDER BY e.name LIMIT ' . $pageSize . ' OFFSET ' . $offset);
  }
  $mode = 'normalized';
  $rows = $stmt->fetchAll();
}

// Stats
$stats = ['all' => 0, 'regular' => 0, 'japan' => 0];
if (!isset($mode)) $mode = 'normalized';
if (!isset($rows) || !is_array($rows)) $rows = [];
if ($mode !== 'normalized') {
  foreach ($rows as $r) {
    $t = $r['employee_type'] ?? 'regular';
    $stats[$t]++;
    $stats['all']++;
  }
} else {
  $stats['all'] = $totalRows;
  $stats['regular'] = $totalRows;
}

// Build DeptCode -> Department Name map (raw modes)
$deptNameMap = [];
try {
  $drows = $pdo->query('SELECT dept_code,name FROM departments')->fetchAll();
  foreach ($drows as $dr) {
    $deptNameMap[$dr['dept_code']] = $dr['name'];
  }
} catch (Throwable $e) { /* ignore */
}

// Pagination calc
$totalPages = max(1, (int)ceil(($totalRows ?: 0) / $pageSize));
if ($pageNum > $totalPages) {
  $pageNum = $totalPages;
}
?>
<div class="content-section fade-in">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="mb-2">Employee Management</h4>
      <p class="text-muted mb-0 small">Manage regular and Japanese employees</p>
    </div>
    <div class="d-flex gap-2">
      <!-- <a href="index.php?page=employees/import" class="btn btn-outline-secondary btn-modern">
        <i class="fas fa-file-upload me-2"></i>Import
      </a> -->
      <!-- <a href="index.php?page=users/sync" class="btn btn-outline-primary btn-modern">
        <i class="fas fa-users-cog me-2"></i>Sync Users
      </a> -->
      <a href="index.php?page=employees/create" class="btn btn-primary btn-modern">
        <i class="fas fa-plus me-2"></i>Add Employee
      </a>
    </div>
  </div>

  <!-- Filter Tabs -->
  <div class="card card-clean border-0 mb-4">
    <div class="card-body p-3">
      <div class="d-flex flex-wrap align-items-center gap-2 w-100">
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

        <div class="ms-auto d-flex align-items-center gap-2">
          <form id="employeeSearchForm" method="get" action="index.php" class="d-flex align-items-center gap-2">
            <input type="hidden" name="page" value="employees/index">
            <input type="hidden" name="filter" value="<?= esc($filter) ?>">
            <div class="input-group input-group-sm position-relative" style="overflow:visible;">
              <input type="text" name="q" id="searchInput" class="form-control" placeholder="Search name / EmpNo" value="<?= esc($search) ?>" autocomplete="off" />
              <div id="searchSuggest" class="list-group position-absolute shadow-sm" style="top:100%;left:0;right:0;z-index:30; max-height:260px; overflow:auto; display:none;"></div>
              <?php if ($hasSearch): ?>
                <button class="btn btn-outline-secondary" type="button" title="Clear" onclick="document.querySelector('#employeeSearchForm input[name=q]').value=''; document.getElementById('employeeSearchForm').submit();">&times;</button>
              <?php endif; ?>
              <button class="btn btn-primary" type="submit" title="Search"><i class="fas fa-search"></i></button>
            </div>
          </form>
          <form method="get" class="ms-2">
            <input type="hidden" name="page" value="employees/index">
            <input type="hidden" name="filter" value="<?= esc($filter) ?>">
            <?php if ($hasSearch): ?><input type="hidden" name="q" value="<?= esc($search) ?>"><?php endif; ?>
            <select name="ps" class="form-select form-select-sm" onchange="this.form.submit()">
              <?php foreach ([20, 25, 50, 75, 100] as $ps): ?>
                <option value="<?= $ps ?>" <?= $ps === $pageSize ? 'selected' : '' ?>><?= $ps ?></option>
              <?php endforeach; ?>
            </select>
          </form>
          <?php if ($mode !== 'normalized'): ?>
            <span class="badge badge-modern bg-light text-dark">
              <i class="fas fa-database me-1"></i>Mode: <?= esc($mode) ?>
            </span>
          <?php endif; ?>
        </div>
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
              <th>Dept</th>
              <th>Designation</th>
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
                <td data-label="Dept">
                  <?php if (!empty($r['DeptCode'])): $dc = $r['DeptCode'];
                    $dn = $deptNameMap[$dc] ?? ''; ?>
                    <div class="small fw-semibold mb-1"><span class="badge badge-modern bg-light text-dark"><?= esc($dc) ?></span></div>
                    <?php if ($dn): ?><div class="small text-muted" style="max-width:120px;white-space:normal; line-height:1.1;"><?= esc($dn) ?></div><?php endif; ?>
                  <?php endif; ?>
                </td>
                <td data-label="Designation"><?php $dName = $r['DesignationName'] ?? '';
                                              if (!$dName && !empty($r['DestCode'])) $dName = $r['DestCode'];
                                              if ($dName): ?><span class="badge bg-secondary-subtle text-dark border"><?= esc($dName) ?></span><?php endif; ?></td>
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
      <?php if ($totalPages > 1): ?>
        <nav class="p-2">
          <ul class="pagination pagination-sm mb-0 flex-wrap">
            <?php
            $baseUrl = function ($p) use ($filter, $search, $pageSize) {
              $u = new \stdClass();
              $query = ['page' => 'employees/index', 'filter' => $filter, 'p' => $p, 'ps' => $pageSize];
              if ($search !== '') $query['q'] = $search;
              return 'index.php?' . http_build_query($query);
            };
            ?>
            <li class="page-item <?= $pageNum <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= $baseUrl(max(1, $pageNum - 1)) ?>">&laquo;</a></li>
            <?php
            $window = 3;
            $start = max(1, $pageNum - $window);
            $end = min($totalPages, $pageNum + $window);
            if ($start > 1) {
              echo '<li class="page-item"><a class="page-link" href="' . $baseUrl(1) . '">1</a></li>';
              if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
            }
            for ($i = $start; $i <= $end; $i++) {
              echo '<li class="page-item ' . ($i == $pageNum ? 'active' : '') . '"><a class="page-link" href="' . $baseUrl($i) . '">' . $i . '</a></li>';
            }
            if ($end < $totalPages) {
              if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
              echo '<li class="page-item"><a class="page-link" href="' . $baseUrl($totalPages) . '">' . $totalPages . '</a></li>';
            }
            ?>
            <li class="page-item <?= $pageNum >= $totalPages ? 'disabled' : '' ?>"><a class="page-link" href="<?= $baseUrl(min($totalPages, $pageNum + 1)) ?>">&raquo;</a></li>
          </ul>
        </nav>
      <?php endif; ?>
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
    url.searchParams.set('page', 'employees/index');
    url.searchParams.set('filter', type);
    url.searchParams.set('p', '1');
    // preserve search query if present
    const qInput = document.querySelector('#employeeSearchForm input[name=q]');
    if (qInput && qInput.value.trim() !== '') {
      url.searchParams.set('q', qInput.value.trim());
    } else {
      url.searchParams.delete('q');
    }
    window.location.href = url.toString();
  }

  // Live suggestion
  const searchEl = document.getElementById('searchInput');
  const suggestBox = document.getElementById('searchSuggest');
  let suggestTimer = null;

  function hideSuggest() {
    suggestBox.style.display = 'none';
    suggestBox.innerHTML = '';
  }

  function fetchSuggest(v) {
    if (!v) {
      hideSuggest();
      return;
    }
    fetch('index.php?page=employees/suggest&q=' + encodeURIComponent(v))
      .then(r => r.json())
      .then(rows => {
        if (!Array.isArray(rows) || rows.length === 0) {
          hideSuggest();
          return;
        }
        suggestBox.innerHTML = rows.map(r => `<button type="button" class="list-group-item list-group-item-action py-1 px-2" data-val="${(r.empno||'').replace(/"/g,'&quot;')}" data-name="${(r.name||'').replace(/"/g,'&quot;')}"><strong>${r.empno||''}</strong> - ${r.name||''}</button>`).join('');
        suggestBox.style.display = 'block';
      }).catch(() => hideSuggest());
  }
  if (searchEl) {
    searchEl.addEventListener('input', () => {
      clearTimeout(suggestTimer);
      suggestTimer = setTimeout(() => fetchSuggest(searchEl.value.trim()), 220);
    });
    document.addEventListener('click', e => {
      if (!suggestBox.contains(e.target) && e.target !== searchEl) hideSuggest();
    });
    suggestBox.addEventListener('click', e => {
      const btn = e.target.closest('button[data-val]');
      if (!btn) return;
      searchEl.value = btn.getAttribute('data-name');
      hideSuggest();
      document.getElementById('employeeSearchForm').submit();
    });
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