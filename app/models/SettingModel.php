<?php
// app/models/SettingModel.php

class SettingModel extends BaseModel {
    protected string $table = 'settings';

    /**
     * Get all settings as an associative array [key => value]
     */
    public function getAllSettings(): array {
        $stmt = $this->db->query("SELECT key_name, setting_value FROM {$this->table}");
        $results = $stmt->fetchAll();
        
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key_name']] = $row['setting_value'];
        }
        return $settings;
    }

    /**
     * Get a specific setting by key
     */
    public function getSetting(string $key): ?string {
        $stmt = $this->db->prepare("SELECT setting_value FROM {$this->table} WHERE key_name = :key_name");
        $stmt->execute(['key_name' => $key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : null;
    }

    /**
     * Update or create a setting
     */
    public function updateSetting(string $key, ?string $value): bool {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE key_name = :key_name");
        $stmt->execute(['key_name' => $key]);
        $exists = $stmt->fetch();

        if ($exists) {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET setting_value = :val WHERE key_name = :key_name");
        } else {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (key_name, setting_value) VALUES (:key_name, :val)");
        }

        return $stmt->execute([
            'key_name' => $key,
            'val'      => $value
        ]);
    }

    /**
     * Update multiple settings at once
     */
    public function updateMany(array $settings): void {
        foreach ($settings as $key => $value) {
            $this->updateSetting($key, $value);
        }
    }
}
