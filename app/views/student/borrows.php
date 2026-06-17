<?php $pageTitle = 'Riwayat Peminjaman Saya'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="flex items-center justify-between mb-8">
    <h2 class="text-2xl font-serif font-bold text-gray-800 flex items-center gap-3">
        <i data-lucide="book-open-check" class="w-6 h-6 text-wisdom-primary"></i> Riwayat Peminjaman Saya
    </h2>
    <span class="px-3 py-1.5 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-500 shadow-sm">
        <?= $borrows['total'] ?> Total Transaksi
    </span>
</div>

<!-- Filter Tabs -->
<div class="flex flex-wrap gap-2 mb-8">
    <?php
    $tabs = [
        '' => ['label' => 'Semua', 'icon' => 'layers'], 
        'booked' => ['label' => 'Booking', 'icon' => 'bookmark'],
        'borrowed' => ['label' => 'Dipinjam', 'icon' => 'book'], 
        'returned' => ['label' => 'Dikembalikan', 'icon' => 'check-circle'], 
        'overdue' => ['label' => 'Terlambat', 'icon' => 'alert-circle'],
        'cancelled' => ['label' => 'Dibatalkan', 'icon' => 'x-circle']
    ];
    ?>
    <?php foreach ($tabs as $val => $tab): ?>
    <a href="index.php?page=my-borrows&status=<?= $val ?>"
       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl border transition-all
           <?= $status===$val ? 'bg-wisdom-primary text-white border-wisdom-primary shadow-md shadow-wisdom-primary/25' : 'bg-wisdom-paper text-gray-600 border-gray-200 hover:bg-white hover:border-gray-300' ?>">
        <i data-lucide="<?= $tab['icon'] ?>" class="w-4 h-4"></i> <?= $tab['label'] ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h3 class="font-serif font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="list" class="w-5 h-5 text-wisdom-accent"></i> Daftar Peminjaman
        </h3>
    </div>

    <?php if (empty($borrows['data'])): ?>
    <div class="text-center py-20 text-gray-400 bg-white">
        <i data-lucide="book-x" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
        <p class="font-serif font-bold text-lg text-gray-600 mb-2">Belum ada riwayat peminjaman</p>
        <p class="text-sm text-gray-500 mb-5">Mulai jelajahi koleksi buku kami dan lakukan peminjaman pertama Anda.</p>
        <a href="index.php?page=books" class="inline-flex items-center gap-2 px-6 py-3 bg-wisdom-primary text-white text-sm font-bold rounded-xl hover:bg-blue-900 transition-colors shadow-lg shadow-wisdom-primary/25">
            <i data-lucide="library" class="w-4 h-4"></i> Jelajahi Katalog
        </a>
    </div>
    <?php else: ?>
    <div class="divide-y divide-gray-100 bg-white">
        <?php foreach ($borrows['data'] as $b): ?>
        <?php
            $isOverdue = date('Y-m-d') > $b['due_date'] && $b['status'] === 'borrowed';
            
            if ($b['status'] === 'booked') {
                $statusDisplay = 'booked';
                $statusLabel = 'Menunggu Diambil';
                $badgeCls = 'bg-sky-50 text-sky-700 border-sky-200';
            } elseif ($b['status'] === 'cancelled') {
                $statusDisplay = 'cancelled';
                $statusLabel = 'Dibatalkan';
                $badgeCls = 'bg-gray-100 text-gray-600 border-gray-300';
            } else {
                $statusDisplay = $b['status'] === 'returned' ? 'returned' : ($isOverdue || $b['status']==='overdue' ? 'overdue' : 'borrowed');
                $statusLabel   = match($statusDisplay){'returned'=>'Dikembalikan','overdue'=>'Terlambat',default=>'Sedang Dipinjam'};
                if ($statusDisplay==='returned') { $badgeCls = 'bg-emerald-50 text-emerald-700 border-emerald-200'; }
                elseif ($statusDisplay==='overdue') { $badgeCls = 'bg-red-50 text-red-700 border-red-200'; }
                else { $badgeCls = 'bg-blue-50 text-blue-700 border-blue-200'; }
            }
        ?>
        <div class="px-6 py-5 hover:bg-blue-50/20 transition-colors">
            <div class="flex items-start gap-4">
                <!-- Book Spine Visual -->
                <?php if (!empty($b['book_cover'])): ?>
                    <div class="w-12 h-16 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-wisdom-primary/30 border border-white/20 overflow-hidden">
                        <img src="<?= htmlspecialchars($b['book_cover']) ?>" alt="Cover" class="w-full h-full object-cover">
                    </div>
                <?php else: ?>
                    <div class="w-12 h-16 bg-gradient-to-br from-wisdom-primary to-blue-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-wisdom-primary/30 border border-white/20">
                        <i data-lucide="book" class="w-6 h-6 text-white/90"></i>
                    </div>
                <?php endif; ?>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-serif font-bold text-gray-800 text-base leading-tight mb-1 line-clamp-2"><?= htmlspecialchars($b['book_title']) ?></p>
                            <p class="text-xs text-gray-500 font-medium flex items-center gap-2">
                                <i data-lucide="pen-tool" class="w-3 h-3 text-gray-400"></i>
                                <?= htmlspecialchars($b['book_author']) ?>
                                <span class="text-gray-300 mx-1">·</span>
                                <span class="font-mono text-gray-400"><?= htmlspecialchars($b['isbn']) ?></span>
                            </p>
                        </div>
                        <span class="flex-shrink-0 px-3 py-1.5 text-xs font-bold rounded-lg border <?= $badgeCls ?>">
                            <?= $statusLabel ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-4 pt-3 border-t border-gray-100">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1 flex items-center gap-1"><i data-lucide="calendar" class="w-3 h-3"></i> Tgl Pinjam</p>
                            <p class="text-sm font-bold text-gray-700"><?= formatDate($b['borrow_date']) ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i> <?= $statusDisplay === 'booked' ? 'Batas Ambil' : 'Jatuh Tempo' ?>
                            </p>
                            <p class="text-sm font-bold <?= $isOverdue?'text-red-600':'text-gray-700' ?>"><?= formatDate($b['due_date']) ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1 flex items-center gap-1"><i data-lucide="calendar-check" class="w-3 h-3"></i> Dikembalikan</p>
                            <p class="text-sm font-bold text-gray-700"><?= $b['return_date'] ? formatDate($b['return_date']) : '—' ?></p>
                        </div>
                    </div>

                    <?php if ($b['fine'] > 0): ?>
                    <div class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-red-50 rounded-xl border border-red-100">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-red-500"></i>
                        <span class="text-sm text-red-700 font-bold">Denda: <?= formatCurrency($b['fine']) ?></span>
                    </div>
                    <?php elseif ($statusDisplay === 'booked'): ?>
                    <div class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-sky-50 rounded-xl border border-sky-100">
                        <i data-lucide="info" class="w-4 h-4 text-sky-500"></i>
                        <span class="text-sm text-sky-700 font-bold">Harap ambil buku ini di perpustakaan sebelum <?= formatDate($b['due_date']) ?>.</span>
                    </div>
                    <?php elseif ($statusDisplay === 'cancelled'): ?>
                    <div class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-xl border border-gray-200">
                        <i data-lucide="x-circle" class="w-4 h-4 text-gray-500"></i>
                        <span class="text-sm text-gray-600 font-bold">Booking buku ini telah kedaluwarsa atau dibatalkan.</span>
                    </div>
                    <?php elseif ($b['status'] === 'returned'): ?>
                    <div class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 rounded-xl border border-emerald-100">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                        <span class="text-sm text-emerald-700 font-bold">Dikembalikan tepat waktu</span>
                    </div>
                    <?php elseif ($isOverdue): ?>
                    <div class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-red-50 rounded-xl border border-red-100">
                        <i data-lucide="clock-alert" class="w-4 h-4 text-red-500"></i>
                        <span class="text-sm text-red-700 font-bold">Segera kembalikan buku ini ke perpustakaan!</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($borrows['total_pages'] > 1): ?>
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-center gap-2 bg-gray-50/50">
        <?php for ($i=1; $i<=$borrows['total_pages']; $i++): ?>
        <a href="index.php?page=my-borrows&status=<?= $status ?>&pg=<?= $i ?>"
           class="min-w-[32px] h-8 flex items-center justify-center rounded-lg border text-sm font-bold transition-colors
           <?= $i==$borrows['current_page']?'bg-wisdom-primary text-white border-wisdom-primary shadow-sm':'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300' ?>">
            <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
