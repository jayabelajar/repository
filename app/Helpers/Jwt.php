<?php

namespace App\Helpers;

class Jwt
{
    public static function encode(array $payload, string $secret): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $segments = [
            self::base64UrlEncode(json_encode($header)),
            self::base64UrlEncode(json_encode($payload)),
        ];

        $signingInput = implode('.', $segments);
        $signature    = hash_hmac('sha256', $signingInput, $secret, true);
        $segments[]   = self::base64UrlEncode($signature);

        return implode('.', $segments);
    }

    public static function decode(string $token, string $secret): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Invalid token format');
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

        $header = json_decode(self::base64UrlDecode($encodedHeader), true);
        $payload = json_decode(self::base64UrlDecode($encodedPayload), true);
        $signature = self::base64UrlDecode($encodedSignature);

        if (!is_array($header) || !is_array($payload)) {
            throw new \InvalidArgumentException('Invalid token');
        }

        if (($header['alg'] ?? '') !== 'HS256') {
            throw new \InvalidArgumentException('Unsupported algorithm');
        }

        $expected = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $secret, true);
        if (!hash_equals($expected, $signature)) {
            throw new \RuntimeException('Signature verification failed');
        }

        if (isset($payload['nbf']) && (int)$payload['nbf'] > time()) {
            throw new \RuntimeException('Token not yet valid');
        }

        if (isset($payload['exp']) && (int)$payload['exp'] < time()) {
            throw new \RuntimeException('Token expired');
        }

        return $payload;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }
}
