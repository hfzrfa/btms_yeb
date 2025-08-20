<?php
// Check if user is logged in and has appropriate role
$user = current_user();
if (!$user || !in_array($user['role'], ['admin', 'manager', 'employee'])) {
    redirect('index.php?page=login');
    return;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    redirect('index.php?page=trips/index');
    return;
}

$pdo = getPDO();

// Get trip details with employee information
$stmt = $pdo->prepare("
    SELECT t.*, 
           COALESCE(t.emp_name, e.Name) as employee_name,
           COALESCE(t.emp_no, e.EmpNo) as employee_empno,
           e.DeptCode, e.ClasCode, e.DestCode, e.Sex, e.GrpCode
    FROM trips t 
    LEFT JOIN employees e ON e.id = t.employee_id 
    WHERE t.id = ?
");
$stmt->execute([$id]);
$trip = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trip) {
    redirect('index.php?page=trips/index');
    return;
}

// Only allow PDF generation for approved trips
if ($trip['status'] !== 'approved') {
    redirect('index.php?page=trips/index');
    return;
}

// Get current user for permission check
$user = current_user();
if ($user['role'] === 'employee' && $trip['employee_id'] != $user['employee_id']) {
    redirect('index.php?page=trips/index');
    return;
}

// NOTE: Originally we sent application/pdf headers but output pure HTML, causing the browser PDF viewer to error.
// We now serve normal HTML so it opens correctly; user can use browser Print > Save as PDF.
// (If true binary PDF needed later, integrate a library like Dompdf/mPDF and restore appropriate headers.)
// header('Content-Type: application/pdf');
// header('Content-Disposition: inline; filename="Business_Trip_' . $trip['employee_empno'] . '_' . date('Y-m-d', strtotime($trip['created_at'])) . '.pdf"');

