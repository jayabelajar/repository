<?php

namespace App\Core\Security;

class Csrf
{
    public static function generate()
    {
        if (!isset($_SESSION)) session_start();

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    public static function token()
    {
        if (!isset($_SESSION)) session_start();
        return $_SESSION['csrf_token'] ?? self::generate();
    }

    public static function check($token)
    {
        if (!isset($_SESSION)) session_start();

        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }
}
