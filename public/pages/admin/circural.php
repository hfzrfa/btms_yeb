<?php
require_role('admin');
$pdo = getPDO();

function esc_html($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

// Choose target table: try exact 'circural', then common variants; allow override via ?table=NAME
$requestedTable = isset($_GET['table']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['table']) : null;
$allTables = [];
try {
    foreach ($pdo->query("SHOW TABLES") as $row) {
        $allTables[] = array_values($row)[0];
    }
} catch (Throwable $e) {
}

$targetTable = null;
if ($requestedTable && in_array($requestedTable, $allTables, true)) {
    $targetTable = $requestedTable;
} else {
    $candidates = ['circural', 'circular', 'circural_approval', 'circular_approval', 'circural_master', 'circulars'];
    foreach ($candidates as $cand) {
        if (in_array($cand, $allTables, true)) {
            $targetTable = $cand;
            break;
        }
    }
    if (!$targetTable) {
        // fuzzy: pick first table containing 'circ'
        foreach ($allTables as $t) {
            if (stripos($t, 'circ') !== false) {
                $targetTable = $t;
                break;
            }
        }
    }
}

if (!$targetTable) {
    echo '<div class="alert alert-danger">Table "circural" not found. No similar tables detected.</div>';
    if (!empty($allTables)) {
        echo '<div class="alert alert-info">Existing tables: ' . esc_html(implode(', ', $allTables)) . '</div>';
    }
    return;
}

// Load columns metadata
$cols = [];
try {
    $cols = $pdo->query("SHOW COLUMNS FROM `$targetTable`")->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
}
if (!$cols) {
    echo '<div class="alert alert-warning">Unable to read columns for table "circural".</div>';
    return;
}

// Identify primary key (prefer 'id')
$pk = 'id';
$hasId = false;
$autoIncrement = false;
foreach ($cols as $c) {
    if (strcasecmp($c['Field'], 'id') === 0) {
        $hasId = true;
        $autoIncrement = stripos($c['Extra'] ?? '', 'auto_increment') !== false;
        break;
    }
}
if (!$hasId) {
    $pk = $cols[0]['Field'];
}

// CSRF + handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $id = $_POST['pk'] ?? null;
        if ($id !== null) {
            try {
                $st = $pdo->prepare("DELETE FROM `$targetTable` WHERE `$pk`=?");
                $st->execute([$id]);
                echo '<div class="alert alert-success">Deleted.</div>';
            } catch (Throwable $e) {
                echo '<div class="alert alert-danger">Delete failed: ' . esc_html($e->getMessage()) . '</div>';
            }
        }
    } elseif ($action === 'save') {
        $mode = $_POST['mode'] ?? 'insert';
        // Build list of editable columns (exclude auto-increment id)
        $fieldNames = array_column($cols, 'Field');
        $editable = [];
        foreach ($fieldNames as $f) {
            if ($f === $pk && $autoIncrement) continue;
            if (isset($_POST['row'][$f])) {
                $editable[$f] = $_POST['row'][$f];
            }
        }
        if ($mode === 'update') {
            $id = $_POST['pk'] ?? null;
            if ($id !== null && $editable) {
                try {
                    $sets = [];
                    $vals = [];
                    foreach ($editable as $f => $v) {
                        $sets[] = "`$f`=?";
                        $vals[] = $v;
                    }
                    $vals[] = $id;
                    $sql = "UPDATE `$targetTable` SET " . implode(',', $sets) . " WHERE `$pk`=?";
                    $stmtUpd = $pdo->prepare($sql);
                    $stmtUpd->execute($vals);
                    $affected = $stmtUpd->rowCount();
                    echo '<div class="alert alert-success">Saved. <small class="text-muted">Rows affected: ' . (int)$affected . '</small></div>';
                } catch (Throwable $e) {
                    echo '<div class="alert alert-danger">Update failed: ' . esc_html($e->getMessage()) . '</div>';
                }
            } else if ($id !== null && !$editable) {
                echo '<div class="alert alert-warning">No fields to update.</div>';
            }
        } else { // insert
            if ($editable) {
                try {
                    $insCols = array_keys($editable);
                    $ph = rtrim(str_repeat('?,', count($insCols)), ',');
                    $sql = "INSERT INTO `$targetTable`(`" . implode('`,`', $insCols) . "`) VALUES($ph)";
                    $stmtIns = $pdo->prepare($sql);
                    $stmtIns->execute(array_values($editable));
                    echo '<div class="alert alert-success">Inserted.</div>';
                } catch (Throwable $e) {
                    echo '<div class="alert alert-danger">Insert failed: ' . esc_html($e->getMessage()) . '</div>';
                }
            }
        }
    }
}

