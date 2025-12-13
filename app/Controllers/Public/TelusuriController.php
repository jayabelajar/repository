<?php

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Models\Repository;
use App\Models\ProgramStudi;
use App\Models\MataKuliah;

class TelusuriController extends Controller
{
    private Repository $repo;
    private ProgramStudi $prodi;
    private MataKuliah $mk;

    public function __construct()
    {
        $this->repo  = new Repository();
        $this->prodi = new ProgramStudi();
        $this->mk    = new MataKuliah();
    }

    public function index()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $filters = [
            'q'      => trim($_GET['q'] ?? ''),
            'tahun'  => trim($_GET['tahun'] ?? ''),
            'jenis'  => trim($_GET['jenis'] ?? ''),
            'prodi'  => trim($_GET['prodi'] ?? ''),
            'mk'     => trim($_GET['mk'] ?? ''),
            'author' => trim($_GET['author'] ?? ''),
        ];

        $keywordParts = array_filter($filters, static fn($v) => $v !== '');
        $keyword = implode(' ', $keywordParts);

        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 25;
        $offset  = ($page - 1) * $perPage;

        $filtersRepo = [
            'q'                => $filters['q'],
            'tahun'            => $filters['tahun'],
            'program_studi_id' => $filters['prodi'],
            'mata_kuliah_id'   => $filters['mk'],
            'jenis_karya'      => $filters['jenis'],
        ];

        $repositories = [];
        $total = 0;
        $pages = 1;

        if ($keyword !== '') {
            $repositories = $this->repo->filter($filtersRepo, $perPage, $offset);
            $total = $this->repo->countFiltered($filtersRepo);
            $pages = (int) max(1, ceil($total / $perPage));

            $qsParts = [];
            foreach ($filters as $k => $v) {
                if ($v !== '') {
                    $qsParts[$k] = $v;
                }
            }
            $queryString     = http_build_query($qsParts);
            $canonicalSearch = $base . '/telusuri' . ($queryString ? '?' . $queryString : '');

            $seo = [
                'title'       => 'Hasil Pencarian "' . $filters['q'] . '" | ' . $appName,
                'description' => 'Menampilkan hasil pencarian untuk "' . $filters['q'] . '" di ' . $appName . '.',
                'canonical'   => $canonicalSearch,
                'robots'      => 'noindex,follow'
            ];

            $this->view('public/telusuri/search', [
                'filters'      => $filters,
                'repositories' => $repositories,
                'total'        => $total,
                'page'         => $page,
                'pages'        => $pages,
                'per_page'     => $perPage,
                'seo'          => $seo,
            ]);
            return;
        }

        $seo = [
            'title'       => 'Telusuri Repository | ' . $appName,
            'description' => 'Jelajahi repository berdasarkan kata kunci, tahun, program studi, jenis karya, dan penulis.',
            'canonical'   => $base . '/telusuri',
        ];

