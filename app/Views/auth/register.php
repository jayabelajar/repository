<?php
use App\Core\Security\Csrf;

$token = $csrf ?? Csrf::token();
$base  = rtrim($base_url, '/');
$error = $_GET['error'] ?? null;

$errors = [
    'csrf'         => 'Sesi kadaluarsa. Muat ulang halaman lalu coba lagi.',
    'empty'        => 'Lengkapi semua kolom wajib terlebih dahulu.',
    'email'        => 'Format email tidak valid.',
    'email_used'   => 'Email sudah digunakan. Silakan masuk atau pakai email lain.',
    'nim_used'     => 'NIM sudah terdaftar. Hubungi admin jika ini keliru.',
    'nidn'         => 'NIDN/NIP wajib diisi untuk akun dosen.',
    'nidn_used'    => 'NIDN/NIP sudah terdaftar.',
    'username_used'=> 'Username sudah dipakai. Coba kombinasikan angka/huruf lain.',
    'nomatch'      => 'Konfirmasi password tidak sama.',
    'weak'         => 'Gunakan password minimal 8 karakter agar lebih aman.',
    'code'         => 'Kode akses tidak valid. Minta kode terbaru ke admin.',
];
?>

<div class="w-full max-w-xl bg-white rounded-2xl shadow-xl p-6 md:p-8 space-y-6 mx-4 sm:mx-0 relative overflow-hidden">
    <div class="absolute -top-12 -left-16 w-40 h-40 bg-emerald-100 rounded-full blur-3xl opacity-70"></div>
    <div class="absolute -bottom-14 -right-16 w-48 h-48 bg-emerald-50 rounded-full blur-3xl opacity-70"></div>

    <div class="relative space-y-2">
        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-[0.2em]">Buat Akun</p>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 leading-tight">Registrasi Pengguna</h1>
        <p class="text-sm text-slate-500">Akses dashboard sesuai peran: mahasiswa, dosen, atau admin (opsi tersembunyi).</p>
    </div>

    <?php if ($error && isset($errors[$error])): ?>
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-red-50 to-amber-50 opacity-70"></div>
            <div class="relative border border-red-100 bg-white/70 backdrop-blur-sm rounded-xl px-4 py-3 text-sm text-red-700 flex items-start gap-3 shadow-sm">
                <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-100 text-red-600">!</span>
                <div>
                    <p class="font-semibold">Registrasi gagal</p>
                    <p class="text-red-600/90"><?= htmlspecialchars($errors[$error], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <form action="<?= $base ?>/daftar" method="POST" class="relative space-y-4">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="role" id="roleInput" value="mahasiswa">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Nama lengkap</label>
                <input
                    type="text"
                    name="nama_lengkap"
                    required
                    autocomplete="name"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    placeholder="Nama sesuai KTP/KRS"
                />
            </div>
            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Email kampus</label>
                <input
                    type="email"
                    name="email"
                    required
                    autocomplete="email"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    placeholder="mahasiswa@kampus.ac.id"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5" id="nimField">
                <label class="block text-sm font-semibold text-slate-700">NIM</label>
                <input
                    type="text"
                    name="nim"
                    required
                    inputmode="numeric"
                    autocomplete="on"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    placeholder="Contoh: 21041010001"
                />
            </div>
            <div class="space-y-1.5 hidden" id="nidnField">
                <label class="block text-sm font-semibold text-slate-700">NIDN/NIP (khusus dosen)</label>
                <input
                    type="text"
                    name="nidn_nip"
                    inputmode="numeric"
                    autocomplete="on"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    placeholder="Contoh: 0810xxxx"
                />
            </div>
            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Username (opsional)</label>
                <input
                    type="text"
                    name="username"
                    autocomplete="username"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    placeholder="Gunakan huruf/angka tanpa spasi"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    minlength="8"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    placeholder="Minimal 8 karakter"
                />
            </div>
            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Konfirmasi password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    minlength="8"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    placeholder="Ulangi password"
                />
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-slate-700">Kode akses</label>
            <input
                type="password"
                name="access_code"
                required
                autocomplete="off"
                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                placeholder="Masukkan kode yang diberikan admin"
            />
            <p class="text-xs text-slate-500">Halaman ini dikunci, hanya yang punya kode akses yang bisa mendaftar.</p>
        </div>

        <details id="roleToggle" class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm text-slate-600">
            <summary class="cursor-pointer font-semibold text-slate-700">Butuh akun admin/dosen? (opsional)</summary>
            <div class="pt-3 space-y-3">
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-slate-700">Pilih peran</label>
                    <select
                        id="roleSelect"
                        class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    >
                        <option value="mahasiswa" selected>Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                        <option value="admin">Admin</option>
                    </select>
                    <p class="text-xs text-slate-500">Default tetap mahasiswa. Gunakan ini hanya jika Anda memang admin/dosen.</p>
                </div>
            </div>
        </details>

        <div class="flex items-start gap-2 text-sm text-slate-600 bg-slate-50 border border-slate-100 rounded-xl px-4 py-3">
            <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">i</span>
            <p>Pastikan email dan NIM sesuai data kampus. Akun baru langsung diarahkan ke dashboard setelah berhasil dibuat.</p>
        </div>

        <button
            class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-emerald-200 hover:shadow-emerald-300 transition-all duration-200 transform active:scale-[0.99] flex justify-center items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Buat akun
        </button>
    </form>

    <div class="relative border-t border-slate-100 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-slate-600">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Sudah punya akun?</span>
        </div>
        <a href="<?= $base ?>/login" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Masuk ke dashboard</a>
    </div>
</div>

<script>
(function() {
    const roleSelect = document.getElementById('roleSelect');
    const roleInput = document.getElementById('roleInput');
    const nimField = document.getElementById('nimField');
    const nidnField = document.getElementById('nidnField');

    const toggleFields = (role) => {
        if (!roleInput) return;
        roleInput.value = role;

        const nimInput = nimField?.querySelector('input[name="nim"]');
        const nidnInput = nidnField?.querySelector('input[name="nidn_nip"]');

        if (role === 'dosen') {
            nimField?.classList.add('hidden');
            nidnField?.classList.remove('hidden');
            if (nimInput) nimInput.required = false;
            if (nidnInput) nidnInput.required = true;
        } else if (role === 'admin') {
            nimField?.classList.add('hidden');
            nidnField?.classList.add('hidden');
            if (nimInput) nimInput.required = false;
            if (nidnInput) nidnInput.required = false;
        } else {
            nimField?.classList.remove('hidden');
            nidnField?.classList.add('hidden');
            if (nimInput) nimInput.required = true;
            if (nidnInput) nidnInput.required = false;
        }
    };

    roleSelect?.addEventListener('change', (e) => toggleFields(e.target.value));
})();
</script>
