<?php
require_role('admin');
$pdo = getPDO();

$id = (int)get('id');
$empno = get('empno');
$isMaster = get('master');

if ($id) {
    // require confirm_empno match if EmpNo column exists
    $doDelete = true;
    try {
        $cols = $pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_COLUMN, 0);
        if (in_array('EmpNo', $cols) || in_array('empno', array_map('strtolower', $cols))) {
            $empNoRow = $pdo->prepare('SELECT EmpNo FROM employees WHERE id=?');
            $empNoRow->execute([$id]);
            $realEmpNo = $empNoRow->fetchColumn();
            if ($realEmpNo && get('confirm_empno') !== $realEmpNo) {
                $doDelete = false;
            }
        }
    } catch (Exception $e) {
    }
    if ($doDelete) {
        $stmt = $pdo->prepare('DELETE FROM employees WHERE id=?');
        $stmt->execute([$id]);
    }
} elseif ($empno) {
    // decide target table
    $target = 'employees';
    if ($isMaster) {
        try {
            $t = $pdo->query("SHOW TABLES LIKE 'employeemaster'")->fetch();
            if ($t) {
                $target = 'employeemaster';
            }
        } catch (Exception $e) {
        }
    } else {
        try {
            $cols = $pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_COLUMN, 0);
            if (!in_array('EmpNo', $cols) && !in_array('empno', array_map('strtolower', $cols))) {
                $t = $pdo->query("SHOW TABLES LIKE 'employeemaster'")->fetch();
                if ($t) {
                    $target = 'employeemaster';
                }
            }
        } catch (Exception $e) {
        }
    }
    // confirm requirement
    if (get('confirm_empno') === $empno) {
        $stmt = $pdo->prepare("DELETE FROM `$target` WHERE EmpNo=?");
        $stmt->execute([$empno]);
    }
}
redirect('index.php?page=employees/index');
