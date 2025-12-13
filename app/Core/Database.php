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
                $msg = 'Database connection failed: ' . $e->getMessage();
                // Log detail (tanpa password) agar mudah debug di shared hosting
                $context = sprintf(
                    '[DB_FAIL] host=%s port=%s db=%s user=%s dsn=%s error=%s',
                    $host,
                    $port,
                    $db,
                    $user,
                    $dsn,
                    $e->getMessage()
                );
                @error_log($msg);
                @file_put_contents(__DIR__ . '/../../storage/logs/db_error.log', $context . PHP_EOL, FILE_APPEND);
                http_response_code(500);
                exit('Database connection failed.');
            }
        }

        return self::$instance;
    }
}
