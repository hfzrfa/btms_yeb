<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $params = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $params['path'] ?? '/',
        'domain' => $params['domain'] ?? '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Basic login throttling helpers
function login_throttle_ok(string $username): bool {
    $now = time();
    // global block timer
    if (!empty($_SESSION['login_block_until']) && $now < $_SESSION['login_block_until']) {
        return false;
    }
    $failures = $_SESSION['login_failures'][$username] ?? [];
    // keep only last 15 minutes
    $failures = array_filter($failures, fn($t)=> $t > $now-900);
    $_SESSION['login_failures'][$username] = $failures;
    if (count($failures) >= 5) {
        // block user for 5 minutes
        $_SESSION['login_block_until'] = $now + 300;
        return false;
    }
    return true;
}

function login_record_failure(string $username): void {
    $now = time();
    $_SESSION['login_failures'][$username] = ($_SESSION['login_failures'][$username] ?? []);
    $_SESSION['login_failures'][$username][] = $now;
}

function login_block_remaining(): int {
    if (!empty($_SESSION['login_block_until'])) {
        $remain = $_SESSION['login_block_until'] - time();
        return $remain>0? $remain:0;
    }
    return 0;
}

function login_attempt(string $username, string $password): bool {
    $username = trim($username);
    if ($username==='') return false;
    // Throttle check
    if (!login_throttle_ok($username)) {
        return false;
    }
    // Search existing user in either db
    $user = find_user_in_db('yeb_business', $username) ?? find_user_in_db('btms_db', $username);
    if (!$user) {
        // Auto-provision from employees master (btms_db)
        try {
            $pdo = getPDO('btms_db');
            $stmt = $pdo->prepare('SELECT id, EmpNo FROM employees WHERE EmpNo = ? LIMIT 1');
            $stmt->execute([$username]);
            $emp = $stmt->fetch();
            if ($emp) {
                $hash = password_hash($emp['EmpNo'], PASSWORD_DEFAULT);
                $ins = $pdo->prepare('INSERT INTO users (username,password,role,employee_id) VALUES (?,?,"employee",?)');
                $ins->execute([$emp['EmpNo'], $hash, $emp['id']]);
                $user = find_user_in_db('btms_db', $username);
            }
        } catch (Throwable $e) {
            return false;
        }
        if (!$user) return false;
    }
    // Force usage of password hashes; allow plain match ONLY if legacy stored unhashed then upgrade
    $legacyPlainMatch = hash_equals($user['password'], $password);
    $valid = password_verify($password, $user['password']) || $legacyPlainMatch;
    // Constant small delay to reduce timing side channel
    usleep(random_int(20000,60000));
    if (!$valid) {
        login_record_failure($username);
        return false;
    }
    // Success: clear failures
    unset($_SESSION['login_failures'][$username]);
    unset($_SESSION['login_block_until']);
    // If legacy plain text, upgrade hash transparently
    if ($legacyPlainMatch || !password_get_info($user['password'])['algo']) {
        try {
            $pdo = getPDO('btms_db');
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $up=$pdo->prepare('UPDATE users SET password=? WHERE id=?');
            $up->execute([$newHash,$user['id']]);
        } catch (Throwable $e) { /* ignore */ }
    }
    // Regenerate session id to prevent fixation
    if (session_status()===PHP_SESSION_ACTIVE) session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
        'employee_id' => $user['employee_id'] ?? null,
        'must_change_password' => $user['must_change_password'] ?? 0
    ];
    return true;
}

function find_user_in_db(string $dbKey, string $username) {
    try {
        $pdo = getPDO($dbKey);
    $stmt = $pdo->prepare('SELECT id, username, password, role, employee_id, must_change_password FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        return $stmt->fetch();
    } catch (Throwable $e) {
        return null;
    }
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool {
    return current_user() !== null;
}

function require_login(): void {
    if (!is_logged_in()) {
        redirect('index.php?page=login');
    }
}

function logout_user(): void {
    $_SESSION = [];
    session_destroy();
}

function user_has_role(string ...$roles): bool {
    $u = current_user();
    if (!$u) return false;
    return in_array($u['role'], $roles, true);
}
