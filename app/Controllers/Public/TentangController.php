<?php

namespace App\Controllers\Public;

use App\Core\Controller;

class TentangController extends Controller
{
    public function index()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        return $this->view('public/tentang', [
            'seo' => [
                'title'       => 'Tentang | ' . $appName,
                'description' => 'Profil dan tujuan ' . $appName . ' sebagai repositori resmi Institut Agama Islam Hasan Jufri.',
                'canonical'   => $base . '/tentang',
            ]
        ], 'public');
    }
}
