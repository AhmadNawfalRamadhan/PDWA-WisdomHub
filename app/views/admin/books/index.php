<?php $pageTitle = 'Katalog Buku';
require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="flex items-center justify-between mb-8">
    <h2 class="text-2xl font-serif font-bold text-gray-800 flex items-center gap-3">
        <i data-lucide="library" class="w-6 h-6 text-wisdom-primary"></i> Kelola Katalog Buku
    </h2>
    <?php if (isAdmin()): ?>
        <a href="index.php?page=books&action=create"
            class="flex items-center gap-2 px-5 py-2.5 bg-wisdom-primary text-white text-sm font-bold rounded-xl hover:bg-blue-900 transition shadow-lg shadow-wisdom-primary/30">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Buku Baru
        </a>
    <?php endif; ?>
</div>

<!-- Search & Filter -->
<form method="GET" action="index.php"
    class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 p-5 mb-8 flex flex-wrap gap-4 items-end">
    <input type="hidden" name="page" value="books">
    <div class="flex-1 min-w-60">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pencarian</label>
        <div class="relative group">
            <i data-lucide="search"
                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 group-focus-within:text-wisdom-primary transition-colors"></i>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                placeholder="Judul, pengarang, ISBN, atau penerbit...">
        </div>
    </div>
    <div class="w-56">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kategori</label>
        <select name="category"
            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all cursor-pointer">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit"
            class="px-6 py-2.5 bg-wisdom-dark text-white text-sm font-bold rounded-xl hover:bg-black transition shadow-sm flex items-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i> Cari
        </button>
        <?php if ($search || $categoryId): ?>
            <a href="index.php?page=books"
                class="px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition flex items-center gap-2">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Reset
            </a>
        <?php endif; ?>
    </div>
</form>

