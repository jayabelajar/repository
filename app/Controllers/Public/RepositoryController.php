<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Models\Repository;
use App\Models\Bookmark;
use App\Models\User;

class RepositoryController extends Controller
{
    public function detail($slug)
    {
        $config = require __DIR__ . '/../../../config/config.php';
        $base   = rtrim($config['base_url'] ?? '', '/');

        $repo = new Repository();
        $data = $repo->getBySlug($slug);

        if (!$data) {
            die("Repository tidak ditemukan.");
        }

        $relations = $repo->getUserRelations((int)$data['id']);
        $userModel = new User();
        $authors   = $userModel->getByIds($relations['authors'] ?? []);
        $advisors  = $userModel->getByIds($relations['advisors'] ?? []);
        $examiners = $userModel->getByIds($relations['examiners'] ?? []);
        $owners    = $userModel->getByIds($relations['owners'] ?? []);

        $seo = [
            'title'       => $data['judul'],
            'description' => mb_strimwidth(strip_tags($data['abstrak']), 0, 155, '...'),
            'keywords'    => $data['keywords'] ?? '',
            'canonical'   => $base . '/repository/' . rawurlencode($slug),
            'og_type'     => 'article',
            'image'       => $base . '/assets/img/inhafi.png',
            'image_alt'   => $data['judul'] ?? 'Repository'
        ];

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $bookmarkable = false;
        $isBookmarked = false;

        $user = $_SESSION['mahasiswa'] ?? $_SESSION['dosen'] ?? null;
        if ($user) {
            $bookmarkable = true;
            $bookmark = new Bookmark();
            $isBookmarked = $bookmark->isBookmarked((int) $user['id'], (int) $data['id']);
        }

        $this->view('public/detail_repository', [
            'seo'          => $seo,
            'data'         => $data,
            'authors'      => $authors,
            'advisors'     => $advisors,
            'examiners'    => $examiners,
            'owners'       => $owners,
            'bookmarkable' => $bookmarkable,
            'isBookmarked' => $isBookmarked,
        ]);
    }
}
