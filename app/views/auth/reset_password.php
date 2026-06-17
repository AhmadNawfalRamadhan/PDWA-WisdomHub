<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Atur Ulang Kata Sandi — <?= APP_NAME ?></title>
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
        }
        body { font-family: var(--font-sans); }
    </style>
</head>

<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
        <div class="text-center mb-8">
            <div
                class="w-16 h-16 bg-gradient-to-br from-wisdom-accent to-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-wisdom-accent/20">
                <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Buat Kata Sandi Baru</h2>
            <p class="text-gray-500 text-sm">Silakan masukkan kata sandi baru untuk akun Anda.</p>
        </div>

        <?php if ($flash = getFlash()): ?>
            <div
                class="mb-6 px-4 py-3 rounded-2xl text-sm font-medium flex items-start gap-3 <?= $flash['type'] === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' ?>">
                <i data-lucide="<?= $flash['type'] === 'success' ? 'check-circle' : 'alert-circle' ?>"
                    class="w-5 h-5 shrink-0 mt-0.5"></i>
                <span><?= $flash['message'] ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=reset_password" class="space-y-5">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Baru</label>
                <div class="relative group">
                    <div
                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-wisdom-primary transition-colors">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <input type="password" name="password" id="pwd" required minlength="6" autofocus
                        class="w-full pl-12 pr-12 py-3 border-2 border-gray-100 rounded-2xl text-sm focus:ring-0 focus:border-wisdom-primary outline-none transition-all bg-gray-50 hover:bg-white focus:bg-white"
                        placeholder="Minimal 6 karakter">
                    <button type="button" onclick="togglePwd()"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                        <i data-lucide="eye" class="w-5 h-5" id="eye-icon"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-wisdom-primary hover:bg-blue-900 text-white font-semibold py-3.5 rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 mt-2">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Kata Sandi
            </button>
        </form>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>