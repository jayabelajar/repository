<?php

namespace App\Controllers\Api;

use App\Models\Bookmark;
use App\Models\Repository;

class BookmarkApiController extends BaseApiController
{
    public function index(): void
    {
        $user  = $this->requireUser();
        $limit = (int) ($_GET['limit'] ?? 20);
        $limit = $limit > 0 ? min($limit, 100) : 20;

        $bookmark = new Bookmark();
        $items = $bookmark->getByUserWithDetail((int) $user['id'], $limit);

        $this->success(['items' => $items]);
    }

    public function toggle(): void
    {
        $user    = $this->requireUser();
        $payload = array_merge($this->getJsonInput(), $_POST ?? []);

        $repoId = isset($payload['repository_id']) ? (int) $payload['repository_id'] : null;
        $slug   = $payload['slug'] ?? null;

        $repoModel = new Repository();

        if (!$repoId && $slug) {
            $repo = $repoModel->getBySlug($slug);
            if (!$repo) {
                $this->fail('Repository tidak ditemukan.', 404);
            }
            $repoId = (int) $repo['id'];
        }

        if (!$repoId) {
            $this->fail('repository_id atau slug wajib diisi.', 422);
        }

        $bookmark = new Bookmark();
        $bookmarked = $bookmark->toggle((int) $user['id'], $repoId);

        $this->success([
            'bookmarked' => $bookmarked
        ]);
    }
}
