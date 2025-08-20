<?php
require_role('employee');
$pdo=getPDO();
$id=(int)get('id');
$stmt=$pdo->prepare('SELECT * FROM trips WHERE id=? AND employee_id=?');
$stmt->execute([$id,current_user()['id']]);
$trip=$stmt->fetch();
if($trip && $trip['status']==='pending'){
    $pdo->prepare('UPDATE trips SET status="cancelled" WHERE id=?')->execute([$id]);
}
redirect('index.php?page=trips/index');
