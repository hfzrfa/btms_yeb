<?php

$user = current_user();
if (!$user) redirect('index.php?page=login');
$id = (int)($_GET['id'] ?? 0);
if (!$id) exit('Missing id');

$pdo = getPDO();
$stmt = $pdo->prepare("SELECT t.*, COALESCE(t.emp_name,e.Name) employee_name, COALESCE(t.emp_no,e.EmpNo) employee_empno, e.DeptCode
                       FROM trips t LEFT JOIN employees e ON e.id=t.employee_id WHERE t.id=?");
$stmt->execute([$id]);
$trip = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trip) exit('Not found');

// Approvers
$approvers = [];
if (!empty($trip['DeptCode'])) {
    try {
        $c = $pdo->prepare("SELECT Approval1,Approval2,Approval3,Approval4,Approval5 FROM circular WHERE DeptCode=? LIMIT 1");
        $c->execute([$trip['DeptCode']]);
        if ($r = $c->fetch(PDO::FETCH_ASSOC)) {
            foreach ($r as $v) {
                $v = trim((string)$v);
                if ($v !== '') $approvers[] = $v;
            }
        }
    } catch (Exception $e) {
    }
}
while (count($approvers) < 5) $approvers[] = '';

$proposalDate = !empty($trip['created_at']) ? strtotime($trip['created_at']) : time();
$d = date('d', $proposalDate);
$m = date('m', $proposalDate);
$y = date('Y', $proposalDate);

$hasSGD = (float)($trip['temp_payment_sgn'] ?? 0) > 0;
$hasYEN = (float)($trip['temp_payment_yen'] ?? 0) > 0;

