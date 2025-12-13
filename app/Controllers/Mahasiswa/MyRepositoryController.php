<?php

namespace App\Controllers\Mahasiswa;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Models\Repository;
use App\Models\ActivityLog;

class MyRepositoryController extends Controller
{
    public function index()
    {
        $mhs = Auth::checkMahasiswa();
        $repoModel = new Repository();
        $logModel  = new ActivityLog();

        $filters = [
            'q'      => trim($_GET['q'] ?? ''),
            'tahun'  => $_GET['tahun'] ?? '',
            'jenis_karya' => $_GET['jenis'] ?? '',
        ];

        $repos = $repoModel->filterByUser((int) $mhs['id'], $filters, 100, 0);
        $headerActivities = array_map(static function ($row) use ($mhs) {
            return [
                'actor'  => $mhs['nama'] ?? $mhs['nama_lengkap'] ?? 'Mahasiswa',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$mhs['id'], 5));

        $this->view('mahasiswa/my_repository', [
            'user'  => $mhs,
            'mhs'   => $mhs,
            'repos' => $repos,
            'filters' => $filters,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/mahasiswa/dashboard'],
                ['label' => 'My Repository'],
            ],
            'header_activities' => $headerActivities,
            'suppress_layout_title' => true,
        ], 'mahasiswa');
    }
}
