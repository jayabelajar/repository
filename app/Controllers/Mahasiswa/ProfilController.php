<?php

namespace App\Controllers\Mahasiswa;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Models\ActivityLog;
use App\Models\User;

class ProfilController extends Controller
{
    public function index()
    {
        $mhs = Auth::checkMahasiswa();
        $userModel = new User();
        $logModel  = new ActivityLog();

        $detail = $userModel->findById((int) $mhs['id']);
        $headerActivities = array_map(static function ($row) use ($mhs) {
            return [
                'actor'  => $mhs['nama'] ?? $mhs['nama_lengkap'] ?? 'Mahasiswa',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$mhs['id'], 5));

        $this->view('mahasiswa/profil', [
            'user'    => $detail,
            'mhs'     => $mhs,
            'message' => $_GET['updated'] ?? null,
            'error'   => $_GET['error'] ?? null,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/mahasiswa/dashboard'],
                ['label' => 'Profil'],
            ],
            'header_activities' => $headerActivities,
            'suppress_layout_title' => true,
        ], 'mahasiswa');
    }

    public function update()
    {
        $mhs = Auth::checkMahasiswa();
        $userModel = new User();

        $nama  = trim($_POST['nama_lengkap'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nama === '' || $email === '') {
            $this->redirect('mahasiswa/profil?error=Lengkapi+nama+dan+email');
        }

        $data = [
            'nama_lengkap' => $nama,
            'email'        => $email,
            'username'     => $username !== '' ? $username : null,
        ];

        if ($password !== '') {
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $userModel->updateProfile((int) $mhs['id'], $data);

        // refresh session data
        $_SESSION['mahasiswa']['nama']  = $nama;
        $_SESSION['mahasiswa']['email'] = $email;

        $this->redirect('mahasiswa/profil?updated=1');
    }
}
