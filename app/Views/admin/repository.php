<?php $base = rtrim($base_url, '/'); ?>

<div class="space-y-4 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight leading-tight">Repository</h1>
            <p class="text-sm text-slate-500">Kelola skripsi, jurnal, dan karya ilmiah mahasiswa.</p>
        </div>
        <a href="<?= $base ?>/admin/repository/create" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-600/20 transition-all active:scale-95 w-full md:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Data</span>
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            <div class="lg:col-span-2 relative group">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="search" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Cari judul atau penulis..." 
                       class="pl-10 pr-4 py-2 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
            </div>

            <div>
                <select name="tahun" onchange="this.form.submit()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer text-slate-600">
                    <option value="">Semua Tahun</option>
                    <?php 
                    $currentYear = date('Y');
                    for($i = $currentYear; $i >= $currentYear - 10; $i--): ?>
                        <option value="<?= $i ?>" <?= (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div>
                <select name="prodi" onchange="this.form.submit()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer text-slate-600">
                    <option value="">Semua Prodi</option>
                    <?php foreach ($prodis as $p): ?>
                        <option value="<?= e($p['id']) ?>" <?= (isset($_GET['prodi']) && $_GET['prodi'] == $p['id']) ? 'selected' : '' ?>><?= e($p['nama_program_studi']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <select name="jenis" onchange="this.form.submit()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer text-slate-600">
                    <option value="">Semua Jenis</option>
                    <?php foreach (['skripsi','tugas_akhir','jurnal','artikel','laporan','pkl','lainnya'] as $jk): ?>
                        <option value="<?= $jk ?>" <?= (isset($_GET['jenis']) && $_GET['jenis'] == $jk) ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ', $jk)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase tracking-wider text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4 min-w-[250px]">Judul & Jenis</th>
                        <th class="px-6 py-4 whitespace-nowrap">Penulis / Tahun</th>
                        <th class="px-6 py-4 min-w-[200px]">Prodi & MK</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4 align-top">
                                    <div class="flex flex-col gap-1.5">
                                        <a href="<?= $base ?>/repository/<?= $item['slug'] ?>" target="_blank" class="text-slate-900 font-semibold line-clamp-2 leading-snug hover:text-emerald-600 transition-colors">
                                            <?= e($item['judul'] ?? '') ?>
                                        </a>
                                        <?php 
                                            $jenis = strtolower($item['jenis_karya'] ?? 'lainnya');
                                            $badgeColor = match($jenis) {
                                                'skripsi' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'tugas_akhir' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                'jurnal' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                'pkl' => 'bg-orange-50 text-orange-600 border-orange-100',
                                                default => 'bg-slate-100 text-slate-600 border-slate-200'
                                            };
                                        ?>
                                        <span class="inline-flex w-fit px-2 py-0.5 rounded text-[10px] font-bold uppercase border <?= $badgeColor ?>">
                                            <?= e(str_replace('_', ' ', $jenis)) ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    <div class="text-slate-700 font-medium"><?= e($item['author'] ?? '-') ?></div>
                                    <div class="text-slate-400 text-xs mt-0.5 flex items-center gap-1 dark:text-slate-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <?= e($item['tahun'] ?? '-') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="text-slate-600 text-xs space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            <?= e($item['prodi'] ?? '-') ?>
                                        </div>
                                        <?php if(!empty($item['mata_kuliah'])): ?>
                                        <div class="flex items-center gap-1.5 pl-3 border-l border-slate-200 ml-0.5">
                                            <span class="text-slate-400">MK:</span>
                                            <?= e($item['mata_kuliah']) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1">

                                        <a href="<?= $base ?>/repository/<?= $item['slug'] ?>" target="_blank" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors border border-transparent hover:border-blue-100 dark:text-slate-200" title="Lihat Postingan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>

                                        <a href="<?= $base ?>/admin/repository/<?= $item['id'] ?>/edit" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors border border-transparent hover:border-emerald-100" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        
                                        <form action="<?= $base ?>/admin/repository/<?= $item['id'] ?>/delete" method="POST" onsubmit="return confirm('Hapus data ini secara permanen?')">
                                            <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                                            <button class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors border border-transparent hover:border-red-100 dark:text-slate-200" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span class="text-sm">Tidak ada data ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (($pages ?? 1) > 1): ?>
            <?php
                $currentPage = $page ?? 1;
                $totalPages  = $pages ?? 1;
                $queryParams = $_GET;
                unset($queryParams['page']);
            ?>
            <div class="p-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-600">
                <span>Halaman <?= $currentPage ?> dari <?= $totalPages ?></span>
                <div class="flex gap-1">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <?php $queryParams['page'] = $p; $qs = http_build_query($queryParams); ?>
                        <a href="?<?= $qs ?>"
                           class="px-3 py-1 rounded-lg border <?= $p === $currentPage ? 'bg-emerald-600 text-white border-emerald-600' : 'border-slate-200 hover:bg-slate-50 text-slate-700' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
