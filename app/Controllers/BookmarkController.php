<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Bookmark;
use App\Models\Repository;

class BookmarkController extends Controller
{
    public function toggle(string $slug)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!\App\Core\Security\Csrf::check($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Sesi kedaluwarsa, silakan ulangi.';
            $this->redirect('login');
        }

        $user = $_SESSION['mahasiswa'] ?? $_SESSION['dosen'] ?? null;
        if (!$user) {
            $this->redirect('login');
        }

        $repoModel = new Repository();
        $bookmarkModel = new Bookmark();

        $repo = $repoModel->getBySlug($slug);
        if (!$repo) {
            http_response_code(404);
            echo "Repository tidak ditemukan.";
            return;
        }

        $toggledOn = $bookmarkModel->toggle((int) $user['id'], (int) $repo['id']);
        $_SESSION['flash_success'] = $toggledOn ? 'Bookmark ditambahkan.' : 'Bookmark dihapus.';

        $referer = $_SERVER['HTTP_REFERER'] ?? ($this->data['base_url'] ?? '/');
        header('Location: ' . $referer);
        exit;
    }
}
