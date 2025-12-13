<?php

namespace App\Core;

use App\Middleware\CsrfMiddleware;

class Router
{
    private static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    public static function get($uri, $callback)
    {
        self::$routes['GET'][$uri] = $callback;
    }

    public static function post($uri, $callback)
    {
        self::$routes['POST'][$uri] = $callback;
    }

    public static function put($uri, $callback)
    {
        self::$routes['PUT'][$uri] = $callback;
    }

    public static function patch($uri, $callback)
    {
        self::$routes['PATCH'][$uri] = $callback;
    }

    public static function delete($uri, $callback)
    {
        self::$routes['DELETE'][$uri] = $callback;
    }

    public function dispatch($method, $requestUri)
    {
        $method = strtoupper($method);
        // Ambil path tanpa query string (tanpa menghapus $_GET!)
        $path = parse_url($requestUri, PHP_URL_PATH);

        // Hapus base folder sesuai config base_url (mis. "/sirepo-v1")
        $config   = require __DIR__ . '/../../config/config.php';
        $basePath = rtrim(parse_url($config['base_url'] ?? '', PHP_URL_PATH) ?? '', '/');
        if ($basePath !== '' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }

        if ($path === '' || $path === false) {
            $path = '/';
        }

        // CSRF middleware untuk POST non-API
        CsrfMiddleware::verify($method, $path);

        // ======================
        // 1. ROUTE STATIS
        // ======================
        if (isset(self::$routes[$method][$path])) {
            return $this->invoke(self::$routes[$method][$path]);
        }

        // ======================
        // 2. ROUTE DINAMIS
        // ======================
        foreach (self::$routes[$method] as $route => $callback) {

            // ubah {param} menjadi regex
            // mendukung: angka, huruf, dash, underscore, titik
            $pattern = preg_replace(
                '/\{([a-zA-Z0-9_]+)\}/',
                '([a-zA-Z0-9\-\_\.]+)',
                $route
            );

            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $path, $matches)) {

                array_shift($matches); // buang full match

                // urldecode parameter
                $matches = array_map('urldecode', $matches);

                return $this->invoke($callback, $matches);
            }
        }

        // ======================
        // 404
        // ======================
        http_response_code(404);
        $config = require __DIR__ . '/../../config/config.php';
        $baseUrl = rtrim($config['base_url'] ?? '', '/');
        $errorView = __DIR__ . '/../Views/errors/404.php';
        if (file_exists($errorView)) {
            $base_url = $baseUrl; // buat tersedia di view
            include $errorView;
        } else {
            echo "404 - Halaman tidak ditemukan.";
        }
        return;
    }

    private function invoke($callback, $params = [])
    {
        // jika function
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        // jika array [Controller::class, method]
        if (is_array($callback)) {

            $controller = $callback[0];
            $method = $callback[1];

            if (!class_exists($controller)) {
                throw new \Exception("Controller $controller tidak ditemukan.");
            }

            $obj = new $controller();
            return call_user_func_array([$obj, $method], $params);
        }

        // format "Controller@method"
        if (is_string($callback) && strpos($callback, '@') !== false) {
            list($controller, $method) = explode('@', $callback);
            $controller = "App\\Controllers\\" . $controller;

            if (!class_exists($controller)) {
                throw new \Exception("Controller $controller tidak ditemukan.");
            }

            $obj = new $controller();
            return call_user_func_array([$obj, $method], $params);
        }

        throw new \Exception("Callback tidak valid.");
    }
}
