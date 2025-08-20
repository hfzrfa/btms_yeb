<?php
// New printable layout replicating official "CALCULATION OF BUSINESS TRIP EXPENSE" form
$user = current_user();
if(!$user) redirect('index.php?page=login');
$pdo = getPDO();
$id = (int)($_GET['id'] ?? 0); if(!$id) exit('Missing id');
$stmt = $pdo->prepare('SELECT s.*, t.tujuan, t.period_from, t.period_to, t.temp_payment_idr, t.temp_payment_sgn, t.temp_payment_yen, t.emp_name, t.emp_no, e.DeptCode, t.destination_district, t.destination_company, t.purpose FROM settlements s LEFT JOIN trips t ON t.id=s.trip_id LEFT JOIN employees e ON e.id=t.employee_id WHERE s.id=?');
$stmt->execute([$id]);
$settle = $stmt->fetch(PDO::FETCH_ASSOC); if(!$settle) exit('Not found');
// Ensure columns for multi currency items (safe idempotent)
try { $pdo->exec('ALTER TABLE settlement_items ADD COLUMN amount_sgd DECIMAL(15,2) NULL, ADD COLUMN amount_yen DECIMAL(15,2) NULL'); } catch(Exception $e){}
// Items grouped by category
$items = [];
try { $q=$pdo->prepare('SELECT category, amount_idr, amount_sgd, amount_yen FROM settlement_items WHERE settlement_id=?'); $q->execute([$id]); $items = $q->fetchAll(PDO::FETCH_ASSOC); } catch(Exception $e){}
try { $pdo->exec("ALTER TABLE settlements ADD COLUMN settlement_date DATE NULL"); } catch(Exception $e){}
if(empty($settle['settlement_date'])) $settle['settlement_date'] = substr($settle['created_at'] ?? date('Y-m-d'),0,10);

$advance = (float)($settle['temp_payment_idr'] ?? 0);
$advance_sgd = (float)($settle['temp_payment_sgn'] ?? 0);
$advance_yen = (float)($settle['temp_payment_yen'] ?? 0);

// Derive rates from temporary composition (IDR / foreign)
$rateSgd = ($advance>0 && $advance_sgd>0)? ($advance / $advance_sgd) : 0;
$rateYen = ($advance>0 && $advance_yen>0)? ($advance / $advance_yen) : 0;

