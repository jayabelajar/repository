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

// Block direct root access on the API subdomain, show simple styled 404 (inline CSS to avoid asset dependency)
$reqPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if ($reqPath === '' || $reqPath === '/') {
    http_response_code(404);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>404 Not Found</title>'
        . '<style>body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#0b1220;color:#d7e1ff;display:flex;align-items:center;justify-content:center;height:100vh;}'
        . '.card{background:#111a2e;border:1px solid #1f2a44;border-radius:10px;padding:32px;max-width:460px;box-shadow:0 20px 60px rgba(0,0,0,0.4);}h1{margin:0 0 12px;font-size:28px;}p{margin:0 0 8px;line-height:1.5;color:#a9b7d9;}code{background:#0b1020;border:1px solid #1f273f;padding:2px 6px;border-radius:4px;color:#f5b94a;}</style>'
        . '</head><body><div class="card"><h1>404 - API</h1>'
        . '<p>Endpoint tidak ditemukan. Silakan gunakan rute API yang valid.</p>'
        . '<p>Contoh: <code>/api/mobile/public/home</code></p>'
        . '</div></body></html>';
    exit;
}

require __DIR__ . '/../../index.php';
