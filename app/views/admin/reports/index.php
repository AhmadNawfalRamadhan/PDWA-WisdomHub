<?php $pageTitle = 'Laporan & Ekspor'; require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="flex items-center justify-between mb-8">
    <h2 class="text-2xl font-serif font-bold text-gray-800 flex items-center gap-3">
        <i data-lucide="bar-chart-3" class="w-6 h-6 text-wisdom-primary"></i> Laporan & Ekspor Data
    </h2>
</div>

<!-- Filter Form -->
<div class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="font-bold text-gray-700 mb-5 flex items-center gap-2 text-sm uppercase tracking-wider">
        <i data-lucide="sliders-horizontal" class="w-4 h-4 text-wisdom-accent"></i> Filter Laporan
    </h3>
    <form method="GET" action="index.php" class="flex flex-wrap gap-4 items-end">
        <input type="hidden" name="page" value="reports">
        <div class="w-48">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
            <div class="relative">
                <i data-lucide="tag" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none"></i>
                <select name="status" class="w-full pl-10 pr-8 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white outline-none appearance-none cursor-pointer transition-all">
                    <option value="">Semua Status</option>
                    <option value="borrowed" <?= $status==='borrowed'?'selected':'' ?>>Dipinjam</option>
                    <option value="returned" <?= $status==='returned'?'selected':'' ?>>Dikembalikan</option>
                    <option value="overdue"  <?= $status==='overdue' ?'selected':'' ?>>Terlambat</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none"></i>
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dari Tanggal</label>
            <div class="relative">
                <i data-lucide="calendar" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none"></i>
                <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>"
                    class="pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white outline-none transition-all">
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sampai Tanggal</label>
            <div class="relative">
                <i data-lucide="calendar" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none"></i>
                <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>"
                    class="pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-0 focus:border-wisdom-primary focus:bg-white outline-none transition-all">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-6 py-2.5 bg-wisdom-dark text-white text-sm font-bold rounded-xl hover:bg-black transition shadow-sm flex items-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i> Tampilkan
            </button>
            <a href="index.php?page=reports" class="px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition flex items-center gap-2">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Export Buttons -->
<div class="flex flex-wrap items-center gap-4 mb-8">
    <a href="index.php?page=reports&action=export-excel&status=<?= urlencode($status) ?>&date_from=<?= urlencode($dateFrom) ?>&date_to=<?= urlencode($dateTo) ?>"
       class="flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/25">
        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export Excel
    </a>
    <a href="index.php?page=reports&action=export-pdf&status=<?= urlencode($status) ?>&date_from=<?= urlencode($dateFrom) ?>&date_to=<?= urlencode($dateTo) ?>"
       target="_blank"
       class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-600/25">
        <i data-lucide="file-text" class="w-4 h-4"></i> Export PDF
    </a>
    <div class="flex items-center gap-2 px-4 py-3 bg-blue-50 text-blue-700 text-sm font-bold rounded-xl border border-blue-100">
        <i data-lucide="database" class="w-4 h-4"></i>
        <span><?= count($data) ?> data ditemukan</span>
    </div>
</div>

<!-- Data Table -->
<div class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <h3 class="font-serif font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="list" class="w-5 h-5 text-wisdom-accent"></i> Rekapitulasi Peminjaman
        </h3>
        <?php if (!empty($data)): ?>
        <span class="px-3 py-1 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-500">
            Total Denda: <span class="text-red-600"><?= formatCurrency(array_sum(array_column($data, 'fine'))) ?></span>
        </span>
        <?php endif; ?>
    </div>

    <?php if (empty($data)): ?>
    <div class="text-center py-20 text-gray-400 bg-white">
        <i data-lucide="bar-chart-3" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
        <p class="font-serif font-bold text-lg text-gray-600 mb-1">Tidak ada data untuk ditampilkan</p>
        <p class="text-sm">Coba ubah filter atau rentang tanggal untuk melihat hasil laporan.</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-10">No</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Anggota</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">NIM/NIS</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Buku</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Kembali</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Denda</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                <?php $no=1; foreach ($data as $row): ?>
                <tr class="hover:bg-blue-50/30 transition-colors">
                    <td class="px-4 py-3.5 text-gray-400 text-xs font-bold"><?= $no++ ?></td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                <?= strtoupper(substr($row['user_name'],0,1)) ?>
                            </div>
                            <span class="font-bold text-gray-800"><?= htmlspecialchars($row['user_name']) ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded text-gray-600"><?= htmlspecialchars($row['student_id'] ?? '—') ?></span>
                    </td>
                    <td class="px-4 py-3.5 text-gray-700 max-w-[200px]">
                        <p class="truncate font-bold text-gray-800 text-xs" title="<?= htmlspecialchars($row['book_title']) ?>"><?= htmlspecialchars($row['book_title']) ?></p>
                        <p class="text-[10px] text-gray-400 font-mono mt-0.5"><?= htmlspecialchars($row['isbn']) ?></p>
                    </td>
                    <td class="px-4 py-3.5 text-gray-600 text-xs whitespace-nowrap font-medium">
                        <div class="flex items-center gap-1"><i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i> <?= formatDate($row['borrow_date']) ?></div>
                    </td>
                    <td class="px-4 py-3.5 text-xs whitespace-nowrap font-bold <?= $row['status']==='overdue'?'text-red-600':'text-gray-600' ?>">
                        <div class="flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3 <?= $row['status']==='overdue'?'text-red-400':'text-gray-400' ?>"></i> <?= formatDate($row['due_date']) ?></div>
                    </td>
                    <td class="px-4 py-3.5 text-gray-600 text-xs whitespace-nowrap font-medium">
                        <?= $row['return_date'] ? '<div class="flex items-center gap-1"><i data-lucide="calendar-check" class="w-3 h-3 text-emerald-500"></i> ' . formatDate($row['return_date']) . '</div>' : '<span class="text-gray-400">—</span>' ?>
                    </td>
                    <td class="px-4 py-3.5">
                        <?php
                        $s=$row['status'];
                        if ($s==='returned') { $badgeCls = 'bg-emerald-50 text-emerald-700 border-emerald-200'; $lbl = 'Dikembalikan'; }
                        elseif ($s==='overdue') { $badgeCls = 'bg-red-50 text-red-700 border-red-200'; $lbl = 'Terlambat'; }
                        else { $badgeCls = 'bg-blue-50 text-blue-700 border-blue-200'; $lbl = 'Dipinjam'; }
                        ?>
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg border <?= $badgeCls ?>"><?= $lbl ?></span>
                    </td>
                    <td class="px-4 py-3.5 text-xs">
                        <?php if ($row['fine']>0): ?>
                        <span class="px-2 py-1 bg-red-50 text-red-700 font-bold rounded border border-red-100"><?= formatCurrency($row['fine']) ?></span>
                        <?php else: ?>
                        <span class="text-gray-400">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-gray-50/80 border-t-2 border-gray-200">
                <tr>
                    <td colspan="8" class="px-4 py-4 text-sm font-bold text-gray-700 flex items-center gap-2">
                        <i data-lucide="calculator" class="w-4 h-4 text-gray-400"></i> Total Akumulasi Denda
                    </td>
                    <td class="px-4 py-4 text-base font-bold text-red-600">
                        <?= formatCurrency(array_sum(array_column($data, 'fine'))) ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
