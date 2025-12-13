<?php
namespace App\Core\Security;

class Throttle
{
    private static function getFilePath(): string
    {
        return __DIR__ . '/../../../storage/logs/login_admin_throttle.json';
    }

    private static function load(): array
    {
        $file = self::getFilePath();
        if (!file_exists($file)) {
            return [];
        }

        $json = file_get_contents($file);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    private static function save(array $data): void
    {
        $file = self::getFilePath();
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        file_put_contents($file, json_encode($data));
    }

    /**
     * @param string $key contoh: admin_login_{username}
     * @param int $maxAttempts
     * @param int $banSeconds
     * @return bool true kalau MASIH dibolehkan login
     */
    public static function allow(string $key, int $maxAttempts, int $banSeconds, ?int $decaySeconds = null): bool
    {
        $data = self::load();
        $now = time();

        // Reset percobaan jika sudah melewati window yang ditentukan
        if ($decaySeconds !== null && isset($data[$key]['last_attempt'])) {
            if (($data[$key]['last_attempt'] + $decaySeconds) < $now) {
                unset($data[$key]);
                self::save($data);
            }
        }

        if (isset($data[$key]['banned_until']) && $data[$key]['banned_until'] > $now) {
            return false;
        }

        if (isset($data[$key]['banned_until']) && $data[$key]['banned_until'] <= $now) {
            unset($data[$key]);
            self::save($data);
        }

        return true;
    }

    public static function hit(string $key, int $maxAttempts, int $banSeconds, ?int $decaySeconds = null): void
    {
        $data = self::load();
        $now = time();

        if ($decaySeconds !== null && isset($data[$key]['last_attempt'])) {
            if (($data[$key]['last_attempt'] + $decaySeconds) < $now) {
                unset($data[$key]);
                $data = self::load(); // reload clean state
            }
        }

        if (!isset($data[$key])) {
            $data[$key] = [
                'attempts'     => 1,
                'last_attempt' => $now,
            ];
        } else {
            $data[$key]['attempts']++;
            $data[$key]['last_attempt'] = $now;
        }

        if ($data[$key]['attempts'] >= $maxAttempts) {
            $data[$key]['banned_until'] = $now + $banSeconds;
        }

        self::save($data);
    }

    public static function reset(string $key): void
    {
        $data = self::load();
        if (isset($data[$key])) {
            unset($data[$key]);
            self::save($data);
        }
    }
}
