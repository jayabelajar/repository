<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Security\Sanitizer;
use App\Models\User;
use App\Core\Database;
use App\Core\Helpers\ActivityLogger;

class UserController extends Controller
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
        $userModel = new User();
        $q = trim($_GET['q'] ?? '');
        $role = $_GET['role'] ?? null;
        if (!in_array($role, ['admin','dosen','mahasiswa'], true)) {
            $role = null;
        }
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset  = ($page - 1) * $perPage;

        $items   = $userModel->getAllOrdered($q, $role, $perPage, $offset);
        $total   = $userModel->countAll($q, $role);
        $pages   = (int) ceil($total / $perPage);

        return $this->view('admin/user', [
            'admin'      => $admin,
            'items'      => $items,
            'search'     => $q,
            'filter_role'=> $role,
            'csrf'       => Csrf::token(),
            'header_activities' => $this->headerActivities(),
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['label' => 'Pengguna'],
            ],
            'page'       => $page,
            'pages'      => $pages,
            'per_page'   => $perPage,
            'total'      => $total,
        ], 'admin');
    }

    public function store()
    {
        Auth::checkAdmin();
        Sanitizer::cleanRequest();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/users');
        }

        $data = $this->collectData();
        if ($data['password_hash'] === null) {
            $_SESSION['flash_error'] = 'Password wajib diisi.';
            return $this->redirect('/admin/users');
        }

        $userModel = new User();
        $userModel->create($data);
        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'create_user', 'users', null, 'Menambah pengguna: ' . ($data['email'] ?? ''));
        $_SESSION['flash_success'] = 'Pengguna ditambahkan.';
        return $this->redirect('/admin/users');
    }

    public function update($id)
    {
        Auth::checkAdmin();
        Sanitizer::cleanRequest();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/users');
        }

        $data = $this->collectData(false);
        $userModel = new User();
        $userModel->updateUser((int)$id, $data);
        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'update_user', 'users', (int)$id, 'Memperbarui pengguna: ' . ($data['email'] ?? ''));
        $_SESSION['flash_success'] = 'Pengguna diperbarui.';
        return $this->redirect('/admin/users');
    }

    public function toggleBan($id)
    {
        Auth::checkAdmin();
        Sanitizer::cleanRequest();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/users');
        }

        $userModel = new User();
        $user = $userModel->findById((int) $id);
        if (!$user) {
            $_SESSION['flash_error'] = 'Pengguna tidak ditemukan.';
            return $this->redirect('/admin/users');
        }

        if (!in_array($user['role'], ['admin','dosen'], true)) {
            $_SESSION['flash_error'] = 'Aksi ini hanya untuk akun admin/dosen.';
            return $this->redirect('/admin/users');
        }

        $now = time();
        $isBanned = !empty($user['banned_until']) && strtotime($user['banned_until']) > $now;
        if ($isBanned) {
            $userModel->resetLoginAttempts((int) $id);
            $userModel->setBanUntil((int) $id, null);
            $_SESSION['flash_success'] = 'Pengguna berhasil di-unban.';
            ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'unban_user', 'users', (int)$id, 'Membuka blokir pengguna: ' . ($user['email'] ?? ''));
        } else {
            $banUntil = date('Y-m-d H:i:s', $now + 7 * 24 * 60 * 60);
            $userModel->setBanUntil((int) $id, $banUntil);
            $_SESSION['flash_success'] = 'Pengguna diblokir sampai ' . $banUntil . '.';
            ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'ban_user', 'users', (int)$id, 'Memblokir pengguna: ' . ($user['email'] ?? ''));
        }

        return $this->redirect('/admin/users');
    }

    public function export()
    {
        Auth::checkAdmin();
        $userModel = new User();
        $items = $userModel->getAllOrdered();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['nama_lengkap','email','username','role','nim','nidn_nip','created_at']);
        foreach ($items as $u) {
            fputcsv($out, [
                $u['nama_lengkap'] ?? '',
                $u['email'] ?? '',
                $u['username'] ?? '',
                $u['role'] ?? '',
                $u['nim'] ?? '',
                $u['nidn_nip'] ?? '',
                $u['created_at'] ?? '',
            ]);
        }
        fclose($out);
        exit;
    }

    public function import()
    {
        Auth::checkAdmin();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/users');
        }
        if (empty($_FILES['csv_file']) || ($_FILES['csv_file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'File CSV tidak ditemukan.';
            return $this->redirect('/admin/users');
        }

        $tmp = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($tmp, 'r');
        if (!$handle) {
            $_SESSION['flash_error'] = 'Gagal membuka file CSV.';
            return $this->redirect('/admin/users');
        }

        $userModel = new User();
        $row = 0;
        $imported = 0;
        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            if ($row === 1 && isset($data[0]) && stripos($data[0], 'nama') !== false) {
                continue; // skip header
            }
            [$nama, $email, $username, $role, $nim, $nidn, $password] = array_pad($data, 7, '');
            $role = in_array($role, ['admin','dosen','mahasiswa'], true) ? $role : 'mahasiswa';
            if ($email === '' || $password === '') {
                continue;
            }
            $userModel->create([
                'nama_lengkap'  => trim($nama),
                'email'         => trim($email),
                'username'      => trim($username) ?: null,
                'role'          => $role,
                'nim'           => $role === 'mahasiswa' ? trim($nim) : null,
                'nidn_nip'      => $role === 'dosen' ? trim($nidn) : null,
                'password_hash' => password_hash(trim($password), PASSWORD_BCRYPT),
            ]);
            $imported++;
        }
        fclose($handle);

        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'import_user', 'users', null, "Import pengguna berhasil: {$imported} baris");
        $_SESSION['flash_success'] = "Import selesai. Berhasil: {$imported} baris.";
        return $this->redirect('/admin/users');
    }

    public function delete($id)
    {
        Auth::checkAdmin();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/users');
        }

        $userModel = new User();
        $userModel->deleteById((int)$id);
        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'delete_user', 'users', (int)$id, 'Menghapus pengguna ID ' . $id);
        $_SESSION['flash_success'] = 'Pengguna dihapus.';
        return $this->redirect('/admin/users');
    }

    private function collectData(bool $passwordRequired = true): array
    {
        $role = $_POST['role'] ?? 'mahasiswa';
        if (!in_array($role, ['admin','dosen','mahasiswa'], true)) {
            $role = 'mahasiswa';
        }

        $password = $_POST['password'] ?? '';
        $passwordHash = null;
        if ($password !== '') {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        } elseif ($passwordRequired) {
            $passwordHash = null;
        }

        return [
            'username'      => trim($_POST['username'] ?? '') ?: null,
            'nama_lengkap'  => trim($_POST['nama_lengkap'] ?? ''),
            'email'         => trim($_POST['email'] ?? ''),
            'role'          => $role,
            'nim'           => $role === 'mahasiswa' ? trim($_POST['nim'] ?? '') : null,
            'nidn_nip'      => $role === 'dosen' ? trim($_POST['nidn_nip'] ?? '') : null,
            'password_hash' => $passwordHash,
        ];
    }
}
