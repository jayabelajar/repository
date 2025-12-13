<?php

if (!function_exists('seo_defaults')) {
    /**
     * Ambil SEO default dari config lalu gabungkan dengan override.
     */
    function seo_defaults(array $override = []): array
    {
        $configSeo = [];
        $configFile = __DIR__ . '/../config/config.php';
        if (file_exists($configFile)) {
            $cfg = require $configFile;
            $configSeo = $cfg['seo'] ?? [];
        }
        return array_merge([
            'title' => $configSeo['title'] ?? '',
            'description' => $configSeo['description'] ?? '',
            'keywords' => $configSeo['keywords'] ?? '',
        ], $override);
    }
}