// Fetch rows for listing
$orderCol = $hasId ? 'id' : $pk;
$rows = [];
try {
    $rows = $pdo->query("SELECT * FROM `$targetTable` ORDER BY `$orderCol` DESC LIMIT 200")->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
}

?>
<h3 class="mb-3">Edit Circural Data <small class="text-muted">(Table: <?= esc_html($targetTable) ?>)</small></h3>
<form method="get" class="mb-3 d-flex align-items-center gap-2">
    <input type="hidden" name="page" value="admin/circural">
    <label class="small text-muted">Table:</label>
    <select name="table" class="form-select form-select-sm" style="max-width:260px;">
        <?php foreach ($allTables as $t): if (stripos($t, 'circ') === false) continue; ?>
            <option value="<?= esc_html($t) ?>" <?= $t === $targetTable ? 'selected' : '' ?>><?= esc_html($t) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-outline-secondary btn-sm">Switch</button>
</form>
<div class="card mb-4">
    <div class="card-header bg-white"><strong>Add New</strong></div>
    <div class="card-body">
        <form method="post" class="row g-2">
            <?= csrf_field(); ?>
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="mode" value="insert">
            <?php foreach ($cols as $c): $f = $c['Field'];
                if ($f === $pk && $autoIncrement) continue; ?>
                <div class="col-md-3">
                    <label class="form-label small"><?= esc_html($f) ?></label>
                    <input type="text" name="row[<?= esc_html($f) ?>]" class="form-control form-control-sm" />
                </div>
            <?php endforeach; ?>
            <div class="col-12">
                <button class="btn btn-primary btn-sm">Insert</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Circural Rows</strong>
        <small class="text-muted">Showing latest up to 200 rows</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-striped table-bordered mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <?php foreach ($cols as $c): ?><th><?= esc_html($c['Field']) ?></th><?php endforeach; ?>
                        <th style="width:160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r): $rowPk = $r[$pk] ?? '';
                        $formId = 'f_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', (string)$rowPk); ?>
                        <tr>
                            <form method="post">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="action" value="save">
                                <input type="hidden" name="mode" value="update">
                                <input type="hidden" name="pk" value="<?= esc_html($rowPk) ?>">
                                <?php foreach ($cols as $c): $f = $c['Field'];
                                    $val = $r[$f] ?? ''; ?>
                                    <td>
                                        <?php if ($f === $pk && $autoIncrement): ?>
                                            <span class="small text-muted"><?= esc_html($val) ?></span>
                                        <?php else: ?>
                                            <input type="text" name="row[<?= esc_html($f) ?>]" value="<?= esc_html($val) ?>" class="form-control form-control-sm" />
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary btn-sm" title="Save Row">Save</button>
                            </form>
                            <form method="post" class="d-inline" onsubmit="return confirm('Delete this row?');">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="pk" value="<?= esc_html($rowPk) ?>">
                                <button class="btn btn-outline-danger btn-sm" title="Delete Row">Delete</button>
                            </form>
        </div>
        </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($rows)): ?>
        <tr>
            <td colspan="<?= count($cols) + 1 ?>" class="text-center text-muted">No data found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
    </table>
    </div>
</div>
</div>