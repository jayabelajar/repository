<?php

namespace App\Core;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);

        // Full absolute path, tidak pakai relative path
        $viewFile   = __DIR__ . '/../Views/' . $view . '.php';
        $layoutFile = __DIR__ . '/../Views/layouts/public.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("View not found: $viewFile");
        }

        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout not found: $layoutFile");
        }

        // Ambil konten view → jadi $content
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Render dalam layout
        include $layoutFile;
    }
}
