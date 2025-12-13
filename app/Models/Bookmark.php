<?php

namespace App\Models;

use App\Core\Model;

class Bookmark extends Model
{
    protected string $table = 'bookmark';

    public function listByUser(int $userId, int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT b.id AS bookmark_id,
                       b.created_at,
                       r.id AS repository_id,
                       r.judul, r.slug, r.author, r.tahun, r.jenis_karya,
                       ps.nama_program_studi AS prodi,
                       mk.nama AS mata_kuliah
                FROM {$this->table} b
                JOIN repository r ON r.id = b.repository_id
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE b.user_id = :uid
                ORDER BY b.created_at DESC
                LIMIT :limit OFFSET :offset";

        return $this->query($sql, [
            'uid'    => $userId,
            'limit'  => $limit,
            'offset' => $offset,
        ])->fetchAll();
    }

    public function getByUser(int $userId)
    {
        $sql = "SELECT b.*, r.judul, r.slug, r.author, r.tahun
                FROM bookmark b
                JOIN repository r ON r.id = b.repository_id
                WHERE b.user_id = :uid
                ORDER BY b.created_at DESC";

        return $this->query($sql, ['uid' => $userId])->fetchAll();
    }

    public function getByUserWithDetail(int $userId, int $limit = 20): array
    {
        $sql = "SELECT b.id AS bookmark_id,
                       b.created_at,
                       r.id AS repository_id,
                       r.judul, r.slug, r.author, r.tahun, r.jenis_karya,
                       ps.nama_program_studi AS prodi,
                       mk.nama AS mata_kuliah
                FROM bookmark b
                JOIN repository r ON r.id = b.repository_id
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE b.user_id = :uid
                ORDER BY b.created_at DESC
                LIMIT :limit";

        return $this->query($sql, ['uid' => $userId, 'limit' => $limit])->fetchAll();
    }

    public function countByUser(int $userId): int
    {
        return (int) $this->query(
            "SELECT COUNT(*) FROM {$this->table} WHERE user_id = :uid",
            ['uid' => $userId]
        )->fetchColumn();
    }

    public function isBookmarked(int $userId, int $repositoryId): bool
    {
        $data = $this->fetch(
            "SELECT id FROM {$this->table} WHERE user_id = :uid AND repository_id = :rid LIMIT 1",
            ['uid' => $userId, 'rid' => $repositoryId]
        );

        return !empty($data);
    }

    public function add(int $userId, int $repositoryId): bool
    {
        return $this->execute(
            "INSERT INTO {$this->table} (user_id, repository_id) VALUES (:uid, :rid)",
            ['uid' => $userId, 'rid' => $repositoryId]
        );
    }

    public function remove(int $userId, int $repositoryId): bool
    {
        return $this->execute(
            "DELETE FROM {$this->table} WHERE user_id = :uid AND repository_id = :rid",
            ['uid' => $userId, 'rid' => $repositoryId]
        );
    }

    public function toggle(int $userId, int $repositoryId): bool
    {
        if ($this->isBookmarked($userId, $repositoryId)) {
            $this->remove($userId, $repositoryId);
            return false; // removed
        }

        $this->add($userId, $repositoryId);
        return true; // added
    }
}
