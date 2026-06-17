<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman — <?= APP_NAME ?></title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; }
        .header { text-align:center; padding: 20px; border-bottom: 2px solid #1e40af; margin-bottom:16px; }
        .header h1 { font-size:18px; font-weight:bold; color:#1e40af; }
        .header p  { font-size:11px; color:#555; margin-top:4px; }
        .meta { display:flex; justify-content:space-between; padding:0 20px 12px; font-size:11px; color:#555; }
        table { width:100%; border-collapse:collapse; margin:0 20px; width:calc(100% - 40px); }
        th { background:#1e40af; color:#fff; padding:7px 8px; text-align:left; font-size:11px; }
        td { padding:6px 8px; border-bottom:1px solid #e5e7eb; font-size:11px; }
        tr:nth-child(even) td { background:#f8fafc; }
        .status-borrowed { color:#92400e; background:#fef3c7; padding:2px 6px; border-radius:4px; font-size:10px; }
        .status-returned { color:#065f46; background:#d1fae5; padding:2px 6px; border-radius:4px; font-size:10px; }
        .status-overdue  { color:#991b1b; background:#fee2e2; padding:2px 6px; border-radius:4px; font-size:10px; }
        .footer { margin-top:20px; padding:12px 20px; border-top:1px solid #e5e7eb; display:flex; justify-content:space-between; font-size:10px; color:#888; }
        tfoot td { font-weight:bold; background:#f1f5f9; }
        @media print { body { -webkit-print-color-adjust:exact; print-color-adjust:exact; } }
    </style>
</head>
<body>
<div class="header">
    <h1><?= APP_NAME ?></h1>
    <p>Laporan Rekapitulasi Peminjaman Buku</p>
    <?php if ($dateFrom || $dateTo || $status): ?>
    <p>
        <?= $status ? 'Status: ' . ucfirst($status) . ' · ' : '' ?>
        <?= $dateFrom ? 'Dari: ' . formatDate($dateFrom) . ' ' : '' ?>
        <?= $dateTo ? 'S/d: ' . formatDate($dateTo) : '' ?>
    </p>
    <?php endif; ?>
</div>

<div class="meta">
    <span>Dicetak: <?= date('d M Y H:i') ?></span>
    <span>Total data: <?= count($borrows) ?> transaksi</span>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Anggota</th>
            <th>NIM/NIS</th>
            <th>Judul Buku</th>
            <th>ISBN</th>
            <th>Tgl Pinjam</th>
            <th>Jatuh Tempo</th>
            <th>Tgl Kembali</th>
            <th>Status</th>
            <th>Denda</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; $totalFine=0; foreach ($borrows as $row): $totalFine += $row['fine']; ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['student_id'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['book_title']) ?></td>
            <td style="font-family:monospace"><?= htmlspecialchars($row['isbn']) ?></td>
            <td><?= formatDate($row['borrow_date']) ?></td>
            <td><?= formatDate($row['due_date']) ?></td>
            <td><?= $row['return_date'] ? formatDate($row['return_date']) : '—' ?></td>
            <td>
                <?php $s=$row['status']; $lbl=match($s){'borrowed'=>'Dipinjam','returned'=>'Dikembalikan','overdue'=>'Terlambat',default=>$s}; ?>
                <span class="status-<?= $s ?>"><?= $lbl ?></span>
            </td>
            <td><?= $row['fine']>0 ? formatCurrency($row['fine']) : '—' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9" style="text-align:right; padding-right:12px;">Total Denda:</td>
            <td style="color:#dc2626;"><?= formatCurrency($totalFine) ?></td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    <span><?= APP_NAME ?> — Sistem Perpustakaan Digital</span>
    <span>Halaman 1</span>
</div>

<script>window.onload = () => window.print();</script>
</body>
</html>
