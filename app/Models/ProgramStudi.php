<?php

namespace App\Models;

use App\Core\Model;

class ProgramStudi extends Model
{
    protected string $table = 'program_studi';

    public function getPaginated(int $limit = 10, int $offset = 0, string $search = '', string $sort = 'asc')
    {
        $direction = strtolower($sort) === 'desc' ? 'DESC' : 'ASC';
        $sql = "SELECT ps.*,
                       (SELECT COUNT(*) FROM repository r WHERE r.program_studi_id = ps.id) AS total_repo
                FROM {$this->table} ps";

        $params = [];
        if ($search !== '') {
            $sql .= " WHERE ps.nama_program_studi LIKE :s";
            $params['s'] = "%{$search}%";
        }

        $sql .= " ORDER BY ps.nama_program_studi {$direction} LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        return $this->query($sql, $params)->fetchAll();
    }

    public function countAll(string $search = ''): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE nama_program_studi LIKE :s";
            $params['s'] = "%{$search}%";
        }
        return (int) $this->query($sql, $params)->fetchColumn();
    }

    public function find(int $id): ?array
    {
        return $this->fetch("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1", ['id' => $id]);
    }

    public function getAllWithCount(): array
    {
        $sql = "SELECT ps.id, ps.nama_program_studi,
                       (SELECT COUNT(*) FROM repository r WHERE r.program_studi_id = ps.id) AS total
                FROM {$this->table} ps
                ORDER BY ps.nama_program_studi ASC";

        return $this->fetchAll($sql);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (nama_program_studi) 
                VALUES (:nama)";
        return $this->execute($sql, [
            'nama'      => $data['nama_program_studi'],
        ]);
    }

    public function updateById(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} 
                SET nama_program_studi = :nama
                WHERE id = :id";
        return $this->execute($sql, [
            'nama' => $data['nama_program_studi'],
            'id'   => $id
        ]);
    }

    public function deleteById(int $id): bool
    {
        return $this->execute("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }
}
