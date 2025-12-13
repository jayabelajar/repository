<?php

namespace App\Models;

use App\Core\Model;

class Repository extends Model
{
    protected string $table = 'repository';
    private array $columnCache = [];

    public function searchPublic(array $filters, int $limit = 10, int $offset = 0): array
    {
        $params = [];
        $whereSql = $this->buildPublicWhere($filters, $params);

        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM {$this->table} r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                {$whereSql}
                ORDER BY r.created_at DESC
                LIMIT :limit OFFSET :offset";

        $params['limit'] = $limit;
        $params['offset'] = $offset;

        $rows = $this->query($sql, $params)->fetchAll();

        return array_values(array_filter($rows, function ($row) {
            return $this->isPublicRecord($row);
        }));
    }

    public function countPublic(array $filters): int
    {
        $params = [];
        $whereSql = $this->buildPublicWhere($filters, $params);

        $sql = "SELECT COUNT(*) 
                FROM {$this->table} r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                {$whereSql}";

        return (int) $this->query($sql, $params)->fetchColumn();
    }

    public function findPublicByIdOrSlug(string $idOrSlug): ?array
    {
        $item = ctype_digit($idOrSlug)
            ? $this->findById((int) $idOrSlug)
            : $this->getBySlug($idOrSlug);

        if (!$item || !$this->isPublicRecord($item) || empty($item['file_pdf'])) {
            return null;
        }

        return $item;
    }

    public function findOwnedByDosen(int $dosenId, int $repoId): ?array
    {
        $sql = "SELECT r.*
                FROM {$this->table} r
                WHERE r.id = :id
                  AND EXISTS (
                    SELECT 1 FROM repository_users ru
                    WHERE ru.repository_id = r.id AND ru.user_id = :uid
                  )
                LIMIT 1";

        $item = $this->fetch($sql, ['id' => $repoId, 'uid' => $dosenId]);
        return $item ?: null;
    }

    public function createOwned(int $dosenId, array $data): ?array
    {
        $this->db->beginTransaction();
        try {
            $this->create($data);
            $repoId = (int) $this->lastInsertId();
            $this->attachOwner($repoId, $dosenId);
            $this->db->commit();
            return $this->findById($repoId);
        } catch (\Throwable $e) {
            $this->db->rollBack();
            return null;
        }
    }

    public function updateOwned(int $dosenId, int $repoId, array $data): bool
    {
        if (!$this->isOwnedBy($repoId, $dosenId)) {
            return false;
        }

        return $this->updateById($repoId, $data);
    }

    public function deleteOwned(int $dosenId, int $repoId): bool
    {
        if (!$this->isOwnedBy($repoId, $dosenId)) {
            return false;
        }

        if ($this->hasColumn('deleted_at')) {
            return $this->execute("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id", ['id' => $repoId]);
        }

        return $this->deleteById($repoId);
    }


    /* ---------------------------
       LATEST
    ------------------------------ */
    public function getLatest(int $limit = 6)
    {
        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                ORDER BY r.created_at DESC
                LIMIT :limit";

        return $this->query($sql, ['limit' => $limit])->fetchAll();
    }

    /* ---------------------------
       GLOBAL SEARCH
    ------------------------------ */
    public function search(string $keyword)
    {
        $kw = "%$keyword%";

        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE r.judul LIKE :k1
                   OR r.author LIKE :k2
                   OR r.tahun LIKE :k3
                   OR ps.nama_program_studi LIKE :k4
                   OR mk.nama LIKE :k5
                   OR r.jenis_karya LIKE :k6
                   OR r.slug LIKE :k7
                   OR r.keywords LIKE :k8
                ORDER BY r.created_at DESC";

        return $this->query($sql, [
            'k1' => $kw,
            'k2' => $kw,
            'k3' => $kw,
            'k4' => $kw,
            'k5' => $kw,
            'k6' => $kw,
            'k7' => $kw,
            'k8' => $kw,
        ])->fetchAll();
    }

