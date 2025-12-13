<?php
namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Helpers\ActivityLogger;

class AuthAdminController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login sebagai role lain, arahkan sesuai role
        if (isset($_SESSION['mahasiswa'])) {
            return $this->redirect('/mahasiswa/dashboard');
        }
        if (isset($_SESSION['dosen'])) {
            return $this->redirect('/dosen/dashboard');
        }
        if (isset($_SESSION['admin'])) {
            return $this->redirect('/admin/dashboard');
        }

        $this->view('auth/login-admin', [
            'page_title' => 'Login Admin',
        ], 'auth');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/__admin/login');
        }

        if (!Csrf::check($_POST['csrf_token'] ?? null)) {
            $this->redirect('/__admin/login?error=csrf');
        }

        $usernameOrEmail = trim($_POST['email'] ?? '');
        $password        = $_POST['password'] ?? '';

        $result = Auth::attempt($usernameOrEmail, $password, 'admin');
        if (!$result['success']) {
            $reason = $result['reason'] ?? 'invalid';
            if ($reason === 'throttled') {
                $this->redirect('/__admin/login?error=throttled');
            }
            if ($reason === 'banned') {
                $this->redirect('/__admin/login?error=banned');
            }
            if ($reason === 'not_found') {
                $this->redirect('/__admin/login?error=notfound');
            }
            $this->redirect('/__admin/login?error=invalid');
        }

        Auth::loginAdmin($result['user']);

        $lat = trim($_POST['latitude'] ?? '') ?: null;
        $lng = trim($_POST['longitude'] ?? '') ?: null;
        ActivityLogger::log(
            $result['user']['id'] ?? null,
            'login',
            null,
            null,
            'User berhasil login (admin)' . ($lat && $lng ? " [lokasi: {$lat},{$lng}]" : ''),
            $lat,
            $lng
        );

        // Arahkan ke dashboard admin
        $this->redirect('/admin/dashboard');
    }
}
