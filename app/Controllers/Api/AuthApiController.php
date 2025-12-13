<?php

namespace App\Controllers\Api;

use App\Core\Security\Auth;
use App\Core\Security\Sanitizer;
use App\Models\ApiToken;

class AuthApiController extends BaseApiController
{
    public function login(): void
    {
        $this->performLogin();
    }

    public function loginMahasiswa(): void
    {
        $this->performLogin('mahasiswa');
    }

    public function loginDosen(): void
    {
        $this->performLogin('dosen');
    }

    private function performLogin(?string $forcedRole = null): void
    {
        Sanitizer::cleanRequest();
        $input = array_merge($this->getJsonInput(), $_POST ?? []);

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $this->enforceRateLimit('api_login_' . $ip, 10, 60, 300);

        $email    = $this->sanitizeString($input['email'] ?? '', 150);
        $password = trim((string) ($input['password'] ?? ''));
        $role     = $forcedRole ?? ($input['role'] ?? '');

        if (!in_array($role, ['mahasiswa', 'dosen'], true)) {
            $this->fail('Role tidak valid. Gunakan mahasiswa atau dosen.', 400);
        }

        if ($email === '' || $password === '') {
            $this->fail('Email dan password wajib diisi.', 422);
        }

        $result = Auth::attempt($email, $password, $role);
        if (!$result['success']) {
            $reason = $result['reason'] ?? 'invalid';
            if ($reason === 'throttled') {
                $this->fail('Terlalu banyak percobaan login. Coba lagi beberapa menit lagi.', 429);
            }
            if ($reason === 'not_found') {
                $this->fail('Pengguna tidak ditemukan.', 404);
            }
            $this->fail('Kredensial tidak valid.', 401);
        }

        $user   = $result['user'];
        $issued = $this->issueToken($user);

        $this->success([
            'access_token' => $issued['token'],
            'token_type'   => 'Bearer',
            'expires_in'   => $issued['expires_in'],
            'user'         => [
                'id'   => $user['id'],
                'name' => $user['nama_lengkap'],
                'role' => $user['role'],
            ],
        ]);
    }

    public function me(): void
    {
        $user = $this->requireUser();

        $this->success([
            'user' => [
                'id'    => $user['id'],
                'name'  => $user['nama_lengkap'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ]
        ]);
    }

    public function logout(): void
    {
        $token = $this->getBearerToken();
        if ($token) {
            $model = new ApiToken();
            $model->revokeToken($token);
        }

        $this->success(['message' => 'Logged out'], 200);
    }
}
