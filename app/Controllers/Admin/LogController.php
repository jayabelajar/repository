<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Helpers\ActivityLogger;

class LogController extends Controller
{
    public function index()
    {
        $admin = Auth::checkAdmin();
        $db = Database::getConnection();

        $page     = max(1, (int)($_GET['page'] ?? 1));
        $perPage  = 50;
        $offset   = ($page - 1) * $perPage;

        $total = (int) $db->query("SELECT COUNT(*) FROM activity_logs")->fetchColumn();

        $stmt = $db->prepare("
            SELECT al.*, u.nama_lengkap
            FROM activity_logs al
            LEFT JOIN users u ON u.id = al.user_id
            ORDER BY al.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $logs = $stmt->fetchAll() ?: [];

        $headerActivities = array_map(static function ($row) {
            return [
                'actor'  => $row['nama_lengkap'] ?: 'System',
                'action' => $row['description'] ?: $row['activity_type'],
                'time'   => $row['created_at'],
            ];
        }, array_slice($logs, 0, 10));

        $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 1;
        $showPagination = $total > $perPage && $totalPages > 1;

        return $this->view('admin/logs', [
            'admin'             => $admin,
            'logs'              => $logs,
            'page'              => $page,
            'per_page'          => $perPage,
            'total'             => $total,
            'total_pages'       => $totalPages,
            'show_pagination'   => $showPagination,
            'csrf_token'        => Csrf::token(),
            'header_activities' => $headerActivities,
            'breadcrumb'        => [
                ['label' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['label' => 'Log Aktivitas'],
            ],
        ], 'admin');
    }

    public function resetOld()
    {
        $admin = Auth::checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/admin/logs');
        }
        if (!Csrf::check($_POST['csrf_token'] ?? null)) {
            return $this->redirect('/admin/logs?reset=csrf');
        }

        $db = Database::getConnection();
        $cutoff = date('Y-m-d H:i:s', strtotime('-3 months'));

        $stmt = $db->prepare("DELETE FROM activity_logs WHERE created_at < :cutoff");
        $stmt->execute(['cutoff' => $cutoff]);
        $deleted = $stmt->rowCount();

        ActivityLogger::log($admin['id'] ?? null, 'delete_logs', 'activity_logs', null, "Hapus log lebih dari 3 bulan, total: {$deleted}");

        return $this->redirect('/admin/logs?reset=ok&deleted=' . (int)$deleted);
    }
}