header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Approval Of Business Trip #<?= $id ?></title>
    <style>
        :root {
            /* ==== gampang diatur ==== */
            --page-margin-mm: 5mm;
            /* kecilkan margin biar area konten makin luas */
            --font-size: 11.2px;
            /* sedikit lebih besar agar terlihat penuh */
            --cell-pad-v: 4px;
            /* padding vertikal */
            --cell-pad-h: 4px;
            /* padding horizontal */

            /* ==== tinggi bagian (mm) – naikin untuk “mengisi” ke bawah ==== */
            --h-approvals: 25mm;
            /* kotak tanda tangan Approval */
            --h-purpose: 14mm;
            /* area Purpose */
            --h-period: 9mm;
            /* baris Periode */
            --h-temp-head: 9mm;
            /* header Temporary Payment */
            --h-temp-row: 9mm;
            /* baris IDR/SGD/YEN */
            --h-collection: 12mm;
            /* Date Of Collection */
            --temp-min-h: 58mm;
            /* tinggi minimum blok Temporary Payment */
            --push: 6mm;
            /* FILLER opsional kalau masih ada sisa putih */
        }

        @page {
            size: A4 landscape;
            margin: var(--page-margin-mm);
        }

        body {
            margin: var(--page-margin-mm);
            font-family: Arial, Helvetica, sans-serif;
            font-size: var(--font-size);
            color: #000;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .middle-center {
            vertical-align: middle !important;
            text-align: center;
        }

        td,
        th {
            border: 1px solid #000;
            padding: var(--cell-pad-v) var(--cell-pad-h);
            vertical-align: middle;
            line-height: 1.22;
            font-weight: normal;
        }

        .center {
            text-align: center
        }

        .right {
            text-align: right
        }

        .bold {
            font-weight: bold
        }

        .shade {
            background: #f2f2f2
        }

        .shade2 {
            background: #e8e8e8
        }

        .no-print {
            display: inline-block
        }

        .h-approvals {
            height: var(--h-approvals);
        }

        .h-purpose {
            height: var(--h-purpose);
        }

        .h-period {
            height: var(--h-period);
        }

        .h-temp-head {
            height: var(--h-temp-head);
        }

        .h-temp-row {
            height: var(--h-temp-row);
        }

        .h-collection {
            height: var(--h-collection);
        }

        .temp-wrapper {
            min-height: var(--temp-min-h);
        }

        /* Baris ekstra untuk memperpanjang Receiver/Signature tanpa mengubah grid kiri */
        .tr-gap td {
            border: 0 !important;
            padding: 0 !important;
            height: 8mm;
            /* atur tinggi tambahan di sini */
            line-height: 0;
            font-size: 0;
        }

        /* Sel kosong tanpa border (buat ngisi kolom yang tidak ter-rowspan di baris tertentu) */
        .no-border {
            border: 0 !important;
            padding: 0 !important;
            border-right: 0 !important;
        }

        .no-border-right-left {
            border-right: 0 !important;
            border-left: 0 !important;
        }

        /* Filler opsional untuk “menyentuh” bagian paling bawah */
        .push-bottom td {
            border: 0;
            padding: 0;
            height: var(--push);
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <button class="no-print" onclick="window.print()" style="float:right;margin:4px 0;">Print</button>

    <!-- Header Bar -->
    <table style="margin-bottom:0; font-size:14px;">
        <tr class="center bold">
            <td style="width:40.6%; height:8mm;">PT.Yoshikawa Electronics Bintan</td>
            <td style="width:33.3%">YBR-HRD065</td>
            <td style="width:33.3%">Initial</td>
        </tr>
    </table>

    <!-- Title Row with Registration Box on Right -->
    <table style="margin-bottom:4px;">
        <tr>
            <td class="center bold" style="font-size:14px;" colspan="9">
                PT.YOSHIKAWA ELECTRONICS BINTAN&nbsp;&nbsp;Approval Of&nbsp;Business Trip
            </td>
            <td style="width:50px;" class="shade" colspan="2">
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td class="center" style="border:0;font-size:12px;">Registration Number</td>
                    </tr>
                    <tr style="text-align:center">
                        <td style="border:0;font-size:12px;">ID = <?= esc($trip['id']) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Proposal / Identification / Approvals -->
    <table style="margin-bottom:0;">
        <tr class="center">
            <td style="width:65px; height:8mm;" class="shade">Proposal Date</td>
            <td style="width:120px;" class="center" colspan="2"><?= $d . '/' . $m . '/' . $y ?></td>

            <td style="width:65px; height:8mm;" class="shade">NIK</td>
            <td style="width:60px;" class="center"><?= esc($trip['employee_empno']) ?></td>

            <td style="width:70px; height:8mm;" colspan="2" class="shade">Name</td>
            <td style="width:120px;" class="center" colspan="2"><?= esc($trip['employee_name']) ?></td>

            <td style="width:100px; height:8mm;" class="shade">Signature</td>
            <td style="width:80px;" colspan="2">&nbsp;</td>
        </tr>

        <tr>
            <td rowspan="2" class="shade middle-center" style="width:120px;">Approval</td>
            <?php foreach ($approvers as $ap): ?>
                <td class="shade center" colspan="2" style="width:65px; height:8mm;"><?= esc($ap) ?></td>
            <?php endforeach; ?>
            <td class="center shade" colspan="1" style="width:120px;">&nbsp;</td>
        </tr>
        <tr class="h-approvals">
            <?php foreach ($approvers as $ap): ?>
                <td colspan="2">&nbsp;</td>
            <?php endforeach; ?>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>

    <!-- Detail Fields -->
    <table style="margin-top:0;">
        <?php
        $deptCode = trim((string)($trip['DeptCode'] ?? ''));
        $deptDisplay = $deptCode !== '' ? $deptCode : '-';

        if ($deptCode !== '') {
            try {
                // Deteksi kolom-kolom yang tersedia di tabel departments
                $cols = $pdo->query('SHOW COLUMNS FROM departments')->fetchAll(PDO::FETCH_COLUMN, 0);
                $lower = array_map('strtolower', $cols);
                $map = array_combine($lower, $cols);
                // Kandidat nama kolom untuk deskripsi/nama departemen
                $nameCandidates = ['deptname','name','description','dept_desc','deptdesc','dept_name'];
                $secCandidates  = ['sectionname','section','section_name'];
                $codeCandidates = ['deptcode','dept_code','code'];
                $nameCol = null; $secCol = null;
                $codeCol = null;
                foreach ($nameCandidates as $c) { if (isset($map[$c])) { $nameCol = $map[$c]; break; } }
                foreach ($secCandidates as $c) { if (isset($map[$c])) { $secCol = $map[$c]; break; } }
                foreach ($codeCandidates as $c) { if (isset($map[$c])) { $codeCol = $map[$c]; break; } }

                // Siapkan SELECT dinamis (alias code/dname/sname)
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
                    // Format: Code / Name[, Section]
                    $deptDisplay = trim($code . ' / ' . $name . ($sec !== '' ? ', ' . $sec : ''));
                }
            } catch (Exception $e) {
                // fallback ke kode saja
            }
        }
        ?>
        <tr>
            <td style="width:120px; height:8mm;" class="shade">Department / Section</td>
            <td colspan="10"><?= esc($deptDisplay) ?></td>
        </tr>
        <tr>
            <td style="height:8mm;" class="shade">Destination</td>
            <td colspan="10"><?= esc($trip['tujuan'] ?: '-') ?></td>
        </tr>
        <tr>
            <td style="height:8mm;" class="shade">Destination Company Name</td>
            <td colspan="10"><?= esc($trip['destination_company'] ?: '-') ?></td>
        </tr>
        <tr>
            <td style="height:8mm;" class="shade">Purpose</td>
            <td colspan="10"><?= esc($trip['purpose'] ?: '-') ?></td>
        </tr>

        <tr class="h-period">
            <td class="shade" rowspan="1">Periode</td>
            <td class="shade center" style="width:70px;">From</td>
            <td style="width:120px;" class="center"><?= $trip['period_from'] ? date('d/m/Y', strtotime($trip['period_from'])) : '' ?></td>
            <td class="shade center" style="width:45px;">To</td>
            <td style="width:120px;" class="center"><?= $trip['period_to'] ? date('d/m/Y', strtotime($trip['period_to'])) : '' ?></td>
            <td colspan="6"></td>
        </tr>
    </table>

    <!-- Temporary Payment Section -->
    <div class="temp-wrapper">
        <table style="margin-top:4px;">
            <tr class="shade center h-temp-head">
                <td class="shade middle-center" rowspan="7" style="width:107px;">Temporary Payment</td>
                <td class="shade middle-center" rowspan="5" style="width:130px;">Amount Of Application</td>
                <td style="width:60px;" class="shade">Currency</td>
                <td style="width:200px;" class="shade">Amount</td>
                <td style="width:200px;" class="shade">Receiver</td>
                <td style="width:200px;" class="shade">Signature</td>
            </tr>

            <!-- 1) IDR -->
            <tr class="h-temp-row">
                <td class="shade center">IDR</td>
                <td><?= $trip['temp_payment_idr'] ? number_format($trip['temp_payment_idr'], 0, ',', '.') : '&nbsp;' ?></td>
                <td rowspan="5"></td>
                <td rowspan="5"></td>
            </tr>

            <!-- 2) SGD -->
            <tr class="h-temp-row">
                <td class="shade center">SGD</td>
                <td>&nbsp;</td>
            </tr>

            <!-- 3) YEN -->
            <tr class="h-temp-row">
                <td class="shade center">YEN</td>
                <td>&nbsp;</td>
            </tr>

            <!-- 4) baris kosong (tetap dihitung ke rowspan) -->
            <tr class="h-temp-row">
                <td class="shade center"></td>
                <td></td>
            </tr>

            <tr class="h-collection">
                <td class="small shade">Date Of Collection</td>

                <td class="no-border-right-left"></td>
                <td class="no-border-right-left"></td>
            </tr>
        </table>
    </div>
</body>

</html>