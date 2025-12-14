<main class="bg-gradient-to-b from-white to-slate-50 min-h-screen">
  <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-10 space-y-5">
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-5 sm:p-7 lg:p-8 space-y-2">
      <p class="text-[11px] font-semibold tracking-[0.2em] text-emerald-600 uppercase">Telusuri</p>
      <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">Telusuri Berdasarkan Tahun</h1>
      <p class="text-sm text-slate-600">Pilih tahun untuk melihat repository berdasarkan tahun terbit.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-4 sm:p-5">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
        <?php foreach ($years as $y): ?>
          <a href="<?= $base_url; ?>/telusuri?tahun=<?= urlencode($y['label']); ?>"
             class="flex items-start gap-3 p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3M16 7V3M4 11h16M5 5h14V21H5z"/>
              </svg>
            </div>
            <div class="min-w-0">
              <p class="font-semibold text-slate-900"><?= e($y['label']); ?></p>
              <p class="text-xs text-slate-500"><?= $y['total']; ?> repository</p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
