<?php
namespace App;

use DateTimeImmutable;

use function App\db;
use function App\env;

/**
 * Generate a cryptographically safe random token string for API auth.
 */
function generate_token_string(int $bytes = 32): string {
    return rtrim(strtr(base64_encode(random_bytes($bytes)), '+/', '-_'), '=');
}

/**
 * Create & persist an API token linked to a user.
 */
function create_api_token(int $userId): array {
    $ttlMinutes = (int) env('TOKEN_TTL_MINUTES', 120);
    $expiresAt  = (new DateTimeImmutable("+{$ttlMinutes} minutes"))->format('Y-m-d H:i:s');

    $token = generate_token_string();

    $stmt = db()->prepare("
        INSERT INTO api_tokens (user_id, token, expired_at, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$userId, $token, $expiresAt]);

    return [
        'token'      => $token,
        'expired_at' => $expiresAt
    ];
}

/**
 * Find the user for a given bearer token (only if not expired).
 */
function verify_token(string $token): ?array {
    $stmt = db()->prepare("
        SELECT u.*
        FROM api_tokens t
        JOIN users u ON u.id = t.user_id
        WHERE t.token = ?
          AND t.expired_at > NOW()
        LIMIT 1
    ");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    return $user ?: null;
}
