<?php

namespace App\Controllers\Public;

use App\Core\Controller;

class MaintenanceController extends Controller
{
    public function index()
    {
        return $this->view('public/maintenance', [], 'no-layout');
    }
}
