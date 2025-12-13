<?php

/**
 * Konfigurasi sekarang membaca dari environment variable untuk mencegah
 * kredensial tersimpan di repository. Siapkan file .env di server/mesin lokal
 * atau set environment variable sesuai nama di bawah ini.
 */
// Pastikan .env terbaca (beberapa hosting tidak memuatnya otomatis)
foreach ([__DIR__ . '/../.env', __DIR__ . '/../../.env'] as $envFile) {
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
        break;
    }
}

$env = static function (string $key, $default = null) {
    // Baca dari $_ENV lebih dulu (beberapa hosting mematikan getenv)
    $value = $_ENV[$key] ?? getenv($key);
    return ($value === false || $value === null) ? $default : $value;
};

$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
// Hilangkan suffix "/public" agar base mengarah ke root project
$basePath = preg_replace('~/public$~', '', $scriptDir) ?: '';
$dynamicBase = ($httpHost ? $scheme . '://' . $httpHost : 'http://localhost') . $basePath;

$envAppEnv = $env('APP_ENV', 'local');
$isLocalHost = $httpHost && (str_contains($httpHost, 'localhost') || str_contains($httpHost, '127.0.0.1') || $envAppEnv === 'local');
$defaultBase = $dynamicBase ?: 'https://sirepo-inhafi.com';

return [
    // Branding utama situs
    'app_name'      => $env('APP_NAME', 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri'),
    // Pakai host/path yang sedang diakses; jika tidak ada (CLI), pakai APP_BASE_URL atau default.
    'base_url'      => rtrim((string) ($dynamicBase ?: $env('APP_BASE_URL', $defaultBase)), '/'),
    // Jika env tidak diset, fallback ke nilai default berikut (disarankan tetap set APP_API_SECRET di env)
    'api_secret'    => $env('APP_API_SECRET', 'QZboOI2D9K0m9rwTXutXkPs9olhhGDXDT7Ota4A6h4c='),
    'environment'   => $env('APP_ENV', 'production'),
    'database'      => [
        'host'    => $env('DB_HOST', 'localhost'),
        'port'    => (int) $env('DB_PORT', 3306),
        'name'    => $env('DB_NAME', 'u804549048_sirepo'),
        'user'    => $env('DB_USER', 'u804549048_sirepo'),
        'pass'    => $env('DB_PASS', 'Jayoynux#2026'),
        'charset' => $env('DB_CHARSET', 'utf8mb4'),
    ],

    // Maintenance mode (kalau true, semua public diarahkan ke maintenance.php)
    'maintenance'   => filter_var($env('APP_MAINTENANCE', false), FILTER_VALIDATE_BOOLEAN),

    // SEO default
    'seo' => [
        'title'       => 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri',
        'description' => 'Repositori resmi karya ilmiah, tugas akhir, laporan, dan penelitian di Institut Agama Islam Hasan Jufri.',
        'keywords'    => 'repository, Institut Agama Islam Hasan Jufri, sirepo, skripsi, tesis, tugas akhir, penelitian, perpustakaan digital',
    ],
];
