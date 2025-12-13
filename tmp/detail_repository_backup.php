<?php
// --- DATA PROCESSING ---
$repo = $data ?? [];
$keywords = [];
$bookmarkable = $bookmarkable ?? false;
$isBookmarked = $isBookmarked ?? false;

if (!empty($repo['keywords'])) {
    $keywords = array_filter(array_map('trim', explode(',', $repo['keywords'])));
}

$jenisKarya = !empty($repo['jenis_karya']) ? ucwords(str_replace('_', ' ', $repo['jenis_karya'])) : 'Karya Ilmiah';

// URL Handling
$fileUrl = null;
$hasFile = !empty($repo['file_pdf']);
if ($hasFile) {
    $fileUrl = preg_match('/^https?:\\/\\//i', $repo['file_pdf']) 
        ? $repo['file_pdf'] 
        : rtrim($base_url, '/') . '/assets/uploads/' . rawurlencode($repo['file_pdf']);
}

$publishedAt = !empty($repo['created_at']) ? date('d M Y', strtotime($repo['created_at'])) : '-';
$isoDate = !empty($repo['created_at']) ? date('c', strtotime($repo['created_at'])) : null;

// User Helper
$mapUser = static function ($u): array {
    $name = $u['nama_lengkap'] ?? $u['username'] ?? 'Pengguna';
    $code = '';
    $role = $u['role'] ?? '';
    // Logika prefix NIP/NIM
    if ($role === 'mahasiswa' && !empty($u['nim'])) {
        $code = 'NIM: ' . $u['nim'];
    } elseif ($role === 'dosen' && !empty($u['nidn_nip'])) {
        $code = 'NIP: ' . $u['nidn_nip'];
    }
    return ['name' => trim($name) ?: 'Pengguna', 'code' => $code];
};

$authorUsers  = array_map($mapUser, $authors ?? []);
$advisorUsers = array_map($mapUser, $advisors ?? []);
$examinerUsers = array_map($mapUser, $examiners ?? []);

