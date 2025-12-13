<?php

use App\Core\App;

// Start session
if (session_status() === PHP_SESSION_NONE) session_start();

// Load .env if exists (simple parser: KEY=VALUE, quotes optional)
$envFile = __DIR__ . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
        $k = trim($k);
        $v = trim($v);
        if ($v !== '' && ($v[0] === '"' || $v[0] === "'")) {
            $v = trim($v, '\'"');
        }
        putenv("$k=$v");
        $_ENV[$k] = $v;
    }
}

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
