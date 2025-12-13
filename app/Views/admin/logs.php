<?php
use App\Core\Security\Csrf;
$base = rtrim($base_url, '/');
$resetStatus = $_GET['reset'] ?? null;
$deleted = (int)($_GET['deleted'] ?? 0);
?>

<div class="space-y-4 animate-fade-in-up">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight leading-tight">Log Aktivitas</h1>
            <p class="text-sm text-slate-500">Rekaman aktivitas sistem terbaru.</p>
        </div>
        <form method="POST" action="<?= $base ?>/admin/logs/reset-old" onsubmit="return confirm('Hapus log yang lebih dari 3 bulan?')" class="w-full md:w-auto">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token ?? Csrf::token()) ?>">
            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-200 text-red-600 text-sm font-semibold hover:bg-red-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Reset log > 3 bulan
            </button>
        </form>
    </div>

    <?php if ($resetStatus === 'ok'): ?>
        <div class="text-sm px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100">
            Berhasil hapus <?= $deleted ?> log yang lebih tua dari 3 bulan.
        </div>
    <?php elseif ($resetStatus === 'csrf'): ?>
        <div class="text-sm px-4 py-3 rounded-xl bg-red-50 text-red-600 border border-red-100">
            Token tidak valid, ulangi tindakan reset log.
        </div>
    <?php endif; ?>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase tracking-wider text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3 w-[140px]">Waktu</th>
                        <th class="px-6 py-3">Pengguna</th>
                        <th class="px-6 py-3">Aktivitas</th>
                        <th class="px-6 py-3">Referensi</th>
                        <th class="px-6 py-3">IP</th>
                        <th class="px-6 py-3">Lokasi (Lat,Long)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700 text-xs md:text-sm">
                                    <?= e($log['created_at'] ?? '') ?>
                                </td>
                                <td class="px-6 py-3 text-slate-800">
                                    <?= e($log['nama_lengkap'] ?? 'System') ?>
                                </td>
                                <td class="px-6 py-3 text-slate-700">
                                    <div class="font-semibold text-slate-800"><?= e($log['activity_type'] ?? '-') ?></div>
                                    <div class="text-slate-500 text-xs"><?= e($log['description'] ?? '') ?></div>
                                </td>
                                <td class="px-6 py-3 text-slate-600">
                                    <?php if (!empty($log['reference_table'])): ?>
                                        <?= e($log['reference_table']) ?> #<?= e($log['reference_id'] ?? '-') ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-3 text-slate-600 whitespace-nowrap">
                                    <?= e($log['ip_address'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-3 text-slate-600 whitespace-nowrap">
                                    <?php if (!empty($log['latitude']) && !empty($log['longitude'])): ?>
                                        <a href="https://www.google.com/maps?q=<?= urlencode($log['latitude']) ?>,<?= urlencode($log['longitude']) ?>" target="_blank" class="text-emerald-600 hover:text-emerald-700 underline">
                                            Lihat Lokasi
                                        </a>
                                        <div class="text-xs text-slate-500">
                                            <?= e($log['latitude']) ?>, <?= e($log['longitude']) ?>
                                        </div>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-sm">Belum ada log.</span>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (($show_pagination ?? false) && ($total_pages ?? 1) > 1): ?>
            <div class="p-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-600">
                <div>Halaman <?= $page ?> dari <?= $total_pages ?></div>
                <div class="flex gap-1">
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                        <a href="<?= $base ?>/admin/logs?page=<?= $p ?>"
                           class="px-3 py-1 rounded-lg border <?= $p === $page ? 'bg-emerald-600 text-white border-emerald-600' : 'border-slate-200 hover:bg-slate-50 text-slate-700' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
