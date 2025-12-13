<?php

namespace App\Controllers\Api;

use App\Core\Security\Sanitizer;
use App\Models\Repository;
use App\Services\UploadService;

class RepositoryApiController extends BaseApiController
{
    private Repository $repo;

    public function __construct()
    {
        parent::__construct();
        $this->repo = new Repository();
    }

    public function publicIndex(): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $this->enforceRateLimit('api_public_repo_' . $ip, 120, 60, 120);

        $qRaw = $_GET['q'] ?? '';
        if (is_string($qRaw) && strlen($qRaw) > 150) {
            $this->fail('Parameter q terlalu panjang.', 400);
        }

        $filters = [
            'q'                => $this->sanitizeString($qRaw, 150),
            'tahun'            => isset($_GET['tahun']) ? (int) $_GET['tahun'] : null,
            'program_studi_id' => isset($_GET['prodi_id'])
                ? (int) $_GET['prodi_id']
                : (isset($_GET['program_studi_id']) ? (int) $_GET['program_studi_id'] : null),
        ];

        if ($filters['tahun'] !== null && $filters['tahun'] < 1900) {
            $this->fail('Parameter tahun tidak valid.', 422);
        }

        $page    = (int) ($_GET['page'] ?? 1);
        $perPage = (int) ($_GET['per_page'] ?? 10);
        [$page, $perPage, $offset] = $this->validatePagination($page, $perPage, 50);

        $items = $this->repo->searchPublic($filters, $perPage, $offset);
        $total = $this->repo->countPublic($filters);

