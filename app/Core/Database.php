<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/config.php';

            $dbConfig = $config['database'] ?? [];
            $host     = $dbConfig['host'] ?? 'localhost';
            $port     = $dbConfig['port'] ?? 3306;
            $db       = $dbConfig['name'] ?? 'sirepo_inhafi';
            $user     = $dbConfig['user'] ?? 'root';
            $pass     = $dbConfig['pass'] ?? '';
            $charset  = $dbConfig['charset'] ?? 'utf8mb4';

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                http_response_code(500);
                exit('Database connection failed.');
            }
        }

        return self::$instance;
    }
}
