<?php $base = rtrim($base_url, '/'); ?>

<div class="space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight leading-tight">Pengaturan Sistem</h1>
            <p class="text-sm text-slate-600">Kelola konfigurasi teknis dan pemeliharaan aplikasi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col md:flex-row gap-6 md:items-center justify-between">
                
                <div class="flex gap-4 items-start">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 border border-orange-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Mode Perbaikan (Maintenance)</h2>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed max-w-xl">
                            Jika aktif, akses publik akan ditutup sementara. Hanya admin yang bisa login.
                        </p>
                    </div>
                </div>

                <form action="<?= $base ?>/admin/settings/maintenance" method="POST" class="flex flex-col md:flex-row md:items-center gap-4 w-full md:w-auto pt-4 md:pt-0 border-t md:border-t-0 border-slate-100">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">
                    
                    <div class="flex items-center justify-between md:justify-start w-full md:w-auto bg-slate-50 md:bg-transparent p-3 md:p-0 rounded-xl md:rounded-none border md:border-none border-slate-100">
                        <span class="text-sm font-medium text-slate-700 md:mr-3">Status Mode</span>
                        
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance" value="1" class="sr-only peer" <?= !empty($setting['maintenance_mode']) ? 'checked' : '' ?>>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-slate-800 text-white text-sm font-semibold hover:bg-slate-900 transition-all shadow-md hover:shadow-lg active:scale-95 flex justify-center items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </button>
                </form>

            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col md:flex-row gap-6 md:items-center justify-between">
                
                <div class="flex gap-4 items-start">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Cadangan Database</h2>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed max-w-xl">
                            Unduh file SQL database terbaru untuk keperluan backup manual atau migrasi server.
                        </p>
                    </div>
                </div>

                <div class="w-full md:w-auto pt-4 md:pt-0 border-t md:border-t-0 border-slate-100">
                    <a href="<?= $base ?>/admin/settings/backup" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white border-2 border-emerald-100 text-emerald-700 text-sm font-bold hover:bg-emerald-50 hover:border-emerald-200 transition-all active:scale-95 shadow-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download SQL
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