        $this->success([
            'items' => $items,
            'meta'  => [
                'page'      => $page,
                'per_page'  => $perPage,
                'total'     => $total,
                'has_next'  => ($offset + count($items)) < $total,
            ]
        ]);
    }

    public function publicShow($idOrSlug): void
    {
        $clean = $this->sanitizeIdOrSlug($idOrSlug);
        if ($clean === null) {
            $this->fail('Parameter tidak valid.', 400);
        }

        $item = $this->repo->findPublicByIdOrSlug($clean);
        if (!$item) {
            $raw = ctype_digit($clean)
                ? $this->repo->findById((int) $clean)
                : $this->repo->getBySlug($clean);

            if ($raw && !$this->repo->isPublicAccessible($raw)) {
                $this->fail('Repository tidak tersedia untuk publik.', 403);
            }

            $this->fail('Repository tidak ditemukan.', 404);
        }

        $this->success(['item' => $item]);
    }

    public function download($id): void
    {
        $clean = $this->sanitizeIdOrSlug($id);
        if ($clean === null || !ctype_digit($clean)) {
            $this->fail('Parameter tidak valid.', 400);
        }

        $repo = $this->repo->findById((int) $clean);
        if (!$repo) {
            $this->fail('Repository tidak ditemukan.', 404);
        }

        if (!$this->repo->isPublicAccessible($repo)) {
            $this->fail('Repository tidak tersedia untuk publik.', 403);
        }

        if (empty($repo['file_pdf'])) {
            $this->fail('File belum tersedia.', 404);
        }

        $this->streamPdf($repo['file_pdf']);
    }

    public function dosenIndex(): void
    {
        $user = $this->currentUser('dosen');
        $page    = (int) ($_GET['page'] ?? 1);
        $perPage = (int) ($_GET['per_page'] ?? 10);
        [$page, $perPage, $offset] = $this->validatePagination($page, $perPage, 50);

        $filters = [
            'q'                => $this->sanitizeString($_GET['q'] ?? '', 150),
            'tahun'            => isset($_GET['tahun']) ? (int) $_GET['tahun'] : null,
            'program_studi_id' => isset($_GET['prodi_id']) ? (int) $_GET['prodi_id'] : null,
        ];

        $items = $this->repo->filterByUser((int) $user['id'], $filters, $perPage, $offset);
        $total = $this->repo->countFilteredByUser((int) $user['id'], $filters);

        $this->success([
            'items' => $items,
            'meta'  => [
                'page'      => $page,
                'per_page'  => $perPage,
                'total'     => $total,
                'has_next'  => ($offset + count($items)) < $total,
            ]
        ]);
    }

    public function store(): void
    {
        $user = $this->currentUser('dosen');
        $payload = array_merge($this->getJsonInput(), $_POST ?? []);
        $data = $this->validateRepositoryPayload($payload, $user);

        $created = $this->repo->createOwned((int) $user['id'], $data);
        if (!$created) {
            $this->fail('Gagal membuat repository.', 500);
        }

        $this->success(['item' => $created], 201);
    }

    public function show($id): void
    {
        $user = $this->currentUser('dosen');
        $repo = $this->ensureOwnership((int) $id, (int) $user['id']);
        $this->success(['item' => $repo]);
    }

    public function update($id): void
    {
        $user = $this->currentUser('dosen');
        $existing = $this->ensureOwnership((int) $id, (int) $user['id']);

        $payload = array_merge($this->getJsonInput(), $_POST ?? []);
        $data = $this->validateRepositoryPayload($payload, $user, $existing);

        if (!$this->repo->updateOwned((int) $user['id'], (int) $existing['id'], $data)) {
            $this->fail('Gagal memperbarui repository.', 500);
        }

        $updated = $this->repo->findById((int) $existing['id']);
        $this->success(['item' => $updated]);
    }

    public function destroy($id): void
    {
        $user = $this->currentUser('dosen');
        $repo = $this->ensureOwnership((int) $id, (int) $user['id']);

        if (!$this->repo->deleteOwned((int) $user['id'], (int) $repo['id'])) {
            $this->fail('Gagal menghapus repository.', 500);
        }

        $this->success(['message' => 'Repository dihapus.']);
    }

    public function uploadFile($id): void
    {
        $user = $this->currentUser('dosen');
        $repo = $this->ensureOwnership((int) $id, (int) $user['id']);

        if (empty($_FILES['file'])) {
            $this->fail('File wajib diunggah.', 422);
        }

        $uploader = new UploadService('uploads');
        try {
            $stored = $uploader->storePdf($_FILES['file']);
        } catch (\RuntimeException $e) {
            $this->fail($e->getMessage(), 422);
        }

        if (!empty($repo['file_pdf'])) {
            $this->safeDeleteOldFile($repo['file_pdf']);
        }

        if (!$this->repo->updateFile((int) $repo['id'], $stored)) {
            $this->fail('Gagal menyimpan file.', 500);
        }

        $this->success([
            'item' => $this->repo->findById((int) $repo['id']),
        ]);
    }

    private function sanitizeIdOrSlug($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (ctype_digit($value)) {
            return (string) ((int) $value);
        }

        $value = strtolower($value);
        if (!preg_match('/^[a-z0-9-]+$/', $value)) {
            return null;
        }

        return $value;
    }

    private function validateRepositoryPayload(array $payload, array $user, ?array $existing = null): array
    {
        $judul = $this->sanitizeString($payload['judul'] ?? ($existing['judul'] ?? ''), 200);
        if ($judul === '') {
            $this->fail('Judul wajib diisi.', 422);
        }

        $slugInput = $payload['slug'] ?? null;
        $slug = $slugInput ? strtolower(trim($slugInput)) : $this->generateSlug($judul);
        if (!$this->isValidSlug($slug)) {
            $this->fail('Slug tidak valid.', 422);
        }
        $slug = $this->ensureUniqueSlug($slug, $existing['id'] ?? null);

        $tahun = isset($payload['tahun']) ? (int) $payload['tahun'] : (int) ($existing['tahun'] ?? date('Y'));
        $currentYear = (int) date('Y') + 1;
        if ($tahun < 1900 || $tahun > $currentYear) {
            $this->fail('Tahun tidak valid.', 422);
        }

        $jenisKarya = $this->sanitizeString($payload['jenis_karya'] ?? ($existing['jenis_karya'] ?? 'lainnya'), 50);
        $author = $this->sanitizeString(
            $payload['author'] ?? ($existing['author'] ?? ($user['nama_lengkap'] ?? '')),
            150
        );

        $prodi = isset($payload['program_studi_id']) ? (int) $payload['program_studi_id']
            : (isset($payload['prodi_id']) ? (int) $payload['prodi_id'] : ($existing['program_studi_id'] ?? null));
        if ($prodi !== null && $prodi <= 0) {
            $prodi = null;
        }

        $mk = isset($payload['mata_kuliah_id']) ? (int) $payload['mata_kuliah_id'] : ($existing['mata_kuliah_id'] ?? null);
        if ($mk !== null && $mk <= 0) {
            $mk = null;
        }

        $abstrak = trim((string) ($payload['abstrak'] ?? ($existing['abstrak'] ?? '')));
        if ($abstrak !== '') {
            $abstrak = strip_tags($abstrak);
        } else {
            $abstrak = null;
        }

        $keywords = $this->sanitizeString($payload['keywords'] ?? ($existing['keywords'] ?? ''), 200);
        if ($keywords === '') {
            $keywords = null;
        }

        return [
            'judul'            => $judul,
            'slug'             => $slug,
            'jenis_karya'      => $jenisKarya ?: 'lainnya',
            'author'           => $author,
            'tahun'            => $tahun,
            'program_studi_id' => $prodi,
            'mata_kuliah_id'   => $mk,
            'abstrak'          => $abstrak,
            'file_pdf'         => $existing['file_pdf'] ?? null,
            'uploaded_by'      => (int) $user['id'],
            'keywords'         => $keywords,
        ];
    }

    private function ensureOwnership(int $repoId, int $userId): array
    {
        $item = $this->repo->findOwnedByDosen($userId, $repoId);
        if (!$item) {
            $this->fail('Repository tidak ditemukan atau bukan milik Anda.', 404);
        }

        return $item;
    }

    private function isValidSlug(string $slug): bool
    {
        return (bool) preg_match('/^[a-z0-9-]+$/', $slug);
    }

    private function generateSlug(string $source): string
    {
        $text = strtolower($source);
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $text = trim((string) $text, '-');

        if (strlen($text) > 80) {
            $text = substr($text, 0, 80);
            $text = rtrim($text, '-');
        }

        return $text === '' ? bin2hex(random_bytes(4)) : $text;
    }

    private function ensureUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $counter = 0;

        while (true) {
            $candidate = $counter === 0 ? $base : $base . '-' . $counter;
            $exists = $this->repo->getBySlug($candidate);

            if (!$exists || ($ignoreId !== null && (int) $exists['id'] === $ignoreId)) {
                return $candidate;
            }

            $counter++;
            if ($counter > 20) {
                return $base . '-' . bin2hex(random_bytes(3));
            }
        }
    }

    private function streamPdf(string $filename): void
    {
        $baseDir = realpath(__DIR__ . '/../../../storage/uploads');
        if ($baseDir === false) {
            $this->fail('File tidak ditemukan.', 404);
        }

        $path = realpath($baseDir . '/' . $filename);
        if (!$path || strpos($path, $baseDir) !== 0 || !is_file($path)) {
            $this->fail('File tidak ditemukan.', 404);
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }

    private function safeDeleteOldFile(string $filename): void
    {
        $baseDir = realpath(__DIR__ . '/../../../storage/uploads');
        if ($baseDir === false) {
            return;
        }

        $path = realpath($baseDir . '/' . $filename);
        if ($path && strpos($path, $baseDir) === 0 && is_file($path)) {
            @unlink($path);
        }
    }
}
