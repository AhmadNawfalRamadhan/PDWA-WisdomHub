<?php $pageTitle = 'Catat Peminjaman';
require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="max-w-3xl mx-auto">
    <a href="index.php?page=borrows"
        class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-wisdom-primary mb-6 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
    </a>

    <div class="bg-wisdom-paper rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-wisdom-dark to-wisdom-primary px-8 py-6 text-white flex items-center gap-4">
            <div
                class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center shadow-inner border border-white/20">
                <i data-lucide="hand-heart" class="w-6 h-6 text-yellow-400"></i>
            </div>
            <div>
                <h2 class="text-2xl font-serif font-bold">Catat Peminjaman Buku</h2>
                <p class="text-blue-100 text-sm mt-1">Isi formulir untuk mencatat transaksi peminjaman baru.</p>
            </div>
        </div>

        <form method="POST" action="index.php?page=borrows&action=store" class="p-8 space-y-6">

            <!-- QR Scan for ISBN -->
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-bold text-blue-800 flex items-center gap-2">
                        <i data-lucide="qr-code" class="w-4 h-4"></i> Scan QR Code Buku
                    </p>
                    <button type="button" onclick="startQRScanner('isbn_scan')"
                        class="px-4 py-2 bg-white border border-blue-200 text-blue-700 text-xs font-bold rounded-xl hover:bg-blue-50 transition-colors shadow-sm flex items-center gap-2">
                        <i data-lucide="camera" class="w-4 h-4"></i> Buka Kamera
                    </button>
                </div>
                <div id="qr-container" class="rounded-xl overflow-hidden shadow-inner mb-3"></div>
                <div class="relative">
                    <i data-lucide="barcode" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    <input type="text" id="isbn_scan" placeholder="Atau ketik ISBN untuk mencari..."
                        oninput="lookupISBN(this.value)"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-blue-200 rounded-xl text-sm focus:ring-0 focus:border-blue-500 focus:bg-white outline-none font-mono transition-all">
                </div>
            </div>

            <!-- Anggota -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Anggota Peminjam <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <i data-lucide="user"
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5 pointer-events-none z-10"></i>
                    <select name="user_id" id="user_id"
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all appearance-none cursor-pointer"
                        required>
                        <option value="">Pilih anggota perpustakaan...</option>
                        <?php foreach ($students as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?>
                                (<?= htmlspecialchars($s['student_id'] ?? $s['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none z-10"></i>
                </div>
            </div>

            <!-- Buku -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Buku yang Dipinjam <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <i data-lucide="book"
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5 pointer-events-none z-10"></i>
                    <select name="book_id" id="book_id"
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all appearance-none cursor-pointer"
                        required>
                        <option value="">Pilih buku dari katalog...</option>
                        <?php foreach ($books as $b): ?>
                            <option value="<?= $b['id'] ?>" data-isbn="<?= htmlspecialchars($b['isbn']) ?>"
                                <?= $preselectedBook == $b['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['title']) ?> (Sisa Stok: <?= $b['stock'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="chevron-down"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none z-10"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pinjam</label>
                    <div class="relative">
                        <i data-lucide="calendar"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none"></i>
                        <input type="date" name="borrow_date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all">
                    </div>
                    <p class="text-xs font-medium text-gray-500 mt-2 flex items-center gap-1"><i data-lucide="clock"
                            class="w-3.5 h-3.5 text-blue-500"></i> Jatuh tempo otomatis dalam <strong><?= BORROW_DAYS ?>
                            hari</strong>.</p>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Peminjaman</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none resize-none transition-all"
                        placeholder="Catatan tambahan (kondisi buku, dsb)..."></textarea>
                </div>
            </div>

            <!-- Info denda -->
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5"></i>
                <div class="text-sm text-amber-800">
                    <p class="font-bold mb-1">Informasi Aturan Peminjaman</p>
                    <ul class="list-disc pl-4 space-y-0.5 text-xs">
                        <li>Denda keterlambatan: <strong><?= formatCurrency(FINE_PER_DAY) ?> / hari</strong></li>
                        <li>Batas maksimum: <strong>3 buku aktif per anggota</strong></li>
                    </ul>
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-gray-100 mt-8">
                <button type="submit"
                    class="px-8 py-3 bg-wisdom-primary text-white text-sm font-bold rounded-xl hover:bg-blue-900 transition-colors shadow-lg shadow-wisdom-primary/30 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Konfirmasi Peminjaman
                </button>
                <a href="index.php?page=borrows"
                    class="px-8 py-3 border border-gray-200 bg-white text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Tom Select overrides to match Tailwind design */
    .ts-wrapper {
        width: 100%;
    }

    .ts-control {
        border-radius: 0.75rem !important;
        border: 1px solid #e5e7eb !important;
        padding: 0.75rem 2.5rem 0.75rem 2.75rem !important;
        font-size: 0.875rem !important;
        background-color: #f9fafb !important;
        min-height: 46px !important;
        box-shadow: none !important;
        display: flex;
        align-items: center;
    }

    .ts-control.focus {
        border-color: var(--color-wisdom-primary) !important;
        background-color: #ffffff !important;
    }

    .ts-wrapper.single .ts-control {
        background-image: none !important;
    }

    .ts-dropdown {
        background-color: #ffffff !important;
        z-index: 50 !important;
        border-radius: 0.75rem !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        margin-top: 0.25rem !important;
        font-size: 0.875rem !important;
        overflow: hidden !important;
    }

    .ts-dropdown .option {
        padding: 0.5rem 1rem !important;
    }

    .ts-dropdown .option.active {
        background-color: #f3f4f6 !important;
        color: inherit !important;
    }

    .ts-control input {
        font-size: 0.875rem !important;
    }

    /* Dark mode overrides */
    .dark .ts-control {
        background-color: #1e1e1e !important;
        border-color: rgba(212, 175, 55, 0.15) !important;
        color: #e8e3d8 !important;
    }

    .dark .ts-control.focus {
        border-color: #d4af37 !important;
        background-color: #242424 !important;
    }

    .dark .ts-dropdown {
        background-color: #1e1e1e !important;
        border-color: rgba(212, 175, 55, 0.15) !important;
        color: #e8e3d8 !important;
    }

    .dark .ts-dropdown .option.active {
        background-color: #2a2a2a !important;
        color: #d4af37 !important;
    }

    .dark .ts-control input {
        color: #e8e3d8 !important;
    }

    .dark .ts-dropdown .ts-dropdown-content {
        background-color: #1e1e1e !important;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.userSelect = new TomSelect('#user_id', {
            maxOptions: 10,
            sortField: { field: "text", direction: "asc" }
        });

        window.bookSelect = new TomSelect('#book_id', {
            maxOptions: 10,
            sortField: { field: "text", direction: "asc" }
        });
    });
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>