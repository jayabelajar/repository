<?php $repoThumb = $base_url . '/assets/img/repo-default.svg'; ?>

<main class="bg-gradient-to-b from-white to-slate-50 min-h-screen">
  <div class="h-6 sm:h-8"></div>

  <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4">
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-5 sm:p-7 lg:p-8 space-y-4">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="space-y-1">
          <p class="text-[11px] font-semibold tracking-[0.2em] text-emerald-600 uppercase">Hasil Pencarian</p>
          <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">Menampilkan repository yang sesuai</h1>
          <p class="text-sm text-slate-600">Gunakan kata kunci untuk mempersempit hasil.</p>
        </div>
        <?php if (!empty($total)): ?>
          <p class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-full">
            <?= (int) $total; ?> hasil ditemukan
          </p>
        <?php endif; ?>
      </div>

      <form action="<?= $base_url; ?>/telusuri" method="get" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-emerald-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path></svg>
          </span>
          <input type="search" name="q" value="<?= e($filters['q'] ?? '') ?>" placeholder="Cari judul, penulis, kata kunci..." class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm">
        </div>
        <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow-sm transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path></svg>
          Cari
        </button>
      </form>
    </div>
  </section>

  <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
    <?php if (empty($repositories)): ?>
      <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-6 text-center text-sm text-slate-500">
        Tidak ada repository yang cocok dengan pencarian Anda.
      </div>
    <?php else: ?>
      <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-4 sm:p-5">
        <div class="grid gap-4 md:grid-cols-2">
          <?php foreach ($repositories as $item): ?>
            <article class="p-4 rounded-xl bg-white border border-slate-200 hover:border-emerald-400 hover:shadow-md transition-all text-xs sm:text-sm space-y-2">
              <div class="flex flex-wrap items-center gap-2 text-[11px]">
                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center gap-1">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3M16 7V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1z"/></svg>
                  <?= e($item['tahun'] ?? '-') ?>
                </span>
                <?php if (!empty($item['prodi'])): ?>
                  <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 truncate">
                    <?= e($item['prodi']) ?>
                  </span>
                <?php endif; ?>
                <?php if (!empty($item['jenis_karya'])): ?>
                  <span class="ml-auto px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                    <?= e(str_replace('_',' ',$item['jenis_karya'])) ?>
                  </span>
                <?php endif; ?>
              </div>

              <a href="<?= $base_url; ?>/repository/<?= e($item['slug'] ?? '') ?>" class="group/title block">
                <h2 class="text-sm font-semibold text-slate-900 line-clamp-2 group-hover/title:text-emerald-700 transition-colors">
                  <?= e($item['judul'] ?? '-') ?>
                </h2>
              </a>

              <p class="text-[11px] text-slate-600 line-clamp-3"><?= e($item['abstrak'] ?? '') ?></p>

              <div class="flex items-center justify-between border-t border-slate-100 pt-2 mt-1">
                <span class="text-[11px] text-slate-500 flex items-center gap-1 truncate">
                  <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="3" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 20c0-3 2.5-5 6-5s6 2 6 5"></path>
                  </svg>
                  <?= e($item['author'] ?? '-') ?>
                </span>
                <?php if (!empty($item['mata_kuliah'])): ?>
                  <span class="text-[11px] text-slate-500 truncate text-right"><?= e($item['mata_kuliah']) ?></span>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <?php if (!empty($pages) && $pages > 1): ?>
          <?php
            $currentPage = $page ?? 1;
            $totalPages  = $pages;
            $params = [];
            foreach (($filters ?? []) as $k => $v) {
              if ($v !== '' && $v !== null) $params[$k] = $v;
            }
          ?>
          <div class="mt-6 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600">
            <span>Halaman <?= $currentPage ?> dari <?= $totalPages ?></span>
            <div class="flex gap-1">
              <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <?php $params['page'] = $p; $qs = http_build_query($params); ?>
                <a href="<?= $base_url; ?>/telusuri?<?= $qs ?>"
                   class="px-3 py-1.5 rounded-lg border <?= $p === $currentPage ? 'bg-emerald-600 text-white border-emerald-600' : 'border-slate-200 hover:bg-slate-50 text-slate-700' ?>">
                  <?= $p ?>
                </a>
              <?php endfor; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
