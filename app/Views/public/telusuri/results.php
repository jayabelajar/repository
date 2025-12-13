<?php
// --- LOGIKA PHP (TIDAK BERUBAH) ---
$heading = $title ?? 'Hasil Pencarian';
$items = $items ?? [];
$repoThumb = $base_url . '/assets/img/repo-default.svg';
$total = isset($total) ? (int) $total : count($items);
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8 bg-slate-50 min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight"><?= e($heading) ?></h1>
            <p class="text-sm text-slate-500 mt-2 font-medium">Menampilkan arsip digital berdasarkan preferensi Anda.</p>
        </div>

        <?php if ($total): ?>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full border border-slate-200 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <p class="text-xs font-semibold text-slate-700">
                    <?= $total ?> Dokumen Ditemukan
                </p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($items)): ?>
        <div class="flex flex-col items-center justify-center py-16 bg-white border border-dashed border-slate-300 rounded-3xl text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h3 class="text-slate-900 font-semibold mb-1">Tidak ada hasil ditemukan</h3>
            <p class="text-sm text-slate-500">Coba gunakan kata kunci lain atau kurangi filter pencarian Anda.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-6 md:grid-cols-2">
            <?php foreach ($items as $item): ?>
                <article class="group relative bg-white border border-slate-200 rounded-2xl p-5 hover:border-emerald-400 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 flex flex-col sm:flex-row gap-5">
                    
                    <div class="w-full sm:w-32 h-40 sm:h-auto rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex-shrink-0 relative">
                        <img src="<?= $repoThumb; ?>" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-2 left-2 px-2 py-1 bg-white/90 backdrop-blur-sm rounded-md border border-slate-200 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-800 leading-none">
                                <?= e($item['tahun'] ?? '-') ?>
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col min-w-0">
                        
                        <div class="flex items-center gap-2 mb-2">
                            <?php if (!empty($item['prodi'] ?? $item['nama_program_studi'] ?? null)): ?>
                                <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-bold uppercase tracking-wide">
                                    <?= e($item['prodi'] ?? $item['nama_program_studi']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h2 class="text-base font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-emerald-700 transition-colors mb-2">
                            <?= e($item['judul'] ?? '-') ?>
                        </h2>

                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4 flex-1">
                            <?= e($item['abstrak'] ?? 'Tidak ada ringkasan tersedia.') ?>
                        </p>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-auto">
                            <div class="flex items-center gap-4 text-xs text-slate-500 font-medium">
                                <div class="flex items-center gap-1.5 max-w-[120px]">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="truncate"><?= e($item['author'] ?? '-') ?></span>
                                </div>
                                
                                <?php if (!empty($item['mata_kuliah'] ?? null)): ?>
                                    <div class="hidden sm:flex items-center gap-1.5 max-w-[120px]">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        <span class="truncate"><?= e($item['mata_kuliah']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($item['slug'])): ?>
                                <a href="<?= $base_url; ?>/repository/<?= e($item['slug']) ?>" class="absolute inset-0 z-10 focus:outline-none">
                                    <span class="sr-only">Lihat detail</span>
                                </a>
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
