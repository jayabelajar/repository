<?php

namespace App\Controllers\Api;

use App\Core\Helpers\ActivityLogger;
use App\Models\User;

class ProfileApiController extends BaseApiController
{
    private User $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new User();
    }

    /**
     * Update profil untuk mahasiswa (nama, email, username, password opsional).
     */
    public function updateMahasiswa(): void
    {
        $user = $this->currentUser('mahasiswa');
        if (!$user) {
            $this->fail('Token tidak valid atau sudah kedaluwarsa.', 401);
        }

        $payload = array_merge($this->getJsonInput(), $_POST ?? []);
        $data = $this->validateProfilePayload($payload, false);

        if (!$this->users->updateProfile((int) $user['id'], $data)) {
            $this->fail('Tidak ada perubahan yang disimpan.', 422);
        }

        $updated = $this->users->findById((int) $user['id']);
        ActivityLogger::log((int) $user['id'], 'update_profile', 'users', (int) $user['id'], 'Perbarui profil mahasiswa');

        $this->success(['user' => $this->publicProfile($updated)]);
    }

    /**
     * Update profil untuk dosen (nama, email, username, nidn_nip, password opsional).
     */
    public function updateDosen(): void
    {
        $user = $this->currentUser('dosen');
        if (!$user) {
            $this->fail('Token tidak valid atau sudah kedaluwarsa.', 401);
        }

        $payload = array_merge($this->getJsonInput(), $_POST ?? []);
        $data = $this->validateProfilePayload($payload, true);

        if (!$this->users->updateProfile((int) $user['id'], $data)) {
            $this->fail('Tidak ada perubahan yang disimpan.', 422);
        }

        $updated = $this->users->findById((int) $user['id']);
        ActivityLogger::log((int) $user['id'], 'update_profile', 'users', (int) $user['id'], 'Perbarui profil dosen');

        $this->success(['user' => $this->publicProfile($updated)]);
    }

    private function validateProfilePayload(array $payload, bool $includeNidn): array
    {
        $nama = $this->sanitizeString($payload['nama_lengkap'] ?? '', 150);
        $email = $this->sanitizeString($payload['email'] ?? '', 150);

        if ($nama === '' || $email === '') {
            $this->fail('Nama dan email wajib diisi.', 422);
        }

        $username = $this->sanitizeString($payload['username'] ?? '', 100);
        $password = (string) ($payload['password'] ?? '');

        $data = [
            'nama_lengkap' => $nama,
            'email'        => $email,
            'username'     => $username !== '' ? $username : null,
        ];

        if ($includeNidn) {
            $nidn = $this->sanitizeString($payload['nidn_nip'] ?? ($payload['nidn'] ?? ''), 100);
            if ($nidn !== '') {
                $data['nidn_nip'] = $nidn;
            }
        }

        if ($password !== '') {
            $data['password'] = $password;
        }

        return $data;
    }

    private function publicProfile(?array $user): array
    {
        if (!$user) {
            return [];
        }

        return [
            'id'           => (int) $user['id'],
            'nama_lengkap' => $user['nama_lengkap'] ?? '',
            'email'        => $user['email'] ?? '',
            'username'     => $user['username'] ?? null,
            'role'         => $user['role'] ?? null,
            'nim'          => $user['nim'] ?? null,
            'nidn_nip'     => $user['nidn_nip'] ?? null,
            'created_at'   => $user['created_at'] ?? null,
        ];
    }
}
