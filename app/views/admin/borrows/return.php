<?php $pageTitle = 'Proses Pengembalian';
require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <a href="index.php?page=borrows"
        class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-wisdom-primary mb-6 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
    </a>

    <div class="bg-wisdom-paper rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-500 px-8 py-6 text-white flex items-center gap-4">
            <div
                class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center shadow-inner border border-white/30 backdrop-blur-sm">
                <i data-lucide="undo-2" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h2 class="text-2xl font-serif font-bold">Proses Pengembalian Buku</h2>
                <p class="text-emerald-100 text-sm mt-1">Konfirmasi penerimaan buku dari anggota.</p>
            </div>
        </div>

        <div class="p-8">
            <!-- Detail Peminjaman -->
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 mb-8 space-y-4 shadow-sm">
                <h3 class="font-bold text-gray-800 border-b border-gray-200 pb-3 mb-4 flex items-center gap-2"><i
                        data-lucide="info" class="w-4 h-4 text-emerald-600"></i> Ringkasan Peminjaman</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Anggota</span>
                        <span class="font-bold text-gray-800 flex items-center gap-2"><i data-lucide="user"
                                class="w-3.5 h-3.5 text-gray-400"></i>
                            <?= htmlspecialchars($borrow['user_name']) ?></span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">NIM/NIS</span>
                        <span
                            class="font-medium text-gray-700"><?= htmlspecialchars($borrow['student_id'] ?? '—') ?></span>
                    </div>
                    <div class="md:col-span-2 pt-2 border-t border-gray-200">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Judul
                            Buku</span>
                        <span class="font-bold text-wisdom-primary flex items-center gap-2"><i data-lucide="book"
                                class="w-4 h-4 text-wisdom-primary"></i>
                            <?= htmlspecialchars($borrow['book_title']) ?></span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal
                            Peminjaman</span>
                        <span class="font-medium text-gray-700 flex items-center gap-2"><i data-lucide="calendar"
                                class="w-3.5 h-3.5 text-gray-400"></i> <?= formatDate($borrow['borrow_date']) ?></span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Batas Waktu
                            (Jatuh Tempo)</span>
                        <span
                            class="font-bold flex items-center gap-2 <?= date('Y-m-d') > $borrow['due_date'] ? 'text-red-600' : 'text-emerald-600' ?>">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i> <?= formatDate($borrow['due_date']) ?>
                        </span>
                    </div>
                </div>

                <div
                    class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center bg-white p-3 rounded-xl border border-gray-100">
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Tanggal Pengembalian (Hari
                        Ini)</span>
                    <span class="font-bold text-gray-800 text-lg flex items-center gap-2"><i
                            data-lucide="calendar-check" class="w-5 h-5 text-emerald-500"></i>
                        <?= date('d M Y') ?></span>
                </div>
            </div>

            <!-- Fine Preview -->
            <?php $today = date('Y-m-d');
            if ($today > $borrow['due_date']): ?>
                <?php $days = (int) (strtotime($today) - strtotime($borrow['due_date'])) / 86400;
                $fine = $days * FINE_PER_DAY; ?>
                <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-5 mb-8 flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-red-700 font-bold text-lg leading-tight mb-1">Peringatan: Buku Terlambat!</p>
                        <p class="text-red-600/80 text-sm mb-2">Buku dikembalikan melewati batas waktu jatuh tempo.</p>
                        <div class="bg-white/50 rounded-lg p-3 inline-block">
                            <p class="text-red-800 text-sm">
                                Keterlambatan: <strong><?= $days ?> hari</strong> <span class="mx-2 text-red-300">|</span>
                                Total Denda: <strong class="text-lg"><?= formatCurrency($fine) ?></strong>
                            </p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 mb-8 flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-emerald-700 font-bold">Status: Tepat Waktu</p>
                        <p class="text-emerald-600/80 text-sm">Terima kasih, buku dikembalikan sesuai atau sebelum batas
                            waktu.</p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=borrows&action=process_return"
                class="mt-8 pt-6 border-t border-gray-100">
                <input type="hidden" name="borrow_id" value="<?= $borrow['id'] ?>">
                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 py-3.5 bg-emerald-600 text-white text-base font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2">
                        <i data-lucide="check-circle-2" class="w-5 h-5"></i> Konfirmasi Pengembalian
                    </button>
                    <a href="index.php?page=borrows"
                        class="px-8 py-3.5 border border-gray-200 bg-white text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 hover:text-gray-800 transition-colors flex items-center justify-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>