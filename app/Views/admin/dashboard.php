<?php 

$cy_labels = $chart_year_labels ?? [];
$cy_data   = $chart_year_data ?? [];
$cj_labels = $chart_jenis_labels ?? [];
$cj_data   = $chart_jenis_data ?? [];

$jam = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('H');
if ($jam >= 5 && $jam < 12) {
    $sapaan = 'Selamat Pagi';
    $sapaanIcon = '&#9728;';
} elseif ($jam >= 12 && $jam < 15) {
    $sapaan = 'Selamat Siang';
    $sapaanIcon = '&#9728;&#9729;';
} elseif ($jam >= 15 && $jam < 18) {
    $sapaan = 'Selamat Sore';
    $sapaanIcon = '&#9729;';
} else {
    $sapaan = 'Selamat Malam';
    $sapaanIcon = '&#9790;';
}

$adminName = e($admin['nama'] ?? 'Admin');
$waveIcon = '&#128075;'; // wave emoji as entity to keep ASCII-safe

?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-4 md:space-y-6 animate-fade-in-up -mt-6">
    
    <div class="relative bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-lg overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-0">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg shadow-inner">
                        <?= $sapaanIcon ?>
                    </div>
                    <div class="flex flex-col gap-1 leading-tight">
                        <p class="text-xs md:text-sm font-semibold text-emerald-700 flex items-center gap-1">
                            <span><?= $sapaan ?></span>
                            <span aria-hidden="true"><?= $waveIcon ?></span>
                        </p>
                        <span class="inline-flex items-center text-[11px] md:text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full leading-none w-fit">
                            <?= $adminName ?> &middot; Admin
                        </span>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800">Dashboard Admin</h1>
                    <p class="text-slate-500 mt-2 max-w-xl text-sm leading-relaxed">
                        Selamat datang di panel kontrol <strong>SIREPO</strong>. Pantau statistik repository dan aktivitas sistem secara real-time.
                    </p>
                </div>
            </div>
            <a href="<?= rtrim($base_url, '/') ?>/admin/repository/create" class="btn-cta inline-flex items-center justify-center w-full md:w-auto px-5 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-transform active:scale-95 text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Upload Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Repository</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mt-1 group-hover:text-emerald-600 transition-colors">
                        <?= number_format($stats['total_repository'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wide">Program Studi</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mt-1 group-hover:text-blue-600 transition-colors">
                        <?= number_format($stats['total_prodi'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wide">Mata Kuliah</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mt-1 group-hover:text-purple-600 transition-colors">
                        <?= number_format($stats['total_mk'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Pengguna</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mt-1 group-hover:text-orange-600 transition-colors">
                        <?= number_format($stats['total_user'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-3 bg-orange-50 text-orange-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        
        <div class="lg:col-span-2 space-y-4 md:space-y-6 flex flex-col">
            
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-sm w-full">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Statistik Repository per Tahun</h2>
                        <p class="text-xs text-slate-500">Tren jumlah repository</p>
                    </div>
                </div>
                <div class="relative h-60 md:h-72 w-full">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 h-full">
                
                <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-sm h-full flex flex-col">
                    <h2 class="text-lg font-bold text-slate-800 mb-2">Sebaran Jenis Karya</h2>
                    <p class="text-xs text-slate-500 mb-4">Proporsi tipe dokumen</p>
                    <div class="relative h-48 flex justify-center mt-auto mb-auto">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col h-96 lg:hidden">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h2>
                            <p class="text-xs text-slate-500">Log sistem real-time</p>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto p-0 custom-scrollbar">
                         <?php if (!empty($activities)): ?>
                            <div class="divide-y divide-slate-50">
                                <?php foreach (array_slice($activities, 0, 5) as $act): ?>
                                    <div class="p-4 hover:bg-slate-50 transition-colors flex gap-3 group">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 text-emerald-600 font-bold text-[10px] flex items-center justify-center border border-slate-200 group-hover:bg-emerald-100 group-hover:text-emerald-700 transition-colors">
                                                <?= strtoupper(substr($act['actor'] ?? '?', 0, 1)) ?>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <p class="text-xs font-semibold text-slate-800 truncate">
                                                    <?= e($act['actor'] ?? 'System') ?>
                                                </p>
                                                <span class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded ml-2 whitespace-nowrap">
                                                    <?= e($act['time'] ?? 'Just now') ?>
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">
                                                <?= e($act['action'] ?? '-') ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="flex flex-col items-center justify-center h-full text-slate-400">
                                <p class="text-sm">Belum ada aktivitas.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                     <div class="p-4 border-t border-slate-100 flex justify-between text-xs text-slate-500 flex-shrink-0">
                        <span></span>
                        <a href="<?= rtrim($base_url, '/') ?>/admin/logs" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Semua Log &rarr;</a>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-5 md:p-6 rounded-2xl shadow-sm text-white flex flex-col justify-between relative overflow-hidden h-full">
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold">Butuh Bantuan?</h3>
                        <p class="text-slate-300 text-sm mt-2 leading-relaxed">
                            Hubungi tim Developer jika ada kendala pada sistem ini.
                        </p>
                    </div>
                    <div class="relative z-10 mt-6">
                        <a href="https://wa.me/628285859400250" target="_blank" rel="noopener noreferrer"
                        class="text-xs font-semibold text-white bg-white/20 hover:bg-white/30 px-4 py-3 rounded-lg transition-colors w-full block text-center">
                            Hubungi Tim Developer
                        </a>
                    </div>

                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-emerald-500 rounded-full blur-3xl opacity-20"></div>
                </div>

            </div>
        </div>

        <div class="hidden lg:flex bg-white rounded-2xl border border-slate-200 shadow-sm flex-col lg:h-[43.5rem]">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h2>
                    <p class="text-xs text-slate-500">Log sistem real-time</p>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-0 custom-scrollbar">
                <?php if (!empty($activities)): ?>
                    <div class="divide-y divide-slate-50">
                        <?php foreach ($activities as $act): ?>
                            <div class="p-4 hover:bg-slate-50 transition-colors flex gap-3 group">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 font-bold text-xs flex items-center justify-center border-2 border-white shadow-sm group-hover:bg-emerald-100 group-hover:text-emerald-700 transition-colors">
                                        <?= strtoupper(substr($act['actor'] ?? '?', 0, 1)) ?>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm font-semibold text-slate-800 truncate">
                                            <?= e($act['actor'] ?? 'System') ?>
                                        </p>
                                        <span class="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded ml-2 whitespace-nowrap">
                                            <?= e($act['time'] ?? 'Just now') ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5 line-clamp-2 leading-relaxed">
                                        <?= e($act['action'] ?? '-') ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center h-full text-slate-400">
                        <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm">Belum ada aktivitas.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="p-4 border-t border-slate-100 flex justify-between text-xs text-slate-500 flex-shrink-0">
                <span></span>
                <a href="<?= rtrim($base_url, '/') ?>/admin/logs" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Semua Log &rarr;</a>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. LINE CHART ---
        const ctxLine = document.getElementById('lineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: <?= json_encode($cy_labels) ?>, 
                datasets: [{
                    label: 'Jumlah Repository',
                    data: <?= json_encode($cy_data) ?>, 
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.15)',
                    fill: true,
                    tension: 0.25,
                    pointRadius: 4,
                    pointBackgroundColor: '#059669',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f1f5f9' }, ticks: { font: { size: 11 }, precision: 0 } },
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                }
            }
        });

        // --- 2. PIE CHART ---
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($cj_labels) ?>,
                datasets: [{
                    data: <?= json_encode($cj_data) ?>,
                    backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ec4899', '#64748b'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'right', 
                        labels: { usePointStyle: true, boxWidth: 8, padding: 15, font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" } }
                    }
                }
            }
        });
    });
</script>
