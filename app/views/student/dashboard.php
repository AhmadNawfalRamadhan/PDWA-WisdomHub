<?php $pageTitle = 'Dashboard Saya';
require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Greeting -->
<div class="relative bg-wisdom-dark rounded-3xl p-8 mb-8 text-white shadow-xl overflow-hidden">
    <!-- Decor -->
    <div class="absolute inset-0 opacity-10"
        style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;">
    </div>
    <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-wisdom-primary rounded-full blur-3xl opacity-50">
    </div>
    <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-48 h-48 bg-wisdom-accent rounded-full blur-3xl opacity-30">
    </div>

    <div class="relative z-10 flex flex-col md:flex-row items-center md:justify-between gap-6">
        <div>
            <h2 class="text-3xl font-serif font-bold text-wisdom-sand flex items-center gap-3">
                Halo, <?= htmlspecialchars($_SESSION['user_name']) ?>! <i data-lucide="hand"
                    class="w-8 h-8 text-yellow-400"></i>
            </h2>
            <p class="text-wisdom-sand/70 mt-2 font-medium">Selamat datang kembali di Pusat Kebijaksanaan & Ilmu
                Pengetahuan.</p>
        </div>
        <div class="flex gap-4">
            <a href="index.php?page=books"
                class="bg-wisdom-accent hover:bg-yellow-600 text-white px-6 py-2.5 rounded-full font-bold shadow-lg shadow-wisdom-accent/20 transition-all flex items-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i> Cari Buku
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<h3 class="font-serif font-bold text-gray-800 mb-4 flex items-center gap-2">
    <i data-lucide="bar-chart" class="w-5 h-5 text-wisdom-primary"></i> Ringkasan Aktivitas
</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div
        class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
        <div
            class="absolute right-0 top-0 w-24 h-full bg-blue-50 -skew-x-12 translate-x-4 opacity-50 group-hover:bg-blue-100 transition-colors">
        </div>
        <div
            class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-200 z-10">
            <i data-lucide="book-open" class="w-7 h-7"></i>
        </div>
        <div class="z-10">
            <p class="text-3xl font-serif font-bold text-gray-800"><?= $myStats['active'] ?></p>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-1">Sedang Dipinjam</p>
        </div>
    </div>

    <div
        class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
        <div
            class="absolute right-0 top-0 w-24 h-full bg-emerald-50 -skew-x-12 translate-x-4 opacity-50 group-hover:bg-emerald-100 transition-colors">
        </div>
        <div
            class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-200 z-10">
            <i data-lucide="check-circle" class="w-7 h-7"></i>
        </div>
        <div class="z-10">
            <p class="text-3xl font-serif font-bold text-gray-800"><?= 3 - $myStats['active'] ?></p>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-1">Slot Tersedia</p>
        </div>
    </div>

    <div
        class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
        <div
            class="absolute right-0 top-0 w-24 h-full bg-yellow-50 -skew-x-12 translate-x-4 opacity-50 group-hover:bg-yellow-100 transition-colors">
        </div>
        <div
            class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center text-yellow-600 border border-yellow-200 z-10">
            <i data-lucide="info" class="w-7 h-7"></i>
        </div>
        <div class="z-10">
            <p class="text-lg font-serif font-bold text-gray-800"><?= formatCurrency(FINE_PER_DAY) ?></p>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-1">Denda per Hari</p>
        </div>
    </div>
</div>

<!-- Active Borrows -->
<div class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h3 class="font-serif font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="book-marked" class="w-5 h-5 text-wisdom-accent"></i> Peminjaman Aktif Saat Ini
        </h3>
        <a href="index.php?page=my-borrows"
            class="text-xs font-semibold bg-white hover:bg-gray-50 text-gray-600 px-3 py-1.5 rounded-lg border border-gray-200 transition-colors flex items-center gap-1">
            Lihat riwayat <i data-lucide="arrow-right" class="w-3 h-3"></i>
        </a>
    </div>

    <?php if (empty($myBorrows['data'])): ?>
        <div class="text-center py-16 text-gray-400 bg-white">
            <i data-lucide="book-dashed" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
            <p class="font-bold text-lg text-gray-600 font-serif mb-2">Tidak ada peminjaman aktif</p>
            <p class="text-sm mb-4">Anda belum meminjam buku apa pun saat ini.</p>
            <a href="index.php?page=books"
                class="inline-flex items-center gap-2 text-wisdom-primary font-bold hover:underline">
                Eksplorasi Katalog <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    <?php else: ?>
        <div class="divide-y divide-gray-50 bg-white">
            <?php foreach ($myBorrows['data'] as $b): ?>
                <?php
                $isOverdue = date('Y-m-d') > $b['due_date'] && $b['status'] !== 'returned';
                $daysDiff = (strtotime($b['due_date']) - strtotime(date('Y-m-d'))) / 86400;
                $s = $b['status'];

                if ($s === 'booked') {
                    $statusLbl = 'Booking';
                    $badgeCls = 'bg-sky-50 text-sky-700 border border-sky-200';
                    $deadlineLbl = 'Batas ambil:';
                } else {
                    $statusLbl = $isOverdue || $s === 'overdue' ? 'Terlambat' : 'Dipinjam';
                    $badgeCls = $isOverdue || $s === 'overdue' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-blue-700 border border-blue-200';
                    $deadlineLbl = 'Jatuh tempo:';
                }
                ?>
                <div class="px-6 py-5 flex items-center gap-5 hover:bg-gray-50 transition-colors group">
                    <?php if (!empty($b['book_cover'])): ?>
                        <div
                            class="w-12 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 shadow-inner overflow-hidden">
                            <img src="<?= htmlspecialchars($b['book_cover']) ?>" alt="Cover" class="w-full h-full object-cover">
                        </div>
                    <?php else: ?>
                        <div
                            class="w-12 h-16 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 shadow-inner">
                            <i data-lucide="book" class="w-6 h-6 text-wisdom-primary"></i>
                        </div>
                    <?php endif; ?>
                    <div class="flex-1 min-w-0">
                        <p
                            class="font-serif font-bold text-gray-800 truncate text-lg group-hover:text-wisdom-primary transition-colors">
                            <?= htmlspecialchars($b['book_title']) ?></p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <i data-lucide="pen-tool" class="w-3 h-3"></i> <?= htmlspecialchars($b['book_author']) ?>
                        </p>
                        <p class="text-xs font-medium flex items-center gap-1.5">
                            <i data-lucide="calendar-clock" class="w-3.5 h-3.5 text-gray-400"></i> <?= $deadlineLbl ?>
                            <span
                                class="<?= $isOverdue ? 'text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded' : 'text-gray-700' ?>">
                                <?= formatDate($b['due_date']) ?>
                            </span>
                            <span
                                class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?= $daysDiff < 0 ? 'bg-red-100 text-red-600' : ($daysDiff == 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-emerald-100 text-emerald-700') ?>">
                                <?php
                                if ($daysDiff < 0)
                                    echo abs($daysDiff) . ' Hari Terlambat';
                                elseif ($daysDiff == 0)
                                    echo 'Hari Ini';
                                else
                                    echo $daysDiff . ' Hari Lagi';
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="px-3 py-1.5 rounded-lg font-bold text-xs shadow-sm <?= $badgeCls ?>">
                            <?= $statusLbl ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>