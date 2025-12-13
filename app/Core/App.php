<?php
namespace App\Core;

class App
{
    public function run()
    {
        $router = new Router();

        try {
            $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        } catch (\Throwable $e) {
            // Log error dengan aman
            $logDir  = __DIR__ . '/../../storage/logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0775, true);
            }
            $logFile = $logDir . '/app.log';
            $message = sprintf(
                "[%s] %s in %s:%d\nRequest: %s %s\nTrace: %s\n\n",
                date('Y-m-d H:i:s'),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $_SERVER['REQUEST_METHOD'] ?? 'CLI',
                $_SERVER['REQUEST_URI'] ?? '',
                $e->getTraceAsString()
            );
            @file_put_contents($logFile, $message, FILE_APPEND);

            // Tampilkan halaman 500 yang aman untuk publik
            http_response_code(500);
            $config = require __DIR__ . '/../../config/config.php';
            $base_url = rtrim($config['base_url'] ?? '', '/');
            $app_name = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
            $errorView = __DIR__ . '/../Views/errors/500.php';

            if (file_exists($errorView)) {
                include $errorView;
            } else {
                echo "Terjadi kesalahan pada server.";
            }
        }
    }
}
