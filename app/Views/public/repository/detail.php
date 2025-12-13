<section class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold text-slate-900 mb-2">
        <?= e($repo['judul']) ?>
    </h1>

    <p class="text-sm text-slate-500 mb-4">
        <?= e($repo['tahun']) ?> - <?= e($repo['author']) ?>
    </p>

    <div class="bg-white border border-slate-200 rounded-xl p-5 text-sm">
        <h2 class="font-semibold text-slate-800 mb-2">Abstrak</h2>
        <p class="text-slate-600 leading-relaxed">
            <?= nl2br(e($repo['abstrak'])) ?>
        </p>

        <?php if (!empty($repo['file_path'])): ?>
        <a href="<?= $base_url; ?>/file/<?= $repo['file_path'] ?>"
           class="mt-4 inline-block px-4 py-2 rounded-full bg-emerald-600 text-white text-xs hover:bg-emerald-700">
           Download File
        </a>
        <?php endif; ?>
    </div>
</section>