// --- SEO SCHEMA ---
$schemaData = [
    "@context" => "https://schema.org",
    "@type" => "ScholarlyArticle",
    "headline" => $repo['judul'] ?? 'Dokumen Akademik',
    "datePublished" => $isoDate,
    "author" => array_map(fn($a) => ["@type" => "Person", "name" => $a['name']], $authorUsers),
    "description" => mb_strimwidth(strip_tags($repo['abstrak'] ?? ''), 0, 160, "...")
];
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script type="application/ld+json"><?= json_encode($schemaData); ?></script>

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="min-h-screen bg-slate-50 text-slate-800">

    <header class="relative bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 text-white pb-20 md:pb-28 overflow-hidden">
        
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            
            <nav class="flex items-center gap-2 text-xs text-emerald-200 mb-6 font-medium">
                <a href="<?= $base_url ?>" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <a href="<?= $base_url ?>/telusuri" class="hover:text-white transition-colors">Repository</a>
                <span>/</span>
                <span class="text-white truncate max-w-[200px]"><?= htmlspecialchars($jenisKarya) ?></span>
            </nav>

            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-2.5 py-1 rounded bg-emerald-700/50 border border-emerald-500/30 text-emerald-50 text-[11px] font-bold uppercase tracking-wider backdrop-blur-sm">
                    <?= htmlspecialchars($jenisKarya) ?>
                </span>
                <?php if (!empty($repo['prodi'])): ?>
                    <span class="px-2.5 py-1 rounded bg-white/10 border border-white/20 text-white text-[11px] font-semibold backdrop-blur-sm">
                        <?= htmlspecialchars($repo['prodi']) ?>
                    </span>
                <?php endif; ?>
                <span class="flex items-center gap-1.5 text-emerald-100/90 text-xs font-medium ml-1">
                    <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <?= $publishedAt ?>
                </span>
            </div>

            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white leading-tight mb-6 tracking-tight max-w-4xl">
                <?= htmlspecialchars($repo['judul'] ?? 'Judul Dokumen') ?>
            </h1>

            <?php if (!empty($authorUsers)): ?>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
                    <?php foreach ($authorUsers as $au): ?>
                        <div class="flex items-center gap-2 group">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            
                            <div class="flex flex-col">
                                <span class="font-semibold text-white text-sm leading-none group-hover:text-emerald-200 transition-colors">
                                    <?= htmlspecialchars($au['name']) ?>
                                </span>
                                <?php if($au['code']): ?>
                                    <span class="text-[10px] text-emerald-100/80 font-mono mt-0.5">
                                        <?= htmlspecialchars($au['code']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 md:-mt-20 relative z-20 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">

            <article class="lg:col-span-2 bg-white rounded-xl shadow-lg shadow-slate-200/50 p-6 md:p-8 border border-slate-200">
                
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Abstrak</h2>
                </div>

                <div class="prose prose-slate prose-sm md:prose-base max-w-none text-slate-600 leading-relaxed text-justify">
                    <?php if (!empty($repo['abstrak'])): ?>
                        <?= nl2br(htmlspecialchars($repo['abstrak'])) ?>
                    <?php else: ?>
                        <p class="text-slate-400 italic bg-slate-50 p-4 rounded text-center text-sm">Belum ada abstrak.</p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($keywords)): ?>
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <h3 class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Kata Kunci</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($keywords as $kw): ?>
                                <a href="<?= $base_url ?>/telusuri?q=<?= urlencode($kw) ?>" class="px-3 py-1.5 rounded-lg bg-slate-50 text-slate-600 text-xs font-semibold border border-slate-200 hover:border-emerald-400 hover:text-emerald-700 transition-all">
                                    <span class="text-slate-300 mr-1">#</span><?= htmlspecialchars($kw) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </article>

            <aside class="lg:col-start-3 space-y-5 lg:sticky lg:top-8">
                
                <div class="bg-white rounded-xl shadow-md shadow-slate-200/50 p-5 border border-slate-200">
                    
                    <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Informasi Akademik</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="group">
                            <dt class="text-[10px] font-bold text-slate-400 uppercase mb-1">Program Studi</dt>
                            <dd class="text-sm font-semibold text-slate-800 leading-snug">
                                <?= !empty($repo['prodi']) ? htmlspecialchars($repo['prodi']) : '-' ?>
                            </dd>
                        </div>

                        <div class="group border-t border-slate-50 pt-3">
                            <dt class="text-[10px] font-bold text-slate-400 uppercase mb-1">Mata Kuliah</dt>
                            <dd class="text-sm font-semibold text-slate-800 leading-snug">
                                <?= !empty($repo['mata_kuliah']) ? htmlspecialchars($repo['mata_kuliah']) : '-' ?>
                            </dd>
                        </div>

                        <div class="group border-t border-slate-50 pt-3">
                            <dt class="text-[10px] font-bold text-slate-400 uppercase mb-1">Pembimbing</dt>
                            <dd class="space-y-2">
                                <?php if (!empty($advisorUsers)): ?>
                                    <?php foreach($advisorUsers as $adv): ?>
                                        <div class="bg-slate-50 p-2 rounded border border-slate-100">
                                            <div class="text-sm font-bold text-slate-800"><?= htmlspecialchars($adv['name']) ?></div>
                                            <?php if($adv['code']): ?>
                                                <div class="text-[10px] text-emerald-600 font-mono mt-0.5"><?= htmlspecialchars($adv['code']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-sm text-slate-800">-</span>
                                <?php endif; ?>
                            </dd>
                        </div>

                        <div class="group border-t border-slate-50 pt-3">
                            <dt class="text-[10px] font-bold text-slate-400 uppercase mb-1">Penguji</dt>
                            <dd class="space-y-1">
                                <?php if (!empty($examinerUsers)): ?>
                                    <?php foreach($examinerUsers as $exm): ?>
                                        <div class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($exm['name']) ?></div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-sm text-slate-800">-</span>
                                <?php endif; ?>
                            </dd>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg shadow-emerald-900/5 p-5 border border-slate-200">
                    
                    <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Akses File</h2>
                    </div>

                    <div class="space-y-3">
                        <?php if ($hasFile): ?>
                            <a href="<?= $fileUrl ?>" target="_blank" class="flex items-center justify-center w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-3 rounded-xl font-bold text-sm transition-all shadow-sm hover:shadow-emerald-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Unduh Full Text
                            </a>
                        <?php else: ?>
                            <div class="w-full bg-slate-50 text-slate-400 px-4 py-3 rounded-xl font-medium text-xs text-center border border-slate-100 cursor-not-allowed">
                                File belum tersedia
                            </div>
                        <?php endif; ?>

                        <button onclick="shareLink()" class="flex w-full items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 hover:border-emerald-300 px-4 py-3 rounded-xl font-bold text-sm transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            Bagikan Halaman
                        </button>

                        <?php if ($bookmarkable && !empty($repo['slug'])): ?>
                            <form action="<?= $base_url; ?>/bookmark/<?= htmlspecialchars($repo['slug']) ?>/toggle" method="POST" class="w-full">
                                <button type="submit" class="flex w-full items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 hover:border-emerald-300 px-4 py-3 rounded-xl font-bold text-sm transition-all">
                                    <svg class="w-5 h-5 <?= $isBookmarked ? 'text-emerald-500 fill-emerald-500' : 'text-slate-400' ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                    <?= $isBookmarked ? 'Tersimpan' : 'Simpan Bookmark' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

            </aside>

        </div>
    </main>
</div>

<script>
function shareLink() {
    if (navigator.share) {
        navigator.share({
            title: '<?= addslashes($repo['judul'] ?? 'Dokumen Akademik') ?>',
            url: window.location.href,
        }).catch(console.error);
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Tautan berhasil disalin!');
    }
}
</script>