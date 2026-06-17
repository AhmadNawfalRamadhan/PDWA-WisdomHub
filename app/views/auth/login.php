<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Login — <?= APP_NAME ?></title>
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="<?= APP_URL ?>/public/assets/js/helpers.js"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --color-wisdom-dark: #0f172a;
            --color-wisdom-primary: #1e3a8a;
            --color-wisdom-accent: #d97706;
            --color-wisdom-sand: #fdfbf7;
        }
        body { font-family: var(--font-sans); }
    </style>
</head>

<body class="min-h-screen bg-slate-50 flex flex-col md:flex-row antialiased">

    <!-- Left: Illustration / Theme Side -->
    <div
        class="hidden md:flex md:w-1/2 bg-wisdom-dark relative overflow-hidden flex-col justify-center items-center p-12 text-center text-white">
        <!-- Multi-layer gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-wisdom-primary via-wisdom-dark to-black"></div>

        <!-- Modern abstract dots pattern overlay -->
        <div class="absolute inset-0 opacity-20"
            style="background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 24px 24px;">
        </div>

        <!-- Glowing orbs -->
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-wisdom-primary/40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-wisdom-accent/20 rounded-full blur-3xl"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl">
        </div>

        <div class="relative z-10 max-w-md">
            <!-- Icon with glow -->
            <div class="relative inline-flex mb-8">
                <div class="absolute inset-0 bg-wisdom-accent/30 rounded-full blur-2xl scale-150"></div>
                <div
                    class="relative w-24 h-24 bg-gradient-to-br from-wisdom-accent to-yellow-600 rounded-3xl flex items-center justify-center backdrop-blur-xl shadow-2xl shadow-wisdom-accent/30">
                    <i data-lucide="library-big" class="w-12 h-12 text-white drop-shadow-lg"></i>
                </div>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold font-sans tracking-tight mb-4 drop-shadow-md text-white">Wisdom
                Hub</h1>
            <p class="text-blue-100/80 leading-relaxed text-sm md:text-base font-light">
                Sistem Manajemen Perpustakaan Modern. Temukan, pinjam, dan baca ribuan buku dari koleksi digital kami.
            </p>

            <!-- Features -->
            <div class="mt-12 space-y-4 text-left">
                <?php foreach ([['book-open', 'Koleksi Literatur & Manuskrip Lengkap'], ['scan-line', 'Peminjaman Berbasis QR Code Instan'], ['bar-chart-2', 'Laporan & Analitik Sirkulasi Real-time']] as [$ico, $txt]): ?>
                    <div
                        class="flex items-center gap-4 bg-white/5 rounded-2xl px-5 py-4 border border-white/10 backdrop-blur-md hover:bg-white/10 transition-colors duration-300">
                        <div
                            class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-inner">
                            <i data-lucide="<?= $ico ?>" class="w-5 h-5 text-wisdom-accent"></i>
                        </div>
                        <span class="text-sm text-blue-50 font-medium"><?= $txt ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Right: Form Side -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white/50 backdrop-blur-xl">
        <div class="w-full max-w-md">

            <div class="md:hidden text-center mb-10">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-wisdom-accent to-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-wisdom-accent/20">
                    <i data-lucide="library-big" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-3xl font-bold font-sans tracking-tight text-gray-900 mb-1">Wisdom Hub</h1>
                <p class="text-gray-500 text-sm font-medium">Sistem Manajemen Perpustakaan</p>
            </div>

            <h2 class="text-3xl font-bold text-gray-900 mb-2 font-sans tracking-tight">Selamat Datang</h2>
            <p class="text-gray-500 mb-8 text-sm font-medium">Silakan masuk ke akun Anda untuk melanjutkan.</p>

            <!-- Flash -->
            <?php if ($flash): ?>
                <div
                    class="mb-6 px-4 py-3 rounded-2xl text-sm font-medium flex items-start gap-3 <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' ?>">
                    <i data-lucide="<?= $flash['type'] === 'success' ? 'check-circle' : 'alert-circle' ?>"
                        class="w-5 h-5 shrink-0 mt-0.5"></i>
                    <span><?= htmlspecialchars($flash['message']) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=login" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-wisdom-primary transition-colors">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>"
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-100 rounded-2xl text-sm focus:ring-0 focus:border-wisdom-primary outline-none transition-all bg-gray-50 hover:bg-white focus:bg-white shadow-sm"
                            placeholder="email@contoh.com" required autofocus>
                    </div>
                </div>

                <div>
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-wisdom-primary transition-colors">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input type="password" name="password" id="pwd"
                            class="w-full pl-12 pr-12 py-3.5 border-2 border-gray-100 rounded-2xl text-sm focus:ring-0 focus:border-wisdom-primary outline-none transition-all bg-gray-50 hover:bg-white focus:bg-white shadow-sm"
                            placeholder="••••••••" required>
                        <button type="button" onclick="togglePwd()"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                            <i data-lucide="eye" class="w-5 h-5" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-wisdom-primary hover:bg-blue-900 text-white font-semibold py-4 rounded-2xl transition-all shadow-xl shadow-wisdom-primary/20 flex items-center justify-center gap-2 mt-4 hover:-translate-y-0.5 duration-300">
                    Masuk <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>

                <div class="text-center mt-4">
                    <a href="index.php?page=forgot_password"
                        class="text-xs font-bold text-wisdom-accent hover:text-yellow-700 hover:underline transition-all">Lupa
                        kata sandi?</a>
                </div>
            </form>

            <div class="mt-8 text-center text-sm text-gray-600 font-medium">
                Belum menjadi anggota?
                <a href="index.php?page=register"
                    class="text-wisdom-accent font-bold hover:text-yellow-700 hover:underline transition-all">Daftar di
                    sini</a>
            </div>



            <div class="mt-8 text-center">
                <a href="index.php?page=home"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-400 hover:text-gray-600 transition-colors group">
                    <i data-lucide="arrow-left"
                        class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform"></i> Kembali ke
                    Katalog Publik
                </a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>