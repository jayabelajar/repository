<?php

namespace App\Controllers\Dosen;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Models\Bookmark;
use App\Models\Repository;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen = Auth::checkDosen();

        $repoModel = new Repository();
        $bookmarkModel = new Bookmark();
        $logModel = new ActivityLog();

        $repos = $repoModel->getForUserDetailed((int) $dosen['id'], 6);
        $bookmarks = $bookmarkModel->getByUserWithDetail((int) $dosen['id'], 6);
        $rawLogs = $logModel->getRecentByUser((int)$dosen['id'], 10);
        $activities = array_map(static function ($row) {
            return [
                'title' => $row['activity_type'] ?? 'Aktivitas',
                'desc'  => $row['description'] ?? '',
                'time'  => $row['created_at'] ?? '',
            ];
        }, $rawLogs);
        $headerActivities = array_map(static function ($row) use ($dosen) {
            return [
                'actor'  => $dosen['nama'] ?? 'Dosen',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $rawLogs);

        $stats = [
            'repo_saya' => $repoModel->countByUser((int) $dosen['id']),
            'bookmark'  => $bookmarkModel->countByUser((int) $dosen['id']),
            'aktivitas' => $logModel->countByUser((int)$dosen['id']),
            'total_bimbingan' => $repoModel->countBimbinganByAdvisor((int)$dosen['id']),
        ];

        return $this->view('dosen/dashboard', [
            'dosen' => $dosen,
            'page_title' => 'Dashboard Dosen',
            'stats' => $stats,
            'repos' => $repos,
            'bookmarks' => $bookmarks,
            'activities' => $activities,
            'header_activities' => $headerActivities,
        ], 'dosen');
    }
}
