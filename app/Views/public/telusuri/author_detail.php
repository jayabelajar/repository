<section class="max-w-7xl mx-auto px-4 py-10">

    <h1 class="text-xl font-semibold text-slate-900 mb-4">Author: <?= $username ?></h1>

    <?php if (empty($items)): ?>
        <p class="text-sm text-slate-600">Tidak ada repository oleh penulis ini.</p>
    <?php else: ?>

    <div class="grid md:grid-cols-3 gap-5">
        <?php foreach ($items as $r): ?>
            <article class="rounded-xl bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-md transition p-4">

                <p class="font-semibold text-sm text-slate-900 line-clamp-2">
                    <?= $r['judul'] ?>
                </p>

                <p class="text-xs text-slate-600 mt-1">Tahun: <?= $r['tahun'] ?></p>

                <a href="<?= $base_url; ?>/repository/<?= $r['slug'] ?>"
                   class="text-xs text-emerald-700 inline-flex items-center gap-1 mt-3 hover:underline">
                    Lihat detail
                </a>

            </article>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

</section>
