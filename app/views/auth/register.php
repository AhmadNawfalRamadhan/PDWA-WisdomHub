<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Daftar — <?= APP_NAME ?></title>
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

<body class="min-h-screen bg-slate-50 flex flex-col md:flex-row-reverse antialiased">

    <!-- Right: Illustration / Theme Side (Reversed for variation) -->
    <div
        class="hidden md:flex md:w-1/2 bg-wisdom-dark relative overflow-hidden flex-col justify-center items-center p-12 text-center text-white">
        <!-- Modern abstract dots pattern overlay -->
        <div class="absolute inset-0 opacity-20"
            style="background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 24px 24px;">
        </div>

        <div class="absolute top-0 right-0 w-80 h-80 bg-wisdom-primary/40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-wisdom-accent/20 rounded-full blur-3xl"></div>

        <div class="relative z-10 max-w-md">
            <!-- Icon with glow -->
            <div class="relative inline-flex mb-8">
                <div class="absolute inset-0 bg-wisdom-accent/30 rounded-full blur-2xl scale-150"></div>
                <div
                    class="relative w-24 h-24 bg-gradient-to-br from-wisdom-accent to-yellow-600 rounded-3xl flex items-center justify-center backdrop-blur-xl shadow-2xl shadow-wisdom-accent/30">
                    <i data-lucide="library-big" class="w-12 h-12 text-white drop-shadow-lg"></i>
                </div>
            </div>

            <h1 class="text-4xl font-bold font-sans tracking-tight mb-4 leading-tight">Bergabunglah<br />dengan Kami
            </h1>
            <p class="text-lg text-blue-100/80 font-medium mb-6">Jadilah bagian dari komunitas pembelajar.</p>
            <p class="text-blue-100/60 leading-relaxed text-sm font-light">Daftarkan diri Anda untuk mendapatkan akses
                eksklusif ke berbagai koleksi buku, jurnal, dan naskah digital berharga di Wisdom Hub.</p>
        </div>
    </div>

    <!-- Left: Form Side -->
    <div
        class="w-full md:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white/50 backdrop-blur-xl z-10 relative">
        <div class="w-full max-w-md">

            <div class="md:hidden text-center mb-10">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-wisdom-accent to-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-wisdom-accent/20">
                    <i data-lucide="library-big" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-3xl font-bold font-sans tracking-tight text-gray-900 mb-1">Wisdom Hub</h1>
                <p class="text-gray-500 text-sm font-medium">Pendaftaran Anggota Baru</p>
            </div>

            <h2 class="text-3xl font-bold text-gray-900 mb-2 font-sans tracking-tight">Daftar Akun Baru</h2>
            <p class="text-gray-500 mb-8 text-sm font-medium">Lengkapi formulir di bawah ini untuk membuat akun Anda.
            </p>

            <!-- Flash -->
            <?php if ($flash): ?>
                <div
                    class="mb-6 px-4 py-3 rounded-2xl text-sm font-medium flex items-start gap-3 <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' ?>">
                    <i data-lucide="<?= $flash['type'] === 'success' ? 'check-circle' : 'alert-circle' ?>"
                        class="w-5 h-5 shrink-0 mt-0.5"></i>
                    <span><?= htmlspecialchars($flash['message']) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=register" enctype="multipart/form-data" class="space-y-5">
                <!-- Profile Picture Upload -->
                <div class="flex flex-col items-center mb-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 self-start">Foto Profil <span
                            class="text-gray-400 font-normal">(opsional)</span></label>
                    <div class="relative group cursor-pointer"
                        onclick="document.getElementById('profile_picture').click()">
                        <div id="avatar-preview"
                            class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:border-wisdom-primary group-hover:shadow-lg group-hover:shadow-wisdom-primary/10">
                            <div id="avatar-placeholder" class="text-center">
                                <i data-lucide="camera"
                                    class="w-8 h-8 text-gray-300 mx-auto group-hover:text-wisdom-primary transition-colors"></i>
                                <p class="text-[10px] text-gray-400 mt-1 font-medium">Upload</p>
                            </div>
                            <img id="avatar-img" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        </div>
                        <div
                            class="absolute -bottom-1 -right-1 w-7 h-7 bg-wisdom-primary rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:scale-100 scale-75">
                            <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="hidden"
                        onchange="previewAvatar(this)">
                    <p class="text-[11px] text-gray-400 mt-2 font-medium">JPG, PNG, atau WebP. Maks 2MB.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-wisdom-primary transition-colors">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>"
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-100 rounded-2xl text-sm focus:ring-0 focus:border-wisdom-primary outline-none transition-all bg-gray-50 hover:bg-white focus:bg-white shadow-sm"
                            placeholder="Nama lengkap Anda" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">NIM / NIS / ID Anggota</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-wisdom-primary transition-colors">
                            <i data-lucide="id-card" class="w-5 h-5"></i>
                        </div>
                        <input type="text" name="student_id" value="<?= htmlspecialchars($studentId ?? '') ?>"
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-100 rounded-2xl text-sm focus:ring-0 focus:border-wisdom-primary outline-none transition-all bg-gray-50 hover:bg-white focus:bg-white shadow-sm"
                            placeholder="Nomor identitas">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon / HP</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-wisdom-primary transition-colors">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                        </div>
                        <input type="text" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>"
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-100 rounded-2xl text-sm focus:ring-0 focus:border-wisdom-primary outline-none transition-all bg-gray-50 hover:bg-white focus:bg-white shadow-sm"
                            placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-wisdom-primary transition-colors">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>"
                            class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-100 rounded-2xl text-sm focus:ring-0 focus:border-wisdom-primary outline-none transition-all bg-gray-50 hover:bg-white focus:bg-white shadow-sm"
                            placeholder="email@contoh.com" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-wisdom-primary transition-colors">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input type="password" name="password" id="pwd"
                            class="w-full pl-12 pr-12 py-3.5 border-2 border-gray-100 rounded-2xl text-sm focus:ring-0 focus:border-wisdom-primary outline-none transition-all bg-gray-50 hover:bg-white focus:bg-white shadow-sm"
                            placeholder="Minimal 6 karakter" required minlength="6">
                        <button type="button" onclick="togglePwd()"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                            <i data-lucide="eye" class="w-5 h-5" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-wisdom-primary hover:bg-blue-900 text-white font-semibold py-4 rounded-2xl transition-all shadow-xl shadow-wisdom-primary/20 flex items-center justify-center gap-2 mt-4 hover:-translate-y-0.5 duration-300">
                    <i data-lucide="user-plus" class="w-5 h-5"></i> Daftar Sekarang
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-600 font-medium">
                Sudah memiliki akun?
                <a href="index.php?page=login"
                    class="text-wisdom-accent font-bold hover:text-yellow-700 hover:underline transition-all">Masuk di
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