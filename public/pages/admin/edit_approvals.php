<?php
// Admin page: Edit Approval Data (from trip_approvals/circural)
require_role('admin');
$pdo = getPDO();

// Self-heal: ensure optional columns exist
try {
  $pdo->exec("ALTER TABLE trip_approvals ADD COLUMN note TEXT NULL");
} catch (Exception $e) {
  // ignore if exists
}

// Fetch all approvals (from trip_approvals table)
$approvals = [];
try {
  $approvals = $pdo->query("SELECT ta.*, t.tujuan, t.employee_id, e.Name AS employee_name, e.EmpNo AS employee_empno FROM trip_approvals ta LEFT JOIN trips t ON t.id=ta.trip_id LEFT JOIN employees e ON e.id=t.employee_id ORDER BY ta.id DESC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// Handle edit request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
  $id = (int)post('id');
  $status = post('status');
  $note = trim(post('note'));
  if ($id && in_array($status, ['approved','rejected','pending'],true)) {
    // Ensure settlements table has a status column (if older DB)
    try { $pdo->exec("ALTER TABLE settlements ADD COLUMN status VARCHAR(20) DEFAULT 'submitted'"); } catch (Exception $e) {}

    // Find the related trip id for this approval
    $tripId = null;
    try {
      $q = $pdo->prepare('SELECT trip_id FROM trip_approvals WHERE id=?');
      $q->execute([$id]);
      $tripId = (int)($q->fetchColumn());
    } catch (Exception $e) {}

    // Run updates in a transaction for consistency
    $pdo->beginTransaction();
    try {
      $st = $pdo->prepare("UPDATE trip_approvals SET status=?, note=? WHERE id=?");
      $st->execute([$status, $note, $id]);
      if ($tripId) {
        // Update the trip's status to reflect admin decision
        $pdo->prepare('UPDATE trips SET status=? WHERE id=?')->execute([$status, $tripId]);
        // If there are settlements for this trip, sync their status as well
        $pdo->prepare('UPDATE settlements SET status=? WHERE trip_id=?')->execute([$status, $tripId]);
      }
      $pdo->commit();
      echo '<div class="alert alert-success">Status updated on approval' . ($tripId ? ', trip, and settlement(s).' : '.') . '</div>';
    } catch (Exception $e) {
      $pdo->rollBack();
      echo '<div class="alert alert-danger">Failed to update: ' . esc($e->getMessage()) . '</div>';
    }
  }
}
?>
<h3>Edit Trip Approvals</h3>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Trip</th>
      <th>Employee</th>
      <th>Status</th>
      <th>Note</th>
      <th>Created</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($approvals as $a): ?>
      <tr>
        <td><?= esc($a['id']) ?></td>
        <td><?= esc($a['tujuan']) ?></td>
        <td><?= esc($a['employee_name']) ?> (<?= esc($a['employee_empno']) ?>)</td>
        <td><?= esc($a['status']) ?></td>
        <td><?= esc($a['note'] ?? '') ?></td>
        <td><?= esc($a['created_at']) ?></td>
        <td>
          <form method="post" style="display:inline-block;min-width:180px;">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" value="<?= esc($a['id']) ?>">
            <select name="status" class="form-select form-select-sm" style="width:90px;display:inline-block;">
              <option value="approved" <?= $a['status']==='approved'?'selected':'' ?>>Approved</option>
              <option value="rejected" <?= $a['status']==='rejected'?'selected':'' ?>>Rejected</option>
              <option value="pending" <?= $a['status']==='pending'?'selected':'' ?>>Pending</option>
            </select>
            <input type="text" name="note" value="<?= esc($a['note'] ?? '') ?>" class="form-control form-control-sm" placeholder="Note" style="width:80px;display:inline-block;">
            <button class="btn btn-primary btn-sm">Save</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($approvals)): ?><tr><td colspan="7">No approvals found.</td></tr><?php endif; ?>
  </tbody>
</table>
