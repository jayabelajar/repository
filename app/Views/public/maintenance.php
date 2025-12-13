<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - SIREPO INHAFI</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857',
                        },
                        slate: {
                            800: '#1e293b', 900: '#0f172a',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-600 antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-64 h-64 md:w-96 md:h-96 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-pulse"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 md:w-96 md:h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    </div>

    <div class="relative z-10 w-full max-w-md md:max-w-lg bg-white/80 backdrop-blur-xl border border-white/50 rounded-3xl shadow-2xl p-8 md:p-12 text-center transform transition-all hover:scale-[1.01] duration-500">
        
        <div class="relative inline-block mb-8">
            <div class="absolute inset-0 bg-emerald-200 rounded-full blur-xl opacity-50 animate-pulse"></div>
            <div class="relative w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-emerald-50 to-white rounded-2xl shadow-lg border border-emerald-100 flex items-center justify-center animate-float">
                <svg class="w-10 h-10 md:w-12 md:h-12 text-emerald-600 animate-[spin_10s_linear_infinite]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <div class="mb-5">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-500 text-[10px] md:text-xs font-bold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span>
                Under Maintenance
            </span>
        </div>

        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-3 tracking-tight leading-tight">
            Sistem Sedang <br/>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Diperbarui</span>
        </h1>
        
        <p class="text-sm md:text-base text-slate-500 leading-relaxed mb-8 max-w-xs mx-auto md:max-w-none">
            SIREPO sedang menjalani pemeliharaan terjadwal untuk meningkatkan performa. Kami akan segera kembali.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?= e($base_url ?? '/') ?>" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 hover:shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4"></path></svg>
                Coba ke Beranda
            </a>
            <a href="mailto:admin@kampus.ac.id" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Hubungi Admin
            </a>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100/60">
            <p class="text-[10px] text-slate-400">
                &copy; <?= date('Y') ?> SIREPO - INHAFI Panel
            </p>
        </div>
    </div>

</body>
</html>
