<?php

namespace App\Models;

use App\Core\Model;

class MataKuliah extends Model
{
    protected string $table = 'mata_kuliah';

    public function getAll(string $search = '', string $sort = 'asc', int $limit = 10, int $offset = 0)
    {
        $direction = strtolower($sort) === 'desc' ? 'DESC' : 'ASC';
        $sql = "SELECT mk.*,
                       (SELECT COUNT(*) FROM repository r WHERE r.mata_kuliah_id = mk.id) AS total
                FROM mata_kuliah mk";

        $params = [];
        if ($search !== '') {
            $sql .= " WHERE mk.nama LIKE :s";
            $params['s'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY mk.nama {$direction} LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        return $this->query($sql, $params)->fetchAll();
    }

    public function countAll(string $search = ''): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE nama LIKE :s";
            $params['s'] = '%' . $search . '%';
        }
        return (int) $this->query($sql, $params)->fetchColumn();
    }

    public function create(string $nama): bool
    {
        $sql = "INSERT INTO {$this->table} (nama) VALUES (:nama)";
        return $this->execute($sql, ['nama' => $nama]);
    }

    public function updateById(int $id, string $nama): bool
    {
        $sql = "UPDATE {$this->table} SET nama = :nama WHERE id = :id";
        return $this->execute($sql, ['nama' => $nama, 'id' => $id]);
    }

    public function deleteById(int $id): bool
    {
        return $this->execute("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }
}
