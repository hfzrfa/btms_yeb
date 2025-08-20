<?php
// One-time script to hash any plaintext (non-hashed) passwords.
require_once __DIR__ . '/../config/db.php';
$pdo = getPDO();
$updated=0; $checked=0;
$rows = $pdo->query("SELECT id, password FROM users")->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r){
  $checked++;
  $pw = $r['password'];
  // Detect bcrypt/argon2 by prefix $2 or $argon; if not, hash.
  if(strpos($pw,'$2')===0 || strpos($pw,'$argon')===0) continue;
  $hash = password_hash($pw, PASSWORD_DEFAULT);
  $st=$pdo->prepare('UPDATE users SET password=? WHERE id=?');
  $st->execute([$hash,$r['id']]);
  $updated++;
}
echo "Checked: $checked Updated: $updated\n";
