<section class="max-w-7xl mx-auto px-4 py-10">

    <h1 class="text-xl font-semibold text-slate-900 mb-4">
        Tahun: <?= $tahun ?>
    </h1>

    <?php if (empty($items)): ?>
        <p class="text-sm text-slate-600">Belum ada repository pada tahun ini.</p>
    <?php else: ?>

    <div class="space-y-3">
        <?php foreach ($items as $r): ?>
            <a href="<?= $base_url; ?>/repository/<?= $r['slug'] ?>"
               class="block p-4 bg-white border border-slate-200 hover:shadow-sm rounded-xl transition">

                <p class="font-semibold text-sm text-slate-900"><?= $r['judul'] ?></p>
                <p class="text-xs text-slate-500">Oleh: <?= $r['author'] ?></p>

            </a>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

</section>
