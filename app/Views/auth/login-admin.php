<?php
use App\Core\Security\Csrf;

$token = class_exists('App\Core\Security\Csrf') ? Csrf::token() : '';
$base_url = $base_url ?? ''; 
$base  = rtrim($base_url, '/');
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SIREPO</title>
    
    <link href="<?= $base ?>/assets/css/app.css" rel="stylesheet"> 
    <style>
        :root { color-scheme: light; }
        body.dark { background: #0f172a; color: #e2e8f0; }
        body.dark .login-card { background: #0b1220; border-color: #1f2937; color: #e2e8f0; }
        body.dark .login-card h1, body.dark .login-card p, body.dark .login-card label, body.dark .login-card span, body.dark .login-card a { color: #e2e8f0 !important; }
        body.dark .login-card .text-gray-900, body.dark .login-card .text-gray-800, body.dark .login-card .text-gray-700 { color: #f8fafc !important; }
        body.dark .login-card .text-gray-600, body.dark .login-card .text-gray-500, body.dark .login-card .text-gray-400 { color: #cbd5e1 !important; }
        body.dark .login-card .bg-gray-50, body.dark .login-card .bg-white { background: #111827 !important; }
        body.dark .login-card input, body.dark .login-card select { color: #f8fafc !important; border-color: #1f2937 !important; }
        body.dark .login-card input::placeholder { color: #94a3b8 !important; }
        body.dark .login-card .bg-red-50 { background-color: #3b0d0d !important; color: #fecdd3 !important; }
        body.dark .login-card .bg-amber-50 { background-color: #422006 !important; color: #fef08a !important; }
        body.dark .login-card .bg-emerald-600 { background-color: #10b981 !important; }
        body.dark .login-card .hover\:bg-emerald-700:hover { background-color: #059669 !important; }
        body.dark .toggle-theme { color: #cbd5e1; }
        body.dark .btn-login { box-shadow: none !important; }
        body.dark .btn-login:hover { box-shadow: none !important; }
    </style>
</head>

<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4 selection:bg-emerald-500 selection:text-white font-sans">

    <div class="w-full max-w-[400px] md:max-w-md bg-white rounded-2xl shadow-xl p-6 md:p-8 space-y-6 transition-all duration-300 relative login-card">
        <button id="themeToggle" class="toggle-theme absolute right-4 top-4 p-2 rounded-full hover:bg-gray-100 transition-colors" aria-label="Toggle tema">
            <svg id="iconSun" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m14.95 7.95L17.5 17.5M6.5 6.5 5.05 5.05M17.5 6.5l1.45-1.45M6.5 17.5 5.05 18.95M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
            <svg id="iconMoon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12.41A8 8 0 1111.59 4 6 6 0 0020 12.41z"/></svg>
        </button>
        
        <div class="text-center space-y-2">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Login Admin</h1>
            <p class="text-sm text-gray-500">Masuk untuk memantau statistik, mengelola data akademik, dan memastikan repositori rapi.</p>
        </div>

        <?php if ($error): ?>
            <div class="animate-shake"> <?php if ($error === 'invalid' || $error === 'notfound'): ?>
                    <?php
                        $roasting = [
                            "Mundur wir! Bukan admin jangan maksa login. Yahaha🤣",
                            "Skill hacking level 1 ya? Passwordnya salah tuh. awowkwok🤣",
                            "Sistem tertawa melihat usaha loginmu.🤡",
                            "Akses Ditolak! Jangan paksa aku panggil polisi ciber.🚓",
                            "Nice try, Script Kiddie. Belajar ngetik lagi sana.",
                            "Hampir berhasil... tapi bohong!",
                            "Wiuwiu ada puliciii kaborr🚓.",
                            "403 Forbidden: Muka anda kurang meyakinkan."
                        ];
                        $pesan = $roasting[array_rand($roasting)];
                    ?>
                    
                    <div class="text-sm px-4 py-3 rounded-xl bg-red-50 text-red-600 border border-red-200 flex items-center gap-3 shadow-sm ring-1 ring-red-100">
                        <div class="shrink-0 p-1.5 bg-red-100 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-[10px] uppercase tracking-wider opacity-70">ACCESS DENIED</span>
                            <span class="font-medium leading-tight"><?= $pesan; ?></span>
                        </div>
                    </div>

                <?php elseif ($error === 'banned'): ?>
                    <div class="text-sm px-4 py-3 rounded-xl bg-amber-50 text-amber-700 border border-amber-100 flex items-start gap-2">
                        <span>Akun diblokir sementara. Istirahat dulu sana.</span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div id="geoError" class="hidden text-xs px-3 py-2 rounded-lg bg-amber-50 text-amber-700 border border-amber-100 animate-pulse"></div>

        <form action="<?= $base; ?>/__admin/login" method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token); ?>">
            <input type="hidden" name="latitude" id="loginLat">
            <input type="hidden" name="longitude" id="loginLng">

            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-gray-700">Email</label>
                <input type="text" name="email" required autocomplete="username" placeholder="admin@example.com" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200" />
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-gray-700">Password</label>
                <div class="relative group">
                    <input type="password" id="passInput" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 outline-none focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200 pr-12" />
                    <button type="button" id="toggleBtn" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-emerald-600 transition-colors focus:outline-none">
                        <svg id="eyeOpen" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg id="eyeClosed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <div class="flex items-center">
                     <input type="checkbox" name="remember" id="rememberMe" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer" />
                     <label for="rememberMe" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">Ingat Saya</label>
                </div>
                <a href="https://wa.me/6285859400250" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:underline transition-colors">Lupa Password?</a>
            </div>

            <button type="submit" class="btn-login w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-emerald-200 hover:shadow-emerald-300 transition-all duration-200 transform active:scale-[0.98] flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Masuk Admin
            </button>
        </form>

        <div class="pt-2 border-t border-gray-100">
            <p class="text-xs text-center text-gray-400 leading-relaxed">
                Selain admin dilarang keras mengakses halaman ini. <br>
                <span class="text-red-400 font-semibold tracking-wide">⚠ HALAMAN INI DIPANTAU PULICI CIBER🚓!! ⚠</span>
            </p>
        </div>
    </div>

    <script>
    // Toggle Password
    document.getElementById('toggleBtn')?.addEventListener('click', function () {
        const input = document.getElementById('passInput');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
        input.focus();
    });

    // Geolocation
    (function () {
        const latInput = document.getElementById('loginLat');
        const lngInput = document.getElementById('loginLng');
        const form = document.querySelector('form');
        const geoError = document.getElementById('geoError');
        let submitting = false;

        const requestGeo = (onSuccess, onFail) => {
            if (!navigator.geolocation) return onFail && onFail('Browser not supported');
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    if (latInput) latInput.value = pos.coords.latitude.toFixed(6);
                    if (lngInput) lngInput.value = pos.coords.longitude.toFixed(6);
                    if (geoError) geoError.classList.add('hidden');
                    if (onSuccess) onSuccess();
                },
                (err) => { if (onFail) onFail('Izinkan akses lokasi & nyalakan GPS!'); },
                { enableHighAccuracy: true, timeout: 5000 }
            );
        };

        requestGeo();

        form?.addEventListener('submit', (e) => {
            if (submitting) return;
            if (!latInput.value) {
                e.preventDefault();
                requestGeo(
                    () => { submitting = true; form.submit(); },
                    (msg) => { 
                        if (geoError) {
                            geoError.textContent = msg; 
                            geoError.classList.remove('hidden');
                        }
                        submitting = true;
                        setTimeout(() => form.submit(), 1500); 
                    }
                );
            }
        });
    })();

    // Theme toggle (reuse global key)
    (function() {
        const key = 'sirepo-theme';
        const btn = document.getElementById('themeToggle');
        const sun = document.getElementById('iconSun');
        const moon = document.getElementById('iconMoon');
        const saved = localStorage.getItem(key);
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const mode = saved || (prefersDark ? 'dark' : 'light');
        const apply = (m) => {
            if (m === 'dark') {
                document.body.classList.add('dark');
                sun?.classList.add('hidden');
                moon?.classList.remove('hidden');
            } else {
                document.body.classList.remove('dark');
                sun?.classList.remove('hidden');
                moon?.classList.add('hidden');
            }
            document.documentElement.style.colorScheme = m;
            localStorage.setItem(key, m);
        };
        apply(mode);
        btn?.addEventListener('click', () => {
            const next = document.body.classList.contains('dark') ? 'light' : 'dark';
            apply(next);
        });
    })();
    </script>
</body>
</html>
