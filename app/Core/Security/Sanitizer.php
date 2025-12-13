<?php

namespace App\Core\Security;

class Sanitizer
{
    public static function clean(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    public static function email(string $value): string
    {
        return filter_var(trim($value), FILTER_SANITIZE_EMAIL);
    }

    /**
     * Bersihkan nilai dasar di $_POST dan $_GET (shallow).
     * Tidak memodifikasi struktur dalam (array bertingkat) karena input form sederhana.
     */
    public static function cleanRequest(): void
    {
        foreach ($_POST as $k => $v) {
            if (is_string($v)) {
                $_POST[$k] = self::clean($v);
            }
        }

        foreach ($_GET as $k => $v) {
            if (is_string($v)) {
                $_GET[$k] = self::clean($v);
            }
        }
    }
}
