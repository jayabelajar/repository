<?php
$base_url = $base_url ?? '';
$app_name = $app_name ?? 'Sistem Informasi Repository Institut Agama Islam Hasan Jufri';
$page_title = '500 - Terjadi Kesalahan | ' . $app_name;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="robots" content="noindex,follow">
    <meta name="description" content="Terjadi kesalahan pada server <?= htmlspecialchars($app_name, ENT_QUOTES, 'UTF-8'); ?>. Silakan coba lagi atau hubungi admin.">
    <link rel="stylesheet" href="<?= $base_url; ?>/assets/css/tailwind.css">
    <style> body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full bg-white/5 border border-white/10 backdrop-blur-lg rounded-3xl shadow-xl p-8 sm:p-10 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-3xl font-black mb-4">500</div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Terjadi Kesalahan Sistem</h1>
        <p class="mt-3 text-sm sm:text-base text-slate-300">Maaf, permintaan Anda tidak dapat diproses saat ini. Silakan coba lagi beberapa saat atau gunakan menu pencarian untuk kembali ke konten.</p>
        <div class="mt-7 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="<?= $base_url; ?>/" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Kembali ke Beranda
            </a>
            <a href="<?= $base_url; ?>/telusuri" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-slate-500 text-slate-100 font-semibold hover:border-emerald-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path></svg>
                Telusuri Repository
            </a>
        </div>
        <p class="mt-6 text-xs text-slate-400">Kode: 500 ? Silakan coba lagi.</p>
    </div>
</body>
</html>
