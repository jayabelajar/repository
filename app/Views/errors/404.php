<?php
$base_url = $base_url ?? '';
$app_name = $app_name ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
$page_title = '404 - Halaman Tidak Ditemukan | ' . $app_name;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="robots" content="noindex,follow">
    <meta name="description" content="Halaman yang Anda cari tidak ditemukan di <?= htmlspecialchars($app_name, ENT_QUOTES, 'UTF-8'); ?>.">
    <link rel="stylesheet" href="<?= $base_url; ?>/assets/css/tailwind.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-slate-100 flex items-center justify-center px-4 py-10">
    <div class="max-w-3xl w-full bg-white/5 backdrop-blur-lg border border-white/10 rounded-3xl shadow-xl p-8 sm:p-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-400/40 text-emerald-300 text-3xl font-black mb-4">404</div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Halaman Tidak Ditemukan</h1>
        <p class="mt-3 text-sm sm:text-base text-slate-300 max-w-2xl mx-auto">Maaf, tautan yang Anda buka tidak tersedia. Periksa kembali URL atau gunakan pencarian untuk menemukan konten di <?= htmlspecialchars($app_name, ENT_QUOTES, 'UTF-8'); ?>.</p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="<?= $base_url; ?>/" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Kembali ke Beranda
            </a>
            <a href="<?= $base_url; ?>/telusuri" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-500 text-slate-100 font-semibold hover:border-emerald-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path></svg>
                Telusuri Repository
            </a>
        </div>
        <p class="mt-6 text-xs text-slate-400">Kode: 404 ? Tidak ditemukan</p>
    </div>
</body>
</html>