$categories = [
    'Airplane','Train','Ferry / Speed','Bus',
    'Taxi 1','Taxi 2','Taxi 3','Taxi 4','Taxi 5',
    'Other','Daily Allowance','Accommodation'
];
// Sum amounts per category
$rowData = [];
foreach($categories as $c){ $rowData[$c] = ['idr'=>0,'sgd'=>0,'yen'=>0]; }
foreach($items as $it){
    $c = $it['category'] ?? ''; if(!isset($rowData[$c])) continue; // ignore unknown
    $rowData[$c]['idr'] += (float)($it['amount_idr'] ?? 0);
    $rowData[$c]['sgd'] += (float)($it['amount_sgd'] ?? 0);
    $rowData[$c]['yen'] += (float)($it['amount_yen'] ?? 0);
}
$sumIdrInput=0;$sumSgd=0;$sumYen=0;
foreach($rowData as $c=>$v){ $sumIdrInput+=$v['idr']; $sumSgd+=$v['sgd']; $sumYen+=$v['yen']; }
$converted = ($rateSgd>0?$sumSgd*$rateSgd:0)+($rateYen>0?$sumYen*$rateYen:0);
$totalIdr = $sumIdrInput + $converted; // matches UI total logic
// Prefer stored total_realisasi if available (so PDF matches saved data exactly)
$storedTotal = isset($settle['total_realisasi']) ? (float)$settle['total_realisasi'] : 0;
if($storedTotal>0){ $totalIdr = $storedTotal; }
// Return To ACC = advance - total if positive
$returnToAcc = $advance>$totalIdr ? ($advance-$totalIdr) : 0;
// Pay To Employee shows the total used (business request) so it mirrors data
$payToEmployee = $totalIdr;
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html><html><head><meta charset="utf-8"><title>Settlement #<?= $id ?></title><style>
body{font-family:Arial,Helvetica,sans-serif;font-size:11px;margin:10px;color:#000;}
table{border-collapse:collapse;width:100%;}
td,th{border:1px solid #000;padding:3px 4px;vertical-align:middle;font-weight:normal;}
.center{text-align:center}.right{text-align:right}.bold{font-weight:bold}
.no-border td,.no-border th{border:0}
@media print{.no-print{display:none!important}body{margin:6px;font-size:10.5px}}
.title-row td{border-top:0;border-bottom:0}
</style></head><body>
<button class="no-print" onclick="window.print()" style="float:right;margin:4px 0;">Print</button>
<table class="header" style="margin-bottom:4px;">
    <tr class="center bold" style="font-size:12px;">
        <td style="width:33.3%">PT. Yoshikawa Electronics Bintan</td>
        <td style="width:33.3%">YBR-HRD066</td>
        <td style="width:33.3%">Initial</td>
    </tr>
    <tr class="center bold" style="font-size:13px;">
        <td colspan="3">CALCULATION OF BUSINESS TRIP EXPENSE</td>
    </tr>
</table>
<table style="margin-bottom:6px;">
    <tr>
        <td style="width:110px;">Proposal Date</td>
        <?php $d=$settle['settlement_date']; $dD=$d?date('d',strtotime($d)):''; $dM=$d?date('m',strtotime($d)):''; $dY=$d?date('Y',strtotime($d)):''; ?>
        <td style="width:30px;" class="center"><?= $dD ?></td>
        <td style="width:30px;" class="center"><?= $dM ?></td>
        <td style="width:50px;" class="center"><?= $dY ?></td>
        <td style="width:80px;">Name</td>
        <td><?= esc($settle['emp_name']) ?></td>
        <td style="width:80px;">Position</td>
        <td style="width:120px;"></td>
    </tr>
    <tr>
        <td>Department</td><td colspan="7"><?= esc($settle['DeptCode'] ?: '-') ?></td>
    </tr>
    <tr>
        <td>Periode</td><td colspan="3"><?= $settle['period_from']?date('d/m/Y',strtotime($settle['period_from'])):'' ?></td>
        <td class="center" style="font-weight:bold;">To</td>
        <td colspan="3"><?= $settle['period_to']?date('d/m/Y',strtotime($settle['period_to'])):'' ?></td>
    </tr>
    <tr>
        <td>Destination</td><td colspan="7"><?= esc($settle['destination_district'] ?: '-') ?></td>
    </tr>
    <tr>
        <td>Destination Company name</td><td colspan="7"><?= esc($settle['destination_company'] ?: '-') ?></td>
    </tr>
    <tr>
        <td>Purpose</td><td colspan="7"><?= esc($settle['purpose'] ?: '-') ?></td>
    </tr>
</table>

<table style="margin-bottom:6px;">
    <tr class="center bold" style="background:#f5f5f5;">
        <td style="width:180px;">Type Of Expenses</td>
        <td style="width:110px;">RP</td>
        <td style="width:90px;">SIN ($)</td>
        <td style="width:90px;">YEN</td>
        <td>Remarks</td>
    </tr>
    <?php foreach($categories as $cat): $v=$rowData[$cat]; ?>
    <tr>
        <td><?= $cat ?></td>
        <td class="right"><?= $v['idr']?number_format($v['idr'],0):'' ?></td>
        <td class="right"><?= $v['sgd']?number_format($v['sgd'],2):'' ?></td>
        <td class="right"><?= $v['yen']?number_format($v['yen'],0):'' ?></td>
        <td></td>
    </tr>
    <?php if($cat==='Bus'): ?>
    <tr style="height:18px;"><td colspan="5">Taxi must write in the day, route fare for taxi ride. If there are too many, write in separate sheet and attach it</td></tr>
    <?php endif; ?>
    <?php endforeach; ?>
    <tr class="bold">
        <td>Total Business Trip Expenses</td>
        <td class="right"><?= number_format($totalIdr,0) ?></td>
        <td class="right"><?= $sumSgd?number_format($sumSgd,2):'' ?></td>
        <td class="right"><?= $sumYen?number_format($sumYen,0):'' ?></td>
        <td></td>
    </tr>
    <tr>
        <td>Temporary Payment</td>
        <td class="right"><?= number_format($advance,0) ?></td>
        <td class="right"><?= $advance_sgd?number_format($advance_sgd,2):'' ?></td>
        <td class="right"><?= $advance_yen?number_format($advance_yen,0):'' ?></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="5" style="border:0;padding:0;">
            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td style="width:180px;border:1px solid #000;" rowspan="2"></td>
                    <td style="width:160px;border:1px solid #000;" class="center">Return To ACC</td>
                    <td style="width:160px;border:1px solid #000;" class="center">Pay to Employee</td>
                    <td style="border:1px solid #000;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border:1px solid #000;" class="right"><?= number_format($returnToAcc,0) ?></td>
                    <td style="border:1px solid #000;" class="right"><?= number_format($payToEmployee,0) ?></td>
                    <td style="border:1px solid #000;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table style="margin-bottom:6px;">
    <tr>
        <td style="width:200px;">Circulation *Used When US$ etc converted into S$ / RP.</td>
        <td style="width:160px;">Day Of Payment</td>
        <td style="width:160px;" class="center">Signature</td>
        <td></td>
    </tr>
</table>

<table style="margin-bottom:8px;">
    <tr class="center bold" style="background:#f5f5f5;">
        <td style="width:110px;">Claim by</td>
        <td style="width:105px;">Concerned<br>Asst.Manager</td>
        <td style="width:105px;">Concerned<br>Manager</td>
        <td style="width:105px;">Concerned GM</td>
        <td style="width:105px;">Calculated by<br>HR</td>
        <td style="width:105px;">HR Manager</td>
        <td style="width:105px;">Financial Advisor<br>Takimoto</td>
        <td style="width:105px;">Financial Advisor<br>Muramoto</td>
    </tr>
    <tr style="height:70px;">
        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
    </tr>
</table>

<div style="display:flex;justify-content:space-between;align-items:flex-start;">
    <div style="font-size:10px;max-width:540px;">(*) Those who went on an overseas business trip other than S'pore should attach business trip schedule for daily allowance calculation</div>
    <table style="width:200px;">
        <tr><td style="width:90px;">Registration No :</td><td></td></tr>
        <tr><td>ID =</td><td></td></tr>
    </table>
</div>

<p style="font-size:10px;margin-top:6px;">Generated: <?= date('d/m/Y H:i') ?> | Form ID: <?= $id ?> | Rates: <?= $rateSgd?('1 SGD='.$rateSgd.' IDR '):'' ?><?= $rateYen?(' | 1 YEN='.$rateYen.' IDR'):'' ?></p>
</body></html>