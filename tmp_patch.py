import re, pathlib
path = pathlib.Path(r"app/Views/admin/mata_kuliah.php")
data = path.read_text()
new_block = """<div id='modalMk' class='fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center px-4'>
    <div class='bg-white w-full max-w-xl rounded-2xl shadow-2xl p-6 md:p-8 relative'>
        <button id='closeModalMk' class='absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition'>
            <span class='sr-only'>Tutup</span>
            <svg class='w-5 h-5' viewBox='0 0 24 24' fill='none' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 6l12 12M6 18L18 6'/></svg>
        </button>
        <div class='flex items-start gap-3 mb-4'>
            <div class='w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center'>
                <svg class='w-5 h-5' viewBox='0 0 24 24' fill='none' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 12h14M5 12l4 4M5 12l4-4'/></svg>
            </div>
            <div>
                <h3 id='modalTitleMk' class='text-lg font-semibold text-slate-900'>Tambah Mata Kuliah</h3>
                <p class='text-sm text-slate-500'>Isi nama mata kuliah dengan benar sebelum menyimpan.</p>
            </div>
        </div>

        <form id='formMk' method='POST' action='<?= $base ?>/admin/mata-kuliah' class='space-y-4'>
            <input type='hidden' name='csrf_token' value='<?= htmlspecialchars($csrf) ?>'>
            <div class='space-y-2'>
                <label class='text-sm font-medium text-slate-700'>Nama Mata Kuliah</label>
                <input type='text' name='nama' required class='w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none placeholder-slate-300' placeholder='Contoh: Pemrograman Web'>
            </div>
            <div class='flex flex-col sm:flex-row justify-end gap-2 pt-2'>
                <button type='button' id='btnCancelMk' class='px-4 py-2.5 rounded-lg border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50'>Batal</button>
                <button class='px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow-sm'>Simpan</button>
            </div>
        </form>
    </div>
</div>
"""
pattern = r"<div id=\"modalMk\"[\s\S]*?</div>\s*\n\s*<script>"
new_data, count = re.subn(pattern, new_block + "\n<script>", data)
print('replaced', count)
path.write_text(new_data)
