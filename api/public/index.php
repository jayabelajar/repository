<?php
// Front controller untuk subdomain API.
// Dialihkan ke aplikasi utama supaya logika tetap satu sumber.

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require __DIR__ . '/../../index.php';
