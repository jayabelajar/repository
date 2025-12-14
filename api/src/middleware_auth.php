<?php
namespace App;

use function App\json;
use function App\verify_token;

/**
 * Enforce Authorization: Bearer <token> header and return the authenticated user row.
 */
function require_auth(): array {
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!str_starts_with($auth, 'Bearer ')) {
        json(['message' => 'Unauthorized'], 401);
    }

    $token = trim(substr($auth, 7));
    if ($token === '') {
        json(['message' => 'Unauthorized'], 401);
    }

    $user = verify_token($token);
    if (!$user) {
        json(['message' => 'Token invalid or expired'], 401);
    }

    return $user;
}
