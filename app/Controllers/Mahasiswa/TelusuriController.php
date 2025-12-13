<?php

namespace App\Controllers\Mahasiswa;

use App\Core\Controller;
use App\Core\Security\Auth;

class TelusuriController extends Controller
{
    public function redirect()
    {
        Auth::checkMahasiswa();
        $this->redirect('telusuri');
    }
}
