<?php

namespace App\Core\Security;

use App\Models\User;

class Auth
{
    // Waktu idle maksimal sebelum logout otomatis (detik)
    private const IDLE_TIMEOUT = 1200; // 20 menit

    private static function start(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    private static function baseUrl(): string
    {
        $config = require __DIR__ . '/../../../config/config.php';
        return rtrim($config['base_url'], '/');
    }

    /* ===========================
       MAHASISWA
    ============================*/
    public static function loginMahasiswa(array $user): void
    {
        self::start();

        session_regenerate_id(true);

        $_SESSION['mahasiswa'] = [
            'id'    => $user['id'],
            'nama'  => $user['nama_lengkap'],
            'email' => $user['email'],
            'nim'   => $user['nim'],
            'last_activity' => time(),
        ];
    }

    public static function checkMahasiswa()
    {
        self::start();

        // Backward compatibility: jika dulu pakai mhs_id/mhs_email, konversi ke struktur baru
        if (!isset($_SESSION['mahasiswa']) && isset($_SESSION['mhs_id'], $_SESSION['mhs_email'])) {
            $_SESSION['mahasiswa'] = [
                'id'    => $_SESSION['mhs_id'],
                'nama'  => $_SESSION['mhs_nama'] ?? '',
                'email' => $_SESSION['mhs_email'],
                'nim'   => $_SESSION['mhs_nim'] ?? null,
            ];
        }

        if (!isset($_SESSION['mahasiswa'])) {
            header('Location: ' . self::baseUrl() . '/login');
            exit;
        }

        self::enforceIdle('mahasiswa', static function () {
            self::logoutMahasiswa();
        });

        return $_SESSION['mahasiswa'];
    }

    public static function logoutMahasiswa(): void
    {
        self::start();
        unset($_SESSION['mahasiswa']);
        session_destroy();
        header('Location: ' . self::baseUrl() . '/login');
        exit;
    }

    /* ===========================
       DOSEN
    ============================*/
    public static function loginDosen(array $user): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['dosen'] = [
            'id'    => $user['id'],
            'nama'  => $user['nama_lengkap'],
            'email' => $user['email'],
            'nidn'  => $user['nidn_nip'] ?? null,
            'last_activity' => time(),
        ];
    }

    public static function checkDosen()
    {
        self::start();

        if (!isset($_SESSION['dosen'])) {
            header('Location: ' . self::baseUrl() . '/__dosen/login');
            exit;
        }

        self::enforceIdle('dosen', static function () {
            self::logoutDosen();
        });

        return $_SESSION['dosen'];
    }

    public static function logoutDosen(): void
    {
        self::start();
        unset($_SESSION['dosen']);
        session_destroy();
        header('Location: ' . self::baseUrl() . '/__dosen/login');
        exit;
    }

    /* ===========================
       ADMIN
    ============================*/
    public static function loginAdmin(array $user): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['admin'] = [
            'id'    => $user['id'],
            'nama'  => $user['nama_lengkap'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'last_activity' => time(),
        ];
    }

    public static function checkAdmin()
    {
        self::start();

        if (!isset($_SESSION['admin'])) {
            header('Location: ' . self::baseUrl() . '/__admin/login');
            exit;
        }

        self::enforceIdle('admin', static function () {
            self::logoutAdmin();
        });

        return $_SESSION['admin'];
    }

    public static function logoutAdmin(): void
    {
        self::start();
        unset($_SESSION['admin']);
        session_destroy();
        header('Location: ' . self::baseUrl() . '/__admin/login');
        exit;
    }

    /* ===========================
       GENERIC ATTEMPT (role)
    ============================*/
    public static function attempt(string $identifier, string $password, string $role): array
    {
        self::start();

        $identifier = trim($identifier);
        if ($identifier === '' || $password === '') {
            return ['success' => false, 'reason' => 'empty'];
        }

        $userModel = new User();
        $user      = null;
        $isAdmin   = ($role === 'admin');
        $throttleKey = 'login_' . $role . '_' . hash('sha256', ($identifier) . '|' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        $maxAttempts = $isAdmin ? 5 : 7;
        $banSeconds  = $isAdmin ? 15 * 60 : 10 * 60;

        if (!Throttle::allow($throttleKey, $maxAttempts, $banSeconds)) {
            return ['success' => false, 'reason' => 'throttled'];
        }

        switch ($role) {
            case 'mahasiswa':
                $user = $userModel->findMahasiswaByEmailOrUsername($identifier);
                break;
            case 'dosen':
                $user = $userModel->findDosen($identifier);
                break;
            case 'admin':
                $user = $userModel->findAdmin($identifier);
                break;
            default:
                return ['success' => false, 'reason' => 'invalid_role'];
        }

        if (!$user) {
            return ['success' => false, 'reason' => 'not_found'];
        }

        if ($isAdmin && $userModel->isBanned($user)) {
            return [
                'success'       => false,
                'reason'        => 'banned',
                'banned_until'  => $user['banned_until'],
            ];
        }

        $storedHash    = (string) ($user['password_hash'] ?? '');
        $hashInfo      = password_get_info($storedHash);
        $validPassword = false;
        $needsRehash   = false;

        if ($hashInfo['algo'] !== 0) {
            $validPassword = password_verify($password, $storedHash);
            $needsRehash   = $validPassword && password_needs_rehash($storedHash, PASSWORD_DEFAULT);
        } elseif (hash_equals($storedHash, $password)) {
            // Migration path: immediately re-hash plain password
            $validPassword = true;
            $needsRehash   = true;
        }

        if (!$validPassword) {
            if ($isAdmin) {
                $userModel->incrementFailedAttempts((int) $user['id']);
            }
            Throttle::hit($throttleKey, $maxAttempts, $banSeconds);
            return ['success' => false, 'reason' => 'invalid_password'];
        }

        if ($isAdmin) {
            $userModel->resetLoginAttempts((int) $user['id']);
        }

        if ($needsRehash) {
            $userModel->updatePasswordHash((int) $user['id'], password_hash($password, PASSWORD_DEFAULT));
        }

        Throttle::reset($throttleKey);

        return [
            'success' => true,
            'user'    => $user,
        ];
    }

    private static function enforceIdle(string $sessionKey, callable $onTimeout): void
    {
        $now = time();
        $last = $_SESSION[$sessionKey]['last_activity'] ?? $now;

        if (($now - $last) > self::IDLE_TIMEOUT) {
            $onTimeout();
        }

        $_SESSION[$sessionKey]['last_activity'] = $now;
    }
}
