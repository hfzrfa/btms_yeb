<?php
require_role('admin');
header('Content-Type: application/json');
$pdo = getPDO();
$q = trim(get('q',''));
$limit = 10;
if($q===''){ echo json_encode([]); exit; }
// Detect raw vs normalized
$hasEmpNo=false; try { $cols=$pdo->query("DESCRIBE employees")->fetchAll(PDO::FETCH_COLUMN,0); foreach($cols as $c){ if(strtolower($c)==='empno'){ $hasEmpNo=true; break; } } } catch(Exception $e){}
if($hasEmpNo){
  $stmt=$pdo->prepare("SELECT EmpNo as empno, Name as name, DeptCode as dept, ClasCode as clas, DestCode as dest, GrpCode as grp FROM employees WHERE (Name LIKE ? OR EmpNo LIKE ?) ORDER BY Name LIMIT $limit");
  $like='%'.$q.'%';
  $stmt->execute([$like,$like]);
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
  exit;
}
// fallback normalized
$stmt=$pdo->prepare("SELECT e.id, e.name FROM employees e WHERE e.name LIKE ? ORDER BY e.name LIMIT $limit");
$stmt->execute(['%'.$q.'%']);
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
$out=[]; foreach($rows as $r){ $out[]=['empno'=>$r['id'],'name'=>$r['name']]; }
echo json_encode($out);
