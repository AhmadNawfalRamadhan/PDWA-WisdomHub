<?php
// app/models/BookModel.php

class BookModel extends BaseModel {

    private string $baseSelect = "
        SELECT b.*, c.name as category_name
        FROM books b
        LEFT JOIN categories c ON b.category_id = c.id
    ";

    public function findById(int $id): ?array {
        return $this->findOne($this->baseSelect . " WHERE b.id = ?", [$id]);
    }

    public function findByIsbn(string $isbn): ?array {
        return $this->findOne($this->baseSelect . " WHERE b.isbn = ?", [$isbn]);
    }

    public function getAll(int $page = 1, string $search = '', int $categoryId = 0): array {
        $sql = $this->baseSelect . " WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ? OR b.publisher LIKE ?)";
            $params = array_merge($params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
        }
        if ($categoryId > 0) {
            $sql .= " AND b.category_id = ?";
            $params[] = $categoryId;
        }

        $sql .= " ORDER BY b.title ASC";
        return $this->paginate($sql, $params, $page);
    }

    public function getAllSimple(string $search = '', int $categoryId = 0): array {
        $sql = $this->baseSelect . " WHERE 1=1";
        $params = [];
        if ($search) {
            $sql .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
            $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
        }
        if ($categoryId > 0) {
            $sql .= " AND b.category_id = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY b.title ASC";
        return $this->findAll($sql, $params);
    }

    public function create(array $data): int {
        $qrCode = generateQRCode($data['isbn']);
        $this->execute(
            "INSERT INTO books (isbn, title, author, publisher, year_published, category_id, stock, total_stock, description, cover_image, qr_code)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['isbn'], $data['title'], $data['author'], $data['publisher'],
             $data['year_published'], $data['category_id'] ?: null,
             $data['stock'], $data['stock'], $data['description'] ?? null,
             $data['cover_image'] ?? null, $qrCode]
        );
        return (int) $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $qrCode = generateQRCode($data['isbn']);
        return $this->execute(
            "UPDATE books SET isbn=?, title=?, author=?, publisher=?, year_published=?,
             category_id=?, total_stock=?, stock=?, description=?, cover_image=?, qr_code=?
             WHERE id=?",
            [$data['isbn'], $data['title'], $data['author'], $data['publisher'],
             $data['year_published'], $data['category_id'] ?: null,
             $data['total_stock'], $data['stock'], $data['description'] ?? null,
             $data['cover_image'] ?? null, $qrCode, $id]
        );
    }

    public function delete(int $id): bool {
        return $this->execute("DELETE FROM books WHERE id = ?", [$id]);
    }

    public function decrementStock(int $id): bool {
        return $this->execute("UPDATE books SET stock = stock - 1 WHERE id = ? AND stock > 0", [$id]);
    }

    public function incrementStock(int $id): bool {
        return $this->execute("UPDATE books SET stock = stock + 1 WHERE id = ?", [$id]);
    }

    public function getTotalBooks(): int {
        return (int) ($this->findOne("SELECT COUNT(*) as c FROM books")['c'] ?? 0);
    }

    public function getTotalStock(): int {
        return (int) ($this->findOne("SELECT SUM(stock) as c FROM books")['c'] ?? 0);
    }

    public function getPopularBooks(int $limit = 5): array {
        return $this->findAll(
            "SELECT b.id, b.title, b.author, b.isbn, c.name as category_name,
                    COUNT(bo.id) as borrow_count
             FROM books b
             LEFT JOIN borrows bo ON b.id = bo.book_id
             LEFT JOIN categories c ON b.category_id = c.id
             GROUP BY b.id
             ORDER BY borrow_count DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function getAvailableBooks(): array {
        return $this->findAll($this->baseSelect . " WHERE b.stock > 0 ORDER BY b.title");
    }

    public function getRandomBooks(int $limit = 10): array {
        return $this->findAll($this->baseSelect . " ORDER BY RAND() LIMIT ?", [$limit]);
    }
}
