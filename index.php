<?php

use App\Core\App;

// Start session
if (session_status() === PHP_SESSION_NONE) session_start();

// AUTOLOAD (ROOT VERSION)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base   = __DIR__ . '/app/'; // beda dengan public!

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) return;

    $relative = substr($class, strlen($prefix));
    $file     = $base . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// LOAD ROUTES (ROOT)
require_once __DIR__ . '/config/routes.php';

// RUN APP
$app = new App();
$app->run();
