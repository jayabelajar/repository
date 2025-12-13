<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security\Auth;
use App\Core\Security\Csrf;
use App\Core\Security\Sanitizer;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\Repository;
use App\Models\User;
use App\Services\UploadService;

class RepositoryController extends Controller
{
    private function syncRelations(int $repoId, Repository $repoModel): void
    {
        $authors   = $_POST['author_ids']   ?? [];
        $advisors  = $_POST['advisor_ids']  ?? [];
        $examiners = $_POST['examiner_ids'] ?? [];
        $ownerId   = !empty($_POST['uploaded_by']) ? (int) $_POST['uploaded_by'] : null;

        $repoModel->syncUserRelations($repoId, $authors, $advisors, $examiners, $ownerId);
    }

    public function index()
    {
        $admin = Auth::checkAdmin();
        $repoModel  = new Repository();
        $prodiModel = new ProgramStudi();
        $mkModel    = new MataKuliah();
        $userModel  = new User();

        $filters = [
            'q'                => trim($_GET['q'] ?? ''),
            'tahun'            => $_GET['tahun'] ?? '',
            'program_studi_id' => $_GET['prodi'] ?? '',
            'jenis_karya'      => $_GET['jenis'] ?? '',
        ];

        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset  = ($page - 1) * $perPage;

        $items   = $repoModel->filter($filters, $perPage, $offset);
        $total   = $repoModel->countFiltered($filters);
        $pages   = (int) ceil($total / $perPage);
        $prodis  = $prodiModel->getAllWithCount();
        $mks     = $mkModel->getAll();
        $authors = $userModel->getAllOrdered();

        return $this->view('admin/repository', [
            'admin'      => $admin,
            'items'      => $items,
            'prodis'     => $prodis,
            'mks'        => $mks,
            'authors'    => $authors,
            'search'     => $filters['q'],
            'csrf'       => Csrf::token(),
            'header_activities' => $repoModel->getRecentActivities(),
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/admin/dashboard'],
                ['label' => 'Repository'],
            ],
            'page'      => $page,
            'pages'     => $pages,
            'per_page'  => $perPage,
            'total'     => $total,
        ], 'admin');
    }

    public function create()
    {
        $admin = Auth::checkAdmin();
        $repoModel  = new Repository();
        $prodiModel = new ProgramStudi();
        $mkModel    = new MataKuliah();
        $userModel  = new User();
        $mahasiswas = $userModel->getAllOrdered('', 'mahasiswa', 1000, 0);
        $dosens     = $userModel->getAllOrdered('', 'dosen', 1000, 0);

        return $this->view('admin/repository-form', [
            'admin'      => $admin,
            'item'       => null,
            'prodis'     => $prodiModel->getAllWithCount(),
            'mks'        => $mkModel->getAll(),
            'authors'    => $userModel->getAllOrdered(),
            'mahasiswas' => $mahasiswas,
            'dosens'     => $dosens,
            'selected_authors'   => [],
            'selected_advisors'  => [],
            'selected_examiners' => [],
            'csrf'       => Csrf::token(),
            'mode'       => 'create',
            'header_activities' => $repoModel->getRecentActivities(),
            'breadcrumb' => [
                ['label' => 'Admin', 'url' => '/admin/dashboard'],
                ['label' => 'Repository', 'url' => '/admin/repository'],
                ['label' => 'Create'],
            ],
        ], 'admin');
    }

    public function edit($id)
    {
        $admin = Auth::checkAdmin();
        $repoModel  = new Repository();
        $item       = $repoModel->findById((int) $id);
        if (!$item) {
            $_SESSION['flash_error'] = 'Data repository tidak ditemukan.';
            return $this->redirect('/admin/repository');
        }

        $prodiModel = new ProgramStudi();
        $mkModel    = new MataKuliah();
        $userModel  = new User();
        $mahasiswas = $userModel->getAllOrdered('', 'mahasiswa', 1000, 0);
        $dosens     = $userModel->getAllOrdered('', 'dosen', 1000, 0);

        $relations = $repoModel->getUserRelations((int)$id);

        return $this->view('admin/repository-form', [
            'admin'      => $admin,
            'item'       => $item,
            'prodis'     => $prodiModel->getAllWithCount(),
            'mks'        => $mkModel->getAll(),
            'authors'    => $userModel->getAllOrdered(),
            'mahasiswas' => $mahasiswas,
            'dosens'     => $dosens,
            'selected_authors'   => $relations['authors'] ?? [],
            'selected_advisors'  => $relations['advisors'] ?? [],
            'selected_examiners' => $relations['examiners'] ?? [],
            'csrf'       => Csrf::token(),
            'mode'       => 'edit',
            'header_activities' => $repoModel->getRecentActivities(),
            'breadcrumb' => [
                ['label' => 'Admin', 'url' => '/admin/dashboard'],
                ['label' => 'Repository', 'url' => '/admin/repository'],
                ['label' => 'Edit'],
            ],
        ], 'admin');
    }

    public function store()
    {
        Auth::checkAdmin();
        Sanitizer::cleanRequest();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/repository');
        }

        $uploaded = $this->handleUpload();
        if ($uploaded === false) {
            $_SESSION['flash_error'] = $_SESSION['flash_error'] ?? 'Upload file gagal.';
            return $this->redirect('/admin/repository');
        }
        $data = $this->collectRequest($uploaded);
        $repoModel = new Repository();
        $repoModel->create($data);
        $repoId = (int) $repoModel->lastInsertId();
        $this->syncRelations($repoId, $repoModel);

        $_SESSION['flash_success'] = 'Repository ditambahkan.';
        if (!empty($_POST['save_and_new'])) {
            return $this->redirect('/admin/repository/create');
        }
        return $this->redirect('/admin/repository');
    }

    public function update($id)
    {
        Auth::checkAdmin();
        Sanitizer::cleanRequest();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/repository');
        }

        $uploaded = $this->handleUpload($_POST['current_file'] ?? null);
        if ($uploaded === false) {
            $_SESSION['flash_error'] = $_SESSION['flash_error'] ?? 'Upload file gagal.';
            return $this->redirect('/admin/repository');
        }
        $data = $this->collectRequest($uploaded, $_POST['current_file'] ?? null);
        $repoModel = new Repository();
        $repoModel->updateById((int)$id, $data);
        $this->syncRelations((int)$id, $repoModel);

        $_SESSION['flash_success'] = 'Repository diperbarui.';
        return $this->redirect('/admin/repository');
    }

    public function delete($id)
    {
        Auth::checkAdmin();
        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kadaluarsa.';
            return $this->redirect('/admin/repository');
        }

        $repoModel = new Repository();
        $repoModel->deleteById((int)$id);

        $_SESSION['flash_success'] = 'Repository dihapus.';
        return $this->redirect('/admin/repository');
    }

    private function collectRequest(?string $uploadedFile = null, ?string $currentFile = null): array
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
            'author'           => trim($_POST['author'] ?? ''),
            'tahun'            => (int)($_POST['tahun'] ?? date('Y')),
            'program_studi_id' => $_POST['program_studi_id'] !== '' ? (int)$_POST['program_studi_id'] : null,
            'mata_kuliah_id'   => $_POST['mata_kuliah_id'] !== '' ? (int)$_POST['mata_kuliah_id'] : null,
            'abstrak'          => $_POST['abstrak'] ?? null,
            'file_pdf'         => $uploadedFile ?? $currentFile ?? null,
            'uploaded_by'      => $_POST['uploaded_by'] !== '' ? (int)$_POST['uploaded_by'] : null,
            'keywords'         => $_POST['keywords'] ?? null,
        ];
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

    private function handleUpload(?string $current = null)
    {
        if (!isset($_FILES['file_pdf']) || ($_FILES['file_pdf']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $uploader = new UploadService();

        try {
            return $uploader->storePdf($_FILES['file_pdf']);
        } catch (\RuntimeException $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            return false;
        }
    }
}
