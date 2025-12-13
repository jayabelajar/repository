<?php

namespace App\Controllers\Mahasiswa;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function index()
    {
        $mhs = Auth::checkMahasiswa();
        $logModel = new ActivityLog();

        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;
        $offset  = ($page - 1) * $perPage;

        $total = $logModel->countByUser((int)$mhs['id']);
        $logs  = $logModel->getPagedByUser((int)$mhs['id'], $perPage, $offset);

        $headerActivities = array_map(static function ($row) use ($mhs) {
            return [
                'actor'  => $mhs['nama'] ?? $mhs['nama_lengkap'] ?? 'Mahasiswa',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$mhs['id'], 5));

        $totalPages = $perPage > 0 ? (int)ceil($total / $perPage) : 1;

        return $this->view('mahasiswa/activity', [
            'mhs'                => $mhs,
            'logs'               => $logs,
            'page'               => $page,
            'total_pages'        => $totalPages,
            'header_activities'  => $headerActivities,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/mahasiswa/dashboard'],
                ['label' => 'Aktivitas'],
            ],
            'suppress_layout_title' => true,
        ], 'mahasiswa');
    }
}