    /* ---------------------------
       BY YEAR
    ------------------------------ */
    public function getAvailableYears()
    {
        $sql = "SELECT tahun AS label, COUNT(*) AS total
                FROM repository
                GROUP BY tahun
                ORDER BY tahun DESC";

        return $this->query($sql)->fetchAll();
    }

    public function getByYear(int $tahun)
    {
        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE r.tahun = :tahun
                ORDER BY r.created_at DESC";

        return $this->query($sql, ['tahun' => $tahun])->fetchAll();
    }

    /* ---------------------------
       PROGRAM STUDI
    ------------------------------ */
    public function getProgramStudiWithCount()
    {
        $sql = "SELECT ps.id, ps.nama_program_studi AS label,
                       COUNT(r.id) AS total
                FROM program_studi ps
                LEFT JOIN repository r ON r.program_studi_id = ps.id
                GROUP BY ps.id, ps.nama_program_studi
                ORDER BY ps.nama_program_studi ASC";

        return $this->query($sql)->fetchAll();
    }

    public function getByProgramStudiSlug(string $slug)
    {
        $sql = "SELECT r.*, ps.nama_program_studi AS prodi
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                WHERE LOWER(REPLACE(ps.nama_program_studi,' ','-')) = :slug
                ORDER BY r.created_at DESC";

        return $this->query($sql, ['slug' => $slug])->fetchAll();
    }

    /* ---------------------------
       MATA KULIAH
    ------------------------------ */
    public function getMataKuliahWithCount()
    {
        $sql = "SELECT mk.id, mk.nama AS label,
                       COUNT(r.id) AS total
                FROM mata_kuliah mk
                LEFT JOIN repository r ON r.mata_kuliah_id = mk.id
                GROUP BY mk.id, mk.nama
                ORDER BY mk.nama ASC";

        return $this->query($sql)->fetchAll();
    }

    public function getByMataKuliahId(int $id)
    {
        $sql = "SELECT r.*, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE r.mata_kuliah_id = :id
                ORDER BY r.created_at DESC";

        return $this->query($sql, ['id' => $id])->fetchAll();
    }

    /* ---------------------------
       AUTHOR
    ------------------------------ */
    public function getAuthors()
    {
        $sql = "SELECT author AS label, COUNT(*) AS total
                FROM repository
                GROUP BY author
                ORDER BY author ASC";

        return $this->query($sql)->fetchAll();
    }

    public function getByAuthor(string $author)
    {
        $sql = "SELECT r.*, ps.nama_program_studi AS prodi
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                WHERE r.author = :auth
                ORDER BY r.created_at DESC";

        return $this->query($sql, ['auth' => $author])->fetchAll();
    }

    /* ---------------------------
       USER RELATIONS (repository_users)
    ------------------------------ */
    public function getUserRelations(int $repoId): array
    {
        $sql = "SELECT user_id, role_in_repo FROM repository_users WHERE repository_id = :rid";
        $rows = $this->query($sql, ['rid' => $repoId])->fetchAll();

        $authors   = [];
        $advisors  = [];
        $examiners = [];
        $owners    = [];

        foreach ($rows as $row) {
            $role = $row['role_in_repo'] ?? '';
            $uid  = (int)($row['user_id'] ?? 0);
            if (!$uid) continue;
            if ($role === 'author') $authors[] = $uid;
            elseif ($role === 'advisor') $advisors[] = $uid;
            elseif ($role === 'examiner') $examiners[] = $uid;
            elseif ($role === 'owner') $owners[] = $uid;
        }

        return [
            'authors'   => array_values(array_unique($authors)),
            'advisors'  => array_values(array_unique($advisors)),
            'examiners' => array_values(array_unique($examiners)),
            'owners'    => array_values(array_unique($owners)),
        ];
    }

