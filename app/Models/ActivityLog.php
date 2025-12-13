<?php

namespace App\Models;

use App\Core\Model;

class ActivityLog extends Model
{
    protected string $table = 'activity_logs';

    public function countByUser(int $userId): int
    {
        return (int) $this->query("SELECT COUNT(*) FROM {$this->table} WHERE user_id = :uid", ['uid' => $userId])->fetchColumn();
    }

    public function getRecentByUser(int $userId, int $limit = 10): array
    {
        $sql = "SELECT description, activity_type, created_at
                FROM {$this->table}
                WHERE user_id = :uid
                ORDER BY created_at DESC
                LIMIT :limit";
        return $this->query($sql, ['uid' => $userId, 'limit' => $limit])->fetchAll() ?: [];
    }

    public function getPagedByUser(int $userId, int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE user_id = :uid
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        return $this->query($sql, ['uid' => $userId, 'limit' => $limit, 'offset' => $offset])->fetchAll() ?: [];
    }
}
