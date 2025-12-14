<?php
namespace App;

use PDO;

function db(): PDO {
    static $pdo;

    if ($pdo) return $pdo;

    $pdo = new PDO(
        "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_NAME'),
        env('DB_USER'),
        env('DB_PASS'),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    return $pdo;
}
