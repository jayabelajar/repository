<?php

namespace App\Controllers\Public;

use App\Core\Controller;

class SyaratController extends Controller
{
    public function index()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        return $this->view('public/syarat', [
            'seo' => [
                'title'       => 'Syarat & Ketentuan | ' . $appName,
                'description' => 'Syarat dan ketentuan penggunaan layanan ' . $appName . ' oleh pengguna dan kontributor.',
                'canonical'   => $base . '/syarat',
            ]
        ]);
    }
}
