<?php
$thumb = $base_url . '/assets/img/repo-default.svg';

// Sapaan waktu konsisten dengan panel lain
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

$namaDosen = e($dosen['nama'] ?? 'Pengajar');
$waveIcon = '&#128075;'; // wave emoji entity
?>

<div class="space-y-6 animate-fade-in-up">
    
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
                            <?= $namaDosen ?> &middot; Dosen
                        </span>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800">
                        Dashboard Dosen
                    </h1>
                    <p class="text-slate-500 mt-2 max-w-xl text-sm leading-relaxed">
                        Kelola publikasi ilmiah Anda maupun repository hasil bimbingan mahasiswa di satu tempat.
                    </p>
                </div>
            </div>
            
            <div class="flex gap-3">
                 <a href="<?= $base_url ?>/dosen/repository/create" class="btn-cta inline-flex items-center justify-center px-5 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-transform active:scale-95 text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Upload Repository
                </a>
            </div>
        </div>
        
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wide">Repository Saya</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mt-1 group-hover:text-emerald-600 transition-colors">
                        <?= number_format($stats['repo_saya'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wide">Bookmark</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mt-1 group-hover:text-blue-600 transition-colors">
                        <?= number_format($stats['bookmark'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wide">Aktivitas</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mt-1 group-hover:text-purple-600 transition-colors">
                        <?= number_format($stats['aktivitas'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Bimbingan</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mt-1 group-hover:text-orange-600 transition-colors">
                        <?= number_format($stats['total_bimbingan'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-3 bg-orange-50 text-orange-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col h-full">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Repository Terbaru</h2>
                    <p class="text-xs text-slate-500">Karya mandiri & bimbingan</p>
                </div>
                <a href="<?= $base_url ?>/dosen/repository" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <?php if (empty($repos)): ?>
                <div class="flex-1 flex flex-col items-center justify-center text-slate-400 py-10 border-2 border-dashed border-slate-100 rounded-xl">
                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <p class="text-sm font-medium">Belum ada repository.</p>
                    <p class="text-xs text-slate-400 mt-1">Upload karya ilmiah atau tugas akhir mahasiswa.</p>
                    <a href="<?= $base_url ?>/dosen/repository/create" class="mt-4 px-4 py-2 bg-emerald-50 text-emerald-600 text-xs font-semibold rounded-lg hover:bg-emerald-100 transition-colors">
                        + Upload Baru
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($repos as $repo): ?>
                        <div class="group flex gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 relative">
                            <div class="w-14 h-14 md:w-16 md:h-16 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 relative">
                                <img src="<?= $thumb ?>" alt="Cover" class="w-full h-full object-cover p-2 opacity-80 group-hover:opacity-100 transition-opacity">
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100 whitespace-nowrap">
                                        <?= htmlspecialchars($repo['tahun']) ?>
                                    </span>
                                    <?php if (!empty($repo['prodi'])): ?>
                                        <span class="text-[10px] text-slate-400 truncate max-w-[100px]"><?= htmlspecialchars($repo['prodi']) ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="text-sm font-bold text-slate-800 line-clamp-1 group-hover:text-emerald-700 transition-colors">
                                    <a href="<?= $base_url; ?>/repository/<?= htmlspecialchars($repo['slug']) ?>" class="focus:outline-none">
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        <?= htmlspecialchars($repo['judul']) ?>
                                    </a>
                                </h3>
                                
                                <div class="flex items-center gap-1 mt-1.5 text-[11px] text-slate-500">
                                    <?php if (!empty($repo['mahasiswa'])): ?>
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span class="truncate">Mhs: <strong class="font-medium text-slate-700"><?= htmlspecialchars($repo['mahasiswa']) ?></strong></span>
                                    <?php else: ?>
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="truncate">Karya Mandiri (Dosen)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Bookmark</h2>
                        <p class="text-xs text-slate-500">Disimpan untuk dibaca nanti</p>
                    </div>
                    <a href="<?= $base_url ?>/dosen/bookmark" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">Kelola &rarr;</a>
                </div>

                <?php if (empty($bookmarks)): ?>
                    <div class="flex flex-col items-center justify-center text-slate-400 py-6">
                        <p class="text-xs">Belum ada bookmark.</p>
                        <a href="<?= $base_url ?>/telusuri" class="mt-1 text-xs text-emerald-600 hover:underline">Telusuri Repository</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach (array_slice($bookmarks, 0, 3) as $item): ?>
                            <div class="flex gap-3 items-start p-2 rounded-lg hover:bg-blue-50/50 transition-colors group">
                                <div class="w-10 h-10 rounded bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0 border border-blue-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-slate-800 truncate group-hover:text-blue-700 transition-colors">
                                        <a href="<?= $base_url ?>/repository/<?= htmlspecialchars($item['slug'] ?? '') ?>">
                                            <?= htmlspecialchars($item['judul'] ?? 'Tanpa Judul') ?>
                                        </a>
                                    </h4>
                                    <p class="text-[11px] text-slate-500 truncate">
                                        <?= htmlspecialchars($item['author'] ?? 'Penulis tidak diketahui') ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h2>
                        <p class="text-xs text-slate-500">Log interaksi sistem</p>
                    </div>
                    <a href="<?= $base_url ?>/dosen/activity" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Semua &rarr;</a>
                </div>
                
                <div class="flex-1 overflow-y-auto max-h-60 custom-scrollbar pr-1">
                    <?php if (!empty($activities)): ?>
                        <div class="relative pl-4 border-l border-slate-100 space-y-6 my-2">
                            <?php foreach ($activities as $act): ?>
                                <div class="relative group">
                                    <span class="absolute -left-[21px] top-1 h-3 w-3 rounded-full border-2 border-white bg-slate-300 group-hover:bg-emerald-500 transition-colors shadow-sm"></span>
                                    
                                    <div class="flex flex-col gap-0.5">
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs font-bold text-slate-700 group-hover:text-emerald-700 transition-colors">
                                                <?= htmlspecialchars($act['title'] ?? 'Aktivitas') ?>
                                            </p>
                                            <span class="text-[10px] text-slate-400 whitespace-nowrap bg-slate-50 px-1.5 py-0.5 rounded">
                                                <?= htmlspecialchars($act['time'] ?? '') ?>
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">
                                            <?= htmlspecialchars($act['desc'] ?? '-') ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center h-20 text-slate-400">
                            <p class="text-xs">Belum ada aktivitas tercatat.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