    public function syncUserRelations(int $repoId, array $authors = [], array $advisors = [], array $examiners = [], ?int $ownerId = null): void
    {
        $authors   = array_unique(array_map('intval', $authors));
        $advisors  = array_unique(array_map('intval', $advisors));
        $examiners = array_unique(array_map('intval', $examiners));

        // Bersihkan dulu relasi
        $this->execute("DELETE FROM repository_users WHERE repository_id = :rid", ['rid' => $repoId]);

        // Owner (opsional)
        if ($ownerId) {
            $this->execute(
                "INSERT INTO repository_users (repository_id, user_id, role_in_repo) VALUES (:rid, :uid, 'owner')",
                ['rid' => $repoId, 'uid' => $ownerId]
            );
        }

        // Authors
        foreach ($authors as $uid) {
            $this->execute(
                "INSERT INTO repository_users (repository_id, user_id, role_in_repo) VALUES (:rid, :uid, 'author')",
                ['rid' => $repoId, 'uid' => $uid]
            );
        }

        // Advisors (pembimbing)
        foreach ($advisors as $uid) {
            $this->execute(
                "INSERT INTO repository_users (repository_id, user_id, role_in_repo) VALUES (:rid, :uid, 'advisor')",
                ['rid' => $repoId, 'uid' => $uid]
            );
        }

        // Examiners (penguji) - requires role_in_repo to support 'examiner'
        foreach ($examiners as $uid) {
            $this->execute(
                "INSERT INTO repository_users (repository_id, user_id, role_in_repo) VALUES (:rid, :uid, 'examiner')",
                ['rid' => $repoId, 'uid' => $uid]
            );
        }
    }

    /* ---------------------------
       JENIS KARYA
    ------------------------------ */
    public function getJenisKaryaList()
    {
        $sql = "SELECT jenis_karya AS label, COUNT(*) AS total
                FROM repository
                GROUP BY jenis_karya
                ORDER BY jenis_karya ASC";

        return $this->query($sql)->fetchAll();
    }

    public function getByJenisKarya(string $jenis)
    {
        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE r.jenis_karya = :jenis
                ORDER BY r.created_at DESC";

        return $this->query($sql, ['jenis' => $jenis])->fetchAll();
    }

    /* ---------------------------
       DETAIL
    ------------------------------ */
    public function getBySlug(string $slug)
    {
        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE r.slug = :slug
                LIMIT 1";

        return $this->query($sql, ['slug' => $slug])->fetch();
    }

    /* =======================================================
       ✨ MAHASISWA DASHBOARD (NEW)
    ========================================================== */

    public function getForMahasiswa(int $user_id, int $limit = 5)
    {
        $sql = "SELECT r.id, r.judul, r.author, r.tahun, r.slug, r.jenis_karya,
                       ps.nama_program_studi AS prodi,
                       mk.nama AS mata_kuliah,
                       ru.role_in_repo
                FROM repository r
                JOIN repository_users ru ON ru.repository_id = r.id
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE ru.user_id = :uid
                ORDER BY r.created_at DESC
                LIMIT :limit";

        return $this->query($sql, [
            'uid'   => $user_id,
            'limit' => $limit
        ])->fetchAll();
    }

    public function countByMahasiswa(int $user_id)
    {
        $sql = "SELECT COUNT(*) FROM repository_users WHERE user_id = :uid";
        return $this->query($sql, ['uid' => $user_id])->fetchColumn();
    }

    public function countByUser(int $userId): int
    {
        $sql = "SELECT COUNT(*) FROM repository_users WHERE user_id = :uid";
        return (int) $this->query($sql, ['uid' => $userId])->fetchColumn();
    }

    public function countBimbinganByAdvisor(int $userId): int
    {
        $sql = "SELECT COUNT(*) 
                FROM repository_users 
                WHERE user_id = :uid AND role_in_repo = 'advisor'";
        return (int)$this->query($sql, ['uid' => $userId])->fetchColumn();
    }

    public function getForUserDetailed(int $user_id, int $limit = 20): array
    {
        $sql = "SELECT r.*,
                       ps.nama_program_studi AS prodi,
                       mk.nama AS mata_kuliah,
                       ru.role_in_repo
                FROM repository r
                JOIN repository_users ru ON ru.repository_id = r.id
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE ru.user_id = :uid
                ORDER BY r.created_at DESC
                LIMIT :limit";

        return $this->query($sql, ['uid' => $user_id, 'limit' => $limit])->fetchAll();
    }

