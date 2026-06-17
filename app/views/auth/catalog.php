<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Katalog Buku — <?= APP_NAME ?></title>
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Google Fonts: Playfair Display (Serif) & Plus Jakarta Sans (Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- JS Helpers -->
    <script src="public/assets/js/helpers.js?v=1.0.1"></script>

    <style>
        /* Standard CSS: Applied instantly by the browser before page render */
        .wh-splash-done .splash-screen {
            display: none !important;
        }

        .splash-screen {
            animation: fadeOut 0.5s ease forwards;
            animation-delay: 1.5s;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
                visibility: visible;
            }

            100% {
                opacity: 0;
                visibility: hidden;
            }
        }

        .splash-logo {
            animation: popIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes popIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .splash-text {
            opacity: 0;
            animation: slideUpFade 0.5s ease forwards;
            animation-delay: 0.3s;
        }

        @keyframes slideUpFade {
            0% {
                transform: translateY(20px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-serif: 'Playfair Display', serif;
            --color-wisdom-dark: #0f172a;      /* Deep night sky */
            --color-wisdom-primary: #1e3a8a;   /* Deep Blue */
            --color-wisdom-accent: #d97706;    /* Antique Gold */
            --color-wisdom-sand: #fdfbf7;      /* Parchment/Sand */
            --color-wisdom-paper: #ffffff;
        }
        body {
            font-family: var(--font-sans);
            background-color: transparent;
            color: var(--color-wisdom-dark);
        }

        /* Fixed video background */
        #page-video-bg {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
            pointer-events: none;
        }
        /* Subtle dark tint over video so text stays readable */
        #page-video-overlay {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(0px);
            pointer-events: none;
        }
        h1, h2, h3, .font-serif {
            font-family: var(--font-sans);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--color-wisdom-sand); }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Infinite Marquee */
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
    </style>
</head>

