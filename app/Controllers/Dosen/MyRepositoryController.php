<?php

namespace App\Controllers\Dosen;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Security\Sanitizer;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\Repository;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\UploadService;

class MyRepositoryController extends Controller
{
    public function index()
    {
        $dosen = Auth::checkDosen();
        $repoModel  = new Repository();
        $prodiModel = new ProgramStudi();
        $mkModel    = new MataKuliah();
        $logModel   = new ActivityLog();

        $filters = [
            'q'                => trim($_GET['q'] ?? ''),
            'tahun'            => $_GET['tahun'] ?? '',
            'program_studi_id' => $_GET['prodi'] ?? '',
            'jenis_karya'      => $_GET['jenis'] ?? '',
        ];
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset  = ($page - 1) * $perPage;

        $repos  = $repoModel->filterByUser((int) $dosen['id'], $filters, $perPage, $offset);
        $total  = $repoModel->countFilteredByUser((int) $dosen['id'], $filters);
        $pages  = (int) ceil($total / $perPage);
        $prodis = $prodiModel->getAllWithCount();
        $mks    = $mkModel->getAll();
        $headerActivities = array_map(static function ($row) use ($dosen) {
            return [
                'actor'  => $dosen['nama'] ?? 'Dosen',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$dosen['id'], 5));

        return $this->view('dosen/my_repository', [
            'dosen'      => $dosen,
            'repos'      => $repos,
            'prodis'     => $prodis,
            'mks'        => $mks,
            'csrf'       => Csrf::token(),
            'header_activities' => $headerActivities,
            'page'       => $page,
            'pages'      => $pages,
            'filters'    => $filters,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dosen/dashboard'],
                ['label' => 'My Repository'],
            ],
            'suppress_layout_title' => true,
        ], 'dosen');
    }

    // Aliases for routes
    public function create()
    {
        $dosen = Auth::checkDosen();
        $prodiModel = new ProgramStudi();
        $mkModel    = new MataKuliah();
        $userModel  = new User();
        $logModel   = new ActivityLog();

        $headerActivities = array_map(static function ($row) {
            return [
                'actor'  => '',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$dosen['id'], 5));

        return $this->view('dosen/repository-form', [
            'dosen'      => $dosen,
            'prodis'     => $prodiModel->getAllWithCount(),
            'mks'        => $mkModel->getAll(),
            'mahasiswas' => $userModel->getAllOrdered('', 'mahasiswa', 1000, 0),
            'dosens'     => $userModel->getAllOrdered('', 'dosen', 1000, 0),
            'selected_authors'   => [],
            'selected_advisors'  => [],
            'selected_examiners' => [],
            'csrf'       => Csrf::token(),
            'mode'       => 'create',
            'header_activities' => $headerActivities,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dosen/dashboard'],
                ['label' => 'My Repository', 'url' => '/dosen/repository'],
                ['label' => 'Create'],
            ],
            'suppress_layout_title' => true,
        ], 'dosen');
    }

