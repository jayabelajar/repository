<?php

namespace App\Controllers\Public;

use App\Core\Controller;

class FaqController extends Controller
{
    public function index()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        return $this->view('public/faq', [
            'seo' => [
                'title'       => 'FAQ | ' . $appName,
                'description' => 'Pertanyaan yang sering diajukan mengenai penggunaan repository di ' . $appName . '.',
                'canonical'   => $base . '/faq',
            ]
        ]);
    }
}
