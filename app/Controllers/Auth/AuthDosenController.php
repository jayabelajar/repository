<?php
namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Helpers\ActivityLogger;

class AuthDosenController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login sebagai role lain, arahkan sesuai role
        if (isset($_SESSION['admin'])) {
            return $this->redirect('/admin/dashboard');
        }
        if (isset($_SESSION['mahasiswa'])) {
            return $this->redirect('/mahasiswa/dashboard');
        }
        if (isset($_SESSION['dosen'])) {
            return $this->redirect('/dosen/dashboard');
        }

        $this->view('auth/login-dosen', [
            'page_title' => 'Login Dosen',
        ], 'auth');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/__dosen/login');
        }

        if (!Csrf::check($_POST['csrf_token'] ?? null)) {
            $this->redirect('/__dosen/login?error=csrf');
        }

        $emailOrUsername = trim($_POST['email'] ?? '');
        $password        = $_POST['password'] ?? '';

        $result = Auth::attempt($emailOrUsername, $password, 'dosen');
        if (!$result['success']) {
            $reason = $result['reason'] ?? 'invalid';
            if ($reason === 'throttled') {
                $this->redirect('/__dosen/login?error=throttled');
            }
            if ($reason === 'banned') {
                $this->redirect('/__dosen/login?error=banned');
            }
            if ($reason === 'not_found') {
                $this->redirect('/__dosen/login?error=notfound');
            }
            $this->redirect('/__dosen/login?error=invalid');
        }

        Auth::loginDosen($result['user']);

        $lat = trim($_POST['latitude'] ?? '') ?: null;
        $lng = trim($_POST['longitude'] ?? '') ?: null;
        ActivityLogger::log(
            $result['user']['id'] ?? null,
            'login',
            null,
            null,
            'User berhasil login (dosen)' . ($lat && $lng ? " [lokasi: {$lat},{$lng}]" : ''),
            $lat,
            $lng
        );

        // Arahkan ke dashboard dosen
        $this->redirect('/dosen/dashboard');
    }
}
