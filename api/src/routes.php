<?php
namespace App;

use function App\db;
use function App\json;
use function App\create_api_token;
use function App\require_auth;

function route(string $method, string $path) {

    // LOGIN
    if ($path === '/v1/auth/login' && $method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true);
        if (!is_array($body)) {
            json(['message' => 'Payload harus JSON'], 400);
        }

        $email    = trim($body['email'] ?? '');
        $password = $body['password'] ?? '';

        if ($email === '' || $password === '') {
            json(['message' => 'Email dan password wajib diisi'], 422);
        }

        $stmt = db()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            json(['message' => 'Login gagal'], 401);
        }

        if (!empty($user['banned_until']) && strtotime($user['banned_until']) > time()) {
            json(['message' => 'Akun sedang diblokir sementara'], 403);
        }

        $token = create_api_token((int) $user['id']);

        json([
            'access_token' => $token['token'],
            'expires_at'   => $token['expired_at'],
            'user'         => public_user($user)
        ]);
    }

    // PROFILE
    if ($path === '/v1/me' && $method === 'GET') {
        $user = require_auth();

        json(['user' => public_user($user)]);
    }

    json(['message' => 'Endpoint tidak ditemukan'], 404);
}

function public_user(array $user): array {
    return [
        'id'           => (int) $user['id'],
        'username'     => $user['username'],
        'nama_lengkap' => $user['nama_lengkap'],
        'email'        => $user['email'],
        'role'         => $user['role'],
        'nim'          => $user['nim'],
        'nidn_nip'     => $user['nidn_nip'],
        'created_at'   => $user['created_at']
    ];
}
