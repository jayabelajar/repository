<?php

namespace App\Controllers\Mahasiswa;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Models\Bookmark;
use App\Models\Repository;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $mhs = Auth::checkMahasiswa();

        $repoModel = new Repository();
        $bookmarkModel = new Bookmark();
        $logModel = new ActivityLog();

        $myRepos      = $repoModel->getForMahasiswa((int) $mhs['id'], 4);
        $bookmarks    = $bookmarkModel->getByUserWithDetail((int) $mhs['id'], 4);
        $rawLogs      = $logModel->getRecentByUser((int)$mhs['id'], 8);
        $activities   = array_map(static function ($row) {
            return [
                'title' => $row['activity_type'] ?? 'Aktivitas',
                'desc'  => $row['description'] ?? '',
                'time'  => $row['created_at'] ?? '',
            ];
        }, $rawLogs);
        $headerActivities = array_map(static function ($row) use ($mhs) {
            return [
                'actor'  => $mhs['nama'] ?? $mhs['nama_lengkap'] ?? 'Mahasiswa',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $rawLogs);

        $stats = [
            'repos'      => (int) $repoModel->countByMahasiswa((int) $mhs['id']),
            'bookmarks'  => (int) $bookmarkModel->countByUser((int) $mhs['id']),
        ];

        return $this->view("mahasiswa/dashboard", [
            'mhs'       => $mhs,
            'stats'     => $stats,
            'myRepos'   => $myRepos,
            'bookmarks' => $bookmarks,
            'activities'=> $activities,
            'header_activities' => $headerActivities,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/mahasiswa/dashboard'],
            ],
            'suppress_layout_title' => true,
        ], "mahasiswa");
    }
}
