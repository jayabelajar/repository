<?php

namespace App\Controllers\Public;

use App\Core\Controller;

class PanduanController extends Controller
{
    public function index()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        return $this->view('public/panduan', [
            'seo' => [
                'title'       => 'Panduan Pengguna | ' . $appName,
                'description' => 'Panduan lengkap menggunakan sistem repository dan fitur pencarian di ' . $appName . '.',
                'canonical'   => $base . '/panduan',
            ]
        ]);
    }
}
