<?php $base = rtrim($base_url, '/'); ?>

<div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="space-y-1">
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-tight">Aktivitas Saya</h1>
            <p class="text-sm text-slate-500">Rekam jejak interaksi dan kegiatan akun Anda.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden flex flex-col">
        
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-6 py-4 w-[180px] whitespace-nowrap">Waktu</th>
                        <th class="px-6 py-4 w-[200px]">Aktivitas</th>
                        <th class="px-6 py-4">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr class="group hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-slate-500 text-xs whitespace-nowrap align-top">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <?= e($log['created_at'] ?? '') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/50">
                                        <?= e($log['activity_type'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 leading-relaxed align-top">
                                    <?= e($log['description'] ?? '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-sm">Belum ada aktivitas tercatat.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (($total_pages ?? 1) > 1): ?>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm">
                <span class="text-slate-500">
                    Halaman <span class="font-bold text-slate-700"><?= $page ?></span> dari <span class="font-bold text-slate-700"><?= $total_pages ?></span>
                </span>
                
                <div class="flex gap-1.5">
                    <?php 
                        // Logic simple pagination agar tidak terlalu panjang
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $page + 2);
                        
                        if ($page > 1): ?>
                            <a href="<?= $base ?>/dosen/activity?page=<?= $page - 1 ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-emerald-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </a>
                        <?php endif;
                    
                        for ($p = $start; $p <= $end; $p++): 
                    ?>
                        <a href="<?= $base ?>/dosen/activity?page=<?= $p ?>" 
                           class="w-8 h-8 flex items-center justify-center rounded-lg border transition-all font-semibold text-xs
                           <?= $p === $page 
                               ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' 
                               : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-emerald-200 hover:text-emerald-600' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; 
                    
                        if ($page < $total_pages): ?>
                            <a href="<?= $base ?>/dosen/activity?page=<?= $page + 1 ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-emerald-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
