<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    protected string $table = 'settings';

    public function get(): ?array
    {
        return $this->fetch("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 1");
    }

    public function updateMaintenance(bool $on): bool
    {
        $row = $this->get();
        if ($row) {
            return $this->execute("UPDATE {$this->table} SET maintenance_mode = :m WHERE id = :id", [
                'm'  => $on ? 1 : 0,
                'id' => $row['id']
            ]);
        }
        return $this->execute("INSERT INTO {$this->table} (maintenance_mode) VALUES (:m)", ['m' => $on ? 1 : 0]);
    }
}

