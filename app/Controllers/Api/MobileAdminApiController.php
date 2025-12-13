<?php

namespace App\Controllers\Api;

use App\Models\ActivityLog;
use App\Models\Bookmark;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\Repository;

class MobileAdminApiController extends BaseApiController
{
    private Repository $repo;
    private Bookmark $bookmark;
    private ActivityLog $activity;

    public function __construct()
    {
        parent::__construct();
        $this->repo      = new Repository();
        $this->bookmark  = new Bookmark();
        $this->activity  = new ActivityLog();
    }

    /**
     * Ringkasan untuk halaman depan aplikasi mobile (tampilan publik).
     */
    public function publicHome(): void
    {
        $limitLatest = (int) ($_GET['limit'] ?? 6);
        $limitLatest = $limitLatest > 0 ? min($limitLatest, 20) : 6;

        $latest = $this->repo->searchPublic([], $limitLatest, 0);

        $prodiModel = new ProgramStudi();
        $mkModel    = new MataKuliah();

        $this->success([
            'latest'   => $latest,
            'lookups'  => [
                'program_studi' => $prodiModel->getAllWithCount(),
                'mata_kuliah'   => $mkModel->getAll(),
                'years'         => $this->repo->getAvailableYears(),
                'jenis_karya'   => $this->repo->getJenisKaryaList(),
                'authors'       => $this->repo->getAuthors(),
            ],
            'generated_at' => date('c'),
        ]);
    }

    /**
     * Dashboard data untuk mahasiswa (statistik, repositori terkini, bookmark, aktivitas).
     */
    public function mahasiswaDashboard(): void
    {
        $user = $this->enforceRole('mahasiswa');

        $myRepos    = $this->repo->getForMahasiswa((int) $user['id'], 6);
        $bookmarks  = $this->bookmark->getByUserWithDetail((int) $user['id'], 6);
        $activities = $this->activity->getRecentByUser((int) $user['id'], 10);

        $this->success([
            'stats' => [
                'repos'      => (int) $this->repo->countByMahasiswa((int) $user['id']),
                'bookmarks'  => (int) $this->bookmark->countByUser((int) $user['id']),
                'activities' => count($activities),
            ],
            'my_repositories' => $myRepos,
            'bookmarks'       => $bookmarks,
            'activities'      => $activities,
        ]);
    }

    /**
     * Daftar repository milik mahasiswa (dengan filter + pagination).
     */
    public function mahasiswaRepositories(): void
    {
        $user = $this->enforceRole('mahasiswa');
        $page    = (int) ($_GET['page'] ?? 1);
        $perPage = (int) ($_GET['per_page'] ?? 10);
        [$page, $perPage, $offset] = $this->validatePagination($page, $perPage, 50);

        $filters = [
            'q'                => $this->sanitizeString($_GET['q'] ?? '', 150),
            'tahun'            => isset($_GET['tahun']) ? (int) $_GET['tahun'] : null,
            'program_studi_id' => isset($_GET['prodi']) ? (int) $_GET['prodi'] : null,
            'mata_kuliah_id'   => isset($_GET['mk']) ? (int) $_GET['mk'] : null,
            'jenis_karya'      => $this->sanitizeString($_GET['jenis'] ?? ($_GET['jenis_karya'] ?? ''), 50),
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

    /**
     * CRUD sederhana bookmark untuk mahasiswa/dosen (list + toggle).
     */
    public function bookmarks(): void
    {
        $user    = $this->requireUser();
        $page    = (int) ($_GET['page'] ?? 1);
        $perPage = (int) ($_GET['per_page'] ?? 20);
        [$page, $perPage, $offset] = $this->validatePagination($page, $perPage, 100);

        $items = $this->bookmark->listByUser((int) $user['id'], $perPage, $offset);
        $total = $this->bookmark->countByUser((int) $user['id']);

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

    public function toggleBookmark(): void
    {
        $user    = $this->requireUser();
        $payload = array_merge($this->getJsonInput(), $_POST ?? []);

        $repoId = isset($payload['repository_id']) ? (int) $payload['repository_id'] : null;
        $slug   = $payload['slug'] ?? null;

        if (!$repoId && $slug) {
            $repo = $this->repo->getBySlug($slug);
            if (!$repo) {
                $this->fail('Repository tidak ditemukan.', 404);
            }
            $repoId = (int) $repo['id'];
        }

        if (!$repoId) {
            $this->fail('repository_id atau slug wajib diisi.', 422);
        }

        $bookmarked = $this->bookmark->toggle((int) $user['id'], $repoId);

        $this->success(['bookmarked' => $bookmarked]);
    }

    /**
     * Riwayat aktivitas user (mahasiswa/dosen) dengan pagination.
     */
    public function activities(): void
    {
        $user = $this->requireUser();
        $page    = (int) ($_GET['page'] ?? 1);
        $perPage = (int) ($_GET['per_page'] ?? 20);
        [$page, $perPage, $offset] = $this->validatePagination($page, $perPage, 100);

        $items = $this->activity->getPagedByUser((int) $user['id'], $perPage, $offset);
        $total = $this->activity->countByUser((int) $user['id']);

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

    /**
     * Dashboard data untuk dosen.
     */
    public function dosenDashboard(): void
    {
        $user = $this->enforceRole('dosen');

        $repos      = $this->repo->getForUserDetailed((int) $user['id'], 6);
        $bookmarks  = $this->bookmark->getByUserWithDetail((int) $user['id'], 6);
        $activities = $this->activity->getRecentByUser((int) $user['id'], 10);

        $this->success([
            'stats' => [
                'repo_saya'       => $this->repo->countByUser((int) $user['id']),
                'bookmark'        => $this->bookmark->countByUser((int) $user['id']),
                'aktivitas'       => $this->activity->countByUser((int) $user['id']),
                'total_bimbingan' => $this->repo->countBimbinganByAdvisor((int) $user['id']),
            ],
            'repositories' => $repos,
            'bookmarks'    => $bookmarks,
            'activities'   => $activities,
        ]);
    }

    private function enforceRole(string $role): array
    {
        $user = $this->currentUser($role);
        if (!$user) {
            $this->fail('Token tidak valid atau sudah kedaluwarsa.', 401);
        }

        return $user;
    }
}
