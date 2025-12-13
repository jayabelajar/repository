<?php

namespace App\Controllers\Dosen;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function index()
    {
        $dosen = Auth::checkDosen();
        $logModel = new ActivityLog();

        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;
        $offset  = ($page - 1) * $perPage;

        $total = $logModel->countByUser((int)$dosen['id']);
        $logs  = $logModel->getPagedByUser((int)$dosen['id'], $perPage, $offset);

        $headerActivities = array_map(static function ($row) use ($dosen) {
            return [
                'actor'  => $dosen['nama'] ?? 'Dosen',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$dosen['id'], 5));

        $totalPages = $perPage > 0 ? (int)ceil($total / $perPage) : 1;

        return $this->view('dosen/activity', [
            'dosen'             => $dosen,
            'logs'              => $logs,
            'page'              => $page,
            'total_pages'       => $totalPages,
            'header_activities' => $headerActivities,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dosen/dashboard'],
                ['label' => 'Activity'],
            ],
            'suppress_layout_title' => true,
        ], 'dosen');
    }
}
