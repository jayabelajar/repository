<?php

namespace App\Middleware;

use App\Controllers\Api\BaseApiController;
use App\Helpers\Jwt;
use App\Models\ApiToken;
use App\Models\User;

class ApiAuthMiddleware
{
    public static function handle(): ?array
    {
        $controller = new BaseApiController();
        $token      = $controller->getBearerToken();

        if (!$token) {
            $controller->fail('Authorization header missing.', 401);
        }

        try {
            $payload = Jwt::decode($token, $controller->getApiSecret());
        } catch (\Throwable $e) {
            $controller->fail('Invalid token.', 401);
        }

        // Optional: pastikan token belum dicabut
        $tokenModel = new ApiToken();
        $record     = $tokenModel->findValidToken($token);
        if (!$record) {
            $controller->fail('Token expired or revoked.', 401);
        }

        $userModel = new User();
        $user      = $userModel->findById((int) ($payload['sub'] ?? 0));
        if (!$user) {
            $controller->fail('User not found.', 401);
        }

        $user['token_jti'] = $payload['jti'] ?? null;
        $_SERVER['api_user'] = $user;

        return $user;
    }
}