<div class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h3 class="font-serif font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="list" class="w-5 h-5 text-wisdom-accent"></i> Daftar Buku
        </h3>
        <span class="px-3 py-1 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-500">Total:
            <?= $books['total'] ?> Buku</span>
    </div>

    <?php if (empty($books['data'])): ?>
        <div class="text-center py-20 text-gray-400 bg-white">
            <i data-lucide="book-x" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
            <p class="font-serif font-bold text-lg text-gray-600 mb-1">Tidak ada buku ditemukan</p>
            <p class="text-sm">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Informasi Buku</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">ISBN</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Ketersediaan</th>
                        <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">QR Code</th>
                        <?php if (isLoggedIn()): ?>
                            <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    <?php foreach ($books['data'] as $book): ?>
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4 cursor-pointer group/title"
                                    onclick='openBookDetailModal(<?= htmlspecialchars(json_encode($book), ENT_QUOTES, 'UTF-8') ?>)'
                                    title="Klik untuk lihat detail">
                                    <div
                                        class="w-12 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm border border-gray-200 overflow-hidden group-hover/title:border-wisdom-primary transition-colors">
                                        <?php if ($book['cover_image']): ?>
                                            <img src="<?= htmlspecialchars($book['cover_image']) ?>"
                                                class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i data-lucide="book"
                                                class="w-6 h-6 text-gray-400 group-hover/title:text-wisdom-primary transition-colors"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p
                                            class="font-serif font-bold text-gray-800 text-base mb-1 group-hover/title:text-wisdom-primary transition-colors">
                                            <?= htmlspecialchars($book['title']) ?></p>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 font-medium">
                                            <span class="flex items-center gap-1"><i data-lucide="pen-tool" class="w-3 h-3"></i>
                                                <?= htmlspecialchars($book['author']) ?></span>
                                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                            <span class="flex items-center gap-1"><i data-lucide="building" class="w-3 h-3"></i>
                                                <?= htmlspecialchars($book['publisher']) ?>
                                                (<?= $book['year_published'] ?>)</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="font-mono text-xs bg-gray-100 px-2 py-1 rounded text-gray-600 border border-gray-200"><?= htmlspecialchars($book['isbn']) ?></span>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="px-3 py-1 bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold rounded-full">
                                    <?= htmlspecialchars($book['category_name'] ?? 'Umum') ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="font-bold text-sm <?= $book['stock'] === 0 ? 'text-red-600' : ($book['stock'] <= 2 ? 'text-yellow-600' : 'text-emerald-600') ?>">
                                        <?= $book['stock'] ?> <span class="text-xs font-normal text-gray-400">tersedia</span>
                                    </span>
                                    <span class="text-gray-400 text-xs font-medium">Total: <?= $book['total_stock'] ?></span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <?php $pct = $book['total_stock'] > 0 ? ($book['stock'] / $book['total_stock'] * 100) : 0; ?>
                                    <div class="h-full rounded-full transition-all <?= $pct === 0 ? 'bg-red-500' : ($pct <= 40 ? 'bg-yellow-500' : 'bg-emerald-500') ?>"
                                        style="width:<?= $pct ?>%"></div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <?php if ($book['qr_code']): ?>
                                    <button type="button"
                                        onclick="openQRModal('<?= htmlspecialchars($book['qr_code']) ?>', '<?= htmlspecialchars(addslashes($book['title'])) ?>', '<?= htmlspecialchars($book['isbn']) ?>')"
                                        class="w-10 h-10 p-1 bg-white border border-gray-200 rounded-lg shadow-sm hover:scale-110 hover:border-wisdom-accent hover:shadow-md transition-all origin-left cursor-zoom-in group"
                                        title="Klik untuk perbesar QR Code">
                                        <img src="<?= htmlspecialchars($book['qr_code']) ?>"
                                            class="w-full h-full group-hover:opacity-80 transition-opacity">
                                    </button>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <?php if (isLoggedIn()): ?>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <?php if (isAdmin()): ?>
                                            <?php if ($book['stock'] > 0): ?>
                                                <a href="index.php?page=borrows&action=create&book_id=<?= $book['id'] ?>"
                                                    title="Pinjamkan Buku"
                                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-100 hover:text-emerald-700 transition-colors border border-emerald-100">
                                                    <i data-lucide="hand-heart" class="w-4 h-4"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="index.php?page=books&action=edit&id=<?= $book['id'] ?>" title="Edit Buku"
                                                class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 hover:text-blue-700 transition-colors border border-blue-100">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            <a href="index.php?page=books&action=delete&id=<?= $book['id'] ?>" title="Hapus Buku"
                                                onclick="event.preventDefault(); let url = this.href; showWisdomConfirm('Apakah Anda yakin ingin menghapus buku ini?', () => window.location.href = url);"
                                                class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 hover:text-red-700 transition-colors border border-red-100">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </a>
                                        <?php elseif (isStudent()): ?>
                                            <?php if ($book['stock'] > 0): ?>
                                                <form method="POST" action="index.php?page=borrows&action=book" class="inline m-0">
                                                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                                    <button type="submit"
                                                        onclick="event.preventDefault(); let form = this.closest('form'); showWisdomConfirm('Anda yakin ingin mem-booking buku ini? Buku harus diambil dalam 2 hari ke depan.', () => form.submit());"
                                                        title="Booking Buku"
                                                        class="px-3 py-1.5 rounded-lg bg-wisdom-primary text-white text-xs font-bold flex items-center gap-1.5 hover:bg-blue-900 transition-colors shadow-sm cursor-pointer">
                                                        <i data-lucide="bookmark" class="w-3.5 h-3.5"></i> Booking
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span
                                                    class="px-2 py-1 text-xs font-bold text-gray-400 bg-gray-50 border border-gray-100 rounded">Habis</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($books['total_pages'] > 1): ?>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
                <span class="text-sm font-medium text-gray-500">
                    Halaman <span class="font-bold text-gray-800"><?= $books['current_page'] ?></span> dari <span
                        class="font-bold text-gray-800"><?= $books['total_pages'] ?></span>
                </span>
                <div class="flex gap-1.5">
                    <?php for ($i = 1; $i <= $books['total_pages']; $i++): ?>
                        <a href="index.php?page=books&search=<?= urlencode($search) ?>&category=<?= $categoryId ?>&page=<?= $i ?>"
                            class="min-w-[32px] h-8 flex items-center justify-center rounded-lg border text-sm font-bold transition-colors
               <?= $i == $books['current_page'] ? 'bg-wisdom-primary text-white border-wisdom-primary shadow-sm' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- QR CODE MODAL -->
