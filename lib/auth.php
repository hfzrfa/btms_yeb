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
function ensure_users_autoincrement(): void {
    static $done=false; if($done) return; $done=true;
    try {
        $pdo = getPDO('btms_db');
        $col = $pdo->query("SHOW COLUMNS FROM users LIKE 'id'")->fetch();
        if($col && stripos($col['Extra']??'', 'auto_increment')===false){
            // Try alter table to add primary key + auto increment if missing
            try { $pdo->exec("ALTER TABLE users MODIFY id INT NOT NULL AUTO_INCREMENT"); } catch(Throwable $e){}
            // Ensure PK
            try { $pdo->exec("ALTER TABLE users ADD PRIMARY KEY (id)"); } catch(Throwable $e){}
        }
    } catch(Throwable $e){ /* ignore */ }
}
function ensure_admin_seed(): void {
    static $done = false; if($done) return; $done=true;
    try {
        $pdo = getPDO('btms_db');
    ensure_users_autoincrement();
        $has = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
        if($has===0) {
            $hash = password_hash('admin', PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users(username,password,role,must_change_password) VALUES('admin',?,'admin',1)")->execute([$hash]);
        }
    } catch(Throwable $e){ /* ignore */ }
}
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
    // Always ensure at least one admin exists (default admin/admin)
    ensure_admin_seed();
    // TEMP debug log (remove later)
    try { file_put_contents(__DIR__.'/../storage/login_debug.log', date('Y-m-d H:i:s')." | user=$username\n", FILE_APPEND); } catch(Throwable $e){}
    // Throttle check
    if (!login_throttle_ok($username)) {
        return false;
    }
    // Search existing user in either db
    $user = find_user_in_db('yeb_business', $username) ?? find_user_in_db('btms_db', $username);
    // Auto-seed admin if none exists and username == admin
    if(!$user && $username === 'admin') {
        try {
            $pdo = getPDO('btms_db');
            ensure_users_autoincrement();
            $hasAdmin = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
            if($hasAdmin === 0) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = $pdo->prepare("INSERT INTO users(username,password,role,must_change_password) VALUES(?,?,'admin',1)");
                $ins->execute([$username,$hash]);
                $user = find_user_in_db('btms_db', $username);
            }
        } catch(Throwable $e){ /* ignore */ }
    }
    if (!$user) {
        // Auto-provision from employees master (btms_db)
        try {
            $pdo = getPDO('btms_db');
            $emp = null; $empId = null;
            ensure_users_autoincrement();
            try {
                $stmt = $pdo->prepare('SELECT id, EmpNo FROM employees WHERE EmpNo = ? LIMIT 1');
                $stmt->execute([$username]);
                $emp = $stmt->fetch();
                if($emp){ $empId = $emp['id']; }
            } catch(Throwable $e){ /* maybe table missing */ }
            if(!$emp){
                // fallback to employeemaster (no id field relationship, set employee_id NULL)
                try {
                    $stmt2 = $pdo->prepare('SELECT EmpNo FROM employeemaster WHERE EmpNo=? LIMIT 1');
                    $stmt2->execute([$username]);
                    $emp = $stmt2->fetch();
                } catch(Throwable $e){ /* ignore */ }
            }
            if ($emp) {
                $empNo = $emp['EmpNo'];
                $hash = password_hash($empNo, PASSWORD_DEFAULT);
                $ins = $pdo->prepare('INSERT INTO users (username,password,role,employee_id) VALUES (?,?,"employee",?)');
                $ins->execute([$empNo, $hash, $empId]);
                $user = find_user_in_db('btms_db', $username);
            }
        } catch (Throwable $e) {
            // silent fail -> treat as invalid user
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
        try { file_put_contents(__DIR__.'/../storage/login_debug.log', date('Y-m-d H:i:s')." | FAIL pw user=$username\n", FILE_APPEND); } catch(Throwable $e){}
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
        'must_change_password' => $user['must_change_password'] ?? 0,
        'login_time' => time()
    ];
    try { file_put_contents(__DIR__.'/../storage/login_debug.log', date('Y-m-d H:i:s')." | SUCCESS user=$username\n", FILE_APPEND); } catch(Throwable $e){}
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
    $u = $_SESSION['user'] ?? null;
    if (!$u) return null;
    // Auto logout after 8 hours (28800 seconds)
    $loginTime = $u['login_time'] ?? 0;
    if ($loginTime && (time() - $loginTime) > 28800) {
        logout_user();
        return null;
    }
    return $u;
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
    // Clear all session data
    if (session_status() === PHP_SESSION_ACTIVE) {
        // Unset all session variables
        $_SESSION = [];
        // Get params to correctly remove the cookie
        $params = session_get_cookie_params();
        $cookieName = session_name();
        // Invalidate the session cookie (set in past)
        setcookie($cookieName, '', time() - 42000, $params['path'] ?? '/', $params['domain'] ?? '', ($params['secure'] ?? false), true);
        // Destroy session storage
        session_destroy();
    }
    // Proactively clear any additional auth-related cookies if added in future (pattern btms_*)
    foreach ($_COOKIE as $k => $v) {
        if (stripos($k, 'btms_') === 0) {
            setcookie($k, '', time() - 42000, '/');
        }
    }
}

function user_has_role(string ...$roles): bool {
    $u = current_user();
    if (!$u) return false;
    return in_array($u['role'], $roles, true);
}
