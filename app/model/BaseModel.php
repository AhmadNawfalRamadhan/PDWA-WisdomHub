<?php
// app/models/BaseModel.php

abstract class BaseModel {
    protected PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function findAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }

    protected function findOne(string $sql, array $params = []): ?array {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    protected function execute(string $sql, array $params = []): bool {
        $this->query($sql, $params);
        return true;
    }

    protected function lastInsertId(): string {
        return $this->db->lastInsertId();
    }

    public function paginate(string $baseQuery, array $params, int $page, int $perPage = 10): array {
        $countQuery = "SELECT COUNT(*) as total FROM (" . $baseQuery . ") as sub";
        $total = $this->findOne($countQuery, $params)['total'] ?? 0;
        $totalPages = (int) ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $data = $this->findAll($baseQuery . " LIMIT $perPage OFFSET $offset", $params);

        return [
            'data'        => $data,
            'total'       => $total,
            'per_page'    => $perPage,
            'current_page'=> $page,
            'total_pages' => $totalPages,
        ];
    }
}
