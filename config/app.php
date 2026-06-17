<?php
// config/app.php - Bootstrap & Router

session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../app/models/BaseModel.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/BookModel.php';
require_once __DIR__ . '/../app/models/BorrowModel.php';
require_once __DIR__ . '/../app/models/CategoryModel.php';
require_once __DIR__ . '/../app/models/SettingModel.php';
require_once __DIR__ . '/../app/controllers/BaseController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/BookController.php';
require_once __DIR__ . '/../app/controllers/BorrowController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/ReportController.php';
require_once __DIR__ . '/../app/controllers/SettingController.php';

// Helper functions
function redirect(string $url): void {
    header("Location: " . APP_URL . $url);
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isStudent(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect('/index.php?page=login');
    }
}

function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        redirect('/index.php?page=dashboard');
    }
}

function sanitize(string $input): string {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function formatDate(string $date): string {
    if (!$date) return '-';
    return date('d M Y', strtotime($date));
}

function formatCurrency(float $amount): string {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function flashMessage(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function generateQRCode(string $isbn): string {
    return "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($isbn);
}

/**
 * Handle profile picture upload.
 * Returns filename on success, null if no file uploaded, or false on error.
 */
function handleProfileUpload(string $inputName = 'profile_picture'): ?string {
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$inputName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mimeType, $allowedTypes)) {
        return null;
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return null;
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . uniqid() . '_' . time() . '.' . strtolower($ext);
    $uploadDir = __DIR__ . '/../public/assets/profiles/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        return $filename;
    }
    return null;
}

/**
 * Parse operational hours JSON or fallback string.
 */
function parseOperationalHours(?string $rawOps): array {
    $opsData = [];
    if (!$rawOps) {
        return [
            ['day' => 'Senin - Jumat', 'time' => '08.00 - 16.00'],
            ['day' => 'Sabtu', 'time' => '08.00 - 12.00'],
            ['day' => 'Minggu & Libur', 'time' => 'Tutup']
        ];
    }

    $decoded = json_decode($rawOps, true);
    if (is_array($decoded)) {
        $opsData = $decoded;
    } else {
        // Fallback from old plain text
        $lines = explode("\n", $rawOps);
        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) == 2) {
                $opsData[] = ['day' => trim($parts[0]), 'time' => trim($parts[1])];
            } else {
                $opsData[] = ['day' => trim($line), 'time' => ''];
            }
        }
    }

    if (empty($opsData)) {
        $opsData = [
            ['day' => 'Senin - Jumat', 'time' => '08.00 - 16.00'],
            ['day' => 'Sabtu', 'time' => '08.00 - 12.00'],
            ['day' => 'Minggu & Libur', 'time' => 'Tutup']
        ];
    }
    return $opsData;
}

// ============================================================
// ROUTER
// ============================================================
$page   = $_GET['page']   ?? 'home';
$action = $_GET['action'] ?? 'index';

$authController      = new AuthController();
$bookController      = new BookController();
$borrowController    = new BorrowController();
$userController      = new UserController();
$dashboardController = new DashboardController();
$reportController    = new ReportController();
$settingController   = new SettingController();

switch ($page) {
    // AUTH
    case 'login':
        $authController->login();
        break;
    case 'register':
        $authController->register();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'forgot_password':
        $authController->forgotPassword();
        break;
    case 'reset_password':
        $authController->resetPassword();
        break;

    // DASHBOARD
    case 'dashboard':
        requireLogin();
        $dashboardController->index();
        break;

    // BOOKS (admin CRUD + public catalog)
    case 'books':
        switch ($action) {
            case 'create': requireAdmin(); $bookController->create(); break;
            case 'store':  requireAdmin(); $bookController->store(); break;
            case 'edit':   requireAdmin(); $bookController->edit(); break;
            case 'update': requireAdmin(); $bookController->update(); break;
            case 'delete': requireAdmin(); $bookController->delete(); break;
            case 'show':   requireLogin(); $bookController->show(); break;
            default:       requireLogin(); $bookController->index(); break;
        }
        break;

    // BORROWS
    case 'borrows':
        requireLogin();
        switch ($action) {
            case 'create': requireAdmin(); $borrowController->create(); break;
            case 'store':  requireAdmin(); $borrowController->store(); break;
            case 'return': requireAdmin(); $borrowController->returnBook(); break;
            case 'process_return': requireAdmin(); $borrowController->processReturn(); break;
            case 'show':   $borrowController->show(); break;
            case 'book':   requireLogin(); $borrowController->book(); break;
            case 'approve_booking': requireAdmin(); $borrowController->approveBooking(); break;
            case 'cancel_booking': requireAdmin(); $borrowController->cancelBooking(); break;
            default:       $borrowController->index(); break;
        }
        break;

    // MY BORROWS (student)
    case 'my-borrows':
        requireLogin();
        $borrowController->myBorrows();
        break;

    // USERS (admin only)
    case 'users':
        requireAdmin();
        switch ($action) {
            case 'create': $userController->create(); break;
            case 'store':  $userController->store(); break;
            case 'edit':   $userController->edit(); break;
            case 'update': $userController->update(); break;
            case 'delete': $userController->delete(); break;
            default:       $userController->index(); break;
        }
        break;

    // REPORTS (admin only)
    case 'reports':
        requireAdmin();
        switch ($action) {
            case 'export-pdf':   $reportController->exportPDF(); break;
            case 'export-excel': $reportController->exportExcel(); break;
            default:             $reportController->index(); break;
        }
        break;

    // SETTINGS (admin only)
    case 'settings':
        requireAdmin();
        switch ($action) {
            case 'update': $settingController->update(); break;
            default:       $settingController->index(); break;
        }
        break;

    // HOME / CATALOG (public)
    case 'home':
    default:
        $bookController->catalog();
        break;
}