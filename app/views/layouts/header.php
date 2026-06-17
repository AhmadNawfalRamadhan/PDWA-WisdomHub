<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?></title>
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Lora:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- App JS Helpers -->
    <script src="<?= APP_URL ?>/public/assets/js/helpers.js"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-serif: 'Lora', serif;
            --color-wisdom-dark: #0f172a;
            --color-wisdom-primary: #1e3a8a;
            --color-wisdom-accent: #d97706;
            --color-wisdom-gold: #d4af37;
            --color-wisdom-sand: #fdfbf7;
            --color-wisdom-paper: #ffffff;
        }
        body {
            font-family: var(--font-sans);
            background-color: var(--color-wisdom-sand);
            color: var(--color-wisdom-dark);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        h1, h2, h3, .font-serif { font-family: var(--font-sans); }

        /* Sidebar active & hover */
        .sidebar-link { @apply flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200 text-wisdom-sand/70 font-medium; }
        .sidebar-link:hover:not(.active) { @apply bg-white/8 text-white translate-x-0.5; }
        .sidebar-link.active { @apply bg-white/15 text-white border border-white/20 shadow-sm; }
        .sidebar-link.active i { @apply text-wisdom-accent; }

        /* Status badges */
        .badge-borrowed { @apply inline-flex items-center bg-yellow-50 text-yellow-800 border border-yellow-200 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide; }
        .badge-returned { @apply inline-flex items-center bg-emerald-50 text-emerald-800 border border-emerald-200 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide; }
        .badge-overdue  { @apply inline-flex items-center bg-red-50 text-red-800 border border-red-200 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide; }

        /* Card base */
        .card { @apply bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100/80 p-6; }

        /* Micro animations */
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(217,119,6,0.4); }
            50%       { box-shadow: 0 0 0 6px rgba(217,119,6,0); }
        }
        .animate-fade-in { animation: fadeSlideIn 0.3s ease both; }
        .animate-pulse-glow { animation: pulseGlow 2s infinite; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* =========================================
           DARK MODE - Elegant Black & Gold
           ========================================= */
        .dark body {
            background-color: #0a0a0a !important;
            color: #e8e3d8 !important;
        }

        /* Main content area */
        .dark main { background-color: #0a0a0a !important; }

        /* Top Header bar */
        .dark header.bg-white\/90 {
            background-color: rgba(15,15,15,0.95) !important;
            border-color: rgba(212,175,55,0.15) !important;
        }

        /* ---- Backgrounds: only target pure classes, NOT opacity variants ---- */
        /* Pure white -> dark card */
        .dark .bg-white { background-color: #141414 !important; }
        .dark .bg-wisdom-paper { background-color: #141414 !important; }
        /* gray-50 variants with opacity modifier -> left alone (transparent feel preserved) */
        .dark .bg-gray-50\/80 { background-color: rgba(20,20,20,0.8) !important; }
        .dark .bg-gray-50\/50 { background-color: rgba(20,20,20,0.5) !important; }
        .dark .bg-gray-50 { background-color: #1a1a1a !important; }

        /* Borders */
        .dark .border-gray-100 { border-color: rgba(212,175,55,0.12) !important; }
        .dark .border-gray-100\/80 { border-color: rgba(212,175,55,0.10) !important; }
        .dark .border-gray-200 { border-color: rgba(212,175,55,0.12) !important; }

        /* ---- Text: only affect plain text, NOT icons ---- */
        .dark .text-gray-800:not([data-lucide]):not(svg),
        .dark .text-gray-700:not([data-lucide]):not(svg) { color: #e8e3d8 !important; }
        .dark .text-gray-600:not([data-lucide]):not(svg) { color: #a89f8c !important; }
        .dark .text-gray-500:not([data-lucide]):not(svg) { color: #8a8070 !important; }
        .dark .text-gray-400:not([data-lucide]):not(svg) { color: #6b6355 !important; }

        /* ---- Preserve explicit icon colors (never override these) ---- */
        .dark [data-lucide].text-wisdom-accent,
        .dark .text-wisdom-accent [data-lucide] { color: #d97706 !important; }

        .dark [data-lucide].text-emerald-500,
        .dark .text-emerald-500 [data-lucide] { color: #10b981 !important; }

        .dark [data-lucide].text-red-500,
        .dark .text-red-500 [data-lucide] { color: #ef4444 !important; }

        .dark [data-lucide].text-red-300\/80,
        .dark [data-lucide].text-red-300 { color: #fca5a5 !important; }

        .dark [data-lucide].text-blue-600,
        .dark [data-lucide].text-blue-700 { color: #60a5fa !important; }

        .dark [data-lucide].text-indigo-600,
        .dark [data-lucide].text-indigo-700 { color: #818cf8 !important; }

        .dark [data-lucide].text-yellow-400,
        .dark [data-lucide].text-yellow-600 { color: #facc15 !important; }

        .dark [data-lucide].text-emerald-600 { color: #34d399 !important; }

        .dark [data-lucide].text-gray-300 { color: #6b7280 !important; }
        .dark [data-lucide].text-gray-400 { color: #6b6355 !important; }

        /* Sidebar icon colors stay white/gold */
        .dark #sidebar [data-lucide] { color: inherit; }

        /* Sidebar: elegant gold highlights when dark */
        .dark #sidebar {
            background: linear-gradient(180deg, #0d0d0d 0%, #111 100%) !important;
            border-right: 1px solid rgba(212,175,55,0.15);
        }
        .dark .sidebar-link.active {
            background-color: rgba(212,175,55,0.12) !important;
            border-color: rgba(212,175,55,0.25) !important;
        }
        .dark .sidebar-link.active [data-lucide] { color: #d4af37 !important; }
        .dark .sidebar-link:hover:not(.active) {
            background-color: rgba(212,175,55,0.06) !important;
        }

        /* Input fields */
        .dark input:not([type=file]),
        .dark textarea,
        .dark select {
            background-color: #1e1e1e !important;
            border-color: rgba(212,175,55,0.15) !important;
            color: #e8e3d8 !important;
        }
        .dark input:focus,
        .dark textarea:focus,
        .dark select:focus {
            border-color: #d4af37 !important;
            background-color: #242424 !important;
        }
        .dark input::placeholder,
        .dark textarea::placeholder { color: #5a5347 !important; }

        /* Tables */
        .dark tbody.divide-y > tr { border-color: rgba(212,175,55,0.08) !important; }
        .dark tr:hover { background-color: rgba(212,175,55,0.04) !important; }
        .dark thead { background-color: #111 !important; }

        /* Buttons */
        .dark .bg-wisdom-primary { background-color: #1a2f6e !important; }
        .dark .text-wisdom-primary:not([data-lucide]) { color: #d4af37 !important; }
        .dark .bg-wisdom-dark { background-color: #0a0a0a !important; border: 1px solid rgba(212,175,55,0.3); }

        /* Action icon buttons (edit/delete/lend in table rows) */
        .dark .border-emerald-100 { border-color: rgba(16,185,129,0.2) !important; }
        .dark .border-blue-100 { border-color: rgba(59,130,246,0.2) !important; }
        .dark .border-red-100 { border-color: rgba(239,68,68,0.2) !important; }

        /* Blue info panels (QR scanner / cover preview) */
        .dark .bg-blue-50\/50 {
            background-color: rgba(212,175,55,0.05) !important;
            border-color: rgba(212,175,55,0.15) !important;
        }
        .dark .text-blue-800:not([data-lucide]) { color: #d4af37 !important; }
        .dark .text-blue-700:not([data-lucide]) { color: #c9a227 !important; }
        .dark .text-blue-600\/70:not([data-lucide]) { color: #a08420 !important; }
        .dark .border-blue-100,
        .dark .border-blue-200 { border-color: rgba(212,175,55,0.15) !important; }

        /* Badges */
        .dark .badge-borrowed { background-color: rgba(217,119,6,0.15) !important; color: #fbbf24 !important; border-color: rgba(217,119,6,0.3) !important; }
        .dark .badge-returned { background-color: rgba(16,185,129,0.12) !important; color: #34d399 !important; border-color: rgba(16,185,129,0.25) !important; }
        .dark .badge-overdue  { background-color: rgba(239,68,68,0.12) !important; color: #f87171 !important; border-color: rgba(239,68,68,0.25) !important; }

        /* Category badge (indigo) */
        .dark .border-indigo-100 { border-color: rgba(99,102,241,0.2) !important; }
        .dark .text-indigo-700:not([data-lucide]) { color: #a5b4fc !important; }

        /* Card */
        .dark .card { border-color: rgba(212,175,55,0.1) !important; }

        /* Scrollbar dark */
        .dark ::-webkit-scrollbar-thumb { background: #2a2a2a; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #3a3520; }

        /* Date chip in topbar */
        .dark .bg-gray-50.px-4 {
            background-color: #1a1a1a !important;
            border-color: rgba(212,175,55,0.15) !important;
        }

        /* Cover preview box */
        .dark #cover_preview_container { background-color: rgba(212,175,55,0.05) !important; border-color: rgba(212,175,55,0.15) !important; }

        /* Pagination active page */
        .dark .bg-wisdom-primary.text-white { background-color: #d4af37 !important; color: #0a0a0a !important; border-color: #d4af37 !important; }

        /* Gold glow on sidebar logo when dark */
        .dark .animate-pulse-glow { animation-name: pulseGlowGold; }
        @keyframes pulseGlowGold {
            0%, 100% { box-shadow: 0 0 0 0 rgba(212,175,55,0.5); }
            50%       { box-shadow: 0 0 0 8px rgba(212,175,55,0); }
        }
    </style>
    <!-- Dark Mode Init (run before render to prevent flash) -->
    <script>
        if (localStorage.getItem('wh-theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body class="bg-wisdom-sand min-h-screen flex antialiased">

    <!-- SIDEBAR BACKDROP -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0 md:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="w-64 min-h-screen bg-wisdom-dark text-wisdom-sand flex flex-col fixed left-0 top-0 z-50 shadow-2xl transition-transform duration-300 -translate-x-full md:translate-x-0">
        <!-- Subtle gradient overlay -->
        <div
            class="absolute inset-0 bg-gradient-to-b from-wisdom-primary/20 via-transparent to-black/20 pointer-events-none">
        </div>
        <!-- Dot pattern -->
        <div class="absolute inset-0 opacity-5 pointer-events-none"
            style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 20px 20px;">
        </div>

        <!-- Logo -->
        <div class="relative z-10 p-5 border-b border-white/10">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div
                        class="w-11 h-11 bg-gradient-to-br from-wisdom-accent to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg shadow-wisdom-accent/30 animate-pulse-glow flex-shrink-0">
                        <i data-lucide="library-big" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-serif font-bold text-base leading-tight truncate"><?= APP_NAME ?></p>
                        <p class="text-wisdom-sand/50 text-[10px] uppercase tracking-widest font-semibold">
                            <?= isAdmin() ? 'Administrator' : 'Anggota' ?>
                        </p>
                    </div>
                </div>
                <!-- Close Button for Mobile -->
                <button id="sidebar-close" class="p-2 -mr-2 rounded-xl text-wisdom-sand/60 hover:text-white hover:bg-white/10 md:hidden transition-colors" title="Tutup Sidebar">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Nav -->
        <nav class="relative z-10 flex-1 py-5 px-3 space-y-1 overflow-y-auto">
            <?php $cur = $_GET['page'] ?? 'home'; ?>

            <p class="text-wisdom-sand/30 text-[9px] font-black uppercase tracking-[0.2em] px-3 mb-3">Menu Utama</p>

            <a href="index.php?page=dashboard" class="sidebar-link <?= $cur === 'dashboard' ? 'active' : '' ?>">
                <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>

            <a href="index.php?page=books" class="sidebar-link <?= $cur === 'books' ? 'active' : '' ?>">
                <i data-lucide="book-open" class="w-4.5 h-4.5 flex-shrink-0"></i>
                <span>Katalog Buku</span>
            </a>

            <?php if (isAdmin()): ?>
                <p class="text-wisdom-sand/30 text-[9px] font-black uppercase tracking-[0.2em] px-3 mb-3 mt-5">Administrasi
                </p>

                <a href="index.php?page=borrows" class="sidebar-link <?= $cur === 'borrows' ? 'active' : '' ?>">
                    <i data-lucide="arrow-left-right" class="w-4.5 h-4.5 flex-shrink-0"></i>
                    <span class="flex-1">Sirkulasi</span>
                </a>
                <a href="index.php?page=users" class="sidebar-link <?= $cur === 'users' ? 'active' : '' ?>">
                    <i data-lucide="users" class="w-4.5 h-4.5 flex-shrink-0"></i>
                    <span>Kelola Anggota</span>
                </a>
                <a href="index.php?page=reports" class="sidebar-link <?= $cur === 'reports' ? 'active' : '' ?>">
                    <i data-lucide="bar-chart-3" class="w-4.5 h-4.5 flex-shrink-0"></i>
                    <span>Laporan</span>
                </a>
                <a href="index.php?page=settings" class="sidebar-link <?= $cur === 'settings' ? 'active' : '' ?>">
                    <i data-lucide="settings" class="w-4.5 h-4.5 flex-shrink-0"></i>
                    <span>Pengaturan</span>
                </a>
            <?php else: ?>
                <p class="text-wisdom-sand/30 text-[9px] font-black uppercase tracking-[0.2em] px-3 mb-3 mt-5">Aktivitas
                    Saya</p>
                <a href="index.php?page=my-borrows" class="sidebar-link <?= $cur === 'my-borrows' ? 'active' : '' ?>">
                    <i data-lucide="history" class="w-4.5 h-4.5 flex-shrink-0"></i>
                    <span>Riwayat Pinjam</span>
                </a>
            <?php endif; ?>
        </nav>

        <!-- User Profile -->
        <div class="relative z-10 p-3 border-t border-white/10">
            <div class="bg-white/6 rounded-2xl p-3 border border-white/10">
                <div class="flex items-center gap-3 mb-3">
                    <?php if (!empty($_SESSION['profile_picture'])): ?>
                        <img src="<?= APP_URL ?>/public/assets/profiles/<?= htmlspecialchars($_SESSION['profile_picture']) ?>"
                            alt="Profile"
                            class="w-9 h-9 rounded-full object-cover border-2 border-wisdom-accent/60 flex-shrink-0 shadow-md">
                    <?php else: ?>
                        <div
                            class="w-9 h-9 bg-gradient-to-br from-wisdom-primary to-blue-700 rounded-full flex items-center justify-center text-sm font-black border-2 border-wisdom-accent/60 flex-shrink-0 text-white shadow-md">
                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate leading-tight">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
                        </p>
                        <p class="text-wisdom-sand/50 text-[10px] uppercase tracking-wider font-semibold">
                            <?= isAdmin() ? 'Administrator' : 'Siswa/Mahasiswa' ?>
                        </p>
                    </div>
                </div>
                <a href="index.php?page=logout"
                    class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs text-red-300/80 hover:text-white hover:bg-red-500/25 rounded-xl transition-all font-bold tracking-wide">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                </a>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-64 ml-0 flex flex-col min-h-screen min-w-0">

        <!-- TOP BAR -->
        <header
            class="bg-white/90 backdrop-blur-md border-b border-gray-100/80 px-4 md:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-3">
                <!-- Hamburger Button for Mobile -->
                <button id="sidebar-toggle" class="p-2 -ml-2 rounded-xl text-gray-500 hover:bg-gray-100 md:hidden transition-colors" title="Buka Sidebar">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <span class="text-sm font-bold text-wisdom-primary md:hidden"><?= $pageTitle ?? 'Dashboard' ?></span>

                <div class="hidden md:flex items-center gap-2 text-xs font-semibold text-gray-400">
                    <i data-lucide="home" class="w-3.5 h-3.5"></i>
                    <span>/</span>
                    <span class="text-wisdom-primary font-bold"><?= $pageTitle ?? 'Dashboard' ?></span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div
                    class="hidden sm:flex items-center gap-2 text-sm font-semibold text-gray-500 bg-gray-50 px-4 py-2 rounded-full border border-gray-100 shadow-inner">
                    <i data-lucide="calendar" class="w-4 h-4 text-wisdom-accent"></i>
                    <?= date('d F Y') ?>
                </div>

                <!-- Dark Mode Toggle -->
                <button id="theme-toggle" title="Toggle Dark Mode"
                    class="w-9 h-9 rounded-full border border-gray-200 bg-gray-50 flex items-center justify-center text-gray-500 hover:border-yellow-400 hover:bg-yellow-50 hover:text-yellow-600 transition-all shadow-sm group">
                    <i data-lucide="moon" id="icon-moon" class="w-4 h-4 block"></i>
                    <i data-lucide="sun" id="icon-sun" class="w-4 h-4 hidden"></i>
                </button>
            </div>
        </header>

        <!-- FLASH MESSAGE -->
        <?php if ($flash = getFlash()): ?>
            <div id="flash-msg"
                class="mx-8 mt-5 px-5 py-3.5 rounded-2xl flex items-center gap-3 text-sm font-semibold shadow-md animate-fade-in
        <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' ?>">
                <div
                    class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 <?= $flash['type'] === 'success' ? 'bg-emerald-100' : 'bg-red-100' ?>">
                    <i data-lucide="<?= $flash['type'] === 'success' ? 'check-circle' : 'alert-circle' ?>"
                        class="<?= $flash['type'] === 'success' ? 'text-emerald-500' : 'text-red-500' ?> w-5 h-5"></i>
                </div>
                <span class="flex-1"><?= htmlspecialchars($flash['message']) ?></span>
                <button onclick="this.parentElement.style.opacity='0'; setTimeout(()=>this.parentElement.remove(),300)"
                    class="text-gray-400 hover:text-gray-600 rounded-lg p-1 hover:bg-gray-100 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        <?php endif; ?>

        <div class="flex-1 p-4 md:p-8">