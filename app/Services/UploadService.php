<?php

namespace App\Services;

class UploadService
{
    private string $dir;
    private int $maxSize;

    public function __construct(string $subdir = 'uploads', int $maxSizeBytes = 10485760)
    {
        $base = realpath(__DIR__ . '/../../storage') ?: __DIR__ . '/../../storage';
        $this->dir = rtrim($base, '/\\') . '/' . trim($subdir, '/');
        $this->maxSize = $maxSizeBytes;

        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0750, true);
        }
    }

    public function storePdf(array $file): string
    {
        $allowedMime = ['application/pdf', 'application/x-pdf'];

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload gagal, silakan coba lagi.');
        }

        if (($file['size'] ?? 0) > $this->maxSize) {
            throw new \RuntimeException('Waduh, file >10MB. Silakan compress dulu ya.');
        }

        $ext = strtolower((string) pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            throw new \RuntimeException('Hanya boleh upload PDF, jangan aneh-aneh.');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = $finfo ? finfo_file($finfo, $file['tmp_name']) : null;
        if ($finfo) {
            finfo_close($finfo);
        }
        if ($mime === false || ($mime !== null && !in_array($mime, $allowedMime, true))) {
            throw new \RuntimeException('MIME file mencurigakan, pastikan PDF asli.');
        }

        // Magic bytes check
        $magic = file_get_contents($file['tmp_name'], false, null, 0, 5);
        if ($magic === false || strncmp($magic, '%PDF-', 5) !== 0) {
            throw new \RuntimeException('File tidak valid, hanya PDF yang diperbolehkan.');
        }

        $safeName = 'repo_' . time() . '_' . bin2hex(random_bytes(8)) . '.pdf';
        $target   = $this->dir . '/' . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new \RuntimeException('Gagal menyimpan file.');
        }

        @chmod($target, 0640);

        return $safeName;
    }

    public function pathFor(string $filename): string
    {
        $candidate = realpath($this->dir . '/' . $filename);
        return $candidate ?: $this->dir . '/' . $filename;
    }

    public function baseDir(): string
    {
        return $this->dir;
    }
}
