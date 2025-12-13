<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Security\Sanitizer;
use App\Models\ProgramStudi;
use App\Core\Database;
use App\Core\Helpers\ActivityLogger;

class ProgramStudiController extends Controller
{
    private ProgramStudi $model;
    private int $perPage = 6;

    public function __construct()
    {
        $this->model = new ProgramStudi();
    }

    private function headerActivities(): array
    {
        $db = Database::getConnection();
        $rows = $db->query("
            SELECT al.description, al.activity_type, al.created_at, u.nama_lengkap
            FROM activity_logs al
            LEFT JOIN users u ON u.id = al.user_id
            ORDER BY al.created_at DESC
            LIMIT 8
        ")->fetchAll() ?: [];

        return array_map(static function ($row) {
            return [
                'actor'  => $row['nama_lengkap'] ?: 'System',
                'action' => $row['description'] ?: $row['activity_type'],
                'time'   => $row['created_at'],
            ];
        }, $rows);
    }

    private function log(string $type, string $description, ?int $refId = null): void
    {
        $db = Database::getConnection();
        $userId = $_SESSION['admin']['id'] ?? null;
        $stmt = $db->prepare("
            INSERT INTO activity_logs (user_id, activity_type, reference_table, reference_id, description, ip_address, user_agent)
            VALUES (:uid, :type, :ref_table, :ref_id, :desc, :ip, :ua)
        ");
        $stmt->execute([
            'uid'       => $userId,
            'type'      => $type,
            'ref_table' => 'program_studi',
            'ref_id'    => $refId,
            'desc'      => $description,
            'ip'        => $_SERVER['REMOTE_ADDR'] ?? null,
            'ua'        => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
    }

    public function index()
    {
        $admin = Auth::checkAdmin();

        $page   = max(1, (int)($_GET['page'] ?? 1));
        $search = trim($_GET['q'] ?? '');
        $sort   = strtolower($_GET['sort'] ?? 'asc');
        if (!in_array($sort, ['asc','desc'], true)) $sort = 'asc';

        $total = $this->model->countAll($search);
        $offset = ($page - 1) * $this->perPage;
        $items = $this->model->getPaginated($this->perPage, $offset, $search, $sort);

        $pages = (int)ceil($total / $this->perPage);

        return $this->view('admin/program_studi', [
            'admin'      => $admin,
            'items'      => $items,
            'total'      => $total,
            'page'       => $page,
            'pages'      => $pages,
            'search'     => $search,
            'perPage'    => $this->perPage,
            'sort'       => $sort,
            'csrf'       => Csrf::token(),
            'header_activities' => $this->headerActivities(),
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['label' => 'Program Studi'],
            ],
        ], 'admin');
    }

    public function store()
    {
        Auth::checkAdmin();
        Sanitizer::cleanRequest();

        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/program-studi');
        }

        $data = [
            'nama_program_studi' => $_POST['nama_program_studi'] ?? '',
        ];

        if ($data['nama_program_studi'] === '') {
            $_SESSION['flash_error'] = 'Nama prodi wajib diisi.';
            return $this->redirect('/admin/program-studi');
        }

        $this->model->create($data);
        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'create_prodi', 'program_studi', null, 'Menambah prodi: ' . ($data['nama_program_studi'] ?? ''));
        $_SESSION['flash_success'] = 'Program studi ditambahkan.';
        return $this->redirect('/admin/program-studi');
    }

    public function update($id)
    {
        Auth::checkAdmin();
        Sanitizer::cleanRequest();

        $id = (int)$id;
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/program-studi');
        }

        $data = [
            'nama_program_studi' => $_POST['nama_program_studi'] ?? '',
        ];

        if ($data['nama_program_studi'] === '') {
            $_SESSION['flash_error'] = 'Nama prodi wajib diisi.';
            return $this->redirect('/admin/program-studi');
        }

        $this->model->updateById($id, $data);
        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'update_prodi', 'program_studi', $id, 'Memperbarui prodi: ' . ($data['nama_program_studi'] ?? ''));
        $_SESSION['flash_success'] = 'Program studi diperbarui.';
        return $this->redirect('/admin/program-studi');
    }

    public function delete($id)
    {
        Auth::checkAdmin();

        $id = (int)$id;
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/program-studi');
        }

        $this->model->deleteById($id);
        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'delete_prodi', 'program_studi', $id, 'Menghapus prodi ID ' . $id);
        $_SESSION['flash_success'] = 'Program studi dihapus.';
        return $this->redirect('/admin/program-studi');
    }
}
