<?php
// app/controllers/ReportController.php

class ReportController extends BaseController {
    private BorrowModel $borrowModel;

    public function __construct() {
        $this->borrowModel = new BorrowModel();
    }

    public function index(): void {
        $status   = $this->param('status');
        $dateFrom = $this->param('date_from');
        $dateTo   = $this->param('date_to');
        $data     = $this->borrowModel->getAllForExport($status, $dateFrom, $dateTo);
        $this->view('admin/reports/index', compact('data', 'status', 'dateFrom', 'dateTo'));
    }

    public function exportExcel(): void {
        $status   = $this->param('status');
        $dateFrom = $this->param('date_from');
        $dateTo   = $this->param('date_to');
        $borrows  = $this->borrowModel->getAllForExport($status, $dateFrom, $dateTo);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="laporan_peminjaman_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');

        echo '<table border="1">';
        echo '<tr style="background:#1e40af;color:white;font-weight:bold;">';
        echo '<th>No</th><th>Anggota</th><th>NIM/NIS</th><th>Judul Buku</th><th>ISBN</th>';
        echo '<th>Tgl Pinjam</th><th>Tgl Jatuh Tempo</th><th>Tgl Kembali</th><th>Status</th><th>Denda</th>';
        echo '</tr>';

        $no = 1;
        foreach ($borrows as $b) {
            $statusLabel = match($b['status']) {
                'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'overdue' => 'Terlambat', default => $b['status']
            };
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . htmlspecialchars($b['user_name']) . '</td>';
            echo '<td>' . htmlspecialchars($b['student_id'] ?? '-') . '</td>';
            echo '<td>' . htmlspecialchars($b['book_title']) . '</td>';
            echo '<td>' . htmlspecialchars($b['isbn']) . '</td>';
            echo '<td>' . formatDate($b['borrow_date']) . '</td>';
            echo '<td>' . formatDate($b['due_date']) . '</td>';
            echo '<td>' . ($b['return_date'] ? formatDate($b['return_date']) : '-') . '</td>';
            echo '<td>' . $statusLabel . '</td>';
            echo '<td>' . ($b['fine'] > 0 ? formatCurrency($b['fine']) : '-') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        exit;
    }

    public function exportPDF(): void {
        $status   = $this->param('status');
        $dateFrom = $this->param('date_from');
        $dateTo   = $this->param('date_to');
        $borrows  = $this->borrowModel->getAllForExport($status, $dateFrom, $dateTo);

        header('Content-Type: text/html; charset=utf-8');
        $this->view('admin/reports/pdf', compact('borrows', 'status', 'dateFrom', 'dateTo'));
        exit;
    }
}
