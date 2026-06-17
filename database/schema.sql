-- ============================================================
-- DATABASE: wisdomhub
-- ============================================================
CREATE DATABASE IF NOT EXISTS wisdomhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wisdomhub;

-- ============================================================
-- TABLE: users
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    student_id VARCHAR(50) NULL COMMENT 'NIM / NIS untuk siswa',
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    profile_picture VARCHAR(255) NULL COMMENT 'Filename foto profil',
    reset_token VARCHAR(64) NULL,
    reset_expires_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: categories
-- ============================================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: books
-- ============================================================
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(20) NOT NULL UNIQUE,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(150) NOT NULL,
    publisher VARCHAR(150) NOT NULL,
    year_published YEAR NOT NULL,
    category_id INT NULL,
    stock INT NOT NULL DEFAULT 1,
    total_stock INT NOT NULL DEFAULT 1,
    description TEXT NULL,
    cover_image VARCHAR(255) NULL,
    qr_code VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- ============================================================
-- TABLE: borrows
-- ============================================================
CREATE TABLE IF NOT EXISTS borrows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE NULL,
    status ENUM('booked', 'borrowed', 'returned', 'overdue', 'cancelled') NOT NULL DEFAULT 'borrowed',
    fine DECIMAL(10,2) DEFAULT 0.00,
    fine_paid TINYINT(1) DEFAULT 0,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE: fines (riwayat denda)
-- ============================================================
CREATE TABLE IF NOT EXISTS fines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    borrow_id INT NOT NULL,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    days_late INT NOT NULL,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (borrow_id) REFERENCES borrows(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- SEED DATA
-- ============================================================

-- Default admin
INSERT INTO users (name, email, password, role) VALUES
('Administrator', 'admin@perpustakaan.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso', 'budi@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Siti Rahayu', 'siti@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');
-- Password semua: password

-- Categories
INSERT INTO categories (name, description) VALUES
('Teknologi', 'Buku-buku seputar teknologi dan komputer'),
('Sastra', 'Novel, puisi, dan karya sastra lainnya'),
('Ilmu Pengetahuan', 'Buku sains dan ilmu pengetahuan umum'),
('Sejarah', 'Buku-buku sejarah dunia dan Indonesia'),
('Matematika', 'Buku pelajaran dan referensi matematika'),
('Bisnis', 'Buku manajemen, ekonomi, dan bisnis');

-- ============================================================
-- TABLE: settings
-- ============================================================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Seed Settings
INSERT INTO settings (key_name, setting_value) VALUES
('contact_phone', '+62 812-3456-7890'),
('contact_email', 'info@wisdomhub.id'),
('contact_address', 'Jl. Pengetahuan No. 42, Kelurahan Ilmu, Kecamatan Bijak, Kota Cerdas 12345'),
('operational_hours', 'Senin - Jumat: 08.00 - 16.00\r\nSabtu: 08.00 - 12.00\r\nMinggu & Libur: Tutup');