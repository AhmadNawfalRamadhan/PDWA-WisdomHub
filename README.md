<div align="center">
  <img src="https://github.com/user-attachments/assets/84e506f1-e7b9-4726-b5b5-b6da7dc53bfe" width="118" height="100" alt="Wisdom Hub Logo">
  <h1>Wisdom Hub</h1>
  <p><strong>Sistem Manajemen Perpustakaan Digital Modern</strong></p>
</div>

Aplikasi perpustakaan digital berbasis web menggunakan arsitektur **MVC** (Model-View-Controller), dibangun dengan PHP murni tanpa framework. Menampilkan splash screen animasi, QR Code, dark mode elegan, dan fitur lengkap untuk admin maupun siswa/mahasiswa.

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Frontend** | Tailwind CSS v4 (CDN) + Lucide Icons + Chart.js |
| **Font** | Plus Jakarta Sans & Playfair Display (Google Fonts) |
| **Backend** | PHP >= 7.4 (Pure MVC, tanpa framework) |
| **Database** | MySQL >= 5.7 |
| **Arsitektur** | MVC (Model-View-Controller) |
| **Email** | PHPMailer (Forgot Password / Reset) |

---

## 📁 Struktur Folder (MVC)

```
PDWA-WisdomHub/
├── index.php                   ← Entry point utama
├── .htaccess                   ← URL rewriting & keamanan direktori
├── .env                        ← Konfigurasi environment (lokal)
├── .env.example                ← Template konfigurasi
├── config/
│   ├── app.php                 ← Bootstrap & Router utama
│   └── database.php            ← Koneksi DB, konstanta & env loader
├── app/
│   ├── model/                  ← MODEL (logika data / query)
│   │   ├── BaseModel.php
│   │   ├── UserModel.php
│   │   ├── BookModel.php
│   │   ├── BorrowModel.php
│   │   ├── CategoryModel.php
│   │   └── SettingModel.php
│   ├── controllers/            ← CONTROLLER (logika bisnis)
│   │   ├── BaseController.php
│   │   ├── AuthController.php
│   │   ├── BookController.php
│   │   ├── BorrowController.php
│   │   ├── UserController.php
│   │   ├── DashboardController.php
│   │   ├── ReportController.php
│   │   └── SettingController.php
│   └── views/                  ← VIEW (tampilan halaman)
│       ├── layouts/
│       │   ├── header.php      ← Sidebar & topbar (authenticated)
│       │   └── footer.php      ← Script penutup
│       ├── auth/
│       │   ├── catalog.php     ← Halaman publik + splash screen
│       │   ├── login.php
│       │   ├── register.php
│       │   ├── forgot_password.php
│       │   └── reset_password.php
│       ├── admin/
│       │   ├── dashboard.php
│       │   ├── books/
│       │   ├── borrows/
│       │   ├── users/
│       │   ├── reports/
│       │   └── settings/
│       └── student/
│           ├── dashboard.php
│           └── borrows.php
├── database/
│   └── schema.sql              ← SQL lengkap + seed data awal
├── lib/                        ← Library pihak ketiga (PHPMailer, dll)
└── public/
    └── assets/
        ├── js/
        │   └── helpers.js      ← Fungsi JS global (confirm dialog, dll)
        ├── videos/             ← Video background halaman katalog
        ├── covers/             ← Upload cover buku
        └── profiles/           ← Upload foto profil user
```

---

## ⚙️ Cara Instalasi

### 1. Persiapan Server
- **XAMPP** / Laragon / WAMP
- PHP >= 7.4
- MySQL >= 5.7
- `mod_rewrite` aktif (Apache)

### 2. Clone / Copy Project
```bash
# Clone ke folder htdocs
git clone <repo-url> htdocs/WisdomHub

# Atau copy manual ke:
htdocs/WisdomHub/
```

### 3. Import Database
```sql
-- Buka phpMyAdmin
-- Buat database baru: wisdomhub
-- Import file: database/schema.sql
```

### 4. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`, lalu sesuaikan:
```env
DB_HOST=localhost
DB_USER=root
DB_PASS=              # sesuaikan password MySQL Anda
DB_NAME=wisdomhub

APP_NAME=Wisdom Hub
APP_URL=http://localhost/WisdomHub
APP_ENV=local
APP_DEBUG=true

FINE_PER_DAY=1000     # Denda Rp 1.000/hari
BORROW_DAYS=14        # Durasi pinjam 14 hari

# (Opsional) Konfigurasi email untuk fitur lupa password
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USER=email@gmail.com
MAIL_PASS=app_password
MAIL_FROM=email@gmail.com
MAIL_FROM_NAME=Wisdom Hub
```

