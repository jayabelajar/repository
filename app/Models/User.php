<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = "users";

    public function findByEmail(string $email, ?string $role = null): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];

        if ($role !== null) {
            $sql .= " AND role = :role";
            $params['role'] = $role;
        }

        $sql .= " LIMIT 1";
        return $this->fetch($sql, $params);
    }

    public function findMahasiswaByEmailOrUsername(string $value)
    {
        $sql = "SELECT * FROM users 
                WHERE role = 'mahasiswa'
                AND (email = :email OR username = :username OR nim = :nim)
                LIMIT 1";

        return $this->query($sql, [
            'email'    => $value,
            'username' => $value,
            'nim'      => $value,
        ])->fetch();
    }


    public function findDosen(string $value)
    {
        $sql = "SELECT * FROM users 
                WHERE role = 'dosen'
                AND (email = :email OR username = :username OR nidn_nip = :nidn)
                LIMIT 1";

        return $this->query($sql, [
            'email'    => $value,
            'username' => $value,
            'nidn'     => $value,
        ])->fetch();
    }

    public function findAdmin(string $value)
    {
        $sql = "SELECT * FROM users 
                WHERE role = 'admin'
                AND (email = :email OR username = :username)
                LIMIT 1";

        return $this->query($sql, [
            'email'    => $value,
            'username' => $value,
        ])->fetch();
    }

    public function getAllOrdered(string $search = '', ?string $role = null, int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM users";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE nama_lengkap LIKE :s
                      OR email LIKE :s_email
                      OR username LIKE :s_username
                      OR nim LIKE :s_nim
                      OR nidn_nip LIKE :s_nidn";
            $params = [
                's'         => '%' . $search . '%',
                's_email'   => '%' . $search . '%',
                's_username'=> '%' . $search . '%',
                's_nim'     => '%' . $search . '%',
                's_nidn'    => '%' . $search . '%',
            ];
        }
        if ($role !== null && in_array($role, ['admin','dosen','mahasiswa'], true)) {
            $sql .= $search === '' ? " WHERE role = :role" : " AND role = :role";
            $params['role'] = $role;
        }
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        return $this->fetchAll($sql, $params);
    }

    public function countAll(string $search = '', ?string $role = null): int
    {
        $sql = "SELECT COUNT(*) FROM users";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE nama_lengkap LIKE :s
                      OR email LIKE :s_email
                      OR username LIKE :s_username
                      OR nim LIKE :s_nim
                      OR nidn_nip LIKE :s_nidn";
            $params = [
                's'         => '%' . $search . '%',
                's_email'   => '%' . $search . '%',
                's_username'=> '%' . $search . '%',
                's_nim'     => '%' . $search . '%',
                's_nidn'    => '%' . $search . '%',
            ];
        }
        if ($role !== null && in_array($role, ['admin','dosen','mahasiswa'], true)) {
            $sql .= $search === '' ? " WHERE role = :role" : " AND role = :role";
            $params['role'] = $role;
        }
        return (int) $this->query($sql, $params)->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        return $this->fetch("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1", ['id' => $id]);
    }

    public function isBanned(array $user): bool
    {
        if (empty($user['banned_until'])) {
            return false;
        }

        $banTime = strtotime($user['banned_until']);
        if ($banTime === false) {
            return false;
        }

        if ($banTime > time()) {
            return true;
        }

        // jika masa ban sudah lewat, reset counter
        $this->resetLoginAttempts((int) $user['id']);
        return false;
    }

    public function incrementFailedAttempts(int $userId, int $maxAttempts = 3, int $banSeconds = 604800): void
    {
        $sql = "UPDATE {$this->table}
                SET failed_attempts = failed_attempts + 1,
                    banned_until = IF(
                        failed_attempts + 1 >= :max_attempts,
                        DATE_ADD(NOW(), INTERVAL :ban_seconds SECOND),
                        banned_until
                    )
                WHERE id = :id";

        $this->execute($sql, [
            'max_attempts' => $maxAttempts,
            'ban_seconds'  => $banSeconds,
            'id'           => $userId,
        ]);
    }

    public function resetLoginAttempts(int $userId): void
    {
        $this->execute(
            "UPDATE {$this->table} SET failed_attempts = 0, banned_until = NULL WHERE id = :id",
            ['id' => $userId]
        );
    }

    public function getByIds(array $ids): array
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        if (empty($ids)) {
            return [];
        }
        $placeholders = [];
        $params = [];
        foreach ($ids as $idx => $id) {
            $key = 'id' . $idx;
            $placeholders[] = ':' . $key;
            $params[$key] = $id;
        }
        $sql = "SELECT * FROM {$this->table} WHERE id IN (" . implode(',', $placeholders) . ")";
        return $this->query($sql, $params)->fetchAll();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
                (username, nama_lengkap, email, password_hash, role, nim, nidn_nip)
                VALUES (:username, :nama, :email, :password_hash, :role, :nim, :nidn_nip)";

        return $this->execute($sql, [
            'username'      => $data['username'] ?? null,
            'nama'          => $data['nama_lengkap'],
            'email'         => $data['email'],
            'password_hash' => $data['password_hash'],
            'role'          => $data['role'],
            'nim'           => $data['nim'] ?? null,
            'nidn_nip'      => $data['nidn_nip'] ?? null,
        ]);
    }

    public function updateUser(int $id, array $data): bool
    {
        $fields = [
            'username'      => $data['username'] ?? null,
            'nama_lengkap'  => $data['nama_lengkap'] ?? null,
            'email'         => $data['email'] ?? null,
            'role'          => $data['role'] ?? null,
            'nim'           => $data['nim'] ?? null,
            'nidn_nip'      => $data['nidn_nip'] ?? null,
        ];

        $set = [];
        $params = [];
        foreach ($fields as $col => $val) {
            if ($val !== null) {
                $set[] = "{$col} = :{$col}";
                $params[$col] = $val;
            }
        }

        if (!empty($data['password_hash'])) {
            $set[] = "password_hash = :password_hash";
            $params['password_hash'] = $data['password_hash'];
        }

        if (empty($set)) {
            return false;
        }

        $params['id'] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id";
        return $this->execute($sql, $params);
    }

    public function deleteById(int $id): bool
    {
        return $this->execute("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $fields = [
            'nama_lengkap' => $data['nama_lengkap'] ?? null,
            'email'        => $data['email'] ?? null,
            'username'     => $data['username'] ?? null,
            'nidn_nip'     => $data['nidn_nip'] ?? null,
        ];

        $set = [];
        $params = [];
        foreach ($fields as $column => $value) {
            if ($value !== null) {
                $set[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
        }

        if (!empty($data['password'])) {
            $set[] = "password_hash = :password_hash";
            $params['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($set)) {
            return false;
        }

        $params['id'] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id";

        return $this->execute($sql, $params);
    }

    public function updatePasswordHash(int $userId, string $passwordHash): void
    {
        $this->execute(
            "UPDATE {$this->table} SET password_hash = :password_hash WHERE id = :id",
            ['password_hash' => $passwordHash, 'id' => $userId]
        );
    }

    public function setBanUntil(int $userId, ?string $datetime): void
    {
        $this->execute(
            "UPDATE {$this->table} SET banned_until = :banned_until WHERE id = :id",
            ['banned_until' => $datetime, 'id' => $userId]
        );
    }
}
