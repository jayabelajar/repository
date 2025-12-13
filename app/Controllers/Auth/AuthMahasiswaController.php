<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Security\Sanitizer;
use App\Core\Helpers\ActivityLogger;

class AuthMahasiswaController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login sebagai role lain, arahkan sesuai role
        if (isset($_SESSION['admin'])) {
            return $this->redirect('/admin/dashboard');
        }
        if (isset($_SESSION['dosen'])) {
            return $this->redirect('/dosen/dashboard');
        }
        if (isset($_SESSION['mahasiswa'])) {
            return $this->redirect('/mahasiswa/dashboard');
        }

        return $this->view("auth/login-mahasiswa", [], "auth");
    }

    public function login()
    {
        if (!Csrf::check($_POST['csrf_token'] ?? null)) {
            return $this->redirect('/login?error=csrf');
        }

        // Hindari memodifikasi password dengan sanitizer; cukup trim.
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            return $this->redirect('/login?error=empty');
        }

        $result = Auth::attempt($email, $password, 'mahasiswa');
        if (!$result['success']) {
            $reason = $result['reason'] ?? 'invalid';
            if ($reason === 'throttled') {
                return $this->redirect('/login?error=throttled');
            }
            if ($reason === 'banned') {
                return $this->redirect('/login?error=banned');
            }
            if ($reason === 'not_found') {
                return $this->redirect('/login?error=notfound');
            }
            return $this->redirect('/login?error=invalid');
        }

        Auth::loginMahasiswa($result['user']);

        $lat = trim($_POST['latitude'] ?? '') ?: null;
        $lng = trim($_POST['longitude'] ?? '') ?: null;
        ActivityLogger::log(
            $result['user']['id'] ?? null,
            'login',
            null,
            null,
            'User berhasil login (mahasiswa)' . ($lat && $lng ? " [lokasi: {$lat},{$lng}]" : ''),
            $lat,
            $lng
        );

        return $this->redirect('/mahasiswa/dashboard');
    }

    public function logout()
    {
        // Logout akan mengarahkan sesuai role yang aktif
        if (isset($_SESSION['admin'])) {
            Auth::logoutAdmin();
        } elseif (isset($_SESSION['dosen'])) {
            Auth::logoutDosen();
        } else {
            Auth::logoutMahasiswa();
        }
    }
}
