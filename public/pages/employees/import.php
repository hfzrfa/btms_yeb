<?php
require_role('admin');
$pdo = getPDO();

// Target table detection similar to create.php
$targetTable = 'employees';
$hasEmpNo = false;
$useEmployeeMaster = false;
$cols = [];
try {
  $d = $pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_ASSOC);
  foreach ($d as $c) {
    $cols[] = $c['Field'];
    if (strtolower($c['Field']) === 'empno') $hasEmpNo = true;
  }
} catch (Exception $e) {
}
if (!$hasEmpNo) {
  try {
    $t = $pdo->query("SHOW TABLES LIKE 'employeemaster'")->fetch();
    if ($t) {
      $useEmployeeMaster = true;
      $targetTable = 'employeemaster';
      $d = $pdo->query("DESCRIBE employeemaster")->fetchAll(PDO::FETCH_ASSOC);
      $cols = [];
      foreach ($d as $c) {
        $cols[] = $c['Field'];
        if (strtolower($c['Field']) === 'empno') $hasEmpNo = true;
      }
    }
  } catch (Exception $e) {
  }
}
if (!$hasEmpNo) {
  echo '<div class="alert alert-warning">Struktur tabel dengan kolom EmpNo tidak ditemukan.</div>';
  echo '<a class="btn btn-secondary" href="index.php?page=employees/index">Kembali</a>';
  return;
}

$baseCols = ['EmpNo', 'Name', 'DeptCode', 'ClasCode', 'DestCode', 'Sex', 'Resigned', 'GrpCode'];
$messages = [];
$inserted = 0;
$updated = 0;
$recreated = 0;
$recreatedGroups = 0;
$recreatedDesignations = 0;
$rowsData = [];
$beforeCount = null;
$afterCount = null;
$targetInfo = $targetTable;

