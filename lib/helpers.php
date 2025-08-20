<?php
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

// Send common security headers (call early in index.php header include)
function send_security_headers(): void {
    static $sent=false; if($sent) return; $sent=true;
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer-when-downgrade');
    header('X-XSS-Protection: 1; mode=block');
    header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
    // Basic CSP (adjust if inline scripts needed)
    header("Content-Security-Policy: default-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com data:; img-src 'self' data:");
}

function esc(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function post(string $key, $default = null) {
    return $_POST[$key] ?? $default;
}

function get(string $key, $default = null) {
    $v = $_GET[$key] ?? $default;
    if (is_string($v)) {
        // basic sanitation to prevent directory traversal in page param
        $v = preg_replace('#[^a-zA-Z0-9_/.-]#','',$v);
        // collapse .. segments
        $v = preg_replace('#\.\.+#','',$v);
    }
    return $v;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_token" value="' . esc(csrf_token()) . '">';
}

function verify_csrf(): bool {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = post('_token');
        return hash_equals($_SESSION['csrf_token'] ?? '', $token ?? '');
    }
    return true;
}