    /* ============================
       API FILTERED LIST + META
    ============================= */
    public function filter(array $filters, int $limit = 10, int $offset = 0): array
    {
        $params = [];
        $where  = $this->buildFilterClause($filters, $params);

        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                {$where}
                ORDER BY r.created_at DESC
                LIMIT :limit OFFSET :offset";

        $params['limit']  = $limit;
        $params['offset'] = $offset;

        return $this->query($sql, $params)->fetchAll();
    }

    public function countFiltered(array $filters): int
    {
        $params = [];
        $where  = $this->buildFilterClause($filters, $params);

        $sql = "SELECT COUNT(*) 
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                {$where}";
        return (int) $this->query($sql, $params)->fetchColumn();
    }

    public function filterByUser(int $userId, array $filters, int $limit = 10, int $offset = 0): array
    {
        $params = [];
        $where  = $this->buildFilterClause($filters, $params);
        $where .= " AND r.id IN (SELECT repository_id FROM repository_users WHERE user_id = :uid)";
        $params['uid'] = $userId;

        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                {$where}
                ORDER BY r.created_at DESC
                LIMIT :limit OFFSET :offset";

        $params['limit']  = $limit;
        $params['offset'] = $offset;

        return $this->query($sql, $params)->fetchAll();
    }

    public function countFilteredByUser(int $userId, array $filters): int
    {
        $params = [];
        $where  = $this->buildFilterClause($filters, $params);
        $where .= " AND r.id IN (SELECT repository_id FROM repository_users WHERE user_id = :uid)";
        $params['uid'] = $userId;

        $sql = "SELECT COUNT(*)
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                {$where}";
        return (int) $this->query($sql, $params)->fetchColumn();
    }

    private function buildFilterClause(array $filters, array &$params): string
    {
        $where = "WHERE 1=1";

        $keyword = trim($filters['q'] ?? '');
        if ($keyword !== '') {
            $params['q1'] = '%' . $keyword . '%';
            $params['q2'] = $params['q1'];
            $params['q3'] = $params['q1'];
            $params['q4'] = $params['q1'];
            $params['q5'] = $params['q1'];
            $params['q6'] = $params['q1'];
            $where .= " AND (r.judul LIKE :q1 
                        OR r.author LIKE :q2 
                        OR r.slug LIKE :q3 
                        OR ps.nama_program_studi LIKE :q4 
                        OR r.keywords LIKE :q5
                        OR mk.nama LIKE :q6)";
        }

        if (isset($filters['tahun']) && $filters['tahun'] !== '' && $filters['tahun'] !== null) {
            $params['tahun'] = (int) $filters['tahun'];
            $where .= " AND r.tahun = :tahun";
        }

        $prodiId = $filters['program_studi_id'] ?? ($filters['prodi'] ?? null);
        if ($prodiId !== '' && $prodiId !== null) {
            $params['ps'] = (int) $prodiId;
            $where .= " AND r.program_studi_id = :ps";
        }

        $mkId = $filters['mata_kuliah_id'] ?? ($filters['mk'] ?? null);
        if ($mkId !== '' && $mkId !== null) {
            $params['mk'] = (int) $mkId;
            $where .= " AND r.mata_kuliah_id = :mk";
        }

        $jenis = $filters['jenis_karya'] ?? ($filters['jenis'] ?? null);
        if ($jenis !== '' && $jenis !== null) {
            $params['jenis'] = $jenis;
            $where .= " AND r.jenis_karya = :jenis";
        }