// Minimal XLSX reader (first sheet) without external libs
function parseXlsxFirstSheet(string $file, array &$messages): array
{
  if (!class_exists('ZipArchive')) {
    $messages[] = 'ZIP extension (ZipArchive) tidak tersedia: XLSX dilewati.';
    return [];
  }
  $zip = new ZipArchive();
  if ($zip->open($file) !== TRUE) return [];
  $sharedStrings = [];
  $rows = [];
  // Load shared strings
  $ssIdx = $zip->locateName('xl/sharedStrings.xml');
  if ($ssIdx !== false) {
    $xml = @simplexml_load_string($zip->getFromIndex($ssIdx));
    if ($xml && isset($xml->si)) {
      foreach ($xml->si as $si) {
        // concatenate possible t nodes
        $textParts = [];
        foreach ($si->t as $t) {
          $textParts[] = (string)$t;
        }
        if (!$textParts && isset($si->r)) { // rich text
          foreach ($si->r as $r) {
            if (isset($r->t)) $textParts[] = (string)$r->t;
          }
        }
        $sharedStrings[] = implode('', $textParts);
      }
    }
  }
  // Determine first sheet path
  $sheetPath = 'xl/worksheets/sheet1.xml';
  $wbIdx = $zip->locateName('xl/workbook.xml');
  $relMap = [];
  if ($wbIdx !== false) {
    $wb = @simplexml_load_string($zip->getFromIndex($wbIdx));
    if ($wb && isset($wb->sheets->sheet[0])) {
      $sheetId = (string)$wb->sheets->sheet[0]['r:id'];
      // relationships
      $relIdx = $zip->locateName('xl/_rels/workbook.xml.rels');
      if ($relIdx !== false) {
        $rels = @simplexml_load_string($zip->getFromIndex($relIdx));
        if ($rels && isset($rels->Relationship)) {
          foreach ($rels->Relationship as $r) {
            $relMap[(string)$r['Id']] = (string)$r['Target'];
          }
        }
      }
      if (isset($relMap[$sheetId])) {
        $target = $relMap[$sheetId];
        if (strpos($target, '/worksheets/') === false) $target = 'worksheets/' . basename($target);
        $sheetPath = 'xl/' . ltrim($target, '/');
      }
    }
  }
  $sheetIdx = $zip->locateName($sheetPath);
  if ($sheetIdx === false) {
    $zip->close();
    return [];
  }
  $sheetXml = @simplexml_load_string($zip->getFromIndex($sheetIdx));
  if (!$sheetXml) {
    $zip->close();
    return [];
  }
  $headers = [];
  $rowNum = 0;
  foreach ($sheetXml->sheetData->row as $row) {
    $cells = [];
    $maxCol = 0;
    $cellValues = [];
    foreach ($row->c as $c) {
      $rAttr = (string)$c['r']; // e.g. A1
      // Convert column letters to index
      $colLetters = preg_replace('/\d+/', '', $rAttr);
      $colIndex = 0;
      for ($i = 0; $i < strlen($colLetters); $i++) {
        $colIndex = $colIndex * 26 + (ord($colLetters[$i]) - 64);
      }
      $colIndex--; // zero-based
      $v = isset($c->v) ? (string)$c->v : '';
      if (isset($c['t']) && (string)$c['t'] === 's') { // shared string
        $v = $sharedStrings[(int)$v] ?? $v;
      }
      $cellValues[$colIndex] = $v;
      if ($colIndex > $maxCol) $maxCol = $colIndex;
    }
    $rowArr = [];
    for ($i = 0; $i <= $maxCol; $i++) {
      $rowArr[] = $cellValues[$i] ?? '';
    }
    if ($rowNum === 0) {
      $headers = array_map(function ($h) {
        $h = preg_replace('/[\xEF\xBB\xBF]/', '', $h);
        return trim($h);
      }, $rowArr);
    } else {
      if (!array_filter($rowArr, fn($x) => trim($x) !== '')) {
        $rowNum++;
        continue;
      }
      $assoc = [];
      foreach ($headers as $i => $h) {
        if ($h === '') continue;
        $assoc[$h] = $rowArr[$i] ?? '';
      }
      if ($assoc) $rows[] = $assoc;
    }
    $rowNum++;
  }
  $zip->close();
  return $rows;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
  $fullReplace = isset($_POST['full_replace']);
  if (!empty($_FILES['file']['tmp_name'])) {
    $tmp = $_FILES['file']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    $rowsData = [];
    if ($ext === 'csv') { // simple CSV
      if (($fh = fopen($tmp, 'r'))) {
        $headers = fgetcsv($fh);
        if (!$headers) {
          $messages[] = 'CSV kosong';
        } else {
          $headers = array_map(function ($h) {
            $h = preg_replace('/[\xEF\xBB\xBF]/', '', $h);
            return trim($h);
          }, $headers);
          while (($r = fgetcsv($fh)) !== false) {
            if (count(array_filter($r, fn($v) => $v !== '')) === 0) continue;
            $row = [];
            foreach ($headers as $i => $h) {
              $row[$h] = $r[$i] ?? '';
            }
            $rowsData[] = $row;
          }
        }
        fclose($fh);
      }
    } elseif ($ext === 'xlsx') {
      $rowsData = parseXlsxFirstSheet($tmp, $messages);
      if (!$rowsData) $messages[] = 'XLSX tidak dapat dibaca atau kosong.';
    } else {
      $messages[] = 'Format file belum didukung. Gunakan CSV.';
    }
    if ($rowsData) {
      // Row count before changes
      try {
        $beforeCount = (int)$pdo->query('SELECT COUNT(*) FROM ' . $targetTable)->fetchColumn();
      } catch (Exception $e) {
      }
      if ($fullReplace) {
        // Full replace: truncate target table first
        try {
          $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        } catch (Exception $e) {
        }
        try {
          $pdo->exec('TRUNCATE TABLE ' . $targetTable);
        } catch (Exception $e) {
          $messages[] = 'Gagal TRUNCATE: ' . esc($e->getMessage());
        }
        try {
          $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
        } catch (Exception $e) {
        }
      }
      // Prepare upsert (by EmpNo)
      $existing = [];
      $inEmp = [];
      foreach ($rowsData as $rd) {
        if (isset($rd['EmpNo'])) $inEmp[] = $pdo->quote(trim($rd['EmpNo']));
      }
      if ($inEmp) {
        $sql = $targetTable === 'employees' ? "SELECT id,EmpNo FROM employees WHERE EmpNo IN (" . implode(',', $inEmp) . ")" : "SELECT EmpNo FROM employeemaster WHERE EmpNo IN (" . implode(',', $inEmp) . ")";
        foreach ($pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) as $ex) {
          $existing[strtoupper($ex['EmpNo'])] = $ex;
        }
      }
      $pdo->beginTransaction();
      try {
        foreach ($rowsData as $rd) {
          $empNo = trim($rd['EmpNo'] ?? '');
          if ($empNo === '') {
            continue;
          }
          $data = [];
          foreach ($baseCols as $c) {
            if (in_array($c, $cols, true)) {
              $data[$c] = trim($rd[$c] ?? '');
            }
          }
          if (isset($existing[strtoupper($empNo)])) {
            // update
            $setParts = [];
            $vals = [];
            foreach ($data as $k => $v) {
              if ($k === 'EmpNo') continue;
              $setParts[] = "$k=?";
              $vals[] = $v === '' ? null : $v;
            }
            if ($targetTable === 'employees') {
              $vals[] = $empNo;
              $sqlU = 'UPDATE employees SET ' . implode(',', $setParts) . ' WHERE EmpNo=?';
            } else {
              $vals[] = $empNo;
              $sqlU = 'UPDATE employeemaster SET ' . implode(',', $setParts) . ' WHERE EmpNo=?';
            }
            if ($setParts) {
              $st = $pdo->prepare($sqlU);
              $st->execute($vals);
              $updated++;
            }
          } else {
            // insert
            $insCols = [];
            $place = [];
            $vals = [];
            foreach ($data as $k => $v) {
              $insCols[] = $k;
              $place[] = '?';
              $vals[] = $v === '' ? null : $v;
            }
            if (!in_array('EmpNo', $insCols, true)) {
              $insCols[] = 'EmpNo';
              $place[] = '?';
              $vals[] = $empNo;
            }
            $sqlI = 'INSERT INTO ' . $targetTable . ' (' . implode(',', $insCols) . ') VALUES (' . implode(',', $place) . ')';
            $st = $pdo->prepare($sqlI);
            $st->execute($vals);
            $inserted++;
          }
        }
        $pdo->commit();
        // Auto-create & map reference tables (departments, groups, designations)
        try {
          // Departments
          $deptCodes = [];
          foreach ($rowsData as $rd) {
            $dc = trim($rd['DeptCode'] ?? '');
            if ($dc !== '') $deptCodes[$dc] = true;
          }
          if ($deptCodes) {
            $existsDepts = [];
            try {
              foreach ($pdo->query('SELECT name FROM departments')->fetchAll(PDO::FETCH_COLUMN) as $n) {
                $existsDepts[strtoupper($n)] = true;
              }
            } catch (Exception $e) {
            }
            foreach (array_keys($deptCodes) as $dc) {
              if (!isset($existsDepts[strtoupper($dc)])) {
                try {
                  $pdo->prepare('INSERT INTO departments(name) VALUES(?)')->execute([$dc]);
                  $recreated++;
                } catch (Exception $e) {
                }
              }
            }
            if ($targetTable === 'employees') {
              try {
                $map = $pdo->query('SELECT id,name FROM departments')->fetchAll(PDO::FETCH_KEY_PAIR);
                foreach ($map as $n => $id) {
                  $pdo->prepare('UPDATE employees SET department_id=? WHERE department_id IS NULL AND DeptCode=?')->execute([$id, $n]);
                }
              } catch (Exception $e) {
              }
            }
          }
          // Groups
          $grpCodes = [];
          foreach ($rowsData as $rd) {
            $gc = trim($rd['GrpCode'] ?? '');
            if ($gc !== '') $grpCodes[$gc] = true;
          }
          if ($grpCodes) {
            $existsGroups = [];
            try {
              foreach ($pdo->query('SELECT name FROM `groups`')->fetchAll(PDO::FETCH_COLUMN) as $g) {
                $existsGroups[strtoupper($g)] = true;
              }
            } catch (Exception $e) {
            }
            foreach (array_keys($grpCodes) as $gc) {
              if (!isset($existsGroups[strtoupper($gc)])) {
                try {
                  $pdo->prepare('INSERT INTO `groups`(name) VALUES(?)')->execute([$gc]);
                  $recreatedGroups++;
                } catch (Exception $e) {
                }
              }
            }
            if ($targetTable === 'employees') {
              try {
                $map = $pdo->query('SELECT id,name FROM `groups`')->fetchAll(PDO::FETCH_KEY_PAIR);
                foreach ($map as $n => $id) {
                  $pdo->prepare('UPDATE employees SET group_id=? WHERE group_id IS NULL AND GrpCode=?')->execute([$id, $n]);
                }
              } catch (Exception $e) {
              }
            }
          }
          // Designations (DestCode)
          $destCodes = [];
          foreach ($rowsData as $rd) {
            $dc = trim($rd['DestCode'] ?? '');
            if ($dc !== '') $destCodes[$dc] = true;
          }
          if ($destCodes) {
            $existsDesignations = [];
            try {
              foreach ($pdo->query('SELECT name FROM designations')->fetchAll(PDO::FETCH_COLUMN) as $n) {
                $existsDesignations[strtoupper($n)] = true;
              }
            } catch (Exception $e) {
            }
            foreach (array_keys($destCodes) as $dc) {
              if (!isset($existsDesignations[strtoupper($dc)])) {
                try {
                  $pdo->prepare('INSERT INTO designations(name) VALUES(?)')->execute([$dc]);
                  $recreatedDesignations++;
                } catch (Exception $e) {
                }
              }
            }
            if ($targetTable === 'employees') {
              try {
                $map = $pdo->query('SELECT id,name FROM designations')->fetchAll(PDO::FETCH_KEY_PAIR);
                foreach ($map as $n => $id) {
                  $pdo->prepare('UPDATE employees SET designation_id=? WHERE designation_id IS NULL AND DestCode=?')->execute([$id, $n]);
                }
              } catch (Exception $e) {
              }
            }
          }
        } catch (Exception $e) {
          $messages[] = 'Mapping referensi gagal: ' . esc($e->getMessage());
        }
        try {
          $afterCount = (int)$pdo->query('SELECT COUNT(*) FROM ' . $targetTable)->fetchColumn();
        } catch (Exception $e) {
        }
        $messages[] = "Import selesai. Insert: $inserted, Update: $updated, Dept baru: $recreated, Group baru: $recreatedGroups, Designation baru: $recreatedDesignations, Sebelum: " . ($beforeCount === null ? '?' : $beforeCount) . ", Sesudah: " . ($afterCount === null ? '?' : $afterCount) . ".";
      } catch (Exception $e) {
        $pdo->rollBack();
        $messages[] = 'Gagal import: ' . esc($e->getMessage());
      }
    }
  } else {
    $messages[] = 'File belum dipilih.';
  }
}
?>
<h4>Import Employees (CSV / XLSX)</h4>
<p class="text-muted small">Target table: <code><?= esc($targetInfo) ?></code>. Upload file CSV atau XLSX (sheet pertama) dengan header minimal: <strong><?= implode(', ', $baseCols) ?></strong>. (Header lain akan diabaikan.) BOM akan dibersihkan otomatis.</p>
<?php foreach ($messages as $m): ?>
  <div class="alert alert-info py-2 mb-2 small"><?= $m ?></div>
