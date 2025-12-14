<!doctype html>
<html lang="id">
<head>
    <?php
        $base = rtrim($base_url ?? '', '/');
        $cssPath = __DIR__ . '/../../../public/assets/css/tailwind.css';
        $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - SIREPO INHAFI</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base; ?>/assets/css/tailwind.css?v=<?= $cssVersion ?>">
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased relative overflow-hidden">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 opacity-70 bg-[radial-gradient(circle_at_18%_20%,rgba(16,185,129,0.18),transparent_35%),radial-gradient(circle_at_80%_-10%,rgba(59,130,246,0.16),transparent_30%),radial-gradient(circle_at_50%_85%,rgba(13,148,136,0.16),transparent_28%)]"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-emerald-500/40 to-transparent"></div>
        <div class="absolute inset-x-8 bottom-10 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
    </div>

    <main class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="flex items-center gap-2 text-emerald-200 text-[11px] uppercase font-semibold tracking-[0.2em] mb-6">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-400"></span>
            </span>
            Maintenance Terjadwal
        </div>

        <div class="bg-white/95 text-slate-800 backdrop-blur-xl rounded-3xl border border-white/40 shadow-[0_25px_80px_-45px_rgba(0,0,0,0.65)] overflow-hidden">
            <div class="grid md:grid-cols-[1.1fr_0.9fr]">
                <div class="p-8 md:p-10 lg:p-12 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l3 3" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        SIREPO sedang diperbarui
                    </div>

                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
                            Kami memoles sistem agar lebih cepat dan stabil
                        </h1>
                        <p class="mt-3 text-base md:text-lg text-slate-600 leading-relaxed">
                            Terima kasih sudah bersabar. Tim sedang melakukan perawatan terjadwal supaya repository tetap aman, responsif, dan nyaman digunakan.
                        </p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-4 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-semibold mb-2">Estimasi selesai</p>
                            <p class="text-lg font-semibold text-slate-900">Segera hari ini</p>
                            <p class="text-xs text-slate-500 mt-1">Kami akan memberi kabar jika ada perubahan jadwal.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-semibold mb-2">Dampak</p>
                            <p class="text-lg font-semibold text-slate-900">Akses sementara ditutup</p>
                            <p class="text-xs text-slate-500 mt-1">Unduhan & unggahan dinonaktifkan sementara untuk menjaga integritas data.</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="<?= e($base_url ?? '/') ?>" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-900 text-white text-sm font-semibold shadow-lg shadow-emerald-500/10 hover:shadow-emerald-500/20 hover:-translate-y-0.5 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4" />
                            </svg>
                            Kembali ke beranda
                        </a>
                        <a href="mailto:admin@kampus.ac.id" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-800 hover:bg-slate-50 hover:-translate-y-0.5 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Hubungi admin
                        </a>
                    </div>
                </div>

                <div class="bg-slate-900/95 text-slate-100 p-8 md:p-10 flex flex-col justify-between space-y-6 border-t md:border-l border-slate-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400 font-semibold">Status pekerjaan</p>
                            <p class="text-xl font-semibold text-white mt-1">Sedang berlangsung</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-200 border border-emerald-400/30">Stabil</span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3 rounded-2xl border border-slate-800 bg-slate-900/60 p-4">
                            <div class="w-9 h-9 rounded-xl bg-emerald-500/15 text-emerald-200 flex items-center justify-center">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Backup & pengecekan data</p>
                                <p class="text-sm text-slate-300">Seluruh data sedang diamankan sebelum update diterapkan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl border border-slate-800 bg-slate-900/60 p-4">
                            <div class="w-9 h-9 rounded-xl bg-amber-500/15 text-amber-200 flex items-center justify-center">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Peningkatan performa</p>
                                <p class="text-sm text-slate-300">Optimasi pencarian, perbaikan keamanan, dan perbaikan bug minor.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl border border-slate-800 bg-slate-900/60 p-4">
                            <div class="w-9 h-9 rounded-xl bg-blue-500/15 text-blue-200 flex items-center justify-center">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16h10M7 8h10" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Pengujian akhir</p>
                                <p class="text-sm text-slate-300">Setelah selesai, layanan akan diuji sebelum dibuka kembali.</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-sm text-slate-300 border-t border-slate-800 pt-4">
                        Butuh akses cepat? Hubungi <a href="mailto:admin@kampus.ac.id" class="font-semibold text-emerald-200 hover:text-emerald-100">admin@kampus.ac.id</a> atau datangi layanan pusat informasi kampus.
                    </div>
                </div>
            </div>
        </div>

        <p class="mt-6 text-xs text-slate-400 text-center">
            &copy; <?= date('Y') ?> SIREPO - INHAFI Panel. Kami akan segera kembali online.
        </p>
    </main>
</body>
</html>
