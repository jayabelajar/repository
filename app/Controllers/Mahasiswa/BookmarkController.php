<?php

namespace App\Controllers\Mahasiswa;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Models\Bookmark;
use App\Models\ActivityLog;

class BookmarkController extends Controller
{
    public function index()
    {
        $mhs = Auth::checkMahasiswa();
        $bookmarkModel = new Bookmark();
        $logModel      = new ActivityLog();

        $bookmarks = $bookmarkModel->getByUserWithDetail((int) $mhs['id'], 40);
        $headerActivities = array_map(static function ($row) use ($mhs) {
            return [
                'actor'  => $mhs['nama'] ?? $mhs['nama_lengkap'] ?? 'Mahasiswa',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$mhs['id'], 5));

        $this->view('mahasiswa/bookmark', [
            'user'      => $mhs,
            'mhs'       => $mhs,
            'bookmarks' => $bookmarks,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/mahasiswa/dashboard'],
                ['label' => 'Bookmarks'],
            ],
            'header_activities' => $headerActivities,
            'suppress_layout_title' => true,
        ], 'mahasiswa');
    }
}