<?php endforeach; ?>
<form method="post" enctype="multipart/form-data" class="mb-4">
  <?= csrf_field(); ?>
  <div class="mb-2"><input type="file" name="file" accept=".csv,.xlsx" required class="form-control form-control-sm"></div>
  <div class="form-check mb-2">
    <input class="form-check-input" type="checkbox" value="1" id="fullReplace" name="full_replace">
    <label class="form-check-label small" for="fullReplace">Full Replace (hapus semua data lama terlebih dahulu)</label>
  </div>
  <button class="btn btn-sm btn-primary">Upload & Import</button>
  <a href="index.php?page=employees/index" class="btn btn-sm btn-secondary">Kembali</a>
</form>
<?php if (!empty($rowsData)): ?>
  <div class="card">
    <div class="card-body p-2">
      <div class="table-responsive" style="max-height:300px;overflow:auto;">
        <table class="table table-sm table-bordered mb-0">
          <thead>
            <tr><?php foreach (array_keys($rowsData[0]) as $h): ?><th><?= esc($h) ?></th><?php endforeach; ?></tr>
          </thead>
          <tbody>
            <?php foreach ($rowsData as $r): ?><tr><?php foreach (array_keys($rowsData[0]) as $h): ?><td><?= esc($r[$h] ?? '') ?></td><?php endforeach; ?></tr><?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif; ?>