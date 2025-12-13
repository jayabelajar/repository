<?php

use App\Core\App;

$isHttps = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
);

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base = __DIR__ . '/../app/';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) return;

    $relative = substr($class, strlen($prefix));
    $file     = $base . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) require_once $file;
});

require_once __DIR__ . '/../config/routes.php';

// helpers global
if (file_exists(__DIR__ . '/../app/Core/helpers.php')) {
    require_once __DIR__ . '/../app/Core/helpers.php';
}

$app = new App();
$app->run();
