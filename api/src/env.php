<?php
namespace App;

$ENV = [];

$file = __DIR__ . '/../.env';
if (file_exists($file)) {
    foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
        if (!$line || str_starts_with($line, '#')) continue;
        [$k, $v] = explode('=', $line, 2);
        $ENV[$k] = trim($v);
    }
}

function env(string $key, $default = null) {
    global $ENV;
    return $ENV[$key] ?? $default;
}
