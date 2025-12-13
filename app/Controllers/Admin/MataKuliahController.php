<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Security\Sanitizer;
use App\Models\MataKuliah;
use App\Core\Database;
use App\Core\Helpers\ActivityLogger;

class MataKuliahController extends Controller
{
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

    public function index()
    {
        $admin = Auth::checkAdmin();
        $mkModel = new MataKuliah();
        $q = trim($_GET['q'] ?? '');
        $sort = strtolower($_GET['sort'] ?? 'asc');
        if (!in_array($sort, ['asc','desc'], true)) $sort = 'asc';
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 6;
        $offset  = ($page - 1) * $perPage;
        $items   = $mkModel->getAll($q, $sort, $perPage, $offset);
        $total   = $mkModel->countAll($q);
        $pages   = (int) ceil($total / $perPage);

        return $this->view('admin/mata_kuliah', [
            'admin'      => $admin,
            'items'      => $items,
            'search'     => $q,
            'sort'       => $sort,
            'page'       => $page,
            'pages'      => $pages,
            'csrf'       => Csrf::token(),
            'header_activities' => $this->headerActivities(),
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['label' => 'Mata Kuliah'],
            ],
        ], 'admin');
    }

    public function store()
    {
        Auth::checkAdmin();
        Sanitizer::cleanRequest();

        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/mata-kuliah');
        }

        $nama = trim($_POST['nama'] ?? '');
        if ($nama === '') {
            $_SESSION['flash_error'] = 'Nama mata kuliah wajib diisi.';
            return $this->redirect('/admin/mata-kuliah');
        }

        $mkModel = new MataKuliah();
        $mkModel->create($nama);
        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'create_mk', 'mata_kuliah', null, 'Menambah mata kuliah: ' . $nama);
        $_SESSION['flash_success'] = 'Mata kuliah ditambahkan.';
        return $this->redirect('/admin/mata-kuliah');
    }

    public function update($id)
    {
        Auth::checkAdmin();
        Sanitizer::cleanRequest();

        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/mata-kuliah');
        }

        $nama = trim($_POST['nama'] ?? '');
        if ($nama === '') {
            $_SESSION['flash_error'] = 'Nama mata kuliah wajib diisi.';
            return $this->redirect('/admin/mata-kuliah');
        }

        $mkModel = new MataKuliah();
        $mkModel->updateById((int)$id, $nama);
        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'update_mk', 'mata_kuliah', (int)$id, 'Memperbarui mata kuliah: ' . $nama);
        $_SESSION['flash_success'] = 'Mata kuliah diperbarui.';
        return $this->redirect('/admin/mata-kuliah');
    }

    public function delete($id)
    {
        Auth::checkAdmin();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/mata-kuliah');
        }

        $mkModel = new MataKuliah();
        $mkModel->deleteById((int)$id);
        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'delete_mk', 'mata_kuliah', (int)$id, 'Menghapus mata kuliah ID ' . $id);
        $_SESSION['flash_success'] = 'Mata kuliah dihapus.';
        return $this->redirect('/admin/mata-kuliah');
    }
}