        $this->view('public/telusuri', [
            'filters'      => $filters,
            'repositories' => $repositories,
            'total'        => $total,
            'seo'          => $seo,
        ]);
    }

    public function byYear()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $years = $this->repo->getAvailableYears();

        $this->view('public/telusuri/year', [
            'years' => $years,
            'seo'   => [
                'title'       => 'Telusuri Berdasarkan Tahun | ' . $appName,
                'description' => 'Pilih tahun publikasi untuk menelusuri repository di ' . $appName . '.',
                'canonical'   => $base . '/telusuri/year',
            ],
        ]);
    }

    public function byYearDetail($tahun)
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $items = $this->repo->getByYear((int)$tahun);

        $this->view('public/telusuri/results', [
            'title' => "Tahun $tahun",
            'items' => $items,
            'seo'   => [
                'title'       => "Repository Tahun $tahun | " . $appName,
                'description' => "Kumpulan repository tahun $tahun di $appName.",
                'canonical'   => $base . '/telusuri/year/' . rawurlencode((string) $tahun),
            ],
        ]);
    }

    public function byProgramStudi()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $items = $this->prodi->getAllWithCount();

        $this->view('public/telusuri/program_studi', [
            'items' => $items,
            'seo'   => [
                'title'       => 'Program Studi | ' . $appName,
                'description' => 'Jelajahi repository berdasarkan program studi di ' . $appName . '.',
                'canonical'   => $base . '/telusuri/program-studi',
            ],
        ]);
    }

    public function byProgramStudiDetail($slug)
    {
        // Langsung arahkan ke pencarian dengan kata kunci slug program studi
        $keyword = urldecode(str_replace('-', ' ', (string) $slug));
        $this->redirect('telusuri?q=' . urlencode($keyword));
    }

    public function byMataKuliah()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $items = $this->mk->getAll('', 'asc', 500, 0);

        $this->view('public/telusuri/mata_kuliah', [
            'items' => $items,
            'seo'   => [
                'title'       => 'Mata Kuliah | ' . $appName,
                'description' => 'Jelajahi repository berdasarkan mata kuliah di ' . $appName . '.',
                'canonical'   => $base . '/telusuri/mata-kuliah',
            ],
        ]);
    }

    public function byMataKuliahDetail($id)
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $items = $this->repo->getByMataKuliahId((int)$id);
        $title = $items[0]['mata_kuliah'] ?? 'Mata Kuliah';

        $this->view('public/telusuri/results', [
            'title' => $title,
            'items' => $items,
            'seo'   => [
                'title'       => $title . ' | ' . $appName,
                'description' => 'Daftar repository mata kuliah ' . $title . ' di ' . $appName . '.',
                'canonical'   => $base . '/telusuri/mata-kuliah/' . rawurlencode((string) $id),
            ],
        ]);
    }

    public function byAuthor()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $authors = $this->repo->getAuthors();

        $this->view('public/telusuri/author', [
            'authors' => $authors,
            'seo'     => [
                'title'       => 'Penulis | ' . $appName,
                'description' => 'Jelajahi repository berdasarkan penulis di ' . $appName . '.',
                'canonical'   => $base . '/telusuri/author',
            ],
        ]);
    }

    public function byAuthorDetail($name)
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $items = $this->repo->getByAuthor($name);
        $title = 'Penulis: ' . urldecode($name);

        $this->view('public/telusuri/results', [
            'title' => $title,
            'items' => $items,
            'seo'   => [
                'title'       => $title . ' | ' . $appName,
                'description' => 'Daftar repository oleh ' . urldecode($name) . ' di ' . $appName . '.',
                'canonical'   => $base . '/telusuri/author/' . rawurlencode($name),
                'robots'      => 'noindex,follow',
            ],
        ]);
    }

    public function jenisKarya()
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $items = $this->repo->getJenisKaryaList();

        $this->view('public/telusuri/jenis_karya', [
            'items' => $items,
            'seo'   => [
                'title'       => 'Jenis Karya | ' . $appName,
                'description' => 'Telusuri repository berdasarkan jenis karya di ' . $appName . '.',
                'canonical'   => $base . '/telusuri/jenis-karya',
            ],
        ]);
    }

    public function jenisKaryaDetail($jenis)
    {
        $config  = require __DIR__ . '/../../../config/config.php';
        $appName = $config['app_name'] ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
        $base    = rtrim($config['base_url'] ?? '', '/');

        $items = $this->repo->getByJenisKarya($jenis);
        $title = ucwords(str_replace('-', ' ', $jenis));

        $this->view('public/telusuri/results', [
            'jenis' => $jenis,
            'items' => $items,
            'seo'   => [
                'title'       => 'Jenis Karya ' . $title . ' | ' . $appName,
                'description' => 'Kumpulan repository dengan jenis karya ' . $title . ' di ' . $appName . '.',
                'canonical'   => $base . '/telusuri/jenis-karya/' . rawurlencode($jenis),
            ],
        ]);
    }
}
