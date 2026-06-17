<?php
// app/controllers/BorrowController.php

class BorrowController extends BaseController {
    private BorrowModel $borrowModel;
    private BookModel   $bookModel;
    private UserModel   $userModel;

    public function __construct() {
        $this->borrowModel = new BorrowModel();
        $this->bookModel   = new BookModel();
        $this->userModel   = new UserModel();
    }

    public function index(): void {
        $this->borrowModel->updateOverdueStatus();
        $search = $this->param('search');
        $status = $this->param('status');
        $page   = max(1, (int) $this->param('page', '1'));
        $borrows = $this->borrowModel->getAll($page, $search, $status);
        $this->view('admin/borrows/index', compact('borrows', 'search', 'status'));
    }

    public function myBorrows(): void {
        $this->borrowModel->updateOverdueStatus();
        $userId = $_SESSION['user_id'];
        $status = $this->param('status');
        $page   = max(1, (int) $this->param('page', '1'));
        $borrows = $this->borrowModel->getByUser($userId, $page, $status);
        $this->view('student/borrows', compact('borrows', 'status'));
    }

    public function show(): void {
        $id     = (int) $this->param('id');
        $borrow = $this->borrowModel->findById($id);
        if (!$borrow) {
            flashMessage('error', 'Data tidak ditemukan.');
            $this->redirect('/index.php?page=borrows');
        }
        if (!isAdmin() && $borrow['user_id'] != $_SESSION['user_id']) {
            flashMessage('error', 'Akses ditolak.');
            $this->redirect('/index.php?page=dashboard');
        }
        $this->view(isAdmin() ? 'admin/borrows/show' : 'student/borrow_detail', compact('borrow'));
    }

    public function create(): void {
        $books   = $this->bookModel->getAvailableBooks();
        $students = $this->userModel->getAllStudents();
        $preselectedBook = (int) $this->param('book_id');
        $this->view('admin/borrows/create', compact('books', 'students', 'preselectedBook'));
    }

    public function store(): void {
        if (!$this->isPost()) {
            $this->redirect('/index.php?page=borrows');
        }

        $userId  = (int) $this->input('user_id');
        $bookId  = (int) $this->input('book_id');
        $date    = $this->input('borrow_date') ?: date('Y-m-d');
        $notes   = $this->input('notes');

        $book = $this->bookModel->findById($bookId);
        if (!$book || $book['stock'] < 1) {
            flashMessage('error', 'Stok buku tidak tersedia.');
            $this->redirect('/index.php?page=borrows&action=create');
            return;
        }

        // Max 3 books per student
        if ($this->borrowModel->getUserBorrowCount($userId) >= 3) {
            flashMessage('error', 'Anggota sudah meminjam 3 buku (maksimum).');
            $this->redirect('/index.php?page=borrows&action=create');
            return;
        }

        // Check if already borrowing the same book
        if ($this->borrowModel->getUserActiveBorrow($userId, $bookId)) {
            flashMessage('error', 'Anggota sudah meminjam buku ini.');
            $this->redirect('/index.php?page=borrows&action=create');
            return;
        }

        $this->borrowModel->create(['user_id' => $userId, 'book_id' => $bookId, 'borrow_date' => $date, 'notes' => $notes]);
        $this->bookModel->decrementStock($bookId);

        flashMessage('success', 'Peminjaman berhasil dicatat!');
        $this->redirect('/index.php?page=borrows');
    }

    public function book(): void {
        if (!$this->isPost()) {
            $this->redirect('/index.php?page=home');
        }

        if (!isStudent()) {
            flashMessage('error', 'Hanya anggota yang dapat melakukan booking buku.');
            $this->redirect('/index.php?page=home');
            return;
        }

        $userId  = $_SESSION['user_id'];
        $bookId  = (int) $this->input('book_id');
        $date    = date('Y-m-d');

        $book = $this->bookModel->findById($bookId);
        if (!$book || $book['stock'] < 1) {
            flashMessage('error', 'Stok buku tidak tersedia.');
            $this->redirect('/index.php?page=home');
            return;
        }

        // Max 3 books per student
        if ($this->borrowModel->getUserBorrowCount($userId) >= 3) {
            flashMessage('error', 'Anda sudah meminjam / booking batas maksimum (3 buku).');
            $this->redirect('/index.php?page=my-borrows');
            return;
        }

        // Check if already borrowing or booked the same book
        $activeBorrow = $this->borrowModel->getUserActiveBorrow($userId, $bookId);
        $alreadyBooked = $this->borrowModel->hasActiveBooking($userId, $bookId);
        
        if ($activeBorrow || $alreadyBooked) {
            flashMessage('error', 'Anda sudah meminjam atau mem-booking buku ini.');
            $this->redirect('/index.php?page=my-borrows');
            return;
        }

        $this->borrowModel->book([
            'user_id' => $userId, 
            'book_id' => $bookId, 
            'borrow_date' => $date, 
            'notes' => 'Booking melalui katalog'
        ]);
        $this->bookModel->decrementStock($bookId);

        flashMessage('success', 'Buku berhasil di-booking! Silakan ambil dalam 2 hari ke depan.');
        $this->redirect('/index.php?page=my-borrows');
    }

    public function approveBooking(): void {
        requireAdmin();
        $id = (int) $this->param('id');
        if ($this->borrowModel->approveBooking($id)) {
            flashMessage('success', 'Booking berhasil disetujui, buku dipinjamkan.');
        } else {
            flashMessage('error', 'Gagal menyetujui booking.');
        }
        $this->redirect('/index.php?page=borrows');
    }

    public function cancelBooking(): void {
        requireAdmin();
        $id = (int) $this->param('id');
        $borrow = $this->borrowModel->findById($id);
        
        if ($borrow && $this->borrowModel->cancelBooking($id)) {
            // Restore stock
            $this->bookModel->incrementStock($borrow['book_id']);
            flashMessage('success', 'Booking dibatalkan dan stok dikembalikan.');
        } else {
            flashMessage('error', 'Gagal membatalkan booking.');
        }
        $this->redirect('/index.php?page=borrows');
    }

    public function returnBook(): void {
        $id     = (int) $this->param('id');
        $borrow = $this->borrowModel->findById($id);
        if (!$borrow) {
            flashMessage('error', 'Data tidak ditemukan.');
            $this->redirect('/index.php?page=borrows');
        }
        $this->view('admin/borrows/return', compact('borrow'));
    }

    public function processReturn(): void {
        if (!$this->isPost()) {
            $this->redirect('/index.php?page=borrows');
        }

        $id     = (int) $this->input('borrow_id');
        $result = $this->borrowModel->processReturn($id);

        if ($result['success']) {
            $borrow = $this->borrowModel->findById($id);
            $this->bookModel->incrementStock($borrow['book_id']);

            if ($result['fine'] > 0) {
                flashMessage('success', "Pengembalian berhasil! Denda: " . formatCurrency($result['fine']) . " ({$result['days_late']} hari terlambat).");
            } else {
                flashMessage('success', 'Pengembalian berhasil! Tepat waktu.');
            }
        } else {
            flashMessage('error', $result['message']);
        }

        $this->redirect('/index.php?page=borrows');
    }
}
