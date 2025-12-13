<?php

namespace App\Controllers\Dosen;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Models\Bookmark;
use App\Models\ActivityLog;

class BookmarkController extends Controller
{
    public function index()
    {
        $dosen = Auth::checkDosen();
        $bookmarkModel = new Bookmark();
        $logModel = new ActivityLog();

        $bookmarks = $bookmarkModel->getByUserWithDetail((int) $dosen['id'], 50);
        $headerActivities = array_map(static function ($row) use ($dosen) {
            return [
                'actor'  => $dosen['nama'] ?? 'Dosen',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$dosen['id'], 5));

        return $this->view('dosen/bookmark', [
            'dosen'      => $dosen,
            'bookmarks'  => $bookmarks,
            'header_activities' => $headerActivities,
            'suppress_layout_title' => true,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dosen/dashboard'],
                ['label' => 'Bookmarks'],
            ],
        ], 'dosen');
    }
}
