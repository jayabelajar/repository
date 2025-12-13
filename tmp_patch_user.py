import re, pathlib
path = pathlib.Path(r"app/Views/admin/user.php")
data = path.read_text()
new_block = """<div id='modalUser' class='fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center px-4'>
    <div class='bg-white w-full max-w-4xl rounded-2xl shadow-2xl p-6 md:p-8 relative overflow-y-auto max-h-[90vh]'>
        <button id='closeModalUser' class='absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition'>
            <span class='sr-only'>Tutup</span>
            <svg class='w-5 h-5' viewBox='0 0 24 24' fill='none' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 6l12 12M6 18L18 6'/></svg>
        </button>
        <div class='flex items-start gap-3 mb-5'>
            <div class='w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center'>
                <svg class='w-5 h-5' viewBox='0 0 24 24' fill='none' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 0c-4 0-7 2-7 6h14c0-4-3-6-7-6Z'/></svg>
            </div>
            <div>
                <h3 id='modalTitleUser' class='text-lg font-semibold text-slate-900'>Tambah Pengguna</h3>
                <p class='text-sm text-slate-500'>Lengkapi informasi pengguna. Username opsional, password isi untuk set/ganti.</p>
            </div>
        </div>

        <form id='formUser' method='POST' action='<?= $base ?>/admin/users' class='grid grid-cols-1 md:grid-cols-2 gap-4 text-sm'>
            <input type='hidden' name='csrf_token' value='<?= htmlspecialchars($csrf) ?>'>
            <div>
                <label class='text-slate-700 font-medium'>Nama Lengkap</label>
                <input type='text' name='nama_lengkap' required class='w-full mt-1 px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none'>
            </div>
            <div>
                <label class='text-slate-700 font-medium'>Email</label>
                <input type='email' name='email' required class='w-full mt-1 px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none'>
            </div>
            <div>
                <label class='text-slate-700 font-medium'>Username</label>
                <input type='text' name='username' class='w-full mt-1 px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none'>
            </div>
            <div>
                <label class='text-slate-700 font-medium'>Role</label>
                <select name='role' class='w-full mt-1 px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none'>
                    <option value='admin'>Admin</option>
                    <option value='dosen'>Dosen</option>
                    <option value='mahasiswa'>Mahasiswa</option>
                </select>
            </div>
            <div>
                <label class='text-slate-700 font-medium'>NIM (Mahasiswa)</label>
                <input type='text' name='nim' class='w-full mt-1 px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none'>
            </div>
            <div>
                <label class='text-slate-700 font-medium'>NIDN/NIP (Dosen)</label>
                <input type='text' name='nidn_nip' class='w-full mt-1 px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none'>
            </div>
            <div>
                <label class='text-slate-700 font-medium'>Password</label>
                <input type='password' name='password' class='w-full mt-1 px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none' placeholder='Isi untuk set / ganti'>
            </div>
            <div class='md:col-span-2 flex flex-col sm:flex-row justify-end gap-2 mt-2'>
                <button type='button' id='btnCancelUser' class='px-4 py-2.5 rounded-lg border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50'>Batal</button>
                <button class='px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow-sm'>Simpan</button>
            </div>
        </form>
    </div>
</div>
"""
pattern = r"<div id=\"modalUser\"[\s\S]*?</div>\s*\n\s*<script>"
new_data, count = re.subn(pattern, new_block + "\n<script>", data)
print('replaced', count)
path.write_text(new_data)
