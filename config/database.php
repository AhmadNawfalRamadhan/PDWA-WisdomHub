<?php
// config/database.php

// ============================================================
// Load .env file
// ============================================================
function loadEnv(string $path): void {
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip komentar
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;

        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);

        // Hapus tanda kutip jika ada
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Load file .env dari root project
loadEnv(__DIR__ . '/../.env');

// Helper function untuk ambil nilai env dengan fallback
function env(string $key, mixed $default = null): mixed {
    $val = $_ENV[$key] ?? getenv($key);
    if ($val === false || $val === null || $val === '') return $default;
    return match (strtolower($val)) {
        'true'  => true,
        'false' => false,
        'null'  => null,
        default => $val,
    };
}

// ============================================================
// Define constants dari .env
// ============================================================
define('DB_HOST',    env('DB_HOST',    'localhost'));
define('DB_USER',    env('DB_USER',    'root'));
define('DB_PASS',    env('DB_PASS',    ''));
define('DB_NAME',    env('DB_NAME',    'wisdomhub'));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));

define('APP_NAME',   env('APP_NAME', 'WisdomHub'));
define('APP_URL',    env('APP_URL',  'http://localhost/WisdomHub'));
define('APP_ENV',    env('APP_ENV',  'local'));
define('APP_DEBUG',  env('APP_DEBUG', true));

define('FINE_PER_DAY', (int) env('FINE_PER_DAY', 1000));
define('BORROW_DAYS',  (int) env('BORROW_DAYS',  14));

// Mail config (untuk SMTP / Forgot Password)
define('MAIL_HOST',      env('MAIL_HOST',      'smtp.gmail.com'));
define('MAIL_PORT',      (int) env('MAIL_PORT', 587));
define('MAIL_USER',      env('MAIL_USER',       ''));
define('MAIL_PASS',      env('MAIL_PASS',       ''));
define('MAIL_FROM',      env('MAIL_FROM',       ''));
define('MAIL_FROM_NAME', env('MAIL_FROM_NAME',  APP_NAME));

// ============================================================
// Database Class (PDO Singleton)
// ============================================================
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
