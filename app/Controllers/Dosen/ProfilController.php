<?php

namespace App\Controllers\Dosen;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Security\Sanitizer;
use App\Core\Helpers\ActivityLogger;
use App\Models\User;
use App\Models\ActivityLog;

class ProfilController extends Controller
{
    public function index()
    {
        $dosen = Auth::checkDosen();
        $userModel = new User();
        $logModel  = new ActivityLog();

        $user = $userModel->findById((int)$dosen['id']);
        $headerActivities = array_map(static function ($row) use ($dosen) {
            return [
                'actor'  => $dosen['nama'] ?? 'Dosen',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$dosen['id'], 5));

        return $this->view('dosen/profile', [
            'dosen'      => $dosen,
            'user'       => $user,
            'csrf'       => Csrf::token(),
            'header_activities' => $headerActivities,
            'suppress_layout_title' => true,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dosen/dashboard'],
                ['label' => 'Profile'],
            ],
        ], 'dosen');
    }

    public function update()
    {
        $dosen = Auth::checkDosen();
        Sanitizer::cleanRequest();
        if (!Csrf::check($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Token tidak valid.';
            return $this->redirect('/dosen/profile');
        }

        $userModel = new User();

        $data = [
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
            'email'        => trim($_POST['email'] ?? ''),
            'username'     => trim($_POST['username'] ?? ''),
            'nidn_nip'     => trim($_POST['nidn_nip'] ?? ''),
        ];
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        $userModel->updateProfile((int)$dosen['id'], $data);

        ActivityLogger::log($dosen['id'] ?? null, 'update_profile', 'users', (int)$dosen['id'], 'Perbarui profil dosen');

        $_SESSION['flash_success'] = 'Profil berhasil diperbarui.';
        return $this->redirect('/dosen/profile');
    }
}
