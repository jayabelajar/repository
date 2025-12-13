<?php

namespace App\Middleware;

use App\Controllers\Api\BaseApiController;

class RoleMiddleware
{
    public static function require(string $role): void
    {
        $controller = new BaseApiController();
        $user = $_SERVER['api_user'] ?? null;

        if (!$user || ($user['role'] ?? null) !== $role) {
            $controller->fail('Forbidden.', 403);
        }
    }
}
