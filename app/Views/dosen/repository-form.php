<?php
$base   = rtrim($base_url, '/');
$isEdit = ($mode ?? '') === 'edit';
$action = $isEdit
    ? "{$base}/dosen/my-repository/" . ($item['id'] ?? '') . "/update"
    : "{$base}/dosen/my-repository";

$mahasiswas = $mahasiswas ?? [];
$dosens     = $dosens ?? [];
$selected_authors   = $selected_authors   ?? [];
$selected_advisors  = $selected_advisors  ?? [];
$selected_examiners = $selected_examiners ?? [];
?>

<script>
(function() {
  const key = 'sirepo-theme';
  if (localStorage.getItem(key) !== 'light') {
    localStorage.setItem(key, 'light');
    document.body.classList.remove('dark');
    document.documentElement.style.colorScheme = 'light';
  }
})();
</script>

<div class="space-y-6 animate-fade-in-up">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight leading-tight">
                <?= $isEdit ? 'Edit Repository' : 'Tambah Repository' ?>
            </h1>
            <p class="text-sm text-slate-500 mt-1"><?= $isEdit ? 'Perbarui data repository' : 'Tambah repository baru' ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?= $base ?>/dosen/repository" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form id="repoForm" action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
        <?php if ($isEdit && !empty($item['file_pdf'])): ?>
            <input type="hidden" name="current_file" value="<?= e($item['file_pdf']) ?>">
        <?php endif; ?>
        <input type="hidden" name="slug" value="<?= e($item['slug'] ?? '') ?>">

        <!-- JUDUL -->
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Judul</label>
            <input type="text" name="judul" value="<?= e($item['judul'] ?? '') ?>" required
                   placeholder="Contoh: Analisis Sistem Informasi Perpustakaan"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
            <p class="text-xs text-slate-400">Slug dibuat otomatis dari judul (maks 80 karakter).</p>
        </div>

        <!-- JENIS / AUTHOR TEXT / TAHUN -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700">Jenis Karya</label>
                <select name="jenis_karya" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
                    <?php foreach (['skripsi','tugas_akhir','jurnal','artikel','laporan','pkl','lainnya'] as $jk): ?>
                        <option value="<?= $jk ?>" <?= (($item['jenis_karya'] ?? '') === $jk) ? 'selected' : '' ?>>
                            <?= ucfirst(str_replace('_',' ', $jk)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700">Penulis</label>
                <input type="text" name="author" value="<?= e($item['author'] ?? ($dosen['nama'] ?? '')) ?>" required
                       placeholder="Contoh: Budi Santoso"
                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700 placeholder:text-slate-400">
                <p class="text-xs text-slate-400">Penulis versi teks. Tandai mahasiswa ada di bagian "Tandai Orang".</p>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700">Tahun</label>
                <input type="number" name="tahun" value="<?= e($item['tahun'] ?? date('Y')) ?>" min="1900" max="<?= date('Y')+1 ?>"
                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
            </div>
        </div>

        <!-- PRODI + MK -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700">Program Studi</label>
                <select name="program_studi_id" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
                    <option value="">Pilih program studi</option>
                    <?php foreach ($prodis as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= (!empty($item['program_studi_id']) && $item['program_studi_id'] == $p['id']) ? 'selected' : '' ?>>
                            <?= e($p['nama_program_studi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700">Mata Kuliah</label>
                <select name="mata_kuliah_id" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700">
                    <option value="">Pilih mata kuliah</option>
                    <?php foreach ($mks as $mk): ?>
                        <option value="<?= $mk['id'] ?>" <?= (!empty($item['mata_kuliah_id']) && $item['mata_kuliah_id'] == $mk['id']) ? 'selected' : '' ?>>
                            <?= e($mk['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- KATA KUNCI -->
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Kata Kunci</label>
            <input type="text" name="keywords" value="<?= e($item['keywords'] ?? '') ?>" placeholder="Contoh: sistem informasi, web, repository"
                   class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700 placeholder:text-slate-400">
        </div>


        <!-- TANDAI ORANG -->
        <div class="space-y-3">
            <!-- Tandai MAHASISWA (WAJIB) -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700">
                    Tandai Penulis <span class="text-rose-600">*</span>
                </label>
                <p class="text-xs text-slate-400">Dosen boleh ikut ditandai sebagai penulis, tapi minimal tetap 1 mahasiswa.</p>

                <div class="relative">
                    <button type="button" data-dd-btn="author" onclick="ddToggle('author')"
                            class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-white text-left flex justify-between items-center">
                        <span id="authorLabel" class="text-slate-500">Pilih mahasiswa...</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="dd-author" class="hidden absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow overflow-hidden">
                        <div class="p-2 border-b border-slate-100 bg-slate-50">
                            <input type="text" id="authorSearch" placeholder="Cari nama / NIM..."
                                   oninput="ddFilter('author', this.value)"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        </div>

                        <div class="max-h-56 overflow-auto">
                            <?php
                            $authorSources = [
                                ['items' => $mahasiswas, 'role' => 'mahasiswa'],
                                ['items' => $dosens, 'role' => 'dosen'],
                            ];
                            ?>
                            <?php foreach ($authorSources as $src): ?>
                                <?php foreach ($src['items'] as $m): ?>
                                    <?php
                                    $id  = (int)($m['id'] ?? 0);
                                    $nm  = ($m['nama_lengkap'] ?? $m['username'] ?? ($src['role'] === 'dosen' ? 'Dosen' : 'Mahasiswa'));
                                    $nim = ($m['nim'] ?? '');
                                    $nid = ($m['nidn_nip'] ?? '');
                                    $code = $src['role'] === 'dosen' ? $nid : $nim;
                                    $txt = trim($nm . ($code ? " ($code)" : ""));
                                    $checked = in_array($id, $selected_authors, true);
                                    $roleText = $src['role'] === 'dosen' ? 'dosen' : 'mahasiswa';
                                    ?>
                                    <label class="dd-author-item flex items-start gap-2 px-3 py-2 hover:bg-slate-50 cursor-pointer"
                                        data-text="<?= e(mb_strtolower($txt)) ?>">
                                        <input type="checkbox" class="dd-author-checkbox mt-1" name="author_ids[]" value="<?= $id ?>"
                                            data-role="<?= $roleText ?>"
                                            <?= $checked ? 'checked' : '' ?>
                                            onchange="ddSync('author')">
                                        <span class="text-sm text-slate-700"><?= e($txt) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div id="authorHiddenInputs"></div>
                <p class="text-xs text-slate-400">Wajib pilih minimal 1 mahasiswa.</p>
            </div>

            <!-- PEMBIMBING + PENGUJI -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- PEMBIMBING -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Tandai Pembimbing (Dosen)</label>

                    <div class="relative">
                        <button type="button" data-dd-btn="advisor" onclick="ddToggle('advisor')"
                                class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-white text-left flex justify-between items-center">
                            <span id="advisorLabel" class="text-slate-500">Pilih pembimbing...</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="dd-advisor" class="hidden absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow overflow-hidden">
                            <div class="p-2 border-b border-slate-100 bg-slate-50">
                                <input type="text" id="advisorSearch" placeholder="Cari dosen / NIDN..."
                                    oninput="ddFilter('advisor', this.value)"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            </div>

                            <div class="max-h-56 overflow-auto">
                                <?php foreach ($dosens as $d): ?>
                                    <?php
                                    $id  = (int)($d['id'] ?? 0);
                                    $nm  = ($d['nama_lengkap'] ?? $d['username'] ?? 'Dosen');
                                    $nid = ($d['nidn_nip'] ?? '');
                                    $txt = trim($nm . ($nid ? " ($nid)" : ""));
                                    $checked = in_array($id, $selected_advisors, true);
                                    ?>
                                    <label class="dd-advisor-item flex items-start gap-2 px-3 py-2 hover:bg-slate-50 cursor-pointer"
                                        data-text="<?= e(mb_strtolower($txt)) ?>">
                                        <input type="checkbox" class="dd-advisor-checkbox mt-1" name="advisor_ids[]" value="<?= $id ?>"
                                            <?= $checked ? 'checked' : '' ?>
                                            onchange="ddSync('advisor')">
                                        <span class="text-sm text-slate-700"><?= e($txt) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div id="advisorHiddenInputs"></div>
                </div>

                <!-- PENGUJI -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Tandai Penguji (Opsional)</label>

                    <div class="relative">
                        <button type="button" data-dd-btn="examiner" onclick="ddToggle('examiner')"
                                class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-white text-left flex justify-between items-center">
                            <span id="examinerLabel" class="text-slate-500">Pilih penguji...</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="dd-examiner" class="hidden absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow overflow-hidden">
                            <div class="p-2 border-b border-slate-100 bg-slate-50">
                                <input type="text" id="examinerSearch" placeholder="Cari dosen / NIDN..."
                                    oninput="ddFilter('examiner', this.value)"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                            </div>

                            <div class="max-h-56 overflow-auto">
                                <?php foreach ($dosens as $d): ?>
                                    <?php
                                    $id  = (int)($d['id'] ?? 0);
                                    $nm  = ($d['nama_lengkap'] ?? $d['username'] ?? 'Dosen');
                                    $nid = ($d['nidn_nip'] ?? '');
                                    $txt = trim($nm . ($nid ? " ($nid)" : ""));
                                    $checked = in_array($id, $selected_examiners, true);
                                    ?>
                                    <label class="dd-examiner-item flex items-start gap-2 px-3 py-2 hover:bg-slate-50 cursor-pointer"
                                        data-text="<?= e(mb_strtolower($txt)) ?>">
                                        <input type="checkbox" class="dd-examiner-checkbox mt-1" name="examiner_ids[]" value="<?= $id ?>"
                                            <?= $checked ? 'checked' : '' ?>
                                            onchange="ddSync('examiner')">
                                        <span class="text-sm text-slate-700"><?= e($txt) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div id="examinerHiddenInputs"></div>
                </div>
            </div>
        </div>

        <!-- ABSTRAK -->
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-700">Abstrak</label>
            <textarea name="abstrak" rows="4" placeholder="Ringkasan singkat isi karya ilmiah"
                      class="w-full px-3 py-2.5 rounded-lg border border-slate-200 bg-slate-50 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700 placeholder:text-slate-400"><?= e($item['abstrak'] ?? '') ?></textarea>
        </div>

        <!-- FILE PDF -->
        <div class="space-y-3">
            <label class="text-sm font-semibold text-slate-700">File PDF</label>
            <?php if ($isEdit && !empty($item['file_pdf'])): ?>
                <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-lg">
                    <div class="text-sm text-slate-600">
                        File saat ini: <span class="font-semibold text-slate-800"><?= e($item['file_pdf']) ?></span>
                    </div>
                    <?php if (!empty($item['slug'])): ?>
                        <a href="<?= $base ?>/repository/<?= urlencode($item['slug']) ?>/download" target="_blank" class="text-emerald-600 text-sm font-semibold hover:underline">Lihat</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <input type="file" name="file_pdf" accept="application/pdf"
                   class="w-full text-sm text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            <p class="text-xs text-slate-400">Format PDF, maks 10MB.</p>
        </div>
        <div class="flex flex-wrap justify-end gap-2 pt-2">
            <a href="<?= $base ?>/dosen/repository" class="px-4 py-2.5 rounded-lg border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-600/20 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Repository' ?></span>
            </button>
        </div>
    </form>
</div>

<script>
function ddToggle(key) {
  const dd = document.getElementById('dd-' + key);
  if (!dd) return;
  dd.classList.toggle('hidden');

  if (!dd.classList.contains('hidden')) {
    const search = document.getElementById(key + 'Search');
    if (search) setTimeout(() => search.focus(), 0);
  }
}

function ddFilter(key, query) {
  const q = (query || '').toLowerCase().trim();
  document.querySelectorAll('.dd-' + key + '-item').forEach(item => {
    const text = item.getAttribute('data-text') || '';
    item.style.display = text.includes(q) ? '' : 'none';
  });
}

function ddSync(key) {
    const label = document.getElementById(key + 'Label');
    if (!label) return;

    const checked = document.querySelectorAll('.dd-' + key + '-checkbox:checked');

    const picked = [];
    checked.forEach(cb => {
        const text = cb.nextElementSibling ? cb.nextElementSibling.textContent.trim() : '';
        if (text) picked.push(text);
    });

  const placeholders = {
    author: 'Pilih mahasiswa...',
    advisor: 'Pilih pembimbing...',
    examiner: 'Pilih penguji...',
  };
  label.textContent = picked.length ? picked.join(', ') : (placeholders[key] || 'Pilih...');
}

document.addEventListener('DOMContentLoaded', () => {
  ddSync('author');
  ddSync('advisor');
  ddSync('examiner');
});

document.addEventListener('click', (e) => {
  ['author','advisor','examiner'].forEach(key => {
    const btn = document.querySelector('[data-dd-btn="' + key + '"]');
    const dd  = document.getElementById('dd-' + key);
    if (!btn || !dd) return;
    if (!btn.contains(e.target) && !dd.contains(e.target)) dd.classList.add('hidden');
  });
});

document.getElementById('repoForm')?.addEventListener('submit', function(e){
  const pickedStudents = document.querySelectorAll('.dd-author-checkbox[data-role="mahasiswa"]:checked').length;
  if (pickedStudents < 1) {
    e.preventDefault();
    alert('Minimal pilih 1 mahasiswa (Tandai Mahasiswa).');
    ddToggle('author');
  }
});
</script>
