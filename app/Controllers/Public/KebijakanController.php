<?php

namespace App\Controllers\Public;

use App\Core\Controller;

class KebijakanController extends Controller
{
    public function index()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        return $this->view('public/kebijakan', [
            'seo' => [
                'title'       => 'Kebijakan Privasi | ' . $appName,
                'description' => 'Kebijakan perlindungan data, privasi, dan penggunaan layanan di ' . $appName . '.',
                'canonical'   => $base . '/kebijakan',
            ]
        ]);
    }
}
