<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Helpers\ActivityLogger;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Security\Sanitizer;
use App\Models\User;

class RegisterController extends Controller
{
    private const ACCESS_CODE_HASH = '$2y$10$8e0kfx9ko0avNSYm1lMinOk05uet.l6jEZnL7eHbKdg2tLdzlnhDy'; // hash dari "JAYANUX2025"

    public function index()
    {
        if (isset($_SESSION['admin'])) {
            return $this->redirect('/admin/dashboard');
        }
        if (isset($_SESSION['dosen'])) {
            return $this->redirect('/dosen/dashboard');
        }
        if (isset($_SESSION['mahasiswa'])) {
            return $this->redirect('/mahasiswa/dashboard');
        }

        return $this->view('auth/register', [
            'csrf' => Csrf::token(),
            'page_title' => 'Daftar Akun Mahasiswa',
        ], 'auth');
    }

    public function submit()
    {
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            return $this->redirect('/daftar?error=csrf');
        }

        // Jangan sanitize password agar tidak mengubah karakter.
        $nama = trim($_POST['nama_lengkap'] ?? '');
        $email = Sanitizer::email($_POST['email'] ?? '');
        $nim = trim($_POST['nim'] ?? '');
        $nidn = trim($_POST['nidn_nip'] ?? '');
        $roleRequested = $_POST['role'] ?? 'mahasiswa';
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm = trim($_POST['password_confirmation'] ?? '');
        $accessCode = trim($_POST['access_code'] ?? '');

        $role = in_array($roleRequested, ['mahasiswa','dosen','admin'], true) ? $roleRequested : 'mahasiswa';

        if ($nama === '' || $email === '' || $password === '') {
            return $this->redirect('/daftar?error=empty');
        }

        if ($role === 'mahasiswa' && $nim === '') {
            return $this->redirect('/daftar?error=empty');
        }
        if ($role === 'dosen' && $nidn === '') {
            return $this->redirect('/daftar?error=nidn');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->redirect('/daftar?error=email');
        }

        if ($password !== $confirm) {
            return $this->redirect('/daftar?error=nomatch');
        }

        if (strlen($password) < 8) {
            return $this->redirect('/daftar?error=weak');
        }

        if ($accessCode === '' || !password_verify($accessCode, self::ACCESS_CODE_HASH)) {
            return $this->redirect('/daftar?error=code');
        }

        $userModel = new User();

        // Cek duplikasi email/username/nim untuk mahasiswa.
        if ($userModel->findByEmail($email) !== null) {
            return $this->redirect('/daftar?error=email_used');
        }

        if ($role === 'mahasiswa' && $userModel->findMahasiswaByEmailOrUsername($nim)) {
            return $this->redirect('/daftar?error=nim_used');
        }

        if ($username !== '') {
            if (
                ($role === 'mahasiswa' && $userModel->findMahasiswaByEmailOrUsername($username)) ||
                ($role === 'dosen' && $userModel->findDosen($username)) ||
                ($role === 'admin' && $userModel->findAdmin($username))
            ) {
                return $this->redirect('/daftar?error=username_used');
            }
        }

        if ($role === 'dosen' && $userModel->findDosen($nidn)) {
            return $this->redirect('/daftar?error=nidn_used');
        }

        $userModel->create([
            'username'      => $username !== '' ? $username : null,
            'nama_lengkap'  => $nama,
            'email'         => $email,
            'role'          => $role,
            'nim'           => $role === 'mahasiswa' ? $nim : null,
            'nidn_nip'      => $role === 'dosen' ? $nidn : null,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
        ]);

        $created = null;
        if ($role === 'mahasiswa') {
            $created = $userModel->findMahasiswaByEmailOrUsername($email);
        } elseif ($role === 'dosen') {
            $created = $userModel->findDosen($email);
        } elseif ($role === 'admin') {
            $created = $userModel->findAdmin($email);
        }

        if ($created) {
            if ($role === 'mahasiswa') {
                Auth::loginMahasiswa($created);
            } elseif ($role === 'dosen') {
                Auth::loginDosen($created);
            } else {
                Auth::loginAdmin($created);
            }
            ActivityLogger::log(
                $created['id'] ?? null,
                'register',
                'users',
                (int) ($created['id'] ?? 0),
                'Registrasi akun ' . $role . ' berhasil'
            );
            if ($role === 'mahasiswa') {
                return $this->redirect('/mahasiswa/dashboard');
            }
            if ($role === 'dosen') {
                return $this->redirect('/dosen/dashboard');
            }
            return $this->redirect('/admin/dashboard');
        }

        return $this->redirect('/login');
    }
}