?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Approval Of Business Trip - <?= esc($trip['employee_empno']) ?></title>
        <style>
                @media print { body{margin:10px;} .no-print{display:none!important;} }
                body{font-family:Arial,Helvetica,sans-serif;font-size:11px;line-height:1.25;margin:15px;color:#000;}
                .btn-print{position:fixed;top:10px;right:10px;background:#222;color:#fff;border:0;padding:6px 14px;font-size:12px;cursor:pointer;border-radius:3px;}
                .btn-print:hover{background:#000;}
                table{border-collapse:collapse;width:100%;}
                .tbl th,.tbl td{border:1px solid #000;padding:4px 6px;vertical-align:top;}
                .center{text-align:center;}
                .small{font-size:10px;}
                .title-row td{font-weight:bold;font-size:13px;}
                .nobr{white-space:nowrap;}
                .h-blank{height:34px;}
                .signature-box{height:42px;}
                .amount-cell{width:90px;}
                .currency-col{width:70px;}
                .id-box{font-weight:bold;text-align:center;}
                .top-meta td{font-size:11px;}
        </style>
</head>
<body>
<button class="btn-print no-print" onclick="window.print()">Print</button>
<?php
// Dynamic approver names based on circular table (DeptCode -> Approval1..7)
$approvers = [];
if(!empty($trip['DeptCode'])) {
    try {
        $cstmt = $pdo->prepare("SELECT Approval1,Approval2,Approval3,Approval4,Approval5,Approval6,Approval7 FROM circular WHERE DeptCode = ? LIMIT 1");
        $cstmt->execute([$trip['DeptCode']]);
        if($row = $cstmt->fetch(PDO::FETCH_ASSOC)) {
            foreach($row as $val) {
                $val = trim((string)$val);
                if($val !== '') $approvers[] = $val; // only non-empty
            }
        }
    } catch(Exception $e) { /* ignore and fallback */ }
}
// Fallback if none found
if(empty($approvers)) {
    $approvers = ['Approval 1','Approval 2','Approval 3','Approval 4','Approval 5'];
}
// Normalize to fixed 5 columns minimum (match sample form) for stable layout
while(count($approvers) < 5) { $approvers[] = ''; }
$apprCount = count($approvers); // used for colspan math
// proposal date parts
$proposalDate = strtotime($trip['created_at']);
$d = $proposalDate?date('d',$proposalDate):'';
$m = $proposalDate?date('m',$proposalDate):'';
$y = $proposalDate?date('Y',$proposalDate):'';
// currency availability
$hasSGD = isset($trip['temp_payment_sgn']) && is_numeric($trip['temp_payment_sgn']) && (float)$trip['temp_payment_sgn'] > 0;
$hasYEN = isset($trip['temp_payment_yen']) && is_numeric($trip['temp_payment_yen']) && (float)$trip['temp_payment_yen'] > 0;
?>
<table class="tbl top-meta" style="margin-bottom:6px;">
    <tr>
        <td style="width:40%;" class="center">PT.Yoshikawa Electronics Bintan</td>
        <td style="width:20%;" class="center">YBR-HR065</td>
        <td style="width:20%;" class="center">Initial</td>
        <td style="width:20%;" class="center">&nbsp;</td>
    </tr>
</table>
<table class="tbl" style="margin-bottom:8px;">
    <tr class="title-row">
        <td class="center">PT.Yoshikawa Electronics Bintan Approval Of Business Trip</td>
    </tr>
</table>

<table class="tbl small">
    <tr>
        <td style="width:90px;" class="center" rowspan="2">Proposal Date</td>
        <td style="width:16px;" class="center">D</td>
        <td style="width:16px;" class="center">M</td>
        <td style="width:28px;" class="center">Y</td>
        <td style="width:55px;" class="center">Emp No</td>
        <td style="width:130px;" class="center">Name</td>
    <?php foreach($approvers as $a): ?>
            <td class="center" style="width:95px;">Approval<br><span class="small"><?= esc($a) ?></span></td>
        <?php endforeach; ?>
        <td style="width:80px;" class="center">Signature</td>
    </tr>
    <tr class="h-blank">
        <td class="center"><?= esc($d) ?></td>
        <td class="center"><?= esc($m) ?></td>
        <td class="center"><?= esc($y) ?></td>
        <td class="center"><?= esc($trip['employee_empno']) ?></td>
        <td><?= esc($trip['employee_name']) ?></td>
    <?php foreach($approvers as $a): ?>
            <td class="signature-box"></td>
        <?php endforeach; ?>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Department / Section</td>
    <td colspan="<?=(3+$apprCount+2)?>"><?= esc($trip['DeptCode'] ?: '-') ?></td>
    </tr>
    <tr>
        <td colspan="2">Destination</td>
    <td colspan="<?=(3+$apprCount+2)?>"><?= esc($trip['tujuan']) ?><?= $trip['destination_company']? ' / '.esc($trip['destination_company']) : '' ?></td>
    </tr>
    <tr>
        <td colspan="2">Purpose</td>
    <td colspan="<?=(3+$apprCount+2)?>"><?= esc($trip['purpose'] ?: '-') ?></td>
    </tr>
    <tr>
        <td rowspan="2" colspan="2" class="center">Period</td>
        <td class="center" style="width:50px;">From</td>
        <td class="center" style="width:70px;"><?= $trip['period_from']?date('d/m/Y',strtotime($trip['period_from'])):'' ?></td>
        <td class="center" style="width:30px;">To</td>
        <td class="center" style="width:70px;"><?= $trip['period_to']?date('d/m/Y',strtotime($trip['period_to'])):'' ?></td>
    <td colspan="<?= ($apprCount+2) ?>"></td>
    </tr>
    <tr>
    <td colspan="<?= ($apprCount+4) ?>"></td>
    </tr>
    <tr>
        <td rowspan="4" colspan="2" class="center">Temporary Payment</td>
        <td class="center" colspan="2">Amount of Application</td>
        <td class="center amount-cell">IDR</td>
        <td class="center amount-cell"><?= $trip['temp_payment_idr']?number_format($trip['temp_payment_idr'],0,',','.'):'-' ?></td>
    <td class="center" colspan="<?= max($apprCount-2,1) ?>">Receiver</td>
        <td class="center" colspan="2">Signature</td>
    </tr>
    <tr>
        <td class="center" colspan="2">SGD</td>
    <td class="center" colspan="2"><?= $hasSGD ? number_format($trip['temp_payment_sgn'],2) : '-' ?></td>
    <td colspan="<?= ($apprCount) ?>"></td>
    </tr>
    <tr>
        <td class="center" colspan="2">YEN</td>
    <td class="center" colspan="2"><?= $hasYEN ? number_format($trip['temp_payment_yen'],0,',','.') : '-' ?></td>
    <td colspan="<?= ($apprCount) ?>"></td>
    </tr>
    <tr>
    <td colspan="<?= ($apprCount+4) ?>">Date for Collection: _____________</td>
    </tr>
    <tr>
        <td rowspan="2" colspan="2" class="center">Settlement</td>
        <td class="center">Date</td>
        <td class="center">Signature</td>
        <td class="center" colspan="2">Registration No</td>
    <td class="center" colspan="<?= ($apprCount) ?>">Record by HRD</td>
    </tr>
    <tr class="h-blank">
        <td></td><td></td>
        <td colspan="2" class="id-box">ID = <?= esc($trip['id']) ?></td>
    <td colspan="<?= ($apprCount) ?>"></td>
    </tr>
</table>

<p class="small" style="margin-top:8px;text-align:center;">Printed <?= date('d/m/Y H:i') ?> &mdash; System Generated Form</p>
<script>/* no js needed except print */</script>
</body>
</html>
