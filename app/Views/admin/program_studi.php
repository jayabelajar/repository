<?php
$base = rtrim($base_url, '/');
?>

<div class="space-y-4 animate-fade-in-up">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight leading-tight">Program Studi</h1>
            <p class="text-sm text-slate-500">Kelola data program studi dengan tampilan konsisten.</p>
        </div>
        <button id="btnAddProdi" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-600/20 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 5v14m-7-7h14"/></svg>
            Tambah Prodi
        </button>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <form class="relative group w-full" method="GET">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="search" name="q" value="<?= e($search) ?>" placeholder="Cari nama prodi" class="pl-10 pr-4 py-2 w-full bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                <button type="submit" class="hidden"></button>
            </form>
            
            <form method="GET" class="w-full">
                <input type="hidden" name="q" value="<?= e($search) ?>">
                <select name="sort" onchange="this.form.submit()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer text-slate-600">
                    <option value="asc" <?= ($sort ?? 'asc') === 'asc' ? 'selected' : '' ?>>Urutkan A-Z</option>
                    <option value="desc" <?= ($sort ?? '') === 'desc' ? 'selected' : '' ?>>Urutkan Z-A</option>
                </select>
            </form>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 uppercase tracking-wider text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3">Nama Prodi</th>
                        <th class="px-6 py-3">Jumlah Repo</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-3 text-slate-900 font-semibold"><?= e($item['nama_program_studi'] ?? '') ?></td>
                                <td class="px-6 py-3 text-slate-700"><?= (int)($item['total_repo'] ?? 0) ?></td>
                                <td class="px-6 py-3 text-right">
                                    <div class="inline-flex gap-2">
                                        <button
                                            class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors border border-transparent hover:border-emerald-100 btnEdit"
                                            data-id="<?= $item['id'] ?>"
                                            data-nama="<?= e($item['nama_program_studi'] ?? '') ?>"
                                            title="Edit"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="<?= $base ?>/admin/program-studi/<?= $item['id'] ?>/delete" method="POST" onsubmit="return confirm('Hapus prodi ini?')" class="inline">
                                            <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                                            <button class="p-2 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-100 border border-transparent transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-500">Belum ada data.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (($pages ?? 1) > 1): ?>
            <?php
                $currentPage = $page ?? 1;
                $totalPages  = $pages ?? 1;
                $queryParams = $_GET;
                unset($queryParams['page']);
            ?>
            <div class="flex items-center justify-between text-sm text-slate-600 border-t border-slate-100 p-4">
                <span>Halaman <?= $currentPage ?> dari <?= $totalPages ?></span>
                <div class="flex gap-1">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <?php $queryParams['page'] = $p; $qs = http_build_query($queryParams); ?>
                        <a href="?<?= $qs ?>"
                           class="px-3 py-1 rounded-lg border <?= $p === $currentPage ? 'bg-emerald-600 text-white border-emerald-600' : 'border-slate-200 hover:bg-slate-50 text-slate-700' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id='modalProdi' class='fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center px-4'>
    <div class='bg-white w-full max-w-xl rounded-2xl shadow-2xl p-6 md:p-8 relative'>
        <button id='closeModalProdi' class='absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition'>
            <span class='sr-only'>Tutup</span>
            <svg class='w-5 h-5' viewBox='0 0 24 24' fill='none' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 6l12 12M6 18L18 6'/></svg>
        </button>
        <div class='flex items-start gap-3 mb-4'>
            <div class='w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center'>
                <svg class='w-5 h-5' viewBox='0 0 24 24' fill='none' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 5v14m-7-7h14'/></svg>
            </div>
            <div>
                <h3 id='modalTitle' class='text-lg font-semibold text-slate-900'>Tambah Program Studi</h3>
                <p class='text-sm text-slate-500'>Masukkan nama program studi secara lengkap.</p>
            </div>
        </div>

        <form id='formProdi' method='POST' action='<?= $base ?>/admin/program-studi' class='space-y-4'>
            <input type='hidden' name='csrf_token' value='<?= e($csrf) ?>'>
            <div class='space-y-2'>
                <label class='text-sm font-medium text-slate-700'>Nama Program Studi</label>
                <input type='text' name='nama_program_studi' required class='w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none'>
            </div>
            <div class='flex flex-col sm:flex-row justify-end gap-2 pt-2'>
                <button type='button' id='btnCancelProdi' class='px-4 py-2.5 rounded-lg border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50'>Batal</button>
                <button class='px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow-sm'>Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
(() => {
    const modal = document.getElementById('modalProdi');
    const btnAdd = document.getElementById('btnAddProdi');
    const btnClose = document.getElementById('closeModalProdi');
    const btnCancel = document.getElementById('btnCancelProdi');
    const form = document.getElementById('formProdi');
    const title = document.getElementById('modalTitle');

    const openModal = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
    const closeModal = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); resetForm(); };
    const resetForm = () => {
        form.reset();
        form.action = '<?= $base ?>/admin/program-studi';
        title.textContent = 'Tambah Program Studi';
    };

    btnAdd?.addEventListener('click', () => {
        resetForm();
        openModal();
    });
    btnClose?.addEventListener('click', closeModal);
    btnCancel?.addEventListener('click', closeModal);
    modal?.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            form.action = '<?= $base ?>/admin/program-studi/' + id + '/update';
            form.nama_program_studi.value = btn.dataset.nama || '';
            title.textContent = 'Edit Program Studi';
            openModal();
        });
    });
})();
</script>
