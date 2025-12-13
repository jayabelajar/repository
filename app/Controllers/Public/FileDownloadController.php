<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Models\Repository;

class FileDownloadController extends Controller
{
    public function repository(string $slug)
    {
        $repo = (new Repository())->getBySlug($slug);
        if (!$repo || empty($repo['file_pdf'])) {
            http_response_code(404);
            exit('File tidak ditemukan.');
        }

        $baseDir = realpath(__DIR__ . '/../../../storage/uploads');
        if ($baseDir === false) {
            http_response_code(404);
            exit('File tidak ditemukan.');
        }

        $path = realpath($baseDir . '/' . $repo['file_pdf']);
        if (!$path || strpos($path, $baseDir) !== 0 || !is_file($path)) {
            http_response_code(404);
            exit('File tidak ditemukan.');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }
}
