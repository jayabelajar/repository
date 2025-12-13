<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Security\Sanitizer;
use App\Models\User;
use App\Core\Database;
use App\Core\Helpers\ActivityLogger;

class ProfileController extends Controller
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
        $adminDb = $userModel->findById((int) $admin['id']);
        if ($adminDb) {
            $admin = array_merge($adminDb, $admin);
        }

        return $this->view('admin/profile', [
            'admin'      => $admin,
            'csrf'       => Csrf::token(),
            'header_activities' => $this->headerActivities(),
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['label' => 'Profil'],
            ],
        ], 'admin');
    }

    public function update()
    {
        $admin = Auth::checkAdmin();
        Sanitizer::cleanRequest();

        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/profile');
        }

        $userModel = new User();
        $data = [
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
            'email'        => trim($_POST['email'] ?? ''),
            'username'     => trim($_POST['username'] ?? ''),
        ];

        $password = $_POST['password'] ?? '';
        if ($password !== '') {
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $userModel->updateProfile((int)$admin['id'], $data);

        $_SESSION['admin']['nama']  = $data['nama_lengkap'] ?: $admin['nama'];
        $_SESSION['admin']['email'] = $data['email'] ?: $admin['email'];

        ActivityLogger::log($_SESSION['admin']['id'] ?? null, 'update_profile', 'users', (int)$admin['id'], 'Perbarui profil admin');

        $_SESSION['flash_success'] = 'Profil diperbarui.';
        return $this->redirect('/admin/profile');
    }
}