<div id="qr-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div id="qr-backdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeQRModal()"></div>

    <!-- Modal Card -->
    <div
        class="relative bg-white dark:bg-[#141414] rounded-3xl shadow-2xl w-full max-w-sm mx-auto animate-fade-in z-10 border border-gray-100 dark:border-yellow-500/20 overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-wisdom-dark to-wisdom-primary px-6 py-5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-blue-200/70 mb-0.5">QR Code Buku</p>
                <h3 id="qr-modal-title" class="font-serif font-bold text-white text-base leading-tight"></h3>
            </div>
            <button onclick="closeQRModal()"
                class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                <i data-lucide="x" class="w-4 h-4 text-white"></i>
            </button>
        </div>

        <!-- QR Image -->
        <div class="px-6 pt-6 pb-4 flex flex-col items-center">
            <div
                class="w-52 h-52 p-3 bg-white rounded-2xl shadow-inner border border-gray-100 flex items-center justify-center mb-4">
                <img id="qr-modal-img" src="" alt="QR Code" class="w-full h-full object-contain">
            </div>

            <div class="text-center mb-5">
                <p class="text-xs text-gray-500 mb-1 font-medium">ISBN</p>
                <code id="qr-modal-isbn"
                    class="text-sm font-bold font-mono bg-gray-50 border border-gray-200 px-3 py-1 rounded-lg text-gray-700"></code>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 w-full">
                <a id="qr-modal-download" href="#" download
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-wisdom-primary text-white text-xs font-bold rounded-xl hover:bg-blue-900 transition-colors shadow-sm">
                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Unduh
                </a>
                <a id="qr-modal-newtab" href="#" target="_blank" rel="noopener"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 bg-gray-50 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-100 transition-colors">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Tab Baru
                </a>
            </div>
        </div>

        <!-- Footer hint -->
        <p class="text-center text-[10px] text-gray-400 pb-4 font-medium">Klik di luar untuk menutup &bull; Tekan ESC
        </p>
    </div>
</div>



<!-- BOOK DETAIL MODAL -->
<div id="book-detail-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog"
    aria-modal="true">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeBookDetailModal()"></div>
    <div
        class="relative bg-white dark:bg-[#141414] rounded-3xl shadow-2xl w-full max-w-2xl mx-auto animate-fade-in z-10 border border-gray-100 dark:border-yellow-500/20 overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div
            class="bg-gradient-to-r from-wisdom-dark to-wisdom-primary px-6 py-5 flex items-center justify-between flex-shrink-0">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-blue-200/70 mb-0.5">Detail Buku</p>
                <h3 class="font-serif font-bold text-white text-base leading-tight">Informasi Lengkap</h3>
            </div>
            <button onclick="closeBookDetailModal()"
                class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                <i data-lucide="x" class="w-4 h-4 text-white"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Cover -->
                <div
                    class="w-32 h-48 md:w-40 md:h-56 flex-shrink-0 bg-gray-100 dark:bg-[#1e1e1e] rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-md flex items-center justify-center">
                    <img id="detail-cover" src="" class="w-full h-full object-cover hidden">
                    <i id="detail-cover-icon" data-lucide="book" class="w-12 h-12 text-gray-400"></i>
                </div>

                <!-- Info -->
                <div class="flex-1">
                    <h2 id="detail-title" class="font-serif font-bold text-2xl text-gray-800 dark:text-gray-100 mb-2">
                    </h2>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span id="detail-category"
                            class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-wider rounded-md border border-indigo-100 dark:border-indigo-800"></span>
                        <span id="detail-isbn"
                            class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-[10px] font-bold uppercase tracking-wider font-mono rounded-md border border-gray-200 dark:border-gray-700"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pengarang</p>
                            <p id="detail-author"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-1.5">
                                <i data-lucide="pen-tool" class="w-3.5 h-3.5 text-wisdom-accent"></i> <span></span></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Penerbit</p>
                            <p id="detail-publisher"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-1.5">
                                <i data-lucide="building" class="w-3.5 h-3.5 text-wisdom-accent"></i> <span></span></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tahun Terbit
                            </p>
                            <p id="detail-year"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-wisdom-accent"></i> <span></span></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Ketersediaan
                            </p>
                            <p id="detail-stock" class="text-sm font-bold flex items-center gap-1.5"></p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-gray-100 dark:border-gray-800">

            <div>
                <p
                    class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="align-left" class="w-4 h-4 text-wisdom-primary dark:text-wisdom-gold"></i> Sinopsis
                    / Deskripsi</p>
                <div id="detail-description"
                    class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-wrap bg-gray-50 dark:bg-[#1a1a1a] p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                </div>
            </div>
        </div>
    </div>
</div>



<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>