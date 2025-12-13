<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Security\Sanitizer;
use App\Core\Security\Throttle;
use App\Helpers\Jwt;
use App\Models\ApiToken;
use App\Models\User;

class BaseApiController extends Controller
{
    protected array $config;
    protected int $tokenTtl;
    protected ApiToken $tokenModel;

    public function __construct()
    {
        $this->config      = require __DIR__ . '/../../../config/config.php';
        $this->tokenTtl    = 60 * 60 * 2; // 2 jam
        $this->tokenModel  = new ApiToken();
    }

    public function getApiSecret(): string
    {
        return (string) ($this->config['api_secret'] ?? '');
    }

    protected function respond($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        header('X-Content-Type-Options: nosniff');
        echo json_encode($data);
        exit;
    }

    protected function success($data = [], int $status = 200): void
    {
        $this->respond([
            'success' => true,
            'data'    => $data
        ], $status);
    }

    public function fail(string $message, int $status = 400, array $errors = []): void
    {
        $payload = [
            'success' => false,
            'message' => $message
        ];

        if (!empty($errors)) {
            $payload['errors'] = $errors;
        }

        $this->respond($payload, $status);
    }

    protected function getJsonInput(): array
    {
        $raw  = file_get_contents('php://input');
        $data = json_decode($raw, true);

        return is_array($data) ? $data : [];
    }

    public function getBearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['Authorization'] ?? null;
        if ($header && stripos($header, 'Bearer ') === 0) {
            return trim(substr($header, 7));
        }

        // fallback query param
        if (!empty($_GET['token'])) {
            return trim((string) $_GET['token']);
        }

        return null;
    }

    protected function issueToken(array $user): array
    {
        $this->tokenModel->cleanupExpired();

        $now   = time();
        $jti   = bin2hex(random_bytes(16));
        $payload = [
            'iss'   => $this->config['base_url'] ?? '',
            'sub'   => (int) $user['id'],
            'role'  => $user['role'] ?? null,
            'email' => $user['email'] ?? null,
            'jti'   => $jti,
            'iat'   => $now,
            'nbf'   => $now,
            'exp'   => $now + $this->tokenTtl,
        ];

        $secret = $this->getApiSecret();
        if ($secret === '') {
            $this->fail('Server misconfigured: API secret missing.', 500);
        }

        $token = Jwt::encode($payload, $secret);
        $this->tokenModel->removeByUser((int) $user['id']);
        $this->tokenModel->createToken((int) $user['id'], $token, $this->tokenTtl);

        return [
            'token'      => $token,
            'expires_in' => $this->tokenTtl,
        ];
    }

    protected function validateToken(?string $token): ?array
    {
        if (!$token) {
            return null;
        }

        $secret = $this->getApiSecret();
        if ($secret === '') {
            return null;
        }

        try {
            $payload = Jwt::decode($token, $secret);
        } catch (\Throwable $e) {
            return null;
        }

        $record = $this->tokenModel->findValidToken($token);
        if (!$record) {
            return null;
        }

        $userModel = new User();
        $user = $userModel->findById((int) $record['user_id']);

        return $user ?: null;
    }

    protected function requireUser(): array
    {
        $user = $this->currentUser();
        if (!$user) {
            $this->fail('Token tidak valid atau sudah kedaluwarsa.', 401);
        }

        return $user;
    }

    protected function currentUser(?string $requiredRole = null): ?array
    {
        $user = $_SERVER['api_user'] ?? null;
        if (!$user) {
            $user = $this->validateToken($this->getBearerToken());
        }

        if ($requiredRole && $user && ($user['role'] ?? null) !== $requiredRole) {
            $this->fail('Forbidden.', 403);
        }

        return $user;
    }

    protected function validatePagination(int $page, int $perPage, int $maxPerPage = 50): array
    {
        $page = $page < 1 ? 1 : $page;
        $perPage = $perPage < 1 ? 10 : $perPage;
        $perPage = $perPage > $maxPerPage ? $maxPerPage : $perPage;

        return [$page, $perPage, ($page - 1) * $perPage];
    }

    protected function enforceRateLimit(string $key, int $maxAttempts, int $decaySeconds, int $banSeconds): void
    {
        if (!Throttle::allow($key, $maxAttempts, $banSeconds, $decaySeconds)) {
            $this->fail('Terlalu banyak permintaan. Coba lagi nanti.', 429);
        }

        Throttle::hit($key, $maxAttempts, $banSeconds, $decaySeconds);
    }

    protected function sanitizeString(?string $value, int $maxLength = 255): string
    {
        $value = Sanitizer::clean((string) ($value ?? ''));
        if (strlen($value) > $maxLength) {
            $value = substr($value, 0, $maxLength);
        }
        return $value;
    }
}
