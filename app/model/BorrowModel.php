<?php
// app/models/BorrowModel.php

class BorrowModel extends BaseModel {

    private string $baseSelect = "
        SELECT br.*, u.name as user_name, u.email as user_email, u.student_id, u.profile_picture as user_profile_picture,
               b.title as book_title, b.isbn, b.author as book_author, b.cover_image as book_cover
        FROM borrows br
        JOIN users u ON br.user_id = u.id
        JOIN books b ON br.book_id = b.id
    ";

    public function findById(int $id): ?array {
        return $this->findOne($this->baseSelect . " WHERE br.id = ?", [$id]);
    }

    public function getAll(int $page = 1, string $search = '', string $status = ''): array {
        $sql = $this->baseSelect . " WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (u.name LIKE ? OR b.title LIKE ? OR b.isbn LIKE ? OR u.student_id LIKE ?)";
            $params = array_merge($params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
        }
        if ($status) {
            $sql .= " AND br.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY br.created_at DESC";
        return $this->paginate($sql, $params, $page);
    }

    public function getByUser(int $userId, int $page = 1, string $status = ''): array {
        $sql = $this->baseSelect . " WHERE br.user_id = ?";
        $params = [$userId];
        if ($status) {
            $sql .= " AND br.status = ?";
            $params[] = $status;
        }
        $sql .= " ORDER BY br.created_at DESC";
        return $this->paginate($sql, $params, $page);
    }

    public function getUserActiveBorrow(int $userId, int $bookId): ?array {
        return $this->findOne(
            $this->baseSelect . " WHERE br.user_id = ? AND br.book_id = ? AND br.status = 'borrowed'",
            [$userId, $bookId]
        );
    }

    public function getUserBorrowCount(int $userId): int {
        return (int) ($this->findOne(
            "SELECT COUNT(*) as c FROM borrows WHERE user_id = ? AND status = 'borrowed'",
            [$userId]
        )['c'] ?? 0);
    }

    public function hasActiveBooking(int $userId, int $bookId): bool {
        $result = $this->findOne(
            "SELECT id FROM borrows WHERE user_id = ? AND book_id = ? AND status = 'booked'",
            [$userId, $bookId]
        );
        return $result !== null && $result !== false;
    }

    public function create(array $data): int {
        $dueDate = date('Y-m-d', strtotime('+' . BORROW_DAYS . ' days', strtotime($data['borrow_date'])));
        $this->execute(
            "INSERT INTO borrows (user_id, book_id, borrow_date, due_date, status, notes)
             VALUES (?, ?, ?, ?, 'borrowed', ?)",
            [$data['user_id'], $data['book_id'], $data['borrow_date'], $dueDate, $data['notes'] ?? null]
        );
        return (int) $this->lastInsertId();
    }

    public function book(array $data): int {
        // due_date for booked status is the expiry date (2 days from booking)
        $dueDate = date('Y-m-d', strtotime('+2 days', strtotime($data['borrow_date'])));
        $this->execute(
            "INSERT INTO borrows (user_id, book_id, borrow_date, due_date, status, notes)
             VALUES (?, ?, ?, ?, 'booked', ?)",
            [$data['user_id'], $data['book_id'], $data['borrow_date'], $dueDate, $data['notes'] ?? null]
        );
        return (int) $this->lastInsertId();
    }

    public function approveBooking(int $id): bool {
        $borrow = $this->findById($id);
        if (!$borrow || $borrow['status'] !== 'booked') return false;

        $borrowDate = date('Y-m-d');
        $dueDate = date('Y-m-d', strtotime('+' . BORROW_DAYS . ' days', strtotime($borrowDate)));
        return $this->execute(
            "UPDATE borrows SET status='borrowed', borrow_date=?, due_date=? WHERE id=?",
            [$borrowDate, $dueDate, $id]
        );
    }

    public function cancelBooking(int $id): bool {
        $borrow = $this->findById($id);
        if (!$borrow || $borrow['status'] !== 'booked') return false;

        return $this->execute(
            "UPDATE borrows SET status='cancelled' WHERE id=?",
            [$id]
        );
    }

    public function processReturn(int $id): array {
        $borrow = $this->findById($id);
        if (!$borrow) return ['success' => false, 'message' => 'Data tidak ditemukan'];

        $returnDate = date('Y-m-d');
        $dueDate    = $borrow['due_date'];
        $fine       = 0;
        $daysLate   = 0;

        if ($returnDate > $dueDate) {
            $diff     = (strtotime($returnDate) - strtotime($dueDate)) / 86400;
            $daysLate = (int) $diff;
            $fine     = $daysLate * FINE_PER_DAY;
        }

        $status = 'returned';
        $this->execute(
            "UPDATE borrows SET return_date=?, status=?, fine=? WHERE id=?",
            [$returnDate, $status, $fine, $id]
        );

        if ($fine > 0) {
            $this->execute(
                "INSERT INTO fines (borrow_id, user_id, amount, days_late) VALUES (?, ?, ?, ?)",
                [$id, $borrow['user_id'], $fine, $daysLate]
            );
        }

        return ['success' => true, 'fine' => $fine, 'days_late' => $daysLate];
    }

    public function updateOverdueStatus(): void {
        // Auto overdue for borrowed books
        $this->execute(
            "UPDATE borrows SET status='overdue'
             WHERE status='borrowed' AND due_date < CURDATE()"
        );

        // Auto cancel for booked books that passed 2 days
        $expiredBookings = $this->findAll("SELECT id, book_id FROM borrows WHERE status='booked' AND due_date < CURDATE()");
        foreach ($expiredBookings as $b) {
            $this->execute("UPDATE borrows SET status='cancelled' WHERE id=?", [$b['id']]);
            // Increment book stock
            $this->execute("UPDATE books SET stock = stock + 1 WHERE id = ?", [$b['book_id']]);
        }
    }

    public function getTotalBorrowed(): int {
        return (int) ($this->findOne(
            "SELECT COUNT(*) as c FROM borrows WHERE status IN ('borrowed','overdue')"
        )['c'] ?? 0);
    }

    public function getTotalOverdue(): int {
        return (int) ($this->findOne(
            "SELECT COUNT(*) as c FROM borrows WHERE status = 'overdue'"
        )['c'] ?? 0);
    }

    public function getTotalFines(): float {
        return (float) ($this->findOne(
            "SELECT COALESCE(SUM(fine),0) as c FROM borrows WHERE fine > 0"
        )['c'] ?? 0);
    }

    public function getRecentActivity(int $limit = 10): array {
        return $this->findAll($this->baseSelect . " ORDER BY br.created_at DESC LIMIT ?", [$limit]);
    }

    public function getAllForExport(string $status = '', string $dateFrom = '', string $dateTo = ''): array {
        $sql    = $this->baseSelect . " WHERE 1=1";
        $params = [];
        if ($status) { $sql .= " AND br.status=?"; $params[] = $status; }
        if ($dateFrom) { $sql .= " AND br.borrow_date >= ?"; $params[] = $dateFrom; }
        if ($dateTo)   { $sql .= " AND br.borrow_date <= ?"; $params[] = $dateTo; }
        $sql .= " ORDER BY br.created_at DESC";
        return $this->findAll($sql, $params);
    }

    public function getBorrowChartData(): array {
        return $this->findAll(
            "SELECT DATE_FORMAT(borrow_date,'%Y-%m') as month, COUNT(*) as total
             FROM borrows
             WHERE borrow_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
             GROUP BY month ORDER BY month"
        );
    }
}
