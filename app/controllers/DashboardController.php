<?php
// app/controllers/DashboardController.php

class DashboardController extends BaseController {
    private BookModel   $bookModel;
    private BorrowModel $borrowModel;
    private UserModel   $userModel;

    public function __construct() {
        $this->bookModel   = new BookModel();
        $this->borrowModel = new BorrowModel();
        $this->userModel   = new UserModel();
    }

    public function index(): void {
        // Update overdue status first
        $this->borrowModel->updateOverdueStatus();

        if (isAdmin()) {
            $stats = [
                'total_books'    => $this->bookModel->getTotalBooks(),
                'total_stock'    => $this->bookModel->getTotalStock(),
                'total_borrowed' => $this->borrowModel->getTotalBorrowed(),
                'total_overdue'  => $this->borrowModel->getTotalOverdue(),
                'total_fines'    => $this->borrowModel->getTotalFines(),
                'total_students' => $this->userModel->getTotalStudents(),
            ];
            $recentActivity = $this->borrowModel->getRecentActivity(8);
            $popularBooks   = $this->bookModel->getPopularBooks(5);
            $chartData      = $this->borrowModel->getBorrowChartData();
            $this->view('admin/dashboard', compact('stats', 'recentActivity', 'popularBooks', 'chartData'));
        } else {
            $userId    = $_SESSION['user_id'];
            $myBorrows = $this->borrowModel->getByUser($userId, 1, 'borrowed');
            $myStats   = [
                'active'   => $this->borrowModel->getUserBorrowCount($userId),
                'overdue'  => 0,
                'returned' => 0,
            ];
            $this->view('student/dashboard', compact('myBorrows', 'myStats'));
        }
    }
}
