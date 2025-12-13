<?php

namespace App\Controllers\Dosen;

use App\Core\Controller;
use App\Core\Security\Auth;

class TelusuriController extends Controller
{
    public function redirect()
    {
        Auth::checkDosen();
        $config = require __DIR__ . '/../../../config/config.php';
        $base   = rtrim($config['base_url'] ?? '', '/');
        $qs     = $_SERVER['QUERY_STRING'] ?? '';
        $url    = $base . '/telusuri' . ($qs ? '?' . $qs : '');
        return $this->redirect($url);
    }
}
