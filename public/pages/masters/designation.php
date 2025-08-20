<?php
require_role('admin');
$pdo = getPDO();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
  if (post('action') === 'create') {
    $pdo->prepare('INSERT INTO designations(name) VALUES(?)')->execute([trim(post('name'))]);
  } elseif (post('action') === 'delete') {
    $pdo->prepare('DELETE FROM designations WHERE id=?')->execute([post('id')]);
  }
  redirect('index.php?page=masters/designation');
}
$rows = $pdo->query('SELECT * FROM designations ORDER BY name')->fetchAll();
?>
<h4>Designations</h4>
<form class="row g-2 mb-3" method="post">
  <?= csrf_field(); ?>
  <input type="hidden" name="action" value="create">
  <div class="col-auto"><input name="name" class="form-control" placeholder="New Designation" required></div>
  <div class="col-auto"><button class="btn btn-primary">Add</button></div>
</form>
<table class="table table-sm table-bordered datatable">
  <thead>
    <tr>
      <th>Name</th>
      <th width=80>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= esc($r['name']) ?></td>
        <td>
          <form method="post" onsubmit="return confirm('Delete?')" class="d-inline">
            <?= csrf_field(); ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $r['id'] ?>">
            <button class="btn btn-danger btn-sm">Del</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>