<?php

namespace App\Models;

use App\Core\Model;

class ApiToken extends Model
{
    protected string $table = 'api_tokens';

    public function createToken(int $userId, string $token, int $ttlSeconds): bool
    {
        $expiredAt = date('Y-m-d H:i:s', time() + $ttlSeconds);
        $tokenHash = hash('sha256', $token);

        $sql = "INSERT INTO {$this->table} (user_id, token, expired_at)
                VALUES (:uid, :token, :expired_at)";

        return $this->execute($sql, [
            'uid'        => $userId,
            'token'      => $tokenHash,
            'expired_at' => $expiredAt,
        ]);
    }

    public function removeByUser(int $userId): void
    {
        $this->execute("DELETE FROM {$this->table} WHERE user_id = :uid", ['uid' => $userId]);
    }

    public function findValidToken(string $token): ?array
    {
        $tokenHash = hash('sha256', $token);
        $sql = "SELECT * FROM {$this->table}
                WHERE token = :token
                  AND expired_at > NOW()
                LIMIT 1";

        return $this->fetch($sql, ['token' => $tokenHash]);
    }

    public function cleanupExpired(): void
    {
        $this->execute("DELETE FROM {$this->table} WHERE expired_at <= NOW()");
    }

    public function revokeToken(string $token): void
    {
        $tokenHash = hash('sha256', $token);
        $this->execute("DELETE FROM {$this->table} WHERE token = :token", ['token' => $tokenHash]);
    }
}