### 5. Aktifkan mod_rewrite (Apache / XAMPP)
Pastikan `mod_rewrite` aktif di `httpd.conf` XAMPP:
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

### 6. Akses Aplikasi
```
http://localhost/WisdomHub
```

---

## 🔑 Akun Default

| Role  | Email | Password |
|-------|-------|----------|
| **Admin** | admin@perpustakaan.com | `password` |
| **Siswa** | budi@student.com | `password` |
| **Siswa** | siti@student.com | `password` |

> ⚠️ Segera ubah password default setelah instalasi di lingkungan produksi.

---

## ✅ Fitur Lengkap

### 🔐 Autentikasi & Akses
- [x] Role-Based Authentication (Admin & Siswa/Mahasiswa)
- [x] Login / Register / Logout
- [x] Lupa Password via Email (PHPMailer + SMTP)
- [x] Reset Password dengan token

### 📚 Manajemen Buku
- [x] CRUD Buku (Tambah, Edit, Hapus, Lihat)
- [x] Search & Filter berdasarkan kategori
- [x] Upload cover buku
- [x] QR Code per buku (via API)
- [x] Manajemen Kategori

### 🔄 Sirkulasi Peminjaman
- [x] Sistem Peminjaman (kurangi stok otomatis)
- [x] Sistem Pengembalian (tambah stok otomatis)
- [x] Booking buku dari katalog publik
- [x] Kalkulasi Denda Otomatis (Rp 1.000/hari)
- [x] Status Overdue otomatis
- [x] Riwayat Peminjaman (per user & global)
- [x] QR Code Scanner via kamera device

### 📊 Laporan & Dashboard
- [x] Dashboard Admin dengan statistik lengkap
- [x] Dashboard Siswa personal
- [x] Statistik Buku Terpopuler
- [x] Chart tren peminjaman 6 bulan (Chart.js)
- [x] Export Excel (.xls)
- [x] Export PDF (print-friendly)

### 🎨 UI/UX
- [x] Splash Screen animasi saat pertama buka aplikasi
- [x] Dark Mode (Elegant Black & Gold)
- [x] Video background di halaman katalog publik
- [x] Running text / marquee judul buku
- [x] Pagination di semua halaman daftar
- [x] Visual indicators (badge warna status pinjam)
- [x] Responsive design (mobile & desktop)

### ⚙️ Pengaturan
- [x] Pengaturan kontak & jam operasional perpustakaan
- [x] Papan informasi publik
- [x] Upload foto profil pengguna

---

## 🔐 Keamanan

- Password di-hash dengan `password_hash()` (bcrypt)
- Input di-sanitasi dengan `htmlspecialchars()`
- PDO Prepared Statements (anti SQL Injection)
- Session-based authentication
- Role check di setiap route/controller
- `.htaccess` memblokir akses langsung ke direktori sensitif
- Token berbatas waktu untuk reset password

---

## 💡 Konstanta Konfigurasi

```php
APP_NAME     = 'Wisdom Hub'                  // Nama aplikasi
APP_URL      = 'http://localhost/WisdomHub'  // URL aplikasi
FINE_PER_DAY = 1000                          // Denda Rp 1.000/hari
BORROW_DAYS  = 14                            // Durasi pinjam 14 hari
```

---

## 🖼️ Tampilan Aplikasi

| Halaman | Deskripsi |
|---------|-----------|
| **Katalog Publik** | Halaman utama dengan splash screen, video background & marquee buku |
| **Login** | Tampilan dua kolom dengan branding Wisdom Hub |
| **Dashboard Admin** | Statistik lengkap + chart tren peminjaman |
| **Dashboard Siswa** | Ringkasan peminjaman aktif & riwayat personal |

---

## 👥 Kontributor

Dibuat sebagai proyek **Pengembangan Web** — Sistem Manajemen Perpustakaan Digital dengan arsitektur MVC PHP.

---

*© 2025 Wisdom Hub — Pusat Kebijaksanaan & Ilmu Pengetahuan*
