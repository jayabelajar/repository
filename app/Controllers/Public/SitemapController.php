<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Models\Repository;

class SitemapController extends Controller
{
    public function index(): void
    {
        $config = require __DIR__ . '/../../../config/config.php';
        $base = rtrim($config['base_url'] ?? '', '/');

        $repoModel = new Repository();
        $slugs = $repoModel->getAllSlugs();

        header('Content-Type: application/xml; charset=utf-8');

        $urls = [];

        $staticPaths = [
            '',
            '/tentang',
            '/kontak',
            '/faq',
            '/panduan',
            '/kebijakan',
            '/syarat',
            '/telusuri',
            '/telusuri/year',
            '/telusuri/program-studi',
            '/telusuri/mata-kuliah',
            '/telusuri/jenis-karya',
            '/telusuri/author',
        ];

        foreach ($staticPaths as $path) {
            $urls[] = [
                'loc' => $base . $path,
                'lastmod' => date('c'),
            ];
        }

        foreach ($slugs as $row) {
            $loc = $base . '/repository/' . rawurlencode($row['slug'] ?? '');
            $lastmod = !empty($row['updated_at']) ? date('c', strtotime($row['updated_at'])) : null;
            $urls[] = ['loc' => $loc, 'lastmod' => $lastmod];
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo '  <url>' . "\n";
            echo '    <loc>' . htmlspecialchars($u['loc'], ENT_QUOTES, 'UTF-8') . '</loc>' . "\n";
            if ($u['lastmod']) {
                echo '    <lastmod>' . htmlspecialchars($u['lastmod'], ENT_QUOTES, 'UTF-8') . '</lastmod>' . "\n";
            }
            echo '  </url>' . "\n";
        }
        echo '</urlset>';
        exit;
    }
}
