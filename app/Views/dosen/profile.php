<?php 
$base = rtrim($base_url, '/'); 
$u    = $user ?? $dosen ?? [];
$nama = $u['nama'] ?? $u['nama_lengkap'] ?? 'Dosen';
$role = $u['role'] ?? 'Dosen';
$email = $u['email'] ?? '';
$username = $u['username'] ?? '';
$nidn = $u['nidn_nip'] ?? '';
?>

<div class="space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight leading-tight">Profil Saya</h1>
            <p class="text-sm text-slate-600">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?= e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="px-4 py-3 rounded-xl bg-red-50 text-red-700 border border-red-100 text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 text-center h-full relative overflow-hidden">
                <div class="flex flex-col items-center relative z-10">
                    
                    <div class="relative group inline-block">
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 p-1 shadow-lg shadow-emerald-200">
                            <div class="w-full h-full rounded-xl bg-white flex items-center justify-center">
                                <span class="text-4xl font-bold text-emerald-600 select-none">
                                    <?= strtoupper(substr($nama, 0, 1)) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="absolute -bottom-2 -right-2">
                            <span class="relative flex h-8 w-8">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-8 w-8 bg-blue-500 border-2 border-white items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                              </span>
                            </span>
                        </div>
                    </div>

                    <h2 class="mt-5 text-lg font-bold text-slate-800"><?= e($nama) ?></h2>
                    
                    <p class="text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full mt-2 font-medium capitalize">
                        <?= e($role) ?>
                    </p>
                    
                    <div class="mt-6 w-full border-t border-slate-100 pt-6">
                        <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-3">Informasi Akun</div>
                        
                        <div class="flex items-center justify-center gap-2 text-slate-600 text-sm mb-2 p-1 hover:bg-slate-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="font-medium font-mono"><?= e($username) ?></span>
                        </div>

                        <div class="flex items-center justify-center gap-2 text-slate-600 text-sm p-1 hover:bg-slate-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="truncate max-w-[180px]"><?= e($email) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-emerald-50 blur-3xl opacity-50 pointer-events-none"></div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 md:p-8">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        Edit Informasi
                    </h3>
                </div>

                <form action="<?= $base ?>/dosen/profile" method="POST" class="space-y-5">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </span>
                                <input type="text" name="nama_lengkap" value="<?= e($nama) ?>" required 
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">Alamat Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <input type="email" name="email" value="<?= e($email) ?>" required 
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">Username</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </span>
                                <input type="text" name="username" value="<?= e($username) ?>" 
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">NIDN / NIP (opsional)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h9m6 0v-6m0 6h-4m4-6h-4"></path></svg>
                                </span>
                                <input type="text" name="nidn_nip" value="<?= e($nidn) ?>" 
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">Password Baru</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                                
                                <input type="password" name="password" id="inputPassword" placeholder="Kosongkan jika tidak diubah" 
                                       class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-sm placeholder:text-slate-400">
                                
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-emerald-600 focus:outline-none cursor-pointer" title="Lihat Password">
                                    <svg id="iconShow" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="iconHide" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-50 mt-4">
                        <button type="reset" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                            Reset
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-600/20 transition-all active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('inputPassword');
        const iconShow = document.getElementById('iconShow');
        const iconHide = document.getElementById('iconHide');

        if (input.type === 'password') {
            input.type = 'text';
            iconShow.classList.remove('hidden');
            iconHide.classList.add('hidden');
        } else {
            input.type = 'password';
            iconShow.classList.add('hidden');
            iconHide.classList.remove('hidden');
        }
    }
</script>
