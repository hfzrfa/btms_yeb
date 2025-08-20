<?php
// Allow both manager and admin to approve/reject
$user=current_user();
if(!in_array($user['role'],['manager','admin'])){ echo '<div class="alert alert-danger">Unauthorized</div>'; return; }
$pdo = getPDO();
$id=(int)get('id');
$stmt=$pdo->prepare('SELECT t.*, e.Name employee_name, e.EmpNo employee_empno FROM trips t LEFT JOIN employees e ON e.id=t.employee_id WHERE t.id=?');
$stmt->execute([$id]);
$trip=$stmt->fetch(PDO::FETCH_ASSOC);
if(!$trip){echo '<div class="alert alert-danger">Not found</div>';return;}

// Determine trip_approvals schema to insert correctly
$approvalCols=[]; try { $approvalCols=$pdo->query("SHOW COLUMNS FROM trip_approvals")->fetchAll(PDO::FETCH_COLUMN,0);} catch(Exception $e) {}
if($_SERVER['REQUEST_METHOD']==='POST' && verify_csrf()){
    $status = post('status');
    if(in_array($status,['approved','rejected'],true)){
        $pdo->prepare('UPDATE trips SET status=?, approved_at=NOW() WHERE id=?')->execute([$status,$id]);
        // Build insert dynamically
        if(in_array('manager_id',$approvalCols)){
            $pdo->prepare('INSERT INTO trip_approvals(trip_id, manager_id, status, created_at) VALUES(?,?,?,NOW())')->execute([$id,$user['id'],$status]);
        } elseif(in_array('approver_name',$approvalCols)) {
            $pdo->prepare('INSERT INTO trip_approvals(trip_id, approver_name, status, created_at) VALUES(?,?,?,NOW())')->execute([$id,$user['username'],$status]);
        }
        redirect('index.php?page=trips/index');
    }
}
// Timeline query tolerance
if(in_array('manager_id',$approvalCols)){
  $timeline=$pdo->prepare('SELECT ta.*, u.username manager FROM trip_approvals ta LEFT JOIN users u ON u.id=ta.manager_id WHERE trip_id=? ORDER BY ta.id');
} else {
  $timeline=$pdo->prepare('SELECT ta.*, ta.approver_name manager FROM trip_approvals ta WHERE trip_id=? ORDER BY ta.id');
}
$timeline->execute([$id]);
$timeline=$timeline->fetchAll(PDO::FETCH_ASSOC);
?>
<h4>Approve / Review Trip</h4>
<div class="card mb-3"><div class="card-body">
  <div class="row g-2">
  <div class="col-md-4"><strong>Employee:</strong> <?= esc($trip['employee_name'] ?? $trip['emp_name'] ?? 'Unknown') ?> (<?= esc($trip['employee_empno'] ?? $trip['emp_no'] ?? '') ?>)</div>
    <div class="col-md-4"><strong>Tujuan:</strong> <?= esc($trip['tujuan']) ?></div>
    <div class="col-md-4"><strong>Tanggal:</strong> <?= esc($trip['tanggal']) ?></div>
    <div class="col-md-4"><strong>Estimasi:</strong> <?= number_format($trip['biaya_estimasi']) ?></div>
    <div class="col-md-12"><strong>Keperluan:</strong><br><?= nl2br(esc($trip['keperluan'])) ?></div>
  </div>
</div></div>
<?php if($trip['status']==='pending'): ?>
<form method="post" class="mb-4">
  <?= csrf_field(); ?>
  <div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select" required>
      <option value="approved">Approve</option>
      <option value="rejected">Reject</option>
    </select>
  </div>
  <button class="btn btn-success">Submit Decision</button>
  <a href="index.php?page=trips/index" class="btn btn-secondary">Back</a>
</form>
<?php else: ?>
<div class="alert alert-info">Trip has been <?= esc($trip['status']) ?>.</div>
<?php endif; ?>
<h5>Approval Timeline</h5>
<ul class="list-group mb-3">
  <?php foreach($timeline as $t): ?>
    <li class="list-group-item small">[<?= esc($t['created_at']) ?>] <strong><?= esc($t['manager'] ?? 'Manager') ?></strong> set status to <span class="badge bg-info text-dark"><?= esc($t['status']) ?></span></li>
  <?php endforeach; ?>
  <?php if(empty($timeline)): ?><li class="list-group-item">No actions yet.</li><?php endif; ?>
</ul>
