<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Database;

class DashboardController extends Controller
{
    private function recentActivities(int $limit = 10): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT al.description, al.activity_type, al.created_at, u.nama_lengkap
            FROM activity_logs al
            LEFT JOIN users u ON u.id = al.user_id
            ORDER BY al.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return array_map(static function ($row) {
            return [
                'actor'  => $row['nama_lengkap'] ?: 'System',
                'action' => $row['description'] ?: $row['activity_type'],
                'time'   => $row['created_at'],
            ];
        }, $rows ?: []);
    }

    private function repoPerYear(): array
    {
        $db = Database::getConnection();
        $rows = $db->query("
            SELECT tahun AS label, COUNT(*) AS total
            FROM repository
            GROUP BY tahun
            ORDER BY tahun ASC
        ")->fetchAll();

        $labels = [];
        $data   = [];
        foreach ($rows as $row) {
            $labels[] = $row['label'];
            $data[]   = (int) $row['total'];
        }
        return [$labels, $data];
    }

    private function repoByJenis(): array
    {
        $db = Database::getConnection();
        $rows = $db->query("
            SELECT jenis_karya AS label, COUNT(*) AS total
            FROM repository
            GROUP BY jenis_karya
            ORDER BY jenis_karya ASC
        ")->fetchAll();

        $labels = [];
        $data   = [];
        foreach ($rows as $row) {
            $labels[] = ucfirst(str_replace('_', ' ', $row['label']));
            $data[]   = (int) $row['total'];
        }
        return [$labels, $data];
    }

    private function countTable(string $table): int
    {
        $db = Database::getConnection();
        return (int) $db->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
    }

    public function index()
    {
        $admin = Auth::checkAdmin();

        [$chartYearLabels, $chartYearData] = $this->repoPerYear();
        [$chartJenisLabels, $chartJenisData] = $this->repoByJenis();

        // Statistik ringkas
        $stats = [
            'total_repository'   => $this->countTable('repository'),
            'total_prodi'        => $this->countTable('program_studi'),
            'total_mk'           => $this->countTable('mata_kuliah'),
            'total_user'         => $this->countTable('users'),
        ];

        $activities = $this->recentActivities(10);

        return $this->view('admin/dashboard', [
            'admin' => $admin,
            'stats' => $stats,
            'activities' => $activities,
            'header_activities' => $activities,
            'chart_year_labels' => $chartYearLabels,
            'chart_year_data'   => $chartYearData,
            'chart_jenis_labels'=> $chartJenisLabels,
            'chart_jenis_data'  => $chartJenisData,
            'breadcrumb'        => [],
            ], 'admin');
    }
}
