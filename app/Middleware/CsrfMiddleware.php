<?php

namespace App\Middleware;

use App\Core\Security\Csrf;

class CsrfMiddleware
{
    /**
     * Verifikasi CSRF untuk semua POST non-API.
     */
    public static function verify(string $method, string $path): void
    {
        if (strtoupper($method) !== 'POST') {
            return;
        }

        // Skip API endpoints (gunakan bearer token, bukan CSRF)
        if (strpos($path, '/api') === 0) {
            return;
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Csrf::check($token)) {
            http_response_code(419);
            exit('Invalid CSRF token.');
        }
    }
}
