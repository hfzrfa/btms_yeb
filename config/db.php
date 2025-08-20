<?php
$config = require __DIR__ . '/config.php';

function getPDO(string $dbKey = 'btms_db'): PDO
{
    $cfg = $GLOBALS['config'][$dbKey] ?? null;
    if (!$cfg) {
        throw new RuntimeException("Database config not found for key: $dbKey");
    }
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['dbname'], $cfg['charset']);
    return new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}
