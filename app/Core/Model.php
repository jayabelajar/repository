<?php
namespace App\Core;

use PDO;
use PDOException;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    protected function query(string $sql, array $params = [])
    {
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $val) {
            // auto detect type
            $paramKey = is_int($key) ? $key + 1 : ':' . $key;
            $type = is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($paramKey, $val, $type);
        }

        $stmt->execute();
        return $stmt;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetch(string $sql, array $params = []): ?array
    {
        $data = $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    protected function execute(string $sql, array $params = []): bool
    {
        return $this->query($sql, $params) !== false;
    }

    public function lastInsertId(): string
    {
        return $this->db->lastInsertId();
    }
}
