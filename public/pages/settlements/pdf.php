<?php
// Settlement printable page (HTML to print) – simplified clean layout like provided screenshot
$user = current_user();
if (!$user) redirect('index.php?page=login');
$pdo = getPDO();
$id = (int)($_GET['id'] ?? 0);
if (!$id) exit('Missing id');

$stmt = $pdo->prepare('SELECT s.*, t.tujuan, t.period_from, t.period_to, t.temp_payment_idr, t.temp_payment_sgn, t.temp_payment_yen, t.emp_name, t.emp_no, e.DeptCode, t.destination_district, t.destination_company, t.purpose FROM settlements s LEFT JOIN trips t ON t.id = s.trip_id LEFT JOIN employees e ON e.id = t.employee_id WHERE s.id=?');
$stmt->execute([$id]);
$settle = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$settle) exit('Not found');

// Items
$items = [];
try {
    $q = $pdo->prepare('SELECT category,description,amount_idr FROM settlement_items WHERE settlement_id=?');
    $q->execute([$id]);
    $items = $q->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}

// Ensure settlement_date column
try {
    $pdo->exec("ALTER TABLE settlements ADD COLUMN settlement_date DATE NULL");
} catch (Exception $e) {
}
if (empty($settle['settlement_date'])) $settle['settlement_date'] = substr($settle['created_at'] ?? date('Y-m-d'), 0, 10);

$advance = (float)($settle['temp_payment_idr'] ?? 0);
$advance_sgd = (float)($settle['temp_payment_sgn'] ?? 0);
$advance_yen = (float)($settle['temp_payment_yen'] ?? 0);
$total = (float)($settle['total_realisasi'] ?? 0);
if (!$total && $items) {
    foreach ($items as $it) {
        $total += (float)$it['amount_idr'];
    }
}
$variance = $total - $advance; // positive -> pay to employee
$returnToAcct = $variance < 0 ? abs($variance) : 0;
$payToEmployee = $variance > 0 ? $variance : 0;

// Derive conversion rates using original temporary payments (assume advance amounts reflect same date rates)
$rateSgd = ($advance_sgd > 0 && $advance > 0) ? ($advance / $advance_sgd) : 0; // IDR per SGD
$rateYen = ($advance_yen > 0 && $advance > 0) ? ($advance / $advance_yen) : 0; // IDR per YEN

// Auto converted totals
$total_sgd = $rateSgd > 0 ? ($total / $rateSgd) : 0;
$total_yen = $rateYen > 0 ? ($total / $rateYen) : 0;

header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Settlement #<?= $id ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 18px;
            color: #000;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            font-weight: normal;
        }

        th {
            background: #f2f2f2;
        }

        .form-table td {
            border: 0;
            padding: 4px 6px;
        }

        .label {
            width: 220px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 10px;
            text-align: left;
        }

        .shaded {
            background: #fafafa;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 8px;
            }
        }
    </style>
</head>

<body>
    <button class="no-print" onclick="window.print()" style="float:right;">Print</button>
    <h1 class="title">Settlement</h1>
    <table class="form-table" style="margin-bottom:6px;">
        <tr>
            <td class="label">Employee No</td>
            <td style="width:230px;"><?= esc($settle['emp_no']) ?></td>
            <td class="label" style="width:70px;">ID</td>
            <td><?= esc($settle['trip_id']) ?></td>
        </tr>
        <tr>
            <td class="label">Name</td>
            <td colspan="3"><?= esc($settle['emp_name']) ?></td>
        </tr>
        <tr>
            <td class="label">Departement</td>
            <td colspan="3"><?= esc($settle['DeptCode'] ?: '-') ?></td>
        </tr>
        <tr>
            <td class="label">Periode From (dd/mm/yyyy)</td>
            <td><?= $settle['period_from'] ? date('d/m/Y', strtotime($settle['period_from'])) : '' ?></td>
            <td class="label">Periode to</td>
            <td><?= $settle['period_to'] ? date('d/m/Y', strtotime($settle['period_to'])) : '' ?></td>
        </tr>
        <tr>
            <td class="label">Destination District</td>
            <td colspan="3"><?= esc($settle['destination_district'] ?: '-') ?></td>
        </tr>
        <tr>
            <td class="label">Destination Company Name</td>
            <td colspan="3"><?= esc($settle['destination_company'] ?: '-') ?></td>
        </tr>
        <tr>
            <td class="label">Purpose</td>
            <td colspan="3"><?= esc($settle['purpose'] ?: '-') ?></td>
        </tr>
    </table>

    <table>
        <tr class="center">
            <th style="width:155px;">Type of Expanse</th>
            <th>Description</th>
            <th style="width:120px;">Rupiah (IDR)</th>
            <th style="width:90px;">SGD$</th>
            <th style="width:90px;">YEN ¥</th>
            <th style="width:90px;">Others</th>
        </tr>
        <?php $descHtml = '';
        if ($items) {
            foreach ($items as $it) {
                $descHtml .= '- ' . esc($it['category']) . ': ' . esc($it['description'] ?: '-') . ' (' . number_format($it['amount_idr'], 0) . ')<br>';
            }
        } else {
            $descHtml = 'No detail items';
        } ?>
        <tr>
            <td class="shaded">Total Business Trip Expanse</td>
            <td><?= $descHtml ?></td>
            <td class="right" style="font-weight:bold;"><?= number_format($total, 0) ?></td>
            <td class="right"><?= $rateSgd ? number_format($total_sgd, 2) : '-' ?></td>
            <td class="right"><?= $rateYen ? number_format($total_yen, 0) : '-' ?></td>
            <td></td>
        </tr>
        <tr>
            <td class="shaded">Temporary Payment</td>
            <td>&nbsp;</td>
            <td class="right"><?= number_format($advance, 0) ?></td>
            <td class="right"><?= $advance_sgd ? number_format($advance_sgd, 2) : '-' ?></td>
            <td class="right"><?= $advance_yen ? number_format($advance_yen, 0) : '-' ?></td>
            <td></td>
        </tr>
        <tr>
            <td rowspan="2" class="center shaded" style="font-weight:bold;">Calculation</td>
            <td class="shaded">Return To Acct</td>
            <td class="right"><?= $returnToAcct ? number_format($returnToAcct, 0) : '' ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td class="shaded">Pay To Employee</td>
            <td class="right"><?= $payToEmployee ? number_format($payToEmployee, 0) : '' ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" class="shaded" style="height:8px;"></td>
        </tr>
    </table>

    <table class="form-table" style="margin-top:8px;">
        <tr>
            <td class="label">Date For Settlement</td>
            <td style="width:180px;"><?= $settle['settlement_date'] ? date('j / n / Y', strtotime($settle['settlement_date'])) : '' ?></td>
        </tr>
    </table>

    <p style="font-size:11px;margin-top:10px;">Status: <strong><?= esc($settle['status'] ?? 'submitted') ?></strong> | Generated: <?= date('d/m/Y H:i') ?> | Variance: <?= number_format($variance, 0) ?></p>
</body>

</html>