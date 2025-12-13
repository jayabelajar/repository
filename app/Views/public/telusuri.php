<main class="bg-gradient-to-b from-white to-slate-50 min-h-screen">

  <!-- Hero / info -->
  <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-4">
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-5 sm:p-7 lg:p-8 space-y-4">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-2">
          <p class="text-[11px] font-semibold tracking-[0.2em] text-emerald-600 uppercase">Telusuri</p>
          <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">Cari Repository Berdasarkan Kategori</h1>
          <p class="text-sm text-slate-600">Pilih filter yang diinginkan untuk melihat daftar repository secara terkelompok.</p>
        </div>
        <div class="w-full md:w-auto flex flex-wrap gap-2 text-[11px] text-emerald-700 font-semibold uppercase tracking-wide">
          <span class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100">Tahun</span>
          <span class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100">Prodi</span>
          <span class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100">Mata Kuliah</span>
          <span class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100">Jenis</span>
          <span class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100">Penulis</span>
        </div>
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

  <!-- Kategori filter cepat (ikon hijau) -->
  <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-4 sm:p-5 lg:p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="<?= $base_url; ?>/telusuri/year" class="group flex items-start gap-3 p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-md transition">
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14"/></svg>
          </div>
          <div class="min-w-0">
          <p class="font-semibold text-slate-900">By Tahun</p>
          <p class="text-xs text-slate-500">Pengelompokan waktu publikasi repository.</p>
        </div>
      </a>

      <a href="<?= $base_url; ?>/telusuri/program-studi" class="group flex items-start gap-3 p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-100">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9-4 9 4-9 4-9-4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v6l9 4 9-4V7"/></svg>
        </div>
        <div class="min-w-0">
          <p class="font-semibold text-slate-900">Program Studi</p>
          <p class="text-xs text-slate-500">Filter berdasarkan jurusan / departemen.</p>
        </div>
      </a>

      <a href="<?= $base_url; ?>/telusuri/mata-kuliah" class="group flex items-start gap-3 p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-100">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16v4H4z"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 8v12"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16v8H4z"/></svg>
        </div>
        <div class="min-w-0">
          <p class="font-semibold text-slate-900">Mata Kuliah</p>
          <p class="text-xs text-slate-500">Topik akademik terkait perkuliahan.</p>
        </div>
      </a>

      <a href="<?= $base_url; ?>/telusuri/jenis-karya" class="group flex items-start gap-3 p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-100">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 4h14v4H5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14v8H5z"/></svg>
        </div>
        <div class="min-w-0">
          <p class="font-semibold text-slate-900">Jenis Karya</p>
          <p class="text-xs text-slate-500">Skripsi, jurnal, laporan, dan lainnya.</p>
        </div>
      </a>

      <a href="<?= $base_url; ?>/telusuri/author" class="group flex items-start gap-3 p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-300 hover:shadow-md transition">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-100">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 20c0-3 2.5-5 6-5s6 2 6 5"/></svg>
        </div>
        <div class="min-w-0">
          <p class="font-semibold text-slate-900">Penulis</p>
          <p class="text-xs text-slate-500">Cari berdasarkan nama penulis/mahasiswa.</p>
        </div>
      </a>
      </div>
    </div>
  </section>
</main>
