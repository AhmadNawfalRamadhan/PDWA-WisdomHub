<?php
// app/models/UserModel.php

class UserModel extends BaseModel {

    public function findById(int $id): ?array {
        return $this->findOne("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public function findByEmail(string $email): ?array {
        return $this->findOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function getAllStudents(): array {
        return $this->findAll("SELECT * FROM users WHERE role = 'student' ORDER BY name");
    }

    public function getAll(int $page = 1, string $search = ''): array {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];
        if ($search) {
            $sql .= " AND (name LIKE ? OR email LIKE ? OR student_id LIKE ?)";
            $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
        }
        $sql .= " ORDER BY created_at DESC";
        return $this->paginate($sql, $params, $page);
    }

    public function create(array $data): bool {
        return $this->execute(
            "INSERT INTO users (name, email, password, role, student_id, phone, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$data['name'], $data['email'], password_hash($data['password'], PASSWORD_BCRYPT),
             $data['role'], !empty($data['student_id']) ? $data['student_id'] : null, !empty($data['phone']) ? $data['phone'] : null, $data['profile_picture'] ?? null]
        );
    }

    public function update(int $id, array $data): bool {
        $studentId = !empty($data['student_id']) ? $data['student_id'] : null;
        $phone = !empty($data['phone']) ? $data['phone'] : null;

        if (!empty($data['password'])) {
            return $this->execute(
                "UPDATE users SET name=?, email=?, password=?, role=?, student_id=?, phone=?, profile_picture=COALESCE(?, profile_picture) WHERE id=?",
                [$data['name'], $data['email'], password_hash($data['password'], PASSWORD_BCRYPT),
                 $data['role'], $studentId, $phone, $data['profile_picture'] ?? null, $id]
            );
        }
        return $this->execute(
            "UPDATE users SET name=?, email=?, role=?, student_id=?, phone=?, profile_picture=COALESCE(?, profile_picture) WHERE id=?",
            [$data['name'], $data['email'], $data['role'],
             $studentId, $phone, $data['profile_picture'] ?? null, $id]
        );
    }

    public function delete(int $id): bool {
        return $this->execute("DELETE FROM users WHERE id = ?", [$id]);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function getTotalStudents(): int {
        return (int) ($this->findOne("SELECT COUNT(*) as c FROM users WHERE role='student'")['c'] ?? 0);
    }
}
