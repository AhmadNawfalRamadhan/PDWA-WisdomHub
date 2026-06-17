<?php
// app/models/CategoryModel.php

class CategoryModel extends BaseModel {

    public function getAll(): array {
        return $this->findAll("SELECT * FROM categories ORDER BY name");
    }

    public function findById(int $id): ?array {
        return $this->findOne("SELECT * FROM categories WHERE id = ?", [$id]);
    }

    public function create(string $name, string $description = ''): bool {
        return $this->execute("INSERT INTO categories (name, description) VALUES (?, ?)", [$name, $description]);
    }

    public function update(int $id, string $name, string $description = ''): bool {
        return $this->execute("UPDATE categories SET name=?, description=? WHERE id=?", [$name, $description, $id]);
    }

    public function delete(int $id): bool {
        return $this->execute("DELETE FROM categories WHERE id = ?", [$id]);
    }
}
