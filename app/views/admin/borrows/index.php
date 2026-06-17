<?php $pageTitle = 'Manajemen Sirkulasi'; require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="flex items-center justify-between mb-8">
    <h2 class="text-2xl font-serif font-bold text-gray-800 flex items-center gap-3">
        <i data-lucide="arrow-left-right" class="w-6 h-6 text-wisdom-primary"></i> Manajemen Sirkulasi
    </h2>
    <a href="index.php?page=borrows&action=create" class="flex items-center gap-2 px-5 py-2.5 bg-wisdom-primary text-white text-sm font-bold rounded-xl hover:bg-blue-900 transition shadow-lg shadow-wisdom-primary/30">
        <i data-lucide="plus" class="w-4 h-4"></i> Catat Peminjaman
    </a>
</div>

<!-- Filter -->
<form method="GET" action="index.php" class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 p-5 mb-8 flex flex-wrap gap-4 items-end">
    <input type="hidden" name="page" value="borrows">
    <div class="flex-1 min-w-60">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pencarian</label>
        <div class="relative group">
            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 group-focus-within:text-wisdom-primary transition-colors"></i>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all"
                placeholder="Nama anggota, judul buku, atau NIM...">
        </div>
    </div>
    <div class="w-56">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
        <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white hover:bg-white outline-none transition-all cursor-pointer">
            <option value="">Semua Status</option>
            <option value="borrowed"  <?= $status==='borrowed' ?'selected':'' ?>>Dipinjam</option>
            <option value="booked"    <?= $status==='booked'   ?'selected':'' ?>>Booking</option>
            <option value="returned"  <?= $status==='returned' ?'selected':'' ?>>Dikembalikan</option>
            <option value="overdue"   <?= $status==='overdue'  ?'selected':'' ?>>Terlambat</option>
            <option value="cancelled" <?= $status==='cancelled'?'selected':'' ?>>Dibatalkan</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-6 py-2.5 bg-wisdom-dark text-white text-sm font-bold rounded-xl hover:bg-black transition shadow-sm flex items-center gap-2">
            <i data-lucide="filter" class="w-4 h-4"></i> Filter
        </button>
        <?php if ($search || $status): ?>
        <a href="index.php?page=borrows" class="px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition flex items-center gap-2">
            <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Reset
        </a>
        <?php endif; ?>
    </div>
</form>

