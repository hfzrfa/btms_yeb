<?php
// Approval Of Business Trip PDF (YBR-HRD065) – layout replicates provided form
$user = current_user();
if (!$user) redirect('index.php?page=login');
$id = (int)($_GET['id'] ?? 0);
if (!$id) exit('Missing id');
$pdo = getPDO();
$stmt = $pdo->prepare("SELECT t.*, COALESCE(t.emp_name,e.Name) employee_name, COALESCE(t.emp_no,e.EmpNo) employee_empno, e.DeptCode FROM trips t LEFT JOIN employees e ON e.id=t.employee_id WHERE t.id=?");
$stmt->execute([$id]);
$trip = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trip) exit('Not found');
// Approvers from circular by DeptCode
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
while (count($approvers) < 5) {
    $approvers[] = '';
}
$proposalDate = !empty($trip['created_at']) ? strtotime($trip['created_at']) : time();
$d = date('d', $proposalDate);
$m = date('m', $proposalDate);
$y = date('Y', $proposalDate);
$hasSGD = (float)($trip['temp_payment_sgn'] ?? 0) > 0;
$hasYEN = (float)($trip['temp_payment_yen'] ?? 0) > 0;
// Registration number (if field exists else generate)
$regNo = !empty($trip['registration_no']) ? $trip['registration_no'] : ('BT-' . str_pad($trip['id'], 5, '0', STR_PAD_LEFT) . '/' . $y);
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Approval Of Business Trip #<?= $id ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 4mm; /* tighter to fill page */
            color: #000;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 3px 4px;
            vertical-align: middle;
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
            background: #f2f2f2;
        }

        .shade2 {
            background: #e8e8e8;
        }

        @media print {
            .no-print {
                display: none !important
            }

            body {
                margin: 4mm; /* unify print margin with body for full width */
                font-size: 10.8px; /* slight bump to better fill vertical space */
            }
        }

        @page {
            size: A4 landscape;
            margin: 5mm; /* reduced margin to maximize printable area */
        }
    </style>
</head>

<body>
    <button class="no-print" onclick="window.print()" style="float:right;margin:4px 0;">Print</button>
    <!-- Header Bar -->
    <table style="margin-bottom:0;">
        <tr class="center bold">
            <td style="width:33.3%">PT.Yoshikawa Electronics Bintan</td>
            <td style="width:33.3%">YBR-HRD065</td>
            <td style="width:33.3%">Initial</td>
        </tr>
    </table>
    <!-- Title Row with Registration Box on Right -->
    <table style="margin-bottom:4px;">
        <tr>
            <td class="center bold" style="font-size:13px;" colspan="9">PT.YOSHIKAWA ELECTRONICS BINTAN&nbsp;&nbsp;Approval Of&nbsp;Business Trip</td>
            <td style="width:180px;" class="shade" colspan="2">
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td class="center" style="border:0;font-size:11px;">Registration Number</td>
                    </tr>
                    <tr style="text-align:center">
                        <td style="border:0;font-size:11px ;">ID = <?= esc($trip['id']) ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- Proposal / Identification / Approvals -->
    <table style="margin-bottom:0;">
        <tr class="center">
            <td style="width:90px;" class="shade">Proposal Date</td>
            <td style="width:140px;" class="center" colspan="2"><?= $d . '/' . $m . '/' . $y ?></td>


            <td style="width:80px;" class="shade">NIK</td>
            <td style="width:70px;" class="center"><?= esc($trip['employee_empno']) ?></td>


            <td style="width:60px;" colspan="2" class="shade">Name</td>
            <td style="width:180px;" class="center" colspan="4"><?= esc($trip['employee_name']) ?></td>


            <td style="width:90px;" class="shade">Signature</td>
        </tr>
        <tr>
            <td rowspan="2" class="shade" style="width:90px;">Approval</td>
            <?php foreach ($approvers as $ap): ?>
                <td class="shade2 center" colspan="2" style="min-width:90px;"><?= esc($ap) ?></td>
            <?php endforeach; ?>
            <td class="center" colspan="2" style="min-width:90px;">&nbsp;</td>
        </tr>
        <tr style="height:42px;">
            <?php foreach ($approvers as $ap): ?>
                <td colspan="2">&nbsp;</td>
            <?php endforeach; ?>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
    <!-- Detail Fields -->
    <table style="margin-top:0;">
        <tr>
            <td style="width:140px;" class="shade">Department / Section</td>
            <td colspan="10"><?= esc($trip['DeptCode'] ?: '-') ?></td>
        </tr>
        <tr>
            <td class="shade">Destination</td>
            <td colspan="10"><?= esc($trip['tujuan'] ?: '-') ?></td>
        </tr>
        <tr>
            <td class="shade">Destination Company Name</td>
            <td colspan="10"><?= esc($trip['destination_company'] ?: '-') ?></td>
        </tr>
        <tr>
            <td class="shade">Purpose</td>
            <td colspan="10" style="height:38px;"><?= esc($trip['purpose'] ?: '-') ?></td>
        </tr>
        <tr>
            <td class="shade" rowspan="1">Periode</td>
            <td class="shade center" style="width:60px;">From</td>
            <td style="width:110px;" class="center"><?= $trip['period_from'] ? date('d/m/Y', strtotime($trip['period_from'])) : '' ?></td>
            <td class="shade center" style="width:35px;">To</td>
            <td style="width:110px;" class="center"><?= $trip['period_to'] ? date('d/m/Y', strtotime($trip['period_to'])) : '' ?></td>
            <td colspan="6"></td>
        </tr>
    </table>
    <!-- Temporary Payment Section (separate table) -->
    <table style="margin-top:4px;">
        <tr class="shade small center">
            <td style="width:110px;">Temporary Payment</td>
            <td style="width:130px;" class="shade">Amount Of Application</td>
            <td style="width:60px;" class="shade">Currency</td>
            <td style="width:200px;" class="shade">Amount</td>
            <td style="width:200px;" class="shade">Receiver</td>
            <td style="width:150px;" class="shade">Signature</td>
        </tr>
        <tr>
            <td rowspan="4"></td>
            <td rowspan="4" class="center"></td>
            <td class="shade center small">IDR</td>
            <td><?= $trip['temp_payment_idr'] ? number_format($trip['temp_payment_idr'], 0, ',', '.') : '&nbsp;' ?></td>
            <td rowspan="4"></td>
            <td rowspan="4"></td>
        </tr>
        <tr>
            <td class="shade center small">SGD</td>
            <td>&nbsp;<?= $hasSGD ? number_format($trip['temp_payment_sgn'], 0, ',', '.') : '' ?></td>
        </tr>
        <tr>
            <td class="shade center small">YEN</td>
            <td>&nbsp;<?= $hasYEN ? number_format($trip['temp_payment_yen'], 0, ',', '.') : '' ?></td>
        </tr>
        <tr>
            <td class="shade center small">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td class="small">Date Of Collection</td>
            <td colspan="3"></td>
            <td></td>
        </tr>
    </table>
</body>

</html>