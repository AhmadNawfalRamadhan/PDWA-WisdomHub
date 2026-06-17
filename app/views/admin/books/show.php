<?php $pageTitle = 'Detail Buku'; require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="max-w-3xl mx-auto">
    <a href="index.php?page=books" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-wisdom-primary mb-6 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Katalog
    </a>

    <div class="bg-wisdom-paper rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-br from-wisdom-primary to-wisdom-dark p-8 md:p-10 flex flex-col md:flex-row items-center md:items-start gap-8 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-wisdom-accent rounded-full blur-3xl opacity-30"></div>
            
            <div class="w-32 h-44 md:w-40 md:h-56 bg-white rounded-xl shadow-xl flex items-center justify-center flex-shrink-0 relative z-10 border-4 border-white/10 overflow-hidden">
                <?php if ($book['cover_image']): ?>
                <img src="<?= htmlspecialchars($book['cover_image']) ?>" class="w-full h-full object-cover">
                <?php else: ?>
                <i data-lucide="book" class="w-12 h-12 text-gray-300"></i>
                <?php endif; ?>
            </div>
            
            <div class="relative z-10 text-center md:text-left flex-1 mt-2 md:mt-0">
                <span class="inline-block px-3 py-1 bg-white/10 border border-white/20 text-blue-100 text-xs font-bold rounded-full mb-3 uppercase tracking-wider backdrop-blur-sm">
                    <?= htmlspecialchars($book['category_name'] ?? 'Umum') ?>
                </span>
                <h2 class="text-3xl font-serif font-bold text-white leading-tight mb-2"><?= htmlspecialchars($book['title']) ?></h2>
                <p class="text-blue-100 text-lg font-medium flex items-center justify-center md:justify-start gap-2 mb-5">
                    <i data-lucide="pen-tool" class="w-4 h-4"></i> <?= htmlspecialchars($book['author']) ?>
                </p>
                <div class="inline-flex items-center">
                    <?php if ($book['stock'] > 0): ?>
                    <span class="px-4 py-2 bg-emerald-500/20 border border-emerald-500/30 text-emerald-100 text-sm font-bold rounded-xl flex items-center gap-2 backdrop-blur-sm shadow-sm">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i> Tersedia (<?= $book['stock'] ?> salinan)
                    </span>
                    <?php else: ?>
                    <span class="px-4 py-2 bg-red-500/20 border border-red-500/30 text-red-100 text-sm font-bold rounded-xl flex items-center gap-2 backdrop-blur-sm shadow-sm">
                        <i data-lucide="x-circle" class="w-4 h-4 text-red-400"></i> Stok Habis
                    </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-10">
            <h3 class="font-serif font-bold text-gray-800 text-lg mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
                <i data-lucide="info" class="w-5 h-5 text-wisdom-accent"></i> Informasi Detail
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <?php $details = [
                    ['ISBN',     $book['isbn'],           'barcode'],
                    ['Penerbit', $book['publisher'],      'building'],
                    ['Tahun Terbit', $book['year_published'], 'calendar'],
                    ['Ketersediaan', $book['stock'].' dari '.$book['total_stock'].' salinan', 'package'],
                ]; ?>
                <?php foreach ($details as [$label, $val, $icon]): ?>
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-blue-100 transition-colors">
                    <div class="w-10 h-10 bg-white shadow-sm rounded-xl flex items-center justify-center flex-shrink-0 text-wisdom-primary">
                        <i data-lucide="<?= $icon ?>" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5"><?= $label ?></p>
                        <p class="text-sm font-bold text-gray-800"><?= htmlspecialchars((string)$val) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($book['description']): ?>
            <div class="mb-8">
                <h3 class="font-serif font-bold text-gray-800 text-lg mb-4 flex items-center gap-2 border-b border-gray-100 pb-4">
                    <i data-lucide="file-text" class="w-5 h-5 text-wisdom-accent"></i> Sinopsis
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-6 rounded-2xl border border-gray-100 italic">"<?= htmlspecialchars($book['description']) ?>"</p>
            </div>
            <?php endif; ?>

            <?php if ($book['qr_code']): ?>
            <div class="mb-8">
                <h3 class="font-serif font-bold text-gray-800 text-lg mb-4 flex items-center gap-2 border-b border-gray-100 pb-4">
                    <i data-lucide="qr-code" class="w-5 h-5 text-wisdom-accent"></i> QR Code ISBN
                </h3>
                <div class="inline-flex items-center gap-5 p-5 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <img src="<?= htmlspecialchars($book['qr_code']) ?>" class="w-24 h-24 rounded-xl" alt="QR Code">
                    <div>
                        <p class="text-sm font-bold text-gray-800 mb-1">Scan untuk detail</p>
                        <p class="text-xs text-gray-500 mb-2">Gunakan fitur scan saat peminjaman.</p>
                        <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 font-mono text-[10px] rounded border border-gray-200"><?= htmlspecialchars($book['isbn']) ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (isAdmin() && $book['stock'] > 0): ?>
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <a href="index.php?page=borrows&action=create&book_id=<?= $book['id'] ?>"
                   class="inline-flex items-center gap-2 px-8 py-3 bg-wisdom-primary text-white text-sm font-bold rounded-xl hover:bg-blue-900 transition-colors shadow-lg shadow-wisdom-primary/30">
                    <i data-lucide="hand-heart" class="w-4 h-4"></i> Proses Peminjaman Buku Ini
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