    public function edit($id)
    {
        $dosen = Auth::checkDosen();
        $repoModel = new Repository();
        $prodiModel = new ProgramStudi();
        $mkModel    = new MataKuliah();
        $userModel  = new User();
        $logModel   = new ActivityLog();

        if (!$this->userOwnsRepo((int)$id, (int)$dosen['id'])) {
            $_SESSION['flash_error'] = 'Repository tidak ditemukan.';
            return $this->redirect('/dosen/my-repository');
        }

        $item = $repoModel->findById((int)$id);
        $headerActivities = array_map(static function ($row) {
            return [
                'actor'  => '',
                'action' => $row['description'] ?? $row['activity_type'],
                'time'   => $row['created_at'] ?? '',
            ];
        }, $logModel->getRecentByUser((int)$dosen['id'], 5));

        $relations = $repoModel->getUserRelations((int)$id);

        return $this->view('dosen/repository-form', [
            'dosen'      => $dosen,
            'item'       => $item,
            'prodis'     => $prodiModel->getAllWithCount(),
            'mks'        => $mkModel->getAll(),
            'mahasiswas' => $userModel->getAllOrdered('', 'mahasiswa', 1000, 0),
            'dosens'     => $userModel->getAllOrdered('', 'dosen', 1000, 0),
            'selected_authors'   => $relations['authors'] ?? [],
            'selected_advisors'  => $relations['advisors'] ?? [],
            'selected_examiners' => $relations['examiners'] ?? [],
            'csrf'       => Csrf::token(),
            'mode'       => 'edit',
            'header_activities' => $headerActivities,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dosen/dashboard'],
                ['label' => 'My Repository', 'url' => '/dosen/repository'],
                ['label' => 'Edit'],
            ],
            'suppress_layout_title' => true,
        ], 'dosen');
    }

    public function store()
    {
        $dosen = Auth::checkDosen();
        Sanitizer::cleanRequest();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/dosen/my-repository');
        }

        $repoModel = new Repository();
        $uploaded  = $this->handleUpload();
        $data      = $this->collectRequest($uploaded, $dosen);
        $repoModel->create($data);
        $repoId = (int) $repoModel->lastInsertId();

        $this->attachOwner($repoId, (int)$dosen['id']);
        $this->syncRelations($repoId, $repoModel, (int)$dosen['id']);

        $_SESSION['flash_success'] = 'Repository ditambahkan.';
        return $this->redirect('/dosen/repository');
    }

    public function update($id)
    {
        $dosen = Auth::checkDosen();
        Sanitizer::cleanRequest();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/dosen/my-repository');
        }

        if (!$this->userOwnsRepo((int)$id, (int)$dosen['id'])) {
            $_SESSION['flash_error'] = 'Repository tidak ditemukan.';
            return $this->redirect('/dosen/repository');
        }

        $repoModel = new Repository();
        $uploaded  = $this->handleUpload($_POST['current_file'] ?? null);
        $data      = $this->collectRequest($uploaded, $dosen, $_POST['current_file'] ?? null);
        $repoModel->updateById((int)$id, $data);
        $this->syncRelations((int)$id, $repoModel, (int)$dosen['id']);

        $_SESSION['flash_success'] = 'Repository diperbarui.';
        return $this->redirect('/dosen/repository');
    }

    public function delete($id)
    {
        Auth::checkDosen();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/dosen/my-repository');
        }

        $repoModel = new Repository();
        $repoModel->deleteById((int)$id);

        $_SESSION['flash_success'] = 'Repository dihapus.';
        return $this->redirect('/dosen/repository');
    }

    private function collectRequest(?string $uploadedFile, array $dosen, ?string $currentFile = null): array
    {
        $jenisList = ['skripsi','tugas_akhir','jurnal','artikel','laporan','pkl','lainnya'];
        $jenis = $_POST['jenis_karya'] ?? 'lainnya';
        if (!in_array($jenis, $jenisList, true)) {
            $jenis = 'lainnya';
        }

        $slugInput = trim($_POST['slug'] ?? '');
        $slug = $this->generateSlug($slugInput !== '' ? $slugInput : ($_POST['judul'] ?? ''));

        return [
            'judul'            => trim($_POST['judul'] ?? ''),
            'slug'             => $slug,
            'jenis_karya'      => $jenis,
            'author'           => trim($_POST['author'] ?? ($dosen['nama'] ?? '')),
            'tahun'            => (int)($_POST['tahun'] ?? date('Y')),
            'program_studi_id' => $_POST['program_studi_id'] !== '' ? (int)$_POST['program_studi_id'] : null,
            'mata_kuliah_id'   => $_POST['mata_kuliah_id'] !== '' ? (int)$_POST['mata_kuliah_id'] : null,
            'abstrak'          => $_POST['abstrak'] ?? null,
            'file_pdf'         => $uploadedFile ?? $currentFile ?? null,
            'uploaded_by'      => (int) $dosen['id'],
            'keywords'         => $_POST['keywords'] ?? null,
        ];
    }

    private function syncRelations(int $repoId, Repository $repoModel, int $ownerId): void
    {
        $authors   = $_POST['author_ids']   ?? [];
        $advisors  = $_POST['advisor_ids']  ?? [];
        $examiners = $_POST['examiner_ids'] ?? [];
        $repoModel->syncUserRelations($repoId, $authors, $advisors, $examiners, $ownerId);
    }

    private function handleUpload(?string $current = null): ?string
    {
        if (!isset($_FILES['file_pdf']) || ($_FILES['file_pdf']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $uploader = new UploadService();

        try {
            return $uploader->storePdf($_FILES['file_pdf']);
        } catch (\RuntimeException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            return $current;
        }
    }

    private function attachOwner(int $repoId, int $userId): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO repository_users (repository_id, user_id, role_in_repo) VALUES (:rid, :uid, 'owner')");
        $stmt->execute(['rid' => $repoId, 'uid' => $userId]);
    }

    private function userOwnsRepo(int $repoId, int $userId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM repository_users WHERE repository_id = :rid AND user_id = :uid");
        $stmt->execute(['rid' => $repoId, 'uid' => $userId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    private function generateSlug(string $source): string
    {
        $text = strtolower($source);
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $text = trim((string)$text, '-');

        if (strlen($text) > 80) {
            $cut = substr($text, 0, 80);
            $cut = rtrim($cut, '-');
            $lastDash = strrpos($cut, '-');
            if ($lastDash !== false && $lastDash >= 50) {
                $cut = substr($cut, 0, $lastDash);
            }
            $text = $cut;
        }

        return $text;
    }
}