<div class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h3 class="font-serif font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="list" class="w-5 h-5 text-wisdom-accent"></i> Data Sirkulasi
        </h3>
        <span class="px-3 py-1 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-500">Total: <?= $borrows['total'] ?> Transaksi</span>
    </div>

    <?php if (empty($borrows['data'])): ?>
    <div class="text-center py-20 text-gray-400 bg-white">
        <i data-lucide="arrow-left-right" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
        <p class="font-serif font-bold text-lg text-gray-600 mb-1">Tidak ada data sirkulasi</p>
        <p class="text-sm">Belum ada transaksi peminjaman yang tercatat atau sesuai dengan filter Anda.</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Peminjam</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Buku</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Denda</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                <?php foreach ($borrows['data'] as $row): ?>
                <tr class="hover:bg-blue-50/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($row['user_profile_picture'])): ?>
                                <img src="<?= APP_URL ?>/public/assets/profiles/<?= htmlspecialchars($row['user_profile_picture']) ?>" alt="<?= htmlspecialchars($row['user_name']) ?>" class="w-8 h-8 rounded-full object-cover shadow-sm flex-shrink-0">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    <?= strtoupper(substr($row['user_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="font-bold text-gray-800"><?= htmlspecialchars($row['user_name']) ?></p>
                                <p class="text-xs text-gray-500 font-medium"><?= htmlspecialchars($row['student_id'] ?? '') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <p class="font-bold text-gray-800 max-w-[200px] truncate" title="<?= htmlspecialchars($row['book_title']) ?>"><?= htmlspecialchars($row['book_title']) ?></p>
                        <p class="text-xs text-gray-500 font-mono bg-gray-100 px-1.5 py-0.5 rounded inline-block mt-1"><?= htmlspecialchars($row['isbn']) ?></p>
                    </td>
                    <td class="px-4 py-4 text-gray-600 text-xs font-medium">
                        <div class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i> <?= formatDate($row['borrow_date']) ?></div>
                    </td>
                    <td class="px-4 py-4 text-xs <?= ($row['status']==='overdue')?'text-red-600 font-bold':'text-gray-600 font-medium' ?>">
                        <div class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5 <?= ($row['status']==='overdue')?'text-red-400':'text-gray-400' ?>"></i> <?= formatDate($row['due_date']) ?></div>
                    </td>
                    <td class="px-4 py-4 text-gray-600 text-xs font-medium">
                        <?= $row['return_date'] ? '<div class="flex items-center gap-1.5"><i data-lucide="calendar-check" class="w-3.5 h-3.5 text-emerald-500"></i> ' . formatDate($row['return_date']) . '</div>' : '<span class="text-gray-400">—</span>' ?>
                    </td>
                    <td class="px-4 py-4">
                        <?php 
                        $s=$row['status']; 
                        if ($s==='returned') {
                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            $lbl = 'Dikembalikan';
                        } elseif ($s==='overdue') {
                            $badgeClass = 'bg-red-50 text-red-700 border-red-200';
                            $lbl = 'Terlambat';
                        } elseif ($s==='booked') {
                            $badgeClass = 'bg-sky-50 text-sky-700 border-sky-200';
                            $lbl = 'Booking';
                        } elseif ($s==='cancelled') {
                            $badgeClass = 'bg-gray-100 text-gray-600 border-gray-300';
                            $lbl = 'Dibatalkan';
                        } else {
                            $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                            $lbl = 'Dipinjam';
                        }
                        ?>
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg border <?= $badgeClass ?>">
                            <?= $lbl ?>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-xs">
                        <?php if ($row['fine']>0): ?>
                        <span class="px-2 py-1 bg-red-50 text-red-700 font-bold rounded border border-red-100"><?= formatCurrency($row['fine']) ?></span>
                        <?php else: ?>
                        <span class="text-gray-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 text-right">
                        <?php if (in_array($row['status'], ['borrowed','overdue'])): ?>
                        <a href="index.php?page=borrows&action=return&id=<?= $row['id'] ?>"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg hover:bg-emerald-100 border border-emerald-100 transition-colors">
                            <i data-lucide="undo" class="w-3.5 h-3.5"></i> Proses
                        </a>
                        <?php elseif ($row['status'] === 'booked'): ?>
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="index.php?page=borrows&action=approve_booking&id=<?= $row['id'] ?>" onclick="event.preventDefault(); let url = this.href; showWisdomConfirm('Setujui booking ini?', () => window.location.href = url);"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-sky-50 text-sky-700 text-xs font-bold rounded-lg hover:bg-sky-100 border border-sky-100 transition-colors" title="Setujui Booking">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Setujui
                            </a>
                            <a href="index.php?page=borrows&action=cancel_booking&id=<?= $row['id'] ?>" onclick="event.preventDefault(); let url = this.href; showWisdomConfirm('Batalkan booking ini?', () => window.location.href = url);"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 text-red-700 text-xs font-bold rounded-lg hover:bg-red-100 border border-red-100 transition-colors" title="Tolak/Batal">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i> Tolak
                            </a>
                        </div>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-400 text-xs font-bold rounded-lg border border-gray-100">
                            <i data-lucide="check" class="w-3.5 h-3.5"></i> Selesai
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($borrows['total_pages'] > 1): ?>
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
        <span class="text-sm font-medium text-gray-500">
            Halaman <span class="font-bold text-gray-800"><?= $borrows['current_page'] ?></span> dari <span class="font-bold text-gray-800"><?= $borrows['total_pages'] ?></span>
        </span>
        <div class="flex gap-1.5">
            <?php for ($i=1; $i<=$borrows['total_pages']; $i++): ?>
            <a href="index.php?page=borrows&search=<?= urlencode($search) ?>&status=<?= $status ?>&page=<?= $i ?>"
               class="min-w-[32px] h-8 flex items-center justify-center rounded-lg border text-sm font-bold transition-colors
               <?= $i==$borrows['current_page']?'bg-wisdom-primary text-white border-wisdom-primary shadow-sm':'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300' ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
