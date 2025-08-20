<?php
$user = current_user();
if (!$user) {
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

// Permission check for employees
if ($user['role'] === 'employee' && $trip['employee_id'] != $user['employee_id']) {
    redirect('index.php?page=trips/index');
    return;
}
?>

<div class="content-section fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-2">Trip Details</h4>
            <p class="text-muted mb-0 small">View business trip request information</p>
        </div>
        <div class="btn-action-group">
            <a href="index.php?page=trips/index" class="btn btn-secondary btn-modern">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
            <?php if($trip['status'] === 'approved'): ?>
                <a href="index.php?page=trips/pdf&id=<?= $trip['id'] ?>" class="btn btn-primary btn-modern" target="_blank">
                    <i class="fas fa-file-pdf me-2"></i>Download PDF
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <!-- Employee Information -->
        <div class="col-lg-6">
            <div class="card card-clean border-0">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-user text-primary me-2"></i>Employee Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Employee No</label>
                            <div class="fw-semibold"><?= esc($trip['employee_empno']) ?></div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Name</label>
                            <div class="fw-semibold"><?= esc($trip['employee_name']) ?></div>
                        </div>
                        <?php if($trip['DeptCode']): ?>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Department</label>
                            <div><span class="badge badge-modern bg-light text-dark"><?= esc($trip['DeptCode']) ?></span></div>
                        </div>
                        <?php endif; ?>
                        <?php if($trip['ClasCode']): ?>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Classification</label>
                            <div><span class="badge badge-modern bg-light text-dark"><?= esc($trip['ClasCode']) ?></span></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trip Status -->
        <div class="col-lg-6">
            <div class="card card-clean border-0">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-info-circle text-primary me-2"></i>Request Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Request ID</label>
                            <div class="fw-semibold">#<?= str_pad($trip['id'], 6, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Status</label>
                            <div>
                                <?php
                                $statusClass = match($trip['status']) {
                                    'approved' => 'status-active',
                                    'pending' => 'status-pending', 
                                    default => 'status-inactive'
                                };
                                $badgeClass = match($trip['status']) {
                                    'approved' => 'bg-success',
                                    'pending' => 'bg-warning',
                                    'rejected' => 'bg-danger',
                                    'cancelled' => 'bg-dark',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="status-indicator <?= $statusClass ?>">
                                    <span class="badge badge-modern <?= $badgeClass ?> text-white">
                                        <?= ucfirst($trip['status']) ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">Request Date</label>
                            <div><?= date('d F Y H:i', strtotime($trip['created_at'])) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trip Details -->
        <div class="col-12">
            <div class="card card-clean border-0">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-plane text-primary me-2"></i>Trip Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label class="form-label small fw-semibold text-muted">Destination</label>
                            <div class="fw-semibold"><?= esc($trip['tujuan']) ?></div>
                        </div>
                        
                        <?php if(!empty($trip['destination_district'])): ?>
                        <div class="col-lg-6">
                            <label class="form-label small fw-semibold text-muted">District</label>
                            <div><?= esc($trip['destination_district']) ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($trip['destination_company'])): ?>
                        <div class="col-lg-6">
                            <label class="form-label small fw-semibold text-muted">Company</label>
                            <div><?= esc($trip['destination_company']) ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($trip['period_from']) && !empty($trip['period_to'])): ?>
                        <div class="col-lg-6">
                            <label class="form-label small fw-semibold text-muted">Period</label>
                            <div><?= date('d M Y', strtotime($trip['period_from'])) ?> - <?= date('d M Y', strtotime($trip['period_to'])) ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-lg-6">
                            <label class="form-label small fw-semibold text-muted">Estimated Cost</label>
                            <div class="fw-semibold text-primary">IDR <?= number_format($trip['biaya_estimasi'], 0, ',', '.') ?></div>
                        </div>
                        
                        <?php if(!empty($trip['temp_payment_idr'])): ?>
                        <div class="col-lg-6">
                            <label class="form-label small fw-semibold text-muted">Advance Payment</label>
                            <div class="fw-semibold text-success">IDR <?= number_format($trip['temp_payment_idr'], 0, ',', '.') ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($trip['purpose'])): ?>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">Purpose</label>
                            <div class="p-3 bg-light rounded"><?= esc($trip['purpose']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
