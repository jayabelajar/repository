<?php
$base = rtrim($base_url, '/');
$csrfBookmark = \App\Core\Security\Csrf::token();
?>

<div class="max-w-7xl mx-auto space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 border-b border-slate-100 pb-4">
        
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight leading-tight">Bookmarks</h1>
            <p class="text-sm text-slate-500">Koleksi repository yang Anda simpan.</p>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">
            <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                <?= count($bookmarks ?? []) ?> Item
            </span>
            <a href="<?= $base ?>/telusuri/" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari Repository
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden min-h-[400px] flex flex-col">
        
        <?php if (empty($bookmarks)): ?>
            <div class="flex-1 flex flex-col items-center justify-center text-slate-400 py-12 p-6">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
                <h3 class="text-slate-800 font-bold text-lg">Belum ada bookmark</h3>
                <p class="text-sm max-w-xs text-center mt-1 text-slate-500">
                    Anda belum menyimpan repository apapun.
                </p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-slate-100">
                <?php foreach ($bookmarks as $item): ?>
                    <div class="group flex flex-col sm:flex-row gap-3 sm:gap-4 p-4 md:p-5 hover:bg-slate-50 transition-colors relative">
                        
                        <div class="flex-1 min-w-0 flex flex-row items-start gap-3 sm:gap-4">
                            
                            <div class="flex-shrink-0 pt-1"> 
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0 flex flex-col justify-center gap-1">
                                
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 flex-shrink-0">
                                        <?= e($item['tahun'] ?? '-') ?>
                                    </span>
                                    <?php if (!empty($item['prodi'])): ?>
                                        <span class="text-[10px] text-slate-500 font-medium px-2 py-0.5 bg-slate-100 rounded border border-slate-200 truncate">
                                            <?= e($item['prodi']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="text-sm md:text-base font-bold text-slate-800 line-clamp-2 group-hover:text-emerald-700 transition-colors">
                                    <a href="<?= $base ?>/repository/<?= e($item['slug'] ?? '') ?>" class="focus:outline-none">
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        <?= e($item['judul'] ?? 'Tanpa Judul') ?>
                                    </a>
                                </h3>
                                
                                <p class="text-xs text-slate-500 line-clamp-1">
                                    Penulis: <span class="font-medium text-slate-700"><?= e($item['author'] ?? 'Tidak diketahui') ?></span>
                                </p>
                            </div>
                        </div>

                        <div class="sm:hidden flex items-center gap-4 mt-2 pt-2 border-t border-slate-100 flex-shrink-0">
                            <a href="<?= $base ?>/repository/<?= e($item['slug'] ?? '') ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:underline">
                                Lihat Detail &rarr;
                            </a>
                            <form action="<?= $base ?>/bookmark/<?= e($item['slug'] ?? '') ?>/toggle" method="POST" onsubmit="return confirm('Hapus bookmark ini?')" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= e($csrfBookmark) ?>">
                                <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>

                        <div class="hidden sm:flex items-center gap-1 flex-shrink-0">
                            <a href="<?= $base ?>/repository/<?= e($item['slug'] ?? '') ?>" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all shadow-sm" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            
                            <form action="<?= $base ?>/bookmark/<?= e($item['slug'] ?? '') ?>/toggle" method="POST" onsubmit="return confirm('Hapus bookmark ini?')" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= e($csrfBookmark) ?>">
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all shadow-sm" title="Hapus Bookmark">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
