<?php $repoThumb = $base_url . '/assets/img/repo-default.svg'; ?>

<main class="bg-gradient-to-b from-white to-slate-50 min-h-screen">
  <div class="h-16"></div>

  <!-- Header Telusuri -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Hasil Pencarian</h1>
        <p class="text-sm text-slate-600 mt-1">
          Menampilkan hasil berdasarkan kata kunci yang kamu gunakan.
        </p>
      </div>

      <?php if (!empty($total)): ?>
        <p class="text-xs text-slate-500">
          <span class="font-semibold text-slate-800"><?= (int)$total ?></span> hasil ditemukan.
        </p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Form pencarian sederhana -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-4">
    <form action="<?= $base_url; ?>/telusuri" method="get"
          class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm flex items-center gap-3 text-sm">
      <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path>
      </svg>
      <input type="text" name="q" value="<?= e($q ?? '') ?>"
             placeholder="Cari judul, penulis, tahun, atau kata kunci..."
             class="flex-1 bg-transparent focus:outline-none min-w-0">
      <button type="submit"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 text-sm">
        Telusuri
      </button>
    </form>
  </section>

  <!-- Daftar hasil -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

    <?php if (empty($repositories)): ?>

      <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-6 text-center text-sm text-slate-500">
        Tidak ada repository yang cocok dengan pencarian Anda.
      </div>

    <?php else: ?>

      <div class="grid gap-4 md:grid-cols-2">
        <?php foreach ($repositories as $item): ?>
          <article class="bg-white border border-slate-200 rounded-2xl p-4 hover:border-emerald-300 hover:shadow-md transition text-xs sm:text-sm">
            <div class="flex items-center justify-between gap-2 text-[11px] text-slate-500">
              <div class="inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M8 7V3M16 7V3M4 11h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1z" />
                </svg>
                <span><?= e($item['tahun'] ?? '-') ?></span>
              </div>
              <?php if (!empty($item['prodi'])): ?>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 line-clamp-1">
                  <?= e($item['prodi']) ?>
                </span>
              <?php endif; ?>
            </div>

            <h2 class="mt-2 text-sm font-semibold text-slate-900 line-clamp-2">
              <?= e($item['judul'] ?? '-') ?>
            </h2>

            <p class="mt-1 text-[11px] text-slate-600 line-clamp-2">
              <?= e($item['abstrak'] ?? '') ?>
            </p>

            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500">
              <span class="inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <circle cx="12" cy="8" r="3" /><path d="M6 20c0-3 2.5-5 6-5s6 2 6 5"></path>
                </svg>
                <?= e($item['author'] ?? '-') ?>
              </span>
              <?php if (!empty($item['mata_kuliah'])): ?>
                <span><?= e($item['mata_kuliah']) ?></span>
              <?php endif; ?>
            </div>

            <div class="mt-3 flex items-center justify-between gap-2">
              <a href="<?= $base_url; ?>/repository/<?= e($item['slug'] ?? '') ?>"
                 class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 hover:underline">
                Lihat detail
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M9 5l7 7-7 7"/>
                </svg>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>
  </section>
</main>
