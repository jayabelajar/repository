<?php
namespace App\Core;

class Controller
{
    protected array $data = [];

    protected function view(string $view, array $data = [], string $layout = 'public')
    {
        $config = require __DIR__ . '/../../config/config.php';

        $defaultSeo = $config['seo'] ?? ['title' => $config['app_name'] ?? '', 'description' => '', 'keywords' => ''];
        $seoMerged = array_merge($defaultSeo, $data['seo'] ?? []);

        $this->data = array_merge($this->data, $data, [
            'app_name' => $config['app_name'],
            'base_url' => rtrim($config['base_url'], '/'),
            'seo'      => $seoMerged,
        ]);

        $contentView = __DIR__ . "/../Views/{$view}.php";

        if (!file_exists($contentView)) {
            throw new \Exception("View {$view} tidak ditemukan");
        }

        extract($this->data);

        /* ==========================================================
           MODE NO-LAYOUT — langsung load file view saja (single page)
           ========================================================== */
        if ($layout === 'no-layout') {
            require $contentView;
            return;
        }

        /* ==========================================================
           MODE NORMAL — pakai layout header/footer
           ========================================================== */
        $layoutView = __DIR__ . "/../Views/layouts/{$layout}.php";

        if (!file_exists($layoutView)) {
            throw new \Exception("Layout {$layout} tidak ditemukan");
        }

        ob_start();
        require $contentView;
        $content = ob_get_clean();

        require $layoutView;
    }

    protected function json($data, int $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $path)
    {
        $config = require __DIR__ . '/../../config/config.php';
        $base = rtrim($config['base_url'], '/');
        header('Location: ' . $base . '/' . ltrim($path, '/'));
        exit;
    }
}
