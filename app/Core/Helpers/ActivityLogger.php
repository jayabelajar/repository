<?php

namespace App\Core\Helpers;

use App\Core\Database;

class ActivityLogger
{
    public static function log(
        ?int $userId,
        string $type,
        ?string $refTable,
        ?int $refId,
        string $description,
        ?string $latitude = null,
        ?string $longitude = null
    ): void {
        $db = Database::getConnection();

        // Normalize lat/long to avoid oversized strings
        $lat = $latitude !== null ? substr((string)$latitude, 0, 50) : null;
        $lng = $longitude !== null ? substr((string)$longitude, 0, 50) : null;

        $stmt = $db->prepare("
            INSERT INTO activity_logs (
                user_id, activity_type, reference_table, reference_id, description, ip_address, latitude, longitude, user_agent
            ) VALUES (
                :uid, :type, :ref_table, :ref_id, :desc, :ip, :lat, :lng, :ua
            )
        ");
        $stmt->execute([
            'uid'       => $userId,
            'type'      => $type,
            'ref_table' => $refTable,
            'ref_id'    => $refId,
            'desc'      => $description,
            'ip'        => $_SERVER['REMOTE_ADDR'] ?? null,
            'lat'       => $lat,
            'lng'       => $lng,
            'ua'        => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
    }
}
