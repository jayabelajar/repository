<?php

namespace App\Controllers\Api;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\Repository;

class LookupApiController extends BaseApiController
{
    public function index(): void
    {
        $repo  = new Repository();
        $prodi = new ProgramStudi();
        $mk    = new MataKuliah();

        $this->success([
            'program_studi' => $prodi->getAllWithCount(),
            'mata_kuliah'   => $mk->getAll(),
            'years'         => $repo->getAvailableYears(),
            'jenis_karya'   => $repo->getJenisKaryaList(),
            'authors'       => $repo->getAuthors(),
        ]);
    }
}
