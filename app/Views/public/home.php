<?php $base = rtrim($base_url ?? '', '/'); ?>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-12 sm:pb-16">
    <div class="grid md:grid-cols-2 gap-12 items-center">
        
        <div class="space-y-4">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-slate-900 leading-tight">
                Sistem Informasi Repository Institut Agama Islam Hasan Jufri
            </h1>

            <p class="text-slate-600 text-base sm:text-lg">
                Temukan koleksi lengkap penelitian, skripsi, dan sumber akademik lainnya dalam platform repository digital yang terstruktur dan mudah diakses.
            </p>

            <div class="pt-4 flex flex-wrap gap-3">
                <a href="<?= $base ?>/telusuri/"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Telusuri Koleksi
                </a>

                <a href="<?= $base ?>/download"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-slate-300 text-sm text-slate-700 font-semibold hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download App
                </a>
            </div>
        </div>

        <div class="relative hidden md:flex items-center justify-center">
            <div class="w-full h-80 bg-emerald-50 rounded-3xl absolute opacity-50"></div>
            
            <div class="relative bg-white border border-slate-200 rounded-2xl p-6 w-11/12 shadow-lg">
                <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.247m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.247"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">5 Publikasi Terbaru</p>
                        <p class="text-xs text-slate-500">Cuplikan karya ilmiah yang baru diunggah</p>
                    </div>
                </div>

                <div class="space-y-3 max-h-48 overflow-y-auto pr-2">
                    <?php foreach (array_slice($latest, 0, 5) as $repo): ?>
                        <a href="<?= $base ?>/repository/<?= e($repo['slug']) ?>"
                           class="block p-3 rounded-lg hover:bg-slate-50 transition-colors border-l-4 border-transparent hover:border-emerald-500">
                            
                            <p class="text-xs font-medium text-slate-800 line-clamp-1 hover:text-emerald-700">
                                <?= e($repo['judul']) ?>
                            </p>

                            <div class="flex items-center gap-3 mt-0.5 text-[11px] text-slate-500">
                                <span>Tahun: <?= e($repo['tahun']) ?></span>
                                <span class="truncate">Oleh: <?= e($repo['author']) ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ✅ DIUBAH: pb-16/sm:pb-20 -> pb-8/sm:pb-10 + tambah deskripsi -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 sm:pb-10">
    <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 border-l-4 border-emerald-500 pl-3">Telusuri Berdasarkan Kategori</h2>
    <p class="text-sm text-slate-500 mt-1 pl-3 mb-6">
        Pilih kategori untuk mempercepat pencarian repository sesuai kebutuhan Anda.
    </p>

    <div class="flex flex-col sm:grid sm:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-5 text-sm">

        <a href="<?= $base ?>/telusuri/year"
           class="flex items-center sm:flex-col justify-start sm:justify-center gap-4 sm:gap-2 rounded-xl bg-white border border-slate-200 p-4 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3M16 7V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1z" />
                </svg>
            </div>
            <div class="text-left sm:text-center">
                <p class="font-semibold text-slate-800 mt-0 sm:mt-1">By Tahun</p>
                <p class="text-xs text-slate-500 hidden sm:block">Pengelompokan waktu</p>
            </div>
        </a>

        <a href="<?= $base ?>/telusuri/program-studi"
           class="flex items-center sm:flex-col justify-start sm:justify-center gap-4 sm:gap-2 rounded-xl bg-white border border-slate-200 p-4 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v6l9 4 9-4V7" />
                </svg>
            </div>
            <div class="text-left sm:text-center">
                <p class="font-semibold text-slate-800 mt-0 sm:mt-1">Program Studi</p>
                <p class="text-xs text-slate-500 hidden sm:block">Filter berdasarkan jurusan</p>
            </div>
        </a>

        <a href="<?= $base ?>/telusuri/mata-kuliah"
           class="flex items-center sm:flex-col justify-start sm:justify-center gap-4 sm:gap-2 rounded-xl bg-white border border-slate-200 p-4 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v4H4z" /><path stroke-linecap="round" stroke-linejoin="round" d="M10 8v12" /><path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16v8H4z" />
                </svg>
            </div>
            <div class="text-left sm:text-center">
                <p class="font-semibold text-slate-800 mt-0 sm:mt-1">Mata Kuliah</p>
                <p class="text-xs text-slate-500 hidden sm:block">Topik akademik terkait</p>
            </div>
        </a>

        <a href="<?= $base ?>/telusuri/jenis-karya"
           class="flex items-center sm:flex-col justify-start sm:justify-center gap-4 sm:gap-2 rounded-xl bg-white border border-slate-200 p-4 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 4h14v4H5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14v8H5z" />
                </svg>
            </div>
            <div class="text-left sm:text-center">
                <p class="font-semibold text-slate-800 mt-0 sm:mt-1">Jenis Karya</p>
                <p class="text-xs text-slate-500 hidden sm:block">Skripsi, Jurnal, Laporan, dll.</p>
            </div>
        </a>

        <a href="<?= $base ?>/telusuri/author"
           class="flex items-center sm:flex-col justify-start sm:justify-center gap-4 sm:gap-2 rounded-xl bg-white border border-slate-200 p-4 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="3" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 20c0-3 2.5-5 6-5s6 2 6 5"></path>
                </svg>
            </div>
            <div class="text-left sm:text-center">
                <p class="font-semibold text-slate-800 mt-0 sm:mt-1">Penulis</p>
                <p class="text-xs text-slate-500 hidden sm:block">Berdasarkan nama penulis</p>
            </div>
        </a>

    </div>
