<?php
// --- helper dasar ---
if (!function_exists('esc')) {
    function esc($s)
    {
        return htmlspecialchars((string)$s ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$user = current_user();
if (!$user) redirect('index.php?page=login');

$pdo = getPDO();
$id = (int)($_GET['id'] ?? 0);
if (!$id) exit('Missing id');

// Ambil data header settlement + trip + employee
$stmt = $pdo->prepare(
    "SELECT s.*, 
          t.tujuan, t.period_from, t.period_to, 
          t.temp_payment_idr, t.temp_payment_sgn, t.temp_payment_yen, 
          t.emp_name, t.emp_no, 
          e.DeptCode, e.DestCode, 
          t.destination_district, t.destination_company, t.purpose,
          s.created_at
   FROM settlements s
   LEFT JOIN trips t ON t.id = s.trip_id
   LEFT JOIN employees e ON e.id = t.employee_id
    WHERE s.id = ?"
);
$stmt->execute([$id]);
$settle = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$settle) exit('Not found');

// Resolusi nama jabatan/position (cari di beberapa kemungkinan tabel)
$positionName = '';
try {
    $destCode = $settle['DestCode'] ?? null;
    if ($destCode) {
        $q = $pdo->query("SHOW TABLES LIKE 'desigcode'")->fetch();
        if ($q) {
            $s = $pdo->prepare("SELECT Description FROM desigcode WHERE Code=? LIMIT 1");
            $s->execute([$destCode]);
            $positionName = (string)$s->fetchColumn();
        }
        if ($positionName === '') {
            $q2 = $pdo->query("SHOW TABLES LIKE 'designations'")->fetch();
            if ($q2) {
                $s = $pdo->prepare("SELECT description FROM designations WHERE code=? LIMIT 1");
                $s->execute([$destCode]);
                $positionName = (string)$s->fetchColumn();
                if ($positionName === '') {
                    $s = $pdo->prepare("SELECT name FROM designations WHERE name=? LIMIT 1");
                    $s->execute([$destCode]);
                    $positionName = (string)$s->fetchColumn();
                }
            }
        }
    }
} catch (Throwable $e) { /* ignore */
}

// Resolusi Department / Section berdasarkan DeptCode (format: code / name[, section])
$deptDisplay = '-';
try {
    $deptCode = trim((string)($settle['DeptCode'] ?? ''));
    if ($deptCode !== '') {
        $deptDisplay = $deptCode; // default ke kode
        // Deteksi kolom di tabel departments
        $cols = $pdo->query('SHOW COLUMNS FROM departments')->fetchAll(PDO::FETCH_COLUMN, 0);
        $lower = array_map('strtolower', $cols);
        $map = array_combine($lower, $cols);
        $nameCandidates = ['deptname','name','description','dept_desc','deptdesc','dept_name'];
        $secCandidates  = ['sectionname','section','section_name'];
        $codeCandidates = ['deptcode','dept_code','code'];
        $nameCol = null; $secCol = null; $codeCol = null;
        foreach ($nameCandidates as $c) { if (isset($map[$c])) { $nameCol = $map[$c]; break; } }
        foreach ($secCandidates as $c)  { if (isset($map[$c])) { $secCol  = $map[$c]; break; } }
        foreach ($codeCandidates as $c) { if (isset($map[$c])) { $codeCol = $map[$c]; break; } }
        $select = ($codeCol ? ('`'.$codeCol.'`') : 'DeptCode') . ' AS code';
        if ($nameCol) $select .= ', `'.$nameCol.'` AS dname';
        if ($secCol)  $select .= ', `'.$secCol.'` AS sname';
        $whereCol = $codeCol ? ('`'.$codeCol.'`') : 'DeptCode';
        $sql = "SELECT $select FROM departments WHERE $whereCol = ? LIMIT 1";
        $s = $pdo->prepare($sql);
        $s->execute([$deptCode]);
        if ($row = $s->fetch(PDO::FETCH_ASSOC)) {
            $code = $row['code'] ?? $deptCode;
            $name = $row['dname'] ?? '';
            $sec  = $row['sname'] ?? '';
            $deptDisplay = trim($code . ' / ' . $name . ($sec !== '' ? ', ' . $sec : ''));
        }
    }
} catch (Throwable $e) { /* ignore, fallback ke kode */ }

// Tambah kolom jika belum ada (toleran)
try {
    $pdo->exec('ALTER TABLE settlement_items ADD COLUMN amount_sgd DECIMAL(15,2) NULL, ADD COLUMN amount_yen DECIMAL(15,2) NULL');
} catch (Exception $e) {
}
try {
    $pdo->exec("ALTER TABLE settlements ADD COLUMN settlement_date DATE NULL");
} catch (Exception $e) {
}

// Tanggal proposal default = created_at
if (empty($settle['settlement_date'])) {
    $settle['settlement_date'] = substr($settle['created_at'] ?? date('Y-m-d'), 0, 10);
}

// Detail item
$items = [];
try {
    $q = $pdo->prepare('SELECT category, amount_idr, amount_sgd, amount_yen FROM settlement_items WHERE settlement_id=?');
    $q->execute([$id]);
    $items = $q->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}

// Advance/Rate
$advance     = (float)($settle['temp_payment_idr'] ?? 0);
$advance_sgd = (float)($settle['temp_payment_sgn'] ?? 0);
$advance_yen = (float)($settle['temp_payment_yen'] ?? 0);
$rateSgd = ($advance > 0 && $advance_sgd > 0) ? ($advance / $advance_sgd) : 0;
$rateYen = ($advance > 0 && $advance_yen > 0) ? ($advance / $advance_yen) : 0;

// Susunan baris di form (mengikuti foto)
$categories = [
    'Airplane',
    'Train',
    'Ferry / Speed',
    'Bus',
    'Taxi 1',
    'Taxi 2',
    'Taxi 3',
    'Taxi 4',
    'Taxi 5',
    'Other',
    'Daily Allowance',

    'Accommodation' // di form tertulis "Accomodation" — dibiarkan meniru
];

// Agregasi per kategori & mata uang
$rowData = [];
foreach ($categories as $c) $rowData[$c] = ['idr' => 0, 'sgd' => 0, 'yen' => 0];
foreach ($items as $it) {
    $c = $it['category'] ?? '';
    if (!isset($rowData[$c])) continue;
    $rowData[$c]['idr'] += (float)($it['amount_idr'] ?? 0);
    $rowData[$c]['sgd'] += (float)($it['amount_sgd'] ?? 0);
    $rowData[$c]['yen'] += (float)($it['amount_yen'] ?? 0);
}

$sumIdrInput = array_sum(array_map(fn($r) => $r['idr'], $rowData));
$sumSgd = array_sum(array_map(fn($r) => $r['sgd'], $rowData));
$sumYen = array_sum(array_map(fn($r) => $r['yen'], $rowData));
$convertedToIdr = ($rateSgd > 0 ? $sumSgd * $rateSgd : 0) + ($rateYen > 0 ? $sumYen * $rateYen : 0);
$totalIdr = $sumIdrInput + $convertedToIdr;

// Jika ada total_realisasi pada settlements, pakai itu sebagai override
if (!empty($settle['total_realisasi'])) {
    $totalIdr = (float)$settle['total_realisasi'];
}

$returnToAcc   = $advance > $totalIdr ? ($advance - $totalIdr) : 0;
$payToEmployee = $totalIdr; // mengikuti formulir (nilai total diulang di kotak "Pay to Employee")

header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Calculation of Business Trip Expense #<?= (int)$id ?><?= isset($settle['reg_no']) && $settle['reg_no'] ? ' / Reg ' . $settle['reg_no'] : '' ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 5mm 10mm 5mm;
        }

        * {
            box-sizing: border-box;

        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #000;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            page-break-inside: avoid;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: middle;
        }

        .center {
            text-align: center;
        }

        .shade {
            background: #f2f2f2;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .nob {
            border: 0 !important;
        }

        /* header bar */
        .header td {
            height: 8mm;
            font-weight: bold;
        }

        /* title */
        .title td {
            border: 0;
            font-weight: bold;
            font-size: 14px;
            padding: 6px 0;
        }

        /* ident section */
        .ident td {
            height: 8mm;
        }

        .label {
            width: 190px;
        }

        .date-val {
            width: 150px;
        }

        .name-lbl {
            width: 100px;
        }

        .pos-lbl {
            width: 100px;
        }

        .pos-val {
            width: 180px;
        }

        /* expense table */
        .exp-head td {
            background: #f2f2f2;
            font-weight: bold;
            padding: 4px 8px;
        }

        .exp-type {
            width: 220px;
        }

        .exp-rp {
            width: 120px;
        }

        .exp-sgd {
            width: 110px;
        }

        .exp-yen {
            width: 110px;
        }

        .expense-row td {
            height: 8mm;
        }

        .taxi-note td {
            height: 8mm;
            font-size: 9.5px;
            padding-left: 8px;
        }

        /* sum rows */
        .sum-row td {
            font-weight: bold;
        }

        /* subgrid (Return to ACC / Pay to Employee) */
        .subgrid td {
            padding: 4px 6px;
        }

        /* circulation/sign */
        .circ td {
            height: 9mm;
        }

        /* signature grid */
        .sign-hdr td {
            background: #f2f2f2;
            font-weight: bold;
        }

        .sign-grid tr:nth-child(2) td {
            height: 40%;
            /* Add height of 20% for Signature grid */
        }

        /* footer */
        .footer {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            margin-top: 10mm;
            min-height: 20%;
            /* Add 20% height to the footer */
        }

        .note {
            font-size: 9.5px;
            max-width: 450px;
            line-height: 1.4;
        }

        .regbox {
            width: 180px;
        }

        .regbox td {
            height: 10mm;
        }

        .no-border-right {
            border-right: 0 !important;
        }

        .no-border-left {
            border-left: 0 !important;
        }

        .no-border-top {
            border-top: 0 !important;
        }

        .no-border-bottom {
            border-bottom: 0 !important;
        }

        /* prevent page breaks inside rows */
        tr,
        td,
        table {
            page-break-inside: avoid;
        }

        .meta {
            font-size: 9px;
            margin-top: 5mm;
        }

        @media print {
            .meta {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Top header strip -->
    <table class="header">
        <tr class="center bold">
            <td style="width:33%;">PT. Yoshikawa Electronics Bintan</td>
            <td style="width:33%">YBR-HRD066</td>
            <td style="width:33%">Initial</td>
        </tr>
    </table>

    <!-- Title -->
    <table class="title">
        <tr>
            <td class="center">CALCULATION OF BUSINESS TRIP EXPENSE</td>
        </tr>
    </table>

    <!-- Identity block -->
    <table class="ident">
        <tr>
            <td class="label shade">Proposal Date</td>
            <td class=""><?= $settle['settlement_date'] ? date('d/m/Y', strtotime($settle['settlement_date'])) : '' ?></td>

            <!-- Name -->
            <td class="name-lbl center shade">Name</td>
            <td style="width:70px;" class=""><?= $settle['emp_no'] ? esc($settle['emp_no']) : '&nbsp;' ?>  </td>
            <td class="no-border-left" colspan="4"><?= $settle['emp_name'] ? esc($settle['emp_name']) : '&nbsp;' ?></td>

        </tr>
        <tr>
            <td class="label shade">Department / Section</td>
            <td colspan="4"><?= esc($deptDisplay) ?></td>

            <td class="center shade" style="width: 190px;">POSITION</td>
            <td class="pos-val" colspan="2"><?= $positionName ? esc($positionName) : '' ?></td>
        </tr>

        <tr>
            <td class="label shade">Periode</td>
            <td colspan="3"><?= $settle['period_from'] ? date('d/m/Y', strtotime($settle['period_from'])) : '' ?></td>
            <td class="center shade" style="width: 600px;">To</td>
            <td colspan="3"><?= $settle['period_to'] ? date('d/m/Y', strtotime($settle['period_to'])) : '' ?></td>
        </tr>
        <tr>
            <td class="label shade">Destination</td>
            <td colspan="7"><?= esc($settle['destination_district'] ?: '-') ?></td>
        </tr>
        <tr>
            <td class="label shade">Destination Company name</td>
            <td colspan="7"><?= esc($settle['destination_company'] ?: '-') ?></td>
        </tr>
        <tr>
            <td class="label shade">Purpose</td>
            <td colspan="7"><?= esc($settle['purpose'] ?: '-') ?></td>
        </tr>
    </table>

    <!-- Expenses table -->
    <table>
        <tr class="exp-head center shade">
            <td class="exp-type shade">Type Of Expenses</td>
            <td class="exp-rp">IDR</td>
            <td class="exp-sgd">SGD</td>
            <td class="exp-yen">YEN</td>
            <td>Description</td>
        </tr>

        <?php foreach ($categories as $cat): $v = $rowData[$cat] ?? ['idr' => 0, 'sgd' => 0, 'yen' => 0]; ?>
            <tr class="expense-row">
                <td><?= $cat === 'Accommodation' ? 'Accomodation' : $cat ?></td>
                <td class="right"><?= $v['idr'] ? number_format($v['idr'], 0, ',', '.') : '' ?></td>
                <td class="right"><?= $v['sgd'] ? number_format($v['sgd'], 2, '.', ',') : '' ?></td>
                <td class="right"><?= $v['yen'] ? number_format($v['yen'], 0, ',', '.') : '' ?></td>
                <td></td>
            </tr>
            <?php if ($cat === 'Bus'): ?>
                <tr class="taxi-note shade">
                    <td colspan="5" style="padding-left:6px;">
                        Taxi must write in the day, route fare for taxi ride. If there are too many, write in separate sheet and attach it
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

        <tr class="sum-row">
            <td class="shade">Total Business Trip Expenses</td>
            <td class="right"><?= number_format($totalIdr, 0, ',', '.') ?></td>
            <td class="right"><?= $sumSgd ? number_format($sumSgd, 2, '.', ',') : '' ?></td>
            <td class="right"><?= $sumYen ? number_format($sumYen, 0, ',', '.') : '' ?></td>
            <td></td>
        </tr>
        <tr>
            <td class="shade">Temporary Payment</td>
            <td class="right"><?= number_format($advance, 0, ',', '.') ?></td>
            <td class="right"></td>
            <td class="right"></td>
            <td></td>
        </tr>

        <!-- subgrid Return to ACC / Pay to Employee (dua baris seperti di foto) -->
        <tr>
            <td colspan="5" class="nob" style="padding:0;">
                <table class="subgrid" style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:219px; border:1px solid #000;" class="center shade">Return To ACC</td>
                        <td style="width:121px; border:1px solid #000;" class="right"><?= number_format($returnToAcc, 0, ',', '.') ?></td>
                        <td style=" width:219px; border:1px solid #000;"></td>
                        <td style="border:1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td style="width:210px; border:1px solid #000;" class="center shade">Pay to Employee</td>
                        <td style="border:1px solid #000;" class="right"><?= number_format($payToEmployee, 0, ',', '.') ?></td>
                        <td style="border:1px solid #000;"></td>
                        <td style="border:1px solid #000;"></td>

                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Circulation / Day / Signature -->
    <table class="circ">
        <tr>
            <td style="width:220px;" class="shade">Circulation *Used When US$ etc converted into S$ / RP.</td>
            <td style="width: 340px;"></td>
            <td colspan="3"></td>
        </tr>

        <tr>
            <td style="width:190px;" class="shade">Day Of Payment</td>
            <td></td>
            <td style="min-width:300px;" class="center shade">Signature</td>

            <td colspan="2"></td>
        </tr>
    </table>

    <!-- Signature grid bawah -->
    <table class="sign-hdr center">
        <tr>
            <td style="width: 120px;">Claim by</td>
            <td style="width: 120px;">Concerned<br>Asst. Manager</td>
            <td style="width: 120px;">Concerned<br>Manager</td>
            <td style="width: 120px;">Concerned GM</td>
            <td style="width: 120px;">Calculated by<br>HR</td>
            <td style="width: 120px;">HR Manager</td>
            <td style="width: 120px;">Financial Advisor</td>


        </tr>
        <tr class="sign-grid" style="height: 130px;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <!-- Footer note + Registration box -->
    <div class="footer" style="margin-top:3mm;">
        <div class="note">(*) Those who went on an overseas business trip other than S'pore should attach business trip schedule for daily allowance calculation</div>
        <table class="regbox">
            <tr>
                <td class="center no-border-right no-border-bottom" style="width:110px;">Registration No</td>
                <td class="right no-border-left no-border-bottom"></td>
            </tr>
            <tr>
                <td class="center no-border-right no-border-top">ID =</td>
                <td class="center right no-border-left no-border-top"><?= (int)$id ?></td>
            </tr>
        </table>
    </div>

</body>

</html>