        return $where;
    }

    public function getAllWithRelations(): array
    {
        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                ORDER BY r.created_at DESC";
        return $this->fetchAll($sql);
    }

    public function getFiltered(string $q = '', int $limit = 10, int $offset = 0): array
    {
        $params = [];
        $where  = "WHERE 1=1";

        if ($q !== '') {
            $params['q1'] = '%' . $q . '%';
            $params['q2'] = $params['q1'];
            $params['q3'] = $params['q1'];
            $params['q4'] = $params['q1'];
            $where .= " AND (r.judul LIKE :q1 OR r.author LIKE :q2 OR r.slug LIKE :q3 OR r.keywords LIKE :q4)";
        }

        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM repository r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                {$where}
                ORDER BY r.created_at DESC
                LIMIT :limit OFFSET :offset";

        $params['limit']  = $limit;
        $params['offset'] = $offset;

        return $this->fetchAll($sql, $params);
    }

    public function countFilteredSimple(string $q = ''): int
    {
        $params = [];
        $where  = "WHERE 1=1";

        if ($q !== '') {
            $params['q1'] = '%' . $q . '%';
            $params['q2'] = $params['q1'];
            $params['q3'] = $params['q1'];
            $params['q4'] = $params['q1'];
            $where .= " AND (r.judul LIKE :q1 OR r.author LIKE :q2 OR r.slug LIKE :q3 OR r.keywords LIKE :q4)";
        }

        $sql = "SELECT COUNT(*) FROM repository r {$where}";
        return (int) $this->query($sql, $params)->fetchColumn();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
                (judul, slug, jenis_karya, author, tahun, program_studi_id, mata_kuliah_id, abstrak, file_pdf, uploaded_by, keywords)
                VALUES
                (:judul, :slug, :jenis_karya, :author, :tahun, :program_studi_id, :mata_kuliah_id, :abstrak, :file_pdf, :uploaded_by, :keywords)";

        return $this->execute($sql, [
            'judul'            => $data['judul'],
            'slug'             => $data['slug'],
            'jenis_karya'      => $data['jenis_karya'],
            'author'           => $data['author'],
            'tahun'            => (int)$data['tahun'],
            'program_studi_id' => $data['program_studi_id'] ?: null,
            'mata_kuliah_id'   => $data['mata_kuliah_id'] ?: null,
            'abstrak'          => $data['abstrak'] ?? null,
            'file_pdf'         => $data['file_pdf'] ?? null,
            'uploaded_by'      => $data['uploaded_by'] ?: null,
            'keywords'         => $data['keywords'] ?? null,
        ]);
    }

    public function updateById(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table}
                SET judul = :judul,
                    slug = :slug,
                    jenis_karya = :jenis_karya,
                    author = :author,
                    tahun = :tahun,
                    program_studi_id = :program_studi_id,
                    mata_kuliah_id = :mata_kuliah_id,
                    abstrak = :abstrak,
                    file_pdf = :file_pdf,
                    uploaded_by = :uploaded_by,
                    keywords = :keywords
                WHERE id = :id";

        return $this->execute($sql, [
            'judul'            => $data['judul'],
            'slug'             => $data['slug'],
            'jenis_karya'      => $data['jenis_karya'],
            'author'           => $data['author'],
            'tahun'            => (int)$data['tahun'],
            'program_studi_id' => $data['program_studi_id'] ?: null,
            'mata_kuliah_id'   => $data['mata_kuliah_id'] ?: null,
            'abstrak'          => $data['abstrak'] ?? null,
            'file_pdf'         => $data['file_pdf'] ?? null,
            'uploaded_by'      => $data['uploaded_by'] ?: null,
            'keywords'         => $data['keywords'] ?? null,
            'id'               => $id,
        ]);
    }

    public function updateFile(int $id, string $fileName): bool
    {
        $sql = "UPDATE {$this->table} SET file_pdf = :file_pdf WHERE id = :id";
        return $this->execute($sql, ['file_pdf' => $fileName, 'id' => $id]);
    }

    public function deleteById(int $id): bool
    {
        return $this->execute("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT r.*, ps.nama_program_studi AS prodi, mk.nama AS mata_kuliah
                FROM {$this->table} r
                LEFT JOIN program_studi ps ON ps.id = r.program_studi_id
                LEFT JOIN mata_kuliah mk ON mk.id = r.mata_kuliah_id
                WHERE r.id = :id
                LIMIT 1";

        return $this->fetch($sql, ['id' => $id]);
    }

    public function getRecentActivities(int $limit = 5): array
    {
        $sql = "SELECT judul, author, 
                       created_at AS waktu
                FROM {$this->table}
                ORDER BY created_at DESC
                LIMIT :limit";

        $rows = $this->query($sql, ['limit' => $limit])->fetchAll();

        return array_map(static function ($row) {
            return [
                'actor'  => $row['author'] ?: 'Admin',
                'action' => 'Repository: ' . ($row['judul'] ?? ''),
                'time'   => $row['waktu'] ?? '',
            ];
        }, $rows);
    }

    public function getAllSlugs(): array
    {
        $sql = "SELECT slug, updated_at FROM {$this->table} ORDER BY updated_at DESC";
        return $this->query($sql)->fetchAll();
    }

    private function buildPublicWhere(array $filters, array &$params): string
    {
        $clauses = ["(r.file_pdf IS NOT NULL AND r.file_pdf <> '')"];

        if ($this->hasColumn('deleted_at')) {
            $clauses[] = "(r.deleted_at IS NULL OR r.deleted_at = '0000-00-00 00:00:00')";
        }

        if ($this->hasColumn('is_public')) {
            $clauses[] = "r.is_public = 1";
        }

        if ($this->hasColumn('is_private')) {
            $clauses[] = "(r.is_private = 0 OR r.is_private IS NULL)";
        }

        if ($this->hasColumn('visibility')) {
            $clauses[] = "(r.visibility IS NULL OR r.visibility NOT IN ('private','draft'))";
        }

        if ($this->hasColumn('status')) {
            $clauses[] = "(r.status IS NULL OR r.status NOT IN ('draft','private'))";
        }

        $keyword = trim($filters['q'] ?? '');
        if ($keyword !== '') {
            $params['q1'] = '%' . $keyword . '%';
            $params['q2'] = $params['q1'];
            $params['q3'] = $params['q1'];
            $clauses[] = "(r.judul LIKE :q1 OR r.author LIKE :q2 OR r.slug LIKE :q3)";
        }

        if (isset($filters['tahun']) && $filters['tahun'] !== '' && $filters['tahun'] !== null) {
            $params['tahun'] = (int) $filters['tahun'];
            $clauses[] = "r.tahun = :tahun";
        }

        if (!empty($filters['program_studi_id'])) {
            $params['ps'] = (int) $filters['program_studi_id'];
            $clauses[] = "r.program_studi_id = :ps";
        }

        return 'WHERE ' . implode(' AND ', $clauses);
    }

    private function attachOwner(int $repoId, int $userId): void
    {
        $this->execute(
            "INSERT INTO repository_users (repository_id, user_id, role_in_repo) VALUES (:rid, :uid, 'owner')",
            ['rid' => $repoId, 'uid' => $userId]
        );
    }

    public function isOwnedBy(int $repoId, int $userId): bool
    {
        $sql = "SELECT COUNT(*) FROM repository_users WHERE repository_id = :rid AND user_id = :uid";
        return (int) $this->query($sql, ['rid' => $repoId, 'uid' => $userId])->fetchColumn() > 0;
    }

    public function isPublicAccessible(array $repo): bool
    {
        return $this->isPublicRecord($repo);
    }

    private function hasColumn(string $column): bool
    {
        if (isset($this->columnCache[$column])) {
            return $this->columnCache[$column];
        }

        // MariaDB/MySQL kadang menolak placeholder pada SHOW COLUMNS, jadi rakit query aman dengan sanitasi.
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            return false;
        }

        $like = $this->db->quote($column);
        $stmt = $this->db->query("SHOW COLUMNS FROM {$this->table} LIKE {$like}");
        $exists = $stmt ? (bool) $stmt->fetch() : false;
        $this->columnCache[$column] = $exists;

        return $exists;
    }

    private function isPublicRecord(array $repo): bool
    {
        if (array_key_exists('deleted_at', $repo) && !empty($repo['deleted_at'])) {
            return false;
        }

        if (array_key_exists('is_public', $repo) && (int) $repo['is_public'] === 0) {
            return false;
        }

        if (array_key_exists('is_private', $repo) && (int) $repo['is_private'] === 1) {
            return false;
        }

        if (array_key_exists('visibility', $repo) && in_array($repo['visibility'], ['private', 'draft'], true)) {
            return false;
        }

        if (array_key_exists('status', $repo) && in_array($repo['status'], ['draft', 'private'], true)) {
            return false;
        }

        return true;
    }
}