</section>


<!-- ✅ DIUBAH: pt-12 -> pt-4 biar jarak lebih dekat -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 sm:pb-20 pt-4">
    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 border-l-4 border-emerald-500 pl-3">Publikasi Terbaru</h2>
            <p class="text-sm text-slate-500 mt-1 pl-3">Karya ilmiah yang baru diarsipkan dan diverifikasi.</p>
        </div>

        <a href="<?= $base ?>/telusuri/"
           class="inline-flex items-center gap-1 text-sm font-medium text-emerald-700 hover:text-emerald-800 hover:underline flex-shrink-0">
            Lihat Semua Repository &raquo;
        </a>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <?php foreach ($latest as $item): ?>
            <article class="p-4 rounded-xl bg-white border border-slate-200 hover:border-emerald-400 transition-all group">

                <div class="flex flex-wrap items-center gap-2 text-xs mb-2">
                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-medium flex items-center gap-1 flex-shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3M16 7V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1z"/></svg>
                        <?= e($item['tahun']) ?>
                    </span>
                    <?php if (!empty($item['prodi'])): ?>
                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 truncate max-w-[60%]">
                            <?= e($item['prodi']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h3 class="text-base font-semibold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition-colors">
                    <?= e($item['judul']) ?>
                </h3>

                <p class="mt-1 text-xs text-slate-600 line-clamp-3">
                    <?= e($item['abstrak'] ?? 'Abstrak belum tersedia.') ?>
                </p>

                <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3">
                    <span class="text-xs text-slate-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="3" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 20c0-3 2.5-5 6-5s6 2 6 5"></path>
                        </svg>
                        <?= e($item['author']) ?>
                    </span>

                    <a href="<?= $base ?>/repository/<?= e($item['slug']) ?>"
                       class="inline-flex items-center gap-1 text-sm font-medium text-emerald-700 hover:underline">
                        Lihat Detail &raquo;
                    </a>
                </div>

            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 sm:pb-8"> 
    <div class="rounded-xl bg-slate-900 text-white px-8 py-10 sm:px-10 sm:py-12
                 flex flex-col lg:flex-row items-center justify-between gap-6">

        <div class="lg:max-w-lg">
            <h3 class="text-2xl sm:text-3xl font-semibold leading-snug">
                Akses Repository Kapan Saja, Di Mana Saja.
            </h3>
            <p class="mt-2 text-sm text-slate-300">
                Mulai telusuri kekayaan intelektual kampus Anda sekarang.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto flex-shrink-0">
            <a href="<?= $base ?>/telusuri/"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-emerald-500 text-slate-900 text-sm font-semibold hover:bg-emerald-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Telusuri Repository
            </a>

            <a href="<?= $base ?>/download"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg border border-slate-600 text-sm font-medium text-slate-300 hover:bg-slate-800 transition-colors">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download App
                </a>
        </div>

    </div>
</section>