<body class="antialiased flex flex-col min-h-screen">

    <!-- LIVE VIDEO BACKGROUND -->
    <video id="page-video-bg" autoplay muted loop playsinline preload="auto">
        <!-- Utamakan file lokal, fallback ke online jika belum ada -->
        <source src="<?= APP_URL ?>/public/assets/videos/wind-turbines.mp4" type="video/mp4">
        <source src="https://motionbgs.com/media/4622/wind-turbines.960x540.mp4" type="video/mp4">
    </video>
    <div id="page-video-overlay"></div>

    <!-- SPLASH SCREEN / OPENING ANIMATION -->
    <div
        class="splash-screen fixed inset-0 z-[100] bg-wisdom-primary flex flex-col items-center justify-center text-white">
        <div
            class="splash-logo w-24 h-24 bg-gradient-to-br from-wisdom-accent to-yellow-600 rounded-3xl flex items-center justify-center shadow-2xl shadow-wisdom-accent/30 mb-6">
            <i data-lucide="library-big" class="w-12 h-12 text-white"></i>
        </div>
        <h1 class="splash-text text-4xl font-bold font-sans tracking-tight mb-2">Wisdom Hub</h1>
        <p class="splash-text text-blue-200/80 text-sm font-medium tracking-widest uppercase"
            style="animation-delay: 0.5s;">Memuat...</p>
    </div>

    <!-- PUBLIC NAV -->
    <nav class="text-wisdom-sand px-3 md:px-6 py-4 sticky top-0 z-50 shadow-md border-b border-white/10"
        style="background: rgba(15,23,42,0.92); backdrop-filter: blur(20px);">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2 md:gap-3">
                <i data-lucide="library-big" class="text-wisdom-accent w-6 h-6 md:w-8 h-8 flex-shrink-0"></i>
                <span class="font-serif font-bold text-lg md:text-2xl tracking-wide whitespace-nowrap"><?= APP_NAME ?></span>
            </div>
            <div class="flex gap-2 md:gap-4">
                <a href="index.php?page=login"
                    class="px-3 py-1.5 md:px-5 md:py-2 text-xs md:text-sm font-medium text-wisdom-sand hover:text-wisdom-accent transition-colors flex items-center gap-1.5 whitespace-nowrap">
                    <i data-lucide="log-in" class="w-3.5 h-3.5 md:w-4 h-4 hidden sm:inline-block"></i> Masuk
                </a>
                <a href="index.php?page=register"
                    class="px-3 py-1.5 md:px-5 md:py-2 text-xs md:text-sm bg-wisdom-accent text-white font-semibold rounded-full hover:bg-yellow-600 transition-all shadow-lg shadow-wisdom-accent/20 flex items-center gap-1.5 whitespace-nowrap">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5 md:w-4 h-4 hidden sm:inline-block"></i> Daftar
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="relative overflow-hidden text-wisdom-sand"
        style="background: rgba(30,58,138,0.88); backdrop-filter: blur(1px);">
        <!-- Subtle dot pattern overlay -->
        <div class="absolute inset-0 opacity-5 pointer-events-none"
            style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;">
        </div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-wisdom-accent/10 rounded-full blur-3xl pointer-events-none">
        </div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 py-16 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-serif mb-4 drop-shadow-md">
                Wisdom Hub <br /> <span class="text-wisdom-accent text-3xl md:text-4xl mt-2 block">Pusat
                    Kebijaksanaan & Ilmu Pengetahuan</span>
            </h1>
            <p class="text-blue-100 max-w-2xl mx-auto text-lg mb-8 leading-relaxed">
                Jelajahi ribuan koleksi literatur, manuskrip, dan buku pengetahuan modern. Terangi pikiranmu di
                perpustakaan digital kami.
            </p>

            <!-- Search & Filter -->
            <form method="GET" action="index.php"
                class="bg-white/10 backdrop-blur-md rounded-3xl p-2 max-w-3xl mx-auto border border-white/20 shadow-2xl flex flex-col md:flex-row gap-2">
                <input type="hidden" name="page" value="home">

                <div class="flex-1 relative flex items-center">
                    <i data-lucide="search" class="absolute left-4 text-wisdom-sand/70 w-5 h-5"></i>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                        class="w-full bg-transparent pl-12 pr-4 py-3 text-wisdom-sand placeholder-wisdom-sand/60 outline-none focus:ring-0"
                        placeholder="Cari judul, pengarang, ISBN...">
                </div>

                <div
                    class="w-full md:w-60 relative flex items-center border-t md:border-t-0 md:border-l border-white/20">
                    <i data-lucide="book-open" class="absolute left-4 text-wisdom-sand/70 w-5 h-5"></i>
                    <select name="category"
                        class="w-full bg-transparent pl-12 pr-8 py-3 text-wisdom-dark md:text-wisdom-sand appearance-none outline-none cursor-pointer [&>option]:text-wisdom-dark">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down"
                        class="absolute right-4 text-wisdom-sand/70 w-4 h-4 pointer-events-none"></i>
                </div>

                <button type="submit"
                    class="bg-wisdom-accent hover:bg-yellow-600 text-white px-8 py-3 rounded-2xl font-semibold transition-colors flex items-center justify-center gap-2">
                    Cari
                </button>
                <?php if ($search || $categoryId): ?>
                    <a href="index.php?page=home"
                        class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-2xl font-medium transition-colors flex items-center justify-center">
                        Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Running Text / Marquee -->
        <?php if (!empty($randomBooks)): ?>
            <div
                class="w-full bg-black/10 border-t border-white/10 mt-8 relative z-10 backdrop-blur-sm overflow-hidden flex whitespace-nowrap group">
                <div
                    class="py-2.5 text-sm font-medium text-wisdom-sand flex items-center tracking-wide animate-marquee shrink-0">
                    <?php foreach ($randomBooks as $rb): ?>
                        <span class="mx-6">
                            <span class="text-wisdom-accent mr-1">✦</span>
                            <?= htmlspecialchars($rb['title']) ?>
                            <span
                                class="text-wisdom-sand/60 text-xs ml-1 italic">(<?= htmlspecialchars($rb['author']) ?>)</span>
                        </span>
                    <?php endforeach; ?>
                </div>
                <!-- Duplicate for seamless loop -->
                <div aria-hidden="true"
                    class="py-2.5 text-sm font-medium text-wisdom-sand flex items-center tracking-wide animate-marquee shrink-0">
                    <?php foreach ($randomBooks as $rb): ?>
                        <span class="mx-6">
                            <span class="text-wisdom-accent mr-1">✦</span>
                            <?= htmlspecialchars($rb['title']) ?>
                            <span
                                class="text-wisdom-sand/60 text-xs ml-1 italic">(<?= htmlspecialchars($rb['author']) ?>)</span>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <main class="flex-1 max-w-7xl mx-auto px-6 py-12 w-full" style="position: relative; z-index: 1;">
        <!-- Results Header -->
        <div class="flex items-center justify-between mb-8 pb-4" style="border-bottom: 1px solid rgba(30,58,138,0.15);">
            <h2 class="text-2xl font-serif font-bold text-wisdom-dark flex items-center gap-2">
                <i data-lucide="library" class="text-wisdom-primary"></i> Koleksi Kami
            </h2>
            <p class="text-sm font-medium text-gray-600 px-3 py-1 rounded-full border border-white/60 shadow-sm"
                style="background: rgba(255,255,255,0.75); backdrop-filter: blur(8px);">
                <?= $books['total'] ?> buku ditemukan
            </p>
        </div>

        <?php if (empty($books['data'])): ?>
            <div class="text-center py-24 rounded-3xl border border-white/60 shadow-lg"
                style="background: rgba(255,255,255,0.75); backdrop-filter: blur(12px);">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <i data-lucide="book-x" class="w-12 h-12"></i>
                </div>
                <h3 class="font-serif text-xl font-bold text-gray-700 mb-2">Buku tidak ditemukan</h3>
                <p class="text-gray-500">Maaf, kami tidak menemukan buku yang cocok dengan pencarian Anda.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($books['data'] as $book): ?>
                    <div class="rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col h-full transform hover:-translate-y-1 relative"
                        style="background: rgba(255,255,255,0.88); backdrop-filter: blur(12px); border: 2px solid rgba(30,58,138,0.30); box-shadow: 0 4px 24px rgba(30,58,138,0.12);">
                        <!-- Book Cover -->
                        <div class="h-64 bg-gray-100 flex items-center justify-center relative overflow-hidden">
                            <?php if ($book['cover_image']): ?>
                                <img src="<?= htmlspecialchars($book['cover_image']) ?>"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <?php else: ?>
                                <div
                                    class="w-full h-full bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                                    <i data-lucide="book-copy" class="w-16 h-16 text-blue-200"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Overlay gradient -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <!-- Stock Badge -->
                            <div class="absolute top-3 right-3 z-10">
                                <?php if ($book['stock'] > 0): ?>
                                    <span
                                        class="px-3 py-1 bg-green-500/90 backdrop-blur-sm text-white text-xs rounded-full font-bold shadow-sm border border-green-400">
                                        Tersedia
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="px-3 py-1 bg-red-500/90 backdrop-blur-sm text-white text-xs rounded-full font-bold shadow-sm border border-red-400">
                                        Habis
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Book Info -->
                        <div class="p-5 flex flex-col flex-1">
                            <p
                                class="text-[10px] uppercase tracking-wider font-bold text-wisdom-primary mb-2 flex items-center gap-1">
                                <i data-lucide="tag" class="w-3 h-3"></i>
                                <?= htmlspecialchars($book['category_name'] ?? 'Umum') ?>
                            </p>
                            <h3
                                class="font-serif font-bold text-gray-900 text-lg leading-tight mb-2 line-clamp-2 group-hover:text-wisdom-primary transition-colors">
                                <?= htmlspecialchars($book['title']) ?>
                            </h3>

                            <div class="space-y-1 mt-auto pt-2">
                                <p class="text-gray-600 text-sm flex items-center gap-2">
                                    <i data-lucide="pen-tool" class="w-4 h-4 text-gray-400"></i>
                                    <span class="truncate"><?= htmlspecialchars($book['author']) ?></span>
                                </p>
                                <p class="text-gray-400 text-xs flex items-center gap-2">
                                    <i data-lucide="barcode" class="w-4 h-4 text-gray-300"></i>
                                    <?= htmlspecialchars($book['isbn']) ?>
                                </p>
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-xs text-gray-500 font-medium bg-gray-50 px-2 py-1 rounded">
                                    Stok: <strong
                                        class="text-wisdom-dark"><?= $book['stock'] ?>/<?= $book['total_stock'] ?></strong>
                                </span>
                                <?php if (isLoggedIn()): ?>
                                    <form method="POST" action="index.php?page=borrows&action=book" class="inline">
                                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                        <button type="submit"
                                            onclick="event.preventDefault(); let form = this.closest('form'); showWisdomConfirm('Anda yakin ingin mem-booking buku ini? Buku harus diambil dalam 2 hari ke depan.', () => form.submit());"
                                            class="text-sm text-wisdom-accent hover:text-yellow-700 font-bold flex items-center gap-1 group/btn cursor-pointer">
                                            Booking <i data-lucide="arrow-right"
                                                class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <a href="index.php?page=login"
                                        class="text-sm text-wisdom-accent hover:text-yellow-700 font-bold flex items-center gap-1 group/btn">
                                        Pinjam <i data-lucide="arrow-right"
                                            class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($books['total_pages'] > 1): ?>
                <div class="mt-12 flex justify-center gap-2">
                    <?php for ($i = 1; $i <= $books['total_pages']; $i++): ?>
                        <a href="index.php?page=home&search=<?= urlencode($search) ?>&category=<?= $categoryId ?>&pg=<?= $i ?>"
                            class="w-10 h-10 flex items-center justify-center text-sm font-medium rounded-full border transition-all <?= $i == $books['current_page'] ? 'bg-wisdom-primary text-white border-wisdom-primary shadow-md' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>



    <!-- ==============================
         DYNAMIC CONTACT SECTION
         ============================== -->
    <section class="w-full py-16 px-6 relative z-10">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">

            <div
                class="flex items-center gap-4 bg-slate-900/50 backdrop-blur-md p-6 rounded-3xl border border-white/10 hover:bg-slate-900/70 transition-colors shadow-xl">
                <div class="w-12 h-12 rounded-xl bg-wisdom-accent/20 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="phone" class="w-6 h-6 text-wisdom-accent"></i>
                </div>
                <div class="text-wisdom-sand">
                    <h4 class="font-bold text-lg">Hubungi Kami</h4>
                    <p class="text-sm opacity-90 mt-1 leading-relaxed">
                        <?= htmlspecialchars($settings['contact_phone'] ?? '-') ?><br><?= htmlspecialchars($settings['contact_email'] ?? '-') ?>
                    </p>
                </div>
            </div>

            <div
                class="flex items-center gap-4 bg-slate-900/50 backdrop-blur-md p-6 rounded-3xl border border-white/10 hover:bg-slate-900/70 transition-colors shadow-xl">
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="map-pin" class="w-6 h-6 text-blue-400"></i>
                </div>
                <div class="text-wisdom-sand">
                    <h4 class="font-bold text-lg">Lokasi Kami</h4>
                    <p class="text-sm opacity-90 mt-1 leading-relaxed">
                        <?= nl2br(htmlspecialchars($settings['contact_address'] ?? '-')) ?>
                    </p>
                </div>
            </div>

            <div
                class="flex items-center gap-4 bg-slate-900/50 backdrop-blur-md p-6 rounded-3xl border border-white/10 hover:bg-slate-900/70 transition-colors shadow-xl">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clock" class="w-6 h-6 text-emerald-400"></i>
                </div>
                <div class="text-wisdom-sand">
                    <h4 class="font-bold text-lg">Jam Operasional</h4>
                    <p class="text-sm opacity-90 mt-1 leading-relaxed">
                        <?php
                        $opsData = parseOperationalHours($settings['operational_hours'] ?? '');
                        foreach ($opsData as $op) {
                            $timeStr = !empty($op['time']) ? htmlspecialchars($op['time']) : '';
                            echo htmlspecialchars($op['day']) . ($timeStr ? ': ' . $timeStr : '') . '<br>';
                        }
                        ?>
                    </p>
                </div>
            </div>

        </div>

        <?php if (!empty(trim($settings['info_board'] ?? ''))): ?>
            <div class="max-w-5xl mx-auto mt-6">
                <div
                    class="bg-yellow-500/10 backdrop-blur-md p-6 rounded-3xl border border-yellow-500/30 flex items-start gap-4 shadow-xl">
                    <div class="w-10 h-10 rounded-xl bg-yellow-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                        <i data-lucide="info" class="w-5 h-5 text-yellow-400"></i>
                    </div>
                    <div class="text-wisdom-sand">
                        <h4 class="font-bold text-lg text-yellow-400">Papan Informasi</h4>
                        <p class="text-sm opacity-90 mt-1 leading-relaxed">
                            <?= nl2br(htmlspecialchars($settings['info_board'])) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </section>

    <footer class="text-wisdom-sand/90 py-8 border-t border-white/10 text-center text-sm"
        style="background: rgba(15,23,42,0.82); backdrop-filter: blur(12px); position: relative; z-index: 1;">
        <div class="max-w-7xl mx-auto px-6 flex flex-col items-center gap-4">
            <i data-lucide="library-big" class="text-wisdom-accent/50 w-8 h-8"></i>
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?> - Pusat Kebijaksanaan &amp; Ilmu Pengetahuan.
            </p>
        </div>
    </footer>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
    </script>
</body>

</html>