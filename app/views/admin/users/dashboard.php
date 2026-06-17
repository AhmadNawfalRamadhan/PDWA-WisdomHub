<?php $pageTitle = 'Dashboard Admin';
require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- STATS CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-5 mb-8">
    <?php $cards = [
        ['Buku', $stats['total_books'], 'book', 'from-blue-500 to-indigo-600', 'text-blue-500', 'bg-blue-50'],
        ['Stok Sisa', $stats['total_stock'], 'package', 'from-emerald-500 to-teal-600', 'text-emerald-500', 'bg-emerald-50'],
        ['Dipinjam', $stats['total_borrowed'], 'hand-coins', 'from-amber-500 to-orange-600', 'text-amber-500', 'bg-amber-50'],
        ['Terlambat', $stats['total_overdue'], 'alert-triangle', 'from-red-500 to-rose-600', 'text-red-500', 'bg-red-50'],
        ['Anggota', $stats['total_students'], 'users', 'from-purple-500 to-fuchsia-600', 'text-purple-500', 'bg-purple-50'],
        ['Total Denda', formatCurrency($stats['total_fines']), 'banknote', 'from-wisdom-accent to-yellow-600', 'text-wisdom-accent', 'bg-yellow-50'],
    ]; ?>
    <?php foreach ($cards as [$label, $val, $icon, $grad, $textColor, $bgColor]): ?>
        <div
            class="bg-wisdom-paper rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow group relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br <?= $grad ?> opacity-10 rounded-full blur-2xl group-hover:opacity-20 transition-opacity">
            </div>
            <div class="flex items-center gap-4 mb-4">
                <div
                    class="w-12 h-12 <?= $bgColor ?> rounded-xl flex items-center justify-center <?= $textColor ?> shadow-inner border border-white">
                    <i data-lucide="<?= $icon ?>" class="w-6 h-6"></i>
                </div>
            </div>
            <p class="text-3xl font-serif font-bold text-gray-800 leading-tight mb-1"><?= $val ?></p>
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider"><?= $label ?></p>
        </div>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Chart -->
    <div
        class="lg:col-span-2 bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
        <h3 class="font-serif font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i data-lucide="trending-up" class="w-5 h-5 text-wisdom-primary"></i> Tren Peminjaman (6 Bulan)
        </h3>
        <canvas id="borrowChart" height="100" class="relative z-10"></canvas>
    </div>

    <!-- Popular Books -->
    <div class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
        <h3 class="font-serif font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i data-lucide="star" class="w-5 h-5 text-wisdom-accent"></i> Buku Terpopuler
        </h3>
        <div class="space-y-4 flex-1">
            <?php foreach ($popularBooks as $i => $book): ?>
                <div class="flex items-center gap-4 group cursor-pointer">
                    <div
                        class="w-8 h-8 flex items-center justify-center text-sm font-bold rounded-xl shadow-sm border border-gray-100 transition-colors
                    <?= $i === 0 ? 'bg-gradient-to-br from-yellow-100 to-yellow-200 text-yellow-700' : ($i === 1 ? 'bg-gradient-to-br from-gray-100 to-gray-200 text-gray-600' : 'bg-gray-50 text-gray-500 group-hover:bg-gray-100') ?>">
                        <?= $i + 1 ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-sm font-bold text-gray-800 truncate group-hover:text-wisdom-primary transition-colors">
                            <?= htmlspecialchars($book['title']) ?>
                        </p>
                        <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5"><i data-lucide="bar-chart-2"
                                class="w-3 h-3"></i> <?= $book['borrow_count'] ?> kali dipinjam</p>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($popularBooks)): ?>
                <div class="text-center py-8 text-gray-400">Belum ada data</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-wisdom-paper rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-serif font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="activity" class="w-5 h-5 text-green-600"></i> Aktivitas Terbaru
        </h3>
        <a href="index.php?page=borrows"
            class="text-xs font-semibold bg-gray-50 hover:bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg border border-gray-200 transition-colors flex items-center gap-1">
            Lihat semua <i data-lucide="chevron-right" class="w-3 h-3"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="border-b-2 border-gray-100">
                    <th class="py-3 pr-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Anggota</th>
                    <th class="py-3 pr-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Buku</th>
                    <th class="py-3 pr-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pinjam</th>
                    <th class="py-3 pr-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($recentActivity as $row): ?>
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($row['user_profile_picture'])): ?>
                                    <img src="<?= APP_URL ?>/public/assets/profiles/<?= htmlspecialchars($row['user_profile_picture']) ?>" alt="<?= htmlspecialchars($row['user_name']) ?>" class="w-8 h-8 rounded-full object-cover shadow-sm flex-shrink-0">
                                <?php else: ?>
                                    <div
                                        class="w-8 h-8 rounded-full bg-wisdom-primary text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        <?= strtoupper(substr($row['user_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="font-bold text-gray-800 group-hover:text-wisdom-primary transition-colors">
                                        <?= htmlspecialchars($row['user_name']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($row['student_id'] ?? '') ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 pr-4 max-w-xs">
                            <p class="font-semibold text-gray-700 truncate"><?= htmlspecialchars($row['book_title']) ?></p>
                        </td>
                        <td class="py-3 pr-4 text-gray-600 font-medium"><?= formatDate($row['borrow_date']) ?></td>
                        <td class="py-3 pr-4 text-gray-600 font-medium"><?= formatDate($row['due_date']) ?></td>
                        <td class="py-3">
                            <?php $s = $row['status'];
                            $label = match ($s) { 'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'overdue' => 'Terlambat', default => $s}; ?>
                            <span
                                class="badge-<?= $s === 'returned' ? 'returned' : ($s === 'overdue' ? 'overdue' : 'borrowed') ?>">
                                <?= $label ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($recentActivity)): ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">Tidak ada aktivitas peminjaman.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<h3 class="font-serif font-bold text-gray-800 mb-4 flex items-center gap-2">
    <i data-lucide="zap" class="w-5 h-5 text-yellow-500"></i> Aksi Cepat
</h3>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
    <a href="index.php?page=borrows&action=create"
        class="group flex items-center gap-4 bg-wisdom-paper hover:bg-blue-50 text-gray-700 hover:text-blue-700 rounded-2xl p-5 border border-gray-100 shadow-sm transition-all hover:shadow-md hover:-translate-y-1">
        <div class="bg-blue-100 text-blue-600 p-3 rounded-xl group-hover:scale-110 transition-transform">
            <i data-lucide="plus-circle" class="w-6 h-6"></i>
        </div>
        <span class="font-bold text-sm">Catat Peminjaman</span>
    </a>
    <a href="index.php?page=books&action=create"
        class="group flex items-center gap-4 bg-wisdom-paper hover:bg-emerald-50 text-gray-700 hover:text-emerald-700 rounded-2xl p-5 border border-gray-100 shadow-sm transition-all hover:shadow-md hover:-translate-y-1">
        <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl group-hover:scale-110 transition-transform">
            <i data-lucide="book-plus" class="w-6 h-6"></i>
        </div>
        <span class="font-bold text-sm">Tambah Buku</span>
    </a>
    <a href="index.php?page=users&action=create"
        class="group flex items-center gap-4 bg-wisdom-paper hover:bg-purple-50 text-gray-700 hover:text-purple-700 rounded-2xl p-5 border border-gray-100 shadow-sm transition-all hover:shadow-md hover:-translate-y-1">
        <div class="bg-purple-100 text-purple-600 p-3 rounded-xl group-hover:scale-110 transition-transform">
            <i data-lucide="user-plus" class="w-6 h-6"></i>
        </div>
        <span class="font-bold text-sm">Tambah Anggota</span>
    </a>
    <a href="index.php?page=reports"
        class="group flex items-center gap-4 bg-wisdom-paper hover:bg-orange-50 text-gray-700 hover:text-orange-700 rounded-2xl p-5 border border-gray-100 shadow-sm transition-all hover:shadow-md hover:-translate-y-1">
        <div class="bg-orange-100 text-orange-600 p-3 rounded-xl group-hover:scale-110 transition-transform">
            <i data-lucide="file-down" class="w-6 h-6"></i>
        </div>
        <span class="font-bold text-sm">Ekspor Laporan</span>
    </a>
</div>

<script>
    // Borrow Chart
    const chartData = <?= json_encode($chartData) ?>;
    const labels = chartData.map(d => d.month);
    const totals = chartData.map(d => parseInt(d.total));

    new Chart(document.getElementById('borrowChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Peminjaman',
                data: totals,
                borderColor: '#1e3a8a', // wisdom-primary
                backgroundColor: 'rgba(30,58,138,0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#d97706', // wisdom-accent
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>