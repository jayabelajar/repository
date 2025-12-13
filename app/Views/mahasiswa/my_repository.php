<?php $base = rtrim($base_url, '/'); ?>

<div class="space-y-4 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight leading-tight">Repository</h1>
            <p class="text-sm text-slate-500">Daftar repository yang melibatkan Anda.</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white border border-slate-200 rounded-2xl shadow-sm p-4">
        <form method="GET" class="w-full sm:w-auto flex gap-2 items-center">
            <div class="relative flex-1 min-w-[220px]">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="search" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Cari judul / penulis / prodi..." class="pl-9 pr-3 py-2 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
            </div>
            <button type="submit" class="px-3 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700">Cari</button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <?php if (empty($repos)): ?>
            <div class="px-6 py-10 text-center text-slate-400">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-sm">Tidak ada data ditemukan.</span>
                </div>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase tracking-wider text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-4 min-w-[250px]">Judul & Jenis</th>
                            <th class="px-6 py-4 whitespace-nowrap">Peran / Tahun</th>
                            <th class="px-6 py-4 min-w-[200px]">Prodi & MK</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($repos as $item): ?>
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
                                    <div class="text-slate-700 font-medium"><?= e($item['role_in_repo'] ?? '-') ?></div>
                                    <div class="text-slate-400 text-xs mt-0.5 flex items-center gap-1">
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

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
