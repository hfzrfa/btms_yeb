<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/guard.php';
require_once __DIR__ . '/../lib/helpers.php';
send_security_headers();

$page = get('page', 'dashboard');

// Global allowed pages list (baseline)
$allowed = [
    'login',
    'signup',
    'dashboard',
    'employees/index',
    'employees/create',
    'employees/import',
    'employees/edit',
    'employees/delete',
    'employees/suggest',
    'masters/department',
    'masters/group',
    'masters/designation',
    'trips/index',
    'trips/create',
    'trips/register',
    'trips/register_admin',
    'trips/register_manager',
    'trips/approve',
    'trips/cancel',
    'trips/employee_search',
    'trips/edit',
    'trips/view',
    'trips/pdf',
    'settlements/index',
    'settlements/create',
    'settlements/pdf',
    'users/sync',
    'users/reset',
    'users/rebuild',
    'users/create',
    'admin/edit_approvals',
    // 'admin/circural'
];

// Role-based restrictions (pages each role MAY access besides login/signup)
$rolePages = [
    'employee' => ['dashboard', 'trips/create', 'trips/pdf', 'trips/view',],
    'manager'  => ['dashboard', 'trips/index', 'trips/create', 'trips/approve', 'trips/edit', 'trips/view', 'trips/pdf', 'settlements/index', 'settlements/create', 'settlements/pdf'],
    'admin'    => $allowed // admin full access (auto includes users/create)
];

if (!in_array($page, $allowed, true)) {
    // unknown page -> 404 minimal
    header('HTTP/1.1 404 Not Found');
    echo '404 Not Found';
    exit;
}

// Public page exception (login/signup) WITHOUT full app header/sidebar
if (in_array($page, ['login', 'signup'], true)) {
    // Minimal head + body wrapper for auth pages
?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BTMS - <?= htmlspecialchars(ucfirst($page)) ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="assets/css/theme.css" rel="stylesheet" />
        <style>
            body {
                background: #f5f7fb;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
        </style>
    </head>

    <body>
        <div class="auth-wrapper w-100">
            <?php include __DIR__ . '/pages/' . $page . '.php'; ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html><?php
            exit;
        }

        require_login();
        // Enforce per-role access
        $cu = current_user();
        if ($cu) {
            $role = $cu['role'];
            $whitelist = $rolePages[$role] ?? [];
            if (!in_array($page, $whitelist, true) && !in_array($page, ['login', 'signup'], true)) {
                header('HTTP/1.1 403 Forbidden');
                echo '<h1 style="font:16px Arial;margin:40px;">403 Forbidden</h1>';
                exit;
            }
        }

        // Raw pages that must NOT include the global layout (so they can send their own headers / printable docs)
        $rawPages = ['trips/pdf', 'settlements/pdf', 'employees/suggest'];
        if (in_array($page, $rawPages, true)) {
            $target = __DIR__ . '/pages/' . $page . '.php';
            if (is_file($target)) {
                include $target; // pdf.php handles its own headers/output
            } else {
                header('HTTP/1.1 404 Not Found');
                echo 'Not found';
            }
            exit;
        }

        // Standard layout flow
        include __DIR__ . '/partials/header.php';
        $target = __DIR__ . '/pages/' . $page . '.php';
        if (is_file($target)) {
            include $target;
        } else {
            echo '<div class="alert alert-danger">Page not found</div>';
        }
        include __DIR__ . '/partials/footer.php';
