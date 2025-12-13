<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
        $prefixMap = [
            '/dosen/dashboard' => 'Dashboard Dosen',
            '/dosen/repository' => 'Repository',
            '/dosen/bookmark' => 'Bookmark',
            '/dosen/activity' => 'Aktivitas',
            '/dosen/profil' => 'Profil',
        ];
        $computedTitle = null;
        foreach ($prefixMap as $prefix => $label) {
            if (strpos($path, $prefix) === 0) { $computedTitle = $label; break; }
        }
        $effectiveTitle = $page_title ?? $computedTitle ?? 'Dashboard Dosen';
    ?>
    <title><?= htmlspecialchars($effectiveTitle) ?> - SIREPO-INHAFI</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <?php
      $cssPath = __DIR__ . '/../../../public/assets/css/tailwind.css';
      $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();
    ?>
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/tailwind.css?v=<?= $cssVersion ?>">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        :root { color-scheme: light; }
        body.dark { color-scheme: dark; background-color: #0f172a; color: #e2e8f0; }
        body.dark .bg-white { background-color: #0b1220 !important; }
        body.dark [class*="bg-white/"] { background-color: rgba(11, 18, 32, 0.9) !important; }
        body.dark .bg-slate-50, body.dark .bg-slate-50\/50 { background-color: #0b1220 !important; }
        body.dark .bg-slate-100 { background-color: #111827 !important; }
        body.dark .bg-slate-200 { background-color: #1f2937 !important; }
        body.dark .text-slate-900 { color: #f8fafc !important; }
        body.dark .text-slate-800, body.dark .text-slate-700 { color: #e2e8f0 !important; }
        body.dark .text-slate-600 { color: #cbd5e1 !important; }
        body.dark .text-slate-500 { color: #94a3b8 !important; }
        body.dark .text-slate-400 { color: #9ca3af !important; }
        body.dark .border-slate-200 { border-color: #1f2937 !important; }
        body.dark .border-slate-100, body.dark .border-slate-50 { border-color: #111827 !important; }
        body.dark .bg-emerald-50 { background-color: #064e3b !important; }
        body.dark .text-emerald-700 { color: #ecfdf3 !important; }
        body.dark .text-emerald-600 { color: #d1fae5 !important; }
        body.dark .border-emerald-100 { border-color: #065f46 !important; }
        body.dark .hover\:bg-slate-50:hover,
        body.dark .hover\:bg-slate-50\/50:hover,
        body.dark .hover\:bg-slate-50\/80:hover,
        body.dark .hover\:bg-white:hover { background-color: #1f2937 !important; color: #e2e8f0 !important; }
        body.dark header, body.dark footer, body.dark nav, body.dark aside { color: #e2e8f0 !important; }
        body.dark header *, body.dark footer *, body.dark nav *, body.dark aside * { color: #e2e8f0 !important; }
        body.dark header .hover\:bg-white:hover { background-color: #1f2937 !important; }
        body.dark header .hover\:text-emerald-600:hover { color: #34d399 !important; }
        body.dark header button, body.dark header a { transition: transform 0.15s ease, background-color 0.15s ease, color 0.15s ease; }
        body.dark header button, body.dark header a { color: #e2e8f0 !important; }
        body.dark header button svg, body.dark header a svg { color: #e2e8f0 !important; }
        body.dark header button:hover, body.dark header a:hover { transform: translateY(-1px); background-color: #1f2937 !important; color: #e2e8f0 !important; }
        body.dark header button:hover svg, body.dark header a:hover svg { color: #e2e8f0 !important; }
        body.dark .menu-item:hover { background-color: #1f2937 !important; color: #e2e8f0 !important; }
        body.dark .menu-item:hover svg { color: #e2e8f0 !important; }
        body.dark .hover\:bg-blue-50:hover,
        body.dark .hover\:bg-blue-50\/50:hover { background-color: #1e293b !important; color: #e2e8f0 !important; border-color: #1e3a8a !important; }
        body.dark .hover\:border-blue-100:hover { border-color: #1e3a8a !important; }
        body.dark .hover\:text-blue-600:hover { color: #bfdbfe !important; }
        body.dark .hover\:bg-red-50:hover { background-color: #3f1d2e !important; color: #fecdd3 !important; }
        body.dark .hover\:border-red-100:hover { border-color: #7f1d1d !important; }
        body.dark .hover\:text-red-600:hover { color: #fecdd3 !important; }
        body.dark .shadow-lg { box-shadow: 0 15px 40px rgba(0,0,0,0.45); }
        body.dark .shadow-sm { box-shadow: 0 8px 24px rgba(0,0,0,0.35); }
        body.dark .ring-slate-900\/5 { --tw-ring-color: rgba(15,23,42,0.35); }
        body.dark h1, body.dark h2, body.dark h3, body.dark .card-title, body.dark .section-title { color: #e2e8f0 !important; }
        body.dark .section-subtitle { color: #cbd5e1 !important; }
        body.dark .btn-cta { background-color: #10b981 !important; color: #ecfeff !important; }
        body.dark .btn-cta:hover { background-color: #059669 !important; color: #ecfeff !important; }
        body.dark table thead th { color: #e2e8f0 !important; border-color: #1f2937 !important; }
        body.dark table tbody tr { color: #f8fafc !important; }
        body.dark table tbody tr:nth-child(odd) { background-color: #0f172a !important; }
        body.dark table tbody tr:nth-child(even) { background-color: #111827 !important; }
        body.dark table td { border-color: #1f2937 !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none;  scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        
        .sidebar-trans { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), margin 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease-in-out; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">
<script>
    (function() {
        const key = 'sirepo-theme';
        const path = (window.location.pathname || '').replace(/\/+$/, '') || '/';
        const defaultLight = /\/dosen\/(repository|my-repository)(\/[^\/]+\/edit|\/create)$/.test(path);

        let saved = localStorage.getItem(key);
        if (!saved || defaultLight) {
            saved = 'light';
            localStorage.setItem(key, saved);
        }

        const mode = saved === 'dark' ? 'dark' : 'light';
        if (mode === 'dark') {
            document.body.classList.add('dark');
        } else {
            document.body.classList.remove('dark');
        }
        document.documentElement.style.colorScheme = mode;
    })();
</script>

<div class="flex h-screen w-full relative overflow-hidden">

    <aside id="sidebar" 
           class="fixed inset-y-0 left-0 z-50 bg-white/95 backdrop-blur-xl border-r border-slate-200 flex flex-col shadow-2xl md:shadow-none
                  sidebar-trans 
                  w-72 
                  -translate-x-full md:translate-x-0">
        
        <div id="sidebarHeader" class="h-16 md:h-20 flex items-center px-6 border-b border-slate-100 transition-all duration-300 flex-shrink-0 overflow-hidden w-full">
            
            <div id="brandWrapper" class="flex items-center gap-3 w-full transition-all duration-300 origin-left">
                
                <div class="relative flex-shrink-0 flex items-center justify-center w-8 h-8 md:w-10 md:h-10 transition-all duration-300">
                    <div class="w-full h-full rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-bold text-base md:text-lg shadow-lg shadow-emerald-200">
                        <?= strtoupper(substr($dosen['nama'] ?? 'D', 0, 1)) ?>
                    </div>
                </div>

                <div id="brandText" class="sidebar-text opacity-100 transition-all duration-300 whitespace-nowrap overflow-hidden flex-1 min-w-0">
                    <p class="text-[10px] font-bold text-emerald-600 tracking-wider uppercase">SIREPO INHAFI</p>
                    <p class="text-sm md:text-base font-bold text-slate-800 leading-tight truncate">Dosen Panel</p>
                </div>
            </div>

            <button id="mobileClose" class="md:hidden text-slate-400 hover:text-red-500 flex-shrink-0 p-2 ml-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 md:py-6 px-3 space-y-4 md:space-y-6 no-scrollbar">
            <?php 
            function renderMenuItem($url, $label, $iconPath, $currentUrl, $baseUrl) {
                $isActive = strpos($currentUrl, $url) !== false;
                $activeClass = $isActive 
                    ? 'bg-emerald-50 text-emerald-700 shadow-sm shadow-emerald-100 ring-1 ring-emerald-200/50' 
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900';
                
                return "
                <a href='{$baseUrl}{$url}' class='menu-item flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {$activeClass}'>
                    <div class='flex-shrink-0 w-5 h-5 flex items-center justify-center'>
                        <svg class='w-5 h-5 flex-shrink-0' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>{$iconPath}</svg>
                    </div>
                    <span class='sidebar-text whitespace-nowrap overflow-hidden'>{$label}</span>
                    " . ($isActive ? "<div class='ml-auto w-1.5 h-1.5 rounded-full bg-emerald-500 sidebar-text flex-shrink-0'></div>" : "") . "
                </a>";
            }
            $current = $_SERVER['REQUEST_URI'];
            ?>

            <div>
                <p class="px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 sidebar-text">Menu Utama</p>
                <div class="space-y-1">
                    <?= renderMenuItem('/dosen/dashboard', 'Dashboard', '<path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>', $current, $base_url) ?>
                    
                    <?= renderMenuItem('/dosen/repository', 'Repository', '<path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>', $current, $base_url) ?>
                    
                    <?= renderMenuItem('/dosen/bookmark', 'Bookmarks', '<path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>', $current, $base_url) ?>
                </div>
            </div>

            <div>
                <p class="px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 sidebar-text">Eksplorasi</p>
                <div class="space-y-1">
                    <?= renderMenuItem('/telusuri/', 'Telusuri', '<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>', $current, $base_url) ?>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex-shrink-0">
            <div id="userCard" class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-100 shadow-sm transition-all duration-300 overflow-hidden">
                <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold flex-shrink-0">
                     <?= strtoupper(substr($dosen['nama'] ?? 'D',0,1)) ?>
                </div>
                <div class="flex-1 min-w-0 sidebar-text">
                    <p class="text-sm font-semibold text-slate-800 truncate"><?= e($dosen['nama'] ?? 'Dosen') ?></p>
                    <p class="text-xs text-slate-400 truncate">Dosen</p>
                </div>
                <?php $csrfLogout = \App\Core\Security\Csrf::token(); ?>
                <form action="<?= $base_url ?>/logout" method="POST" class="sidebar-text flex-shrink-0">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfLogout) ?>">
                    <button class="text-slate-400 hover:text-red-500 transition-colors" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

    <div id="mainContent" class="flex-1 flex flex-col h-full trans-all md:ml-72 bg-slate-50 w-full overflow-y-auto custom-scrollbar">
        
        <header class="h-16 md:h-20 px-4 md:px-6 bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 flex items-center justify-between flex-shrink-0 w-full">
            
            <div class="flex items-center gap-2 md:gap-4 flex-1 min-w-0">
                <button id="desktopToggle" class="hidden md:flex p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>

                <button id="mobileHamburger" class="md:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <div class="md:hidden font-bold text-slate-800 text-base whitespace-nowrap truncate">
                    SIREPO <span class="text-emerald-600">INHAFI</span>
                </div>

                <div class="hidden md:block w-full max-w-sm ml-2">
                    <form action="<?= $base_url ?>/dosen/repository" method="GET" class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="search" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Cari repository..." 
                               class="w-full py-2.5 pl-10 pr-4 bg-slate-100 border-none rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500/20 transition-all">
                    </form>
                </div>
            </div>

            <div class="flex items-center gap-1 md:gap-4 flex-shrink-0 ml-2">
                <button id="themeToggle" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg flex-shrink-0" aria-label="Toggle tema">
                    <svg id="iconSun" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m14.95 7.95L17.5 17.5M6.5 6.5 5.05 5.05M17.5 6.5l1.45-1.45M6.5 17.5 5.05 18.95M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    <svg id="iconMoon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12.41A8 8 0 1111.59 4 6 6 0 0020 12.41z"/></svg>
                </button>
                
                <?php $notifActivities = $header_activities ?? ($activities ?? []); ?>
                <div class="relative">
                    <button id="notifBtn" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg relative flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <?php if (!empty($notifActivities)): ?>
                        <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white"></span>
                        </span>
                        <?php endif; ?>
                    </button>
                    
                    <div id="notifDropdown" class="hidden absolute right-[-50px] sm:right-0 mt-3 w-[85vw] sm:w-80 md:w-96 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 overflow-hidden ring-1 ring-slate-900/5 origin-top-right transition-all transform opacity-0 scale-95 data-[state=open]:opacity-100 data-[state=open]:scale-100">
                        <div class="px-5 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                            <span class="font-bold text-sm text-slate-800">Notifikasi</span>
                            <a href="<?= $base_url ?>/dosen/activity" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua</a>
                        </div>
                        
                        <ul class="max-h-[50vh] overflow-y-auto custom-scrollbar divide-y divide-slate-50">
                            <?php if (!empty($notifActivities)): ?>
                                <?php foreach ($notifActivities as $act): ?>
                                    <li class="p-4 hover:bg-slate-50 transition-colors cursor-default group">
                                        <div class="flex gap-3 items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                 <div class="w-8 h-8 rounded-full bg-slate-100 text-emerald-600 font-bold text-[10px] flex items-center justify-center border border-slate-200 group-hover:border-emerald-200 group-hover:bg-emerald-50 transition-colors">
                                                    <?= strtoupper(substr($act['actor'] ?? 'D', 0, 1)) ?>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0"> 
                                                <div class="flex justify-between items-baseline gap-2 mb-0.5">
                                                    <p class="text-sm font-bold text-slate-700 truncate">
                                                        <?= htmlspecialchars($act['actor'] ?? 'System') ?>
                                                    </p>
                                                    <span class="text-[10px] text-slate-400 whitespace-nowrap flex-shrink-0">
                                                        <?= htmlspecialchars($act['time'] ?? '') ?>
                                                    </span>
                                                </div>
                                                <p class="text-xs text-slate-500 leading-snug line-clamp-2 break-words">
                                                    <?= htmlspecialchars($act['action'] ?? '-') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="py-12 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <p class="text-xs">Tidak ada notifikasi baru.</p>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="relative pl-2 border-l border-slate-200 ml-2">
                    <button id="profileBtn" class="flex items-center gap-2 md:gap-3 focus:outline-none group">
                        
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-emerald-600 transition-colors"><?= htmlspecialchars($dosen['nama'] ?? 'Dosen') ?></span>
                            <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Dosen</span>
                        </div>

                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-400 p-[2px] flex-shrink-0">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                <span class="font-bold text-emerald-600 text-xs md:text-sm"><?= strtoupper(substr($dosen['nama'] ?? 'D',0,1)) ?></span>
                            </div>
                        </div>
                    </button>
                    
                    <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-56 bg-white border border-slate-100 rounded-xl shadow-lg z-50 origin-top-right">
                        
                        <div class="md:hidden px-4 py-3 border-b border-slate-50 bg-slate-50/50">
                            <p class="text-sm font-bold text-slate-800 truncate"><?= htmlspecialchars($dosen['nama'] ?? 'Dosen') ?></p>
                            <p class="text-[10px] text-slate-500 uppercase tracking-wide">Dosen</p>
                        </div>

                        <div class="p-2 space-y-1">
                            <a href="<?= $base_url ?>/dosen/profile" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-600 rounded-lg hover:bg-slate-50 hover:text-emerald-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Edit Profile
                            </a>
                            <form action="<?= $base_url ?>/logout" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfLogout) ?>">
                                <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
        <?php if (!empty($_SESSION['flash_success']) || !empty($_SESSION['flash_error'])): ?>
            <div class="px-4 md:px-6 pt-3">
                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="mb-3 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3 shadow-sm">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <div class="text-sm font-semibold"><?= e($_SESSION['flash_success']); ?></div>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div class="mb-3 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 shadow-sm">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <div class="text-sm font-semibold"><?= e($_SESSION['flash_error']); ?></div>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <main class="w-full p-4 md:p-8">
            <div class="max-w-7xl mx-auto space-y-4 animate-fade-in-up">
                
                <?php
                    $page_title = $page_title ?? '';
                    $page_subtitle = $page_subtitle ?? '';
                    $breadcrumbs = $breadcrumb ?? [
                        ['label' => 'Dashboard', 'url' => '/dosen/dashboard'],
                        ['label' => $page_title],
                    ];
                    $effectiveTitle = $page_title;
                    if ($effectiveTitle === '' && !empty($breadcrumbs)) {
                        $last = end($breadcrumbs);
                        $effectiveTitle = $last['label'] ?? '';
                    }
                ?>
                
                <?php $suppressLayoutTitle = $suppress_layout_title ?? false; ?>
                <?php if ($effectiveTitle && strtolower($effectiveTitle) !== 'dashboard' && strtolower($effectiveTitle) !== 'dashboard dosen'): ?>
                    <div class="space-y-1 mb-2">
                        <div class="flex items-center gap-2 text-sm text-slate-400 flex-wrap">
                            <?php foreach ($breadcrumbs as $index => $crumb): ?>
                                <?php if ($index > 0): ?>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <?php endif; ?>
                                <?php if (!empty($crumb['url'])): ?>
                                    <a href="<?= $base_url ?>/<?= ltrim($crumb['url'], '/') ?>" class="font-medium text-emerald-600 hover:text-emerald-700 transition-colors">
                                        <?= htmlspecialchars($crumb['label']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="<?= $index === count($breadcrumbs) - 1 ? 'text-emerald-600 font-medium' : '' ?>">
                                        <?= htmlspecialchars($crumb['label']) ?>
                                    </span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!$suppressLayoutTitle): ?>
                        <div>
                            <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight leading-tight"><?= htmlspecialchars($effectiveTitle) ?></h1>
                            <?php if (!empty($page_subtitle)): ?>
                                <p class="text-sm text-slate-500 mt-1"><?= htmlspecialchars($page_subtitle) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="w-full">
                    <?= $content ?>
                </div>
            </div>
        </main>

        <footer class="py-6 px-6 md:px-8 text-xs text-slate-400 border-t border-slate-200 mt-auto bg-white/50 backdrop-blur-sm flex-shrink-0">
            <div class="flex flex-col items-center md:flex-row md:items-center md:justify-between gap-2 text-center md:text-left">
                <span>&copy; <?= date('Y') ?> 
                <a href="https://inhafi.ac.id" target="_blank" >
                    Institut Agama Islam Hasan Jufri</span>
                </a>
                <span class="font-semibold text-slate-600">Dikembangkan oleh 
                <a href="#" target="_blank" >
                   VeritasDev
                </a> | 
                <a href="https://polije.ac.id" target="_blank" >
                   Politeknik Negeri Jember
                </a>  
                </span>
                </span>
            </div>
        </footer>

    </div>
</div>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
</style>

<script>
    const themeToggle = document.getElementById('themeToggle');
    const iconSun = document.getElementById('iconSun');
    const iconMoon = document.getElementById('iconMoon');
    const THEME_KEY = 'sirepo-theme';

    function setTheme(mode) {
        if (mode === 'dark') {
            document.body.classList.add('dark');
            iconSun?.classList.add('hidden');
            iconMoon?.classList.remove('hidden');
            document.documentElement.style.colorScheme = 'dark';
        } else {
            document.body.classList.remove('dark');
            iconSun?.classList.remove('hidden');
            iconMoon?.classList.add('hidden');
            document.documentElement.style.colorScheme = 'light';
        }
        localStorage.setItem(THEME_KEY, mode);
    }

    const currentTheme = document.body.classList.contains('dark') ? 'dark' : 'light';
    setTheme(currentTheme);

    themeToggle?.addEventListener('click', () => {
        const next = document.body.classList.contains('dark') ? 'light' : 'dark';
        setTheme(next);
    });

    // === LOGIC UTAMA (SAMA PERSIS DENGAN ADMIN) ===
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const sidebarHeader = document.getElementById('sidebarHeader');
    const brandWrapper = document.getElementById('brandWrapper'); 
    const brandText = document.getElementById('brandText');
    const userCard = document.getElementById('userCard');
    const desktopToggle = document.getElementById('desktopToggle');
    
    const sidebarTexts = document.querySelectorAll('.sidebar-text');
    const menuItems = document.querySelectorAll('.menu-item');

    const SIDEBAR_KEY = 'sirepo-sidebar-expanded';
    let isExpanded = localStorage.getItem(SIDEBAR_KEY) !== 'false';

    function collapseSidebar(persist = true) {
        sidebar.classList.remove('w-72');
        sidebar.classList.add('w-20');

        mainContent.classList.remove('md:ml-72');
        mainContent.classList.add('md:ml-20');

        sidebarHeader.classList.remove('px-6');
        sidebarHeader.classList.add('px-0', 'justify-center');

        brandWrapper.classList.remove('gap-3', 'w-full');
        brandWrapper.classList.add('justify-center');

        sidebarTexts.forEach(el => el.classList.add('hidden'));
        if(brandText) brandText.style.width = '0px';

        menuItems.forEach(el => {
            el.classList.remove('px-4');
            el.classList.add('justify-center', 'px-0');
        });

        userCard.classList.remove('p-3', 'gap-3');
        userCard.classList.add('p-2', 'justify-center');

        isExpanded = false;
        if (persist) localStorage.setItem(SIDEBAR_KEY, 'false');
    }

    function expandSidebar(persist = true) {
        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-72');

        mainContent.classList.remove('md:ml-20');
        mainContent.classList.add('md:ml-72');

        sidebarHeader.classList.remove('px-0', 'justify-center');
        sidebarHeader.classList.add('px-6'); 

        brandWrapper.classList.remove('justify-center');
        brandWrapper.classList.add('gap-3', 'w-full'); 

        sidebarTexts.forEach(el => el.classList.remove('hidden'));
        if(brandText) brandText.style.width = '';

        menuItems.forEach(el => {
            el.classList.remove('justify-center', 'px-0');
            el.classList.add('px-4');
        });

        userCard.classList.remove('p-2', 'justify-center');
        userCard.classList.add('p-3', 'gap-3');

        isExpanded = true;
        if (persist) localStorage.setItem(SIDEBAR_KEY, 'true');
    }

    // apply initial state
    if (!isExpanded) {
        collapseSidebar(false);
    }

    // 1. DESKTOP TOGGLE LOGIC
    desktopToggle.addEventListener('click', () => {
        if(window.innerWidth < 768) return; 

        if(isExpanded) {
            collapseSidebar();
        } else {
            expandSidebar();
        }
    });

    // 2. MOBILE LOGIC
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mobileHamburger = document.getElementById('mobileHamburger');
    const mobileClose = document.getElementById('mobileClose');

    function toggleMobileSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        sidebarOverlay.classList.toggle('hidden');
    }

    mobileHamburger.addEventListener('click', toggleMobileSidebar);
    mobileClose.addEventListener('click', toggleMobileSidebar);
    sidebarOverlay.addEventListener('click', toggleMobileSidebar);

    // 3. DROPDOWN
    function setupDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.remove('opacity-0', 'scale-95');
                    menu.setAttribute('data-state', 'open');
                }, 10);
            } else {
                menu.classList.add('opacity-0', 'scale-95');
                menu.removeAttribute('data-state');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 200); 
            }
        });

        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.add('opacity-0', 'scale-95');
                menu.removeAttribute('data-state');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 200);
            }
        });
    }
    setupDropdown('notifBtn', 'notifDropdown');
    setupDropdown('profileBtn', 'profileDropdown');
</script>

</body>
</html>
