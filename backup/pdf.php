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
    <title>Business Trip Authorization - <?= esc($trip['employee_empno']) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #1565c0;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #1565c0;
            margin-bottom: 5px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 25px;
        }
        
        .info-section {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info-section h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: bold;
            color: #1565c0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            width: 120px;
            flex-shrink: 0;
        }
        
        .info-value {
            flex: 1;
        }
        
        .trip-details {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .trip-details h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: bold;
            color: #1565c0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-approved {
            background-color: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 30px;
        }
        
        .signature-section {
            text-align: center;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin: 40px 0 10px 0;
            height: 1px;
        }
        
        .print-info {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .btn-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1565c0;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .btn-print:hover {
            background: #0d47a1;
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Print PDF
    </button>

    <div class="header">
        <div class="company-logo">YEB</div>
        <div class="company-name">PT. Yoshikawa Electronic Bintan</div>
        <div class="document-title">Business Trip Authorization</div>
    </div>

    <div class="info-grid">
        <div class="info-section">
            <h3>Employee Information</h3>
            <div class="info-row">
                <span class="info-label">Employee No:</span>
                <span class="info-value"><?= esc($trip['employee_empno']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value"><?= esc($trip['employee_name']) ?></span>
            </div>
            <?php if($trip['DeptCode']): ?>
            <div class="info-row">
                <span class="info-label">Department:</span>
                <span class="info-value"><?= esc($trip['DeptCode']) ?></span>
            </div>
            <?php endif; ?>
            <?php if($trip['ClasCode']): ?>
            <div class="info-row">
                <span class="info-label">Classification:</span>
                <span class="info-value"><?= esc($trip['ClasCode']) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="info-section">
            <h3>Authorization Details</h3>
            <div class="info-row">
                <span class="info-label">Request ID:</span>
                <span class="info-value">#<?= str_pad($trip['id'], 6, '0', STR_PAD_LEFT) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Request Date:</span>
                <span class="info-value"><?= date('d F Y', strtotime($trip['created_at'])) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge status-approved">Approved</span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Print Date:</span>
                <span class="info-value"><?= date('d F Y H:i') ?></span>
            </div>
        </div>
    </div>

    <div class="trip-details">
        <h3>Trip Details</h3>
        <div class="info-grid">
            <div>
                <div class="info-row">
                    <span class="info-label">Destination:</span>
                    <span class="info-value"><?= esc($trip['tujuan']) ?></span>
                </div>
                <?php if(!empty($trip['destination_company'])): ?>
                <div class="info-row">
                    <span class="info-label">Company:</span>
                    <span class="info-value"><?= esc($trip['destination_company']) ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($trip['period_from']) && !empty($trip['period_to'])): ?>
                <div class="info-row">
                    <span class="info-label">Period:</span>
                    <span class="info-value"><?= date('d M Y', strtotime($trip['period_from'])) ?> - <?= date('d M Y', strtotime($trip['period_to'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
            <div>
                <div class="info-row">
                    <span class="info-label">Estimated Cost:</span>
                    <span class="info-value">IDR <?= number_format($trip['biaya_estimasi'], 0, ',', '.') ?></span>
                </div>
                <?php if(!empty($trip['temp_payment_idr'])): ?>
                <div class="info-row">
                    <span class="info-label">Temporary Payment:</span>
                    <span class="info-value">IDR <?= number_format($trip['temp_payment_idr'], 0, ',', '.') ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($trip['purpose'])): ?>
                <div class="info-row">
                    <span class="info-label">Purpose:</span>
                    <span class="info-value"><?= esc($trip['purpose']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="signature-section">
            <div><strong>Employee</strong></div>
            <div class="signature-line"></div>
            <div><?= esc($trip['employee_name']) ?></div>
            <div><?= esc($trip['employee_empno']) ?></div>
        </div>
        
        <div class="signature-section">
            <div><strong>Department Head</strong></div>
            <div class="signature-line"></div>
            <div>_________________</div>
            <div>Date: _____________</div>
        </div>
        
        <div class="signature-section">
            <div><strong>HR Manager</strong></div>
            <div class="signature-line"></div>
            <div>_________________</div>
            <div>Date: _____________</div>
        </div>
    </div>

    <div class="print-info">
        <p>This document was generated electronically by the Business Trip Management System.</p>
        <p>Generated on <?= date('d F Y H:i:s') ?> | Document ID: BT-<?= str_pad($trip['id'], 6, '0', STR_PAD_LEFT) ?>-<?= date('Ymd') ?></p>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
        
        // Handle print button
        function printDocument() {
            window.print();
        }
    </script>
</body>
</html>

    <!-- Temporary Payment Block (separate table for precise layout) -->
    <table style="margin-top:4px;">
        <tr class="shade small center">
            <td style="width:110px;" rowspan="5">Temporary Payment</td>
            <td style="width:130px;" rowspan="4" class="shade">Amount Of Application</td>
            <td style="width:60px;" class="shade">IDR</td>
            <td style="width:220px;"><?= $trip['temp_payment_idr'] ? number_format($trip['temp_payment_idr'], 0, ',', '.') : '&nbsp' ?></td>
            <td style="width:200px;" rowspan="1" class="shade">Receiver</td>
            <td style="width:150px;" rowspan="1" colspan="1" class="shade">Signature</td>
        </tr>
        <tr>
            <td class="shade center small">SGD</td>
            <td>&nbsp;<?= $hasSGD ? number_format($trip['temp_payment_sgn'], 2) : '' ?></td>
        </tr>
        <tr>
            <td class="shade center small">YEN</td>
            <td>&nbsp;<?= $hasYEN ? number_format($trip['temp_payment_yen'], 0, ',', '.') : '' ?></td>
        </tr>

        <tr>
            <td class="shade center small">&nbsp</td>
            <td></td>
        </tr>
        <tr>
            <td class="shade" rowspan="2" colspan="2"></td>
        </tr>
        <tr>
            <td class="small">Date Of Collection</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    



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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Of Business Trip - <?= esc($trip['employee_empno']) ?></title>
    <style>
        @media print {
            body {
                margin: 10px;
            }

            .no-print {
                display: none !important;
            }
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.25;
            margin: 15px;
            color: #000;
        }

        .btn-print {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #222;
            color: #fff;
            border: 0;
            padding: 6px 14px;
            font-size: 12px;
            cursor: pointer;
            border-radius: 3px;
        }

        .btn-print:hover {
            background: #000;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .tbl th,
        .tbl td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .small {
            font-size: 10px;
        }

        .title-row td {
            font-weight: bold;
            font-size: 13px;
        }

        .nobr {
            white-space: nowrap;
        }

        .h-blank {
            height: 34px;
        }

        .signature-box {
            height: 42px;
        }

        .amount-cell {
            width: 90px;
        }

        .currency-col {
            width: 150px;
        }

        .id-box {
            font-weight: bold;
            text-align: center;
        }

        .top-meta td {
            font-size: 11px;
        }
    </style>
</head>

<body>
    <button class="btn-print no-print" onclick="window.print()">Print</button>
    <?php
    // Dynamic approver names based on circular table (DeptCode -> Approval1..7)
    $approvers = [];
    if (!empty($trip['DeptCode'])) {
        try {
            $cstmt = $pdo->prepare("SELECT Approval1,Approval2,Approval3,Approval4,Approval5,Approval6,Approval7 FROM circular WHERE DeptCode = ? LIMIT 1");
            $cstmt->execute([$trip['DeptCode']]);
            if ($row = $cstmt->fetch(PDO::FETCH_ASSOC)) {
                foreach ($row as $val) {
                    $val = trim((string)$val);
                    if ($val !== '') $approvers[] = $val; // only non-empty
                }
            }
        } catch (Exception $e) { /* ignore and fallback */
        }
    }
    // Fallback if none found
    if (empty($approvers)) {
        $approvers = ['Approval 1', 'Approval 2', 'Approval 3', 'Approval 4', 'Approval 5', 'Approval 6', 'Approval 7'];
    }
    // Normalize to fixed 5 columns minimum (match sample form) for stable layout
    while (count($approvers) < 5) {
        $approvers[] = '';
    }
    $apprCount = count($approvers); // used for colspan math
    // proposal date parts
    $proposalDate = strtotime($trip['created_at']);
    $d = $proposalDate ? date('d', $proposalDate) : '';
    $m = $proposalDate ? date('m', $proposalDate) : '';
    $y = $proposalDate ? date('Y', $proposalDate) : '';
    // currency availability
    $hasSGD = isset($trip['temp_payment_sgn']) && is_numeric($trip['temp_payment_sgn']) && (float)$trip['temp_payment_sgn'] > 0;
    $hasYEN = isset($trip['temp_payment_yen']) && is_numeric($trip['temp_payment_yen']) && (float)$trip['temp_payment_yen'] > 0;
    ?>
    <table class="tbl top-meta" style="margin-bottom:6px;">
        <tr>
            <td style="width:40%;" class="center">PT.Yoshikawa Electronics Bintan</td>
            <td style="width:20%;" class="center">YBR-HR065</td>
            <td style="width:20%;" class="center">Initial</td>
        </tr>
    </table>
    <table class="tbl" style="margin-bottom:8px;">
        <tr class="title-row">
            <td class="center">PT.Yoshikawa Electronics Bintan Approval Of Business Trip</td>
        </tr>
    </table>

    <?php
    // Derive registration number (use existing field if present, else build one)
    $regNo = '';
    if (!empty($trip['registration_no'])) {
        $regNo = $trip['registration_no'];
    } else {
        $created = !empty($trip['created_at']) ? strtotime($trip['created_at']) : time();
        $regNo = 'BT-' . str_pad((string)$trip['id'], 5, '0', STR_PAD_LEFT) . '/' . date('Y', $created);
    }
    ?>
    <table class="tbl" style="margin-bottom:8px;">
        <tr>
            <th style="width:160px;">Registration Number</th>
            <td></td>
            <th style="width:120px;">ID</th>
            <td class="id-box"><?= esc($trip['id']) ?></td>
        </tr>
    </table>

    <table class="tbl small">
        <tr>
            <td colspan="2" style="width:75px;" class="center">Proposal Date</td>
            <td colspan="2" style="width:75px;" class="center">
                <?= ($d && $m && $y) ? esc($d . '/' . $m . '/' . $y) : '' ?>
            </td>

            <td style="width:55px; background:#e0e0e0;" class="center">NIK</td>
            <td style="width:55px;" class="center"><?= esc($trip['employee_empno']) ?></td>

            <td style="width:80px; background:#e0e0e0;" class="center">Name</td>
            <td style="width:80px;" class="center"><?= esc($trip['employee_name']) ?></td>

                        
            <td style="width:80px; background:#e0e0e0;" class="center">Signature</td>
            <td colspan="<?= $apprCount + 4 ?>">&nbsp;</td>

        </tr>
        <tr>
            <td class="center" colspan="2" rowspan="2">Approval</td>
            <?php foreach ($approvers as $a): ?>
                <td class="center" style="width:95px;"><?= esc($a) ?></td>
            <?php endforeach; ?>

        </tr>

        <tr>
            <?php foreach ($approvers as $a): ?>
                <td class="signature-box"></td>
            <?php endforeach; ?>
            <td class="signature-box"></td>


        <tr>
            <td colspan="2">Department / Section</td>
            <td colspan="<?= (3 + $apprCount + 2) ?>"><?= esc($trip['DeptCode'] ?: '-') ?></td>
        </tr>
        <tr>
            <td colspan="2">Destination</td>
            <td colspan="<?= (3 + $apprCount + 2) ?>"><?= esc($trip['tujuan']) ?><?= $trip['destination_company'] ? ' / ' . esc($trip['destination_company']) : '' ?></td>
        </tr>
        <tr>
            <td colspan="2">Purpose</td>
            <td colspan="<?= (3 + $apprCount + 2) ?>"><?= esc($trip['purpose'] ?: '-') ?></td>
        </tr>
        <tr>
            <td rowspan="2" colspan="1">Period</td>
            <td class="center" style="width:50px;">From</td>
            <td class="center" style="width:70px;"><?= $trip['period_from'] ? date('d/m/Y', strtotime($trip['period_from'])) : '' ?></td>
            <td class="center" style="width:30px;">To</td>
            <td class="center" style="width:70px;"><?= $trip['period_to'] ? date('d/m/Y', strtotime($trip['period_to'])) : '' ?></td>
            <td colspan="<?= ($apprCount + 2) ?>"></td>
        </tr>
        <tr>

        </tr>

        <!-- Temporary Payment -->
        <?php
        // total columns already = 7 + $apprCount (see earlier explanation)
        ?>
        <tr>
            <td rowspan="5" colspan="2" class="center">Temporary Payment</td>
            <td rowspan="4" class="center">Amount Of<br>Application</td>

            <td class="center currency-col">IDR</td>
            <td class="center amount-cell" colspan="2">
                <?= $trip['temp_payment_idr'] ? number_format($trip['temp_payment_idr'], 0, ',', '.') : '-' ?>
            </td>

            <td class="center" rowspan="4" colspan="6">Receiver</td>
            <td class="center" rowspan="4" colspan="4">Signature</td>

        </tr>
        <tr>
            <td class="center currency-col">SGD</td>
            <td class="center amount-cell" colspan="2">
                <?= $hasSGD ? number_format($trip['temp_payment_sgn'], 2) : '-' ?>
            </td>
        </tr>
        <tr>
            <td class="center currency-col">YEN</td>
            <td class="center amount-cell" colspan="2">
                <?= $hasYEN ? number_format($trip['temp_payment_yen'], 0, ',', '.') : '-' ?>
            </td>
        </tr>
        <tr>
            <td class="center currency-col" >&nbsp;</td>
            <td class="center amount-cell" colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="small">Date Of Collection</td>
            <td colspan="<?= $apprCount + 4 ?>">&nbsp;</td>
        </tr>


    </table>
</body>

</html>
