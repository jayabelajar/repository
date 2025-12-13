<?php
$repoThumb = $base_url . '/assets/img/repo-default.svg';

// Sapaan waktu dengan ikon berbasis entity (ASCII-safe)
$jam = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('H');
if ($jam >= 5 && $jam < 12) {
    $sapaan = 'Selamat Pagi';
    $sapaanIcon = '&#9728;'; // Matahari terbit
} elseif ($jam >= 12 && $jam < 15) {
    $sapaan = 'Selamat Siang';
    $sapaanIcon = '&#9728;&#9729;'; // Cerah siang
} elseif ($jam >= 15 && $jam < 18) {
    $sapaan = 'Selamat Sore';
    $sapaanIcon = '&#9729;'; // Berawan sore
} else {
    $sapaan = 'Selamat Malam';
    $sapaanIcon = '&#9790;'; // Bulan malam
}
$waveIcon = '&#128075;'; // wave emoji entity
?>

<div class="space-y-6">
    
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
                            <?= e($mhs['nama'] ?? 'Mahasiswa Aktif'); ?> &middot; Mahasiswa
                        </span>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">
                        Dashboard Mahasiswa
                    </h1>
                    <p class="text-slate-500 mt-2 max-w-xl text-sm leading-relaxed">
                        Ringkasan repository dan aksi cepat untuk telusuri koleksi kami.
                    </p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <a href="<?= $base_url ?>/telusuri" class="btn-cta inline-flex items-center justify-center px-5 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-transform text-sm active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Telusuri Repository
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
        
        <a href="<?= $base_url; ?>/mahasiswa/my-repository" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-500 uppercase tracking-wide">Repository Saya</p>
                    <h3 class="text-2xl md:text-3xl font-extrabold text-slate-800 mt-1 group-hover:text-emerald-700 transition-colors">
                        <?= number_format($stats['repos'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-lg">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </a>
        
        <a href="<?= $base_url; ?>/mahasiswa/bookmarks" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] md:text-xs font-semibold text-slate-500 uppercase tracking-wide">Bookmarks</p>
                    <h3 class="text-2xl md:text-3xl font-extrabold text-slate-800 mt-1 group-hover:text-blue-600 transition-colors">
                        <?= number_format($stats['bookmarks'] ?? 0) ?>
                    </h3>
                </div>
                <div class="p-2.5 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        
        <section class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm lg:col-span-1">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Repository Terbaru</h2>
                    <p class="text-xs text-slate-500">Karya mandiri & bimbingan</p>
                </div>
                <a href="<?= $base_url; ?>/mahasiswa/my-repository" class="text-xs font-semibold text-emerald-600 hover:underline active:text-emerald-700 transition-colors">Lihat Semua &rarr;</a>
            </div>

            <?php if (empty($myRepos)): ?>
                <div class="py-6 text-center text-slate-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 19V5a2 2 0 012-2h4l2 2h4a2 2 0 012 2v14M4 19h16M4 19a2 2 0 002 2h12a2 2 0 002-2M10 9h4"></path></svg>
                    <p class="text-sm">Belum ada repository yang terdaftar.</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach (array_slice($myRepos, 0, 4) as $repo): ?>
                        <article class="flex gap-3 p-3 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 relative group">
                            <div class="w-14 h-14 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0">
                                <img src="<?= $repoThumb; ?>" alt="Thumbnail Repository" class="w-full h-full object-cover p-2">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100 whitespace-nowrap"><?= e($repo['tahun'] ?? '-') ?></span>
                                    <?php if (!empty($repo['prodi'])): ?>
                                        <span class="text-[10px] text-slate-500 truncate max-w-[100px]"><?= e($repo['prodi']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 line-clamp-2 leading-tight group-hover:text-emerald-700 transition-colors">
                                    <a href="<?= $base_url; ?>/repository/<?= e($repo['slug']) ?>" class="focus:outline-none">
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        <?= e($repo['judul']) ?>
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 mt-1"><?= e($repo['role_in_repo'] ?? 'Penulis Utama') ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 md:gap-6 lg:col-span-2">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Bookmark</h2>
                        <p class="text-xs text-slate-500">Disimpan untuk dibaca nanti</p>
                    </div>
                    <a href="<?= $base_url ?>/mahasiswa/bookmarks" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">Kelola &rarr;</a>
                </div>

                <?php if (empty($bookmarks)): ?>
                    <div class="flex flex-col items-center justify-center text-slate-400 py-6">
                        <p class="text-sm">Belum ada bookmark.</p>
                        <a href="<?= $base_url ?>/telusuri" class="mt-1 text-sm font-semibold text-emerald-600 hover:underline">Telusuri Repository</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach (array_slice($bookmarks, 0, 3) as $item): ?>
                            <a href="<?= $base_url ?>/repository/<?= e($item['slug'] ?? '') ?>" class="flex gap-3 items-start p-3 rounded-xl hover:bg-blue-50/50 transition-colors group relative">
                                <span class="absolute inset-0"></span>
                                <div class="w-9 h-9 rounded bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0 border border-blue-100 dark:bg-[#1e293b] dark:text-blue-100 dark:border-[#1e3a8a]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-slate-800 line-clamp-2 group-hover:text-blue-700 transition-colors leading-tight">
                                        <?= e($item['judul'] ?? 'Tanpa Judul') ?>
                                    </h4>
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                        <?= e($item['author'] ?? 'Penulis tidak diketahui') ?>
                                    </p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h2>
                        <p class="text-xs text-slate-500">Log interaksi Anda</p>
                    </div>
                    <a href="<?= $base_url ?>/mahasiswa/activity" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Semua &rarr;</a>
                </div>
                
                <div class="flex-1 overflow-y-auto max-h-60 custom-scrollbar pr-1">
                    <?php if (empty($activities)): ?>
                        <div class="flex flex-col items-center justify-center h-20 text-slate-400 py-4">
                            <p class="text-sm">Tidak ada aktivitas terbaru.</p>
                            <p class="text-xs text-slate-400 mt-1">Saatnya beraksi!</p>
                        </div>
                    <?php else: ?>
                        <div class="relative pl-4 border-l border-slate-100 space-y-5 my-2">
                            <?php foreach (array_slice($activities, 0, 4) as $act): ?>
                                <div class="relative group">
                                    <span class="absolute -left-[21px] top-1.5 h-3 w-3 rounded-full border-2 border-white bg-slate-300 shadow-sm"></span>
                                    
                                    <div class="flex flex-col gap-0.5">
                                        <div class="flex items-start justify-between">
                                            <p class="text-xs font-bold text-slate-700 leading-tight">
                                                <?= e($act['title'] ?? 'Aktivitas Baru') ?>
                                            </p>
                                            <span class="text-[10px] text-slate-400 whitespace-nowrap bg-slate-50 px-1.5 py-0.5 rounded flex-shrink-0 ml-2">
                                                <?= e($act['time'] ?? '-') ?>
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-snug">
                                            <?= e($act['desc'] ?? '-') ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
