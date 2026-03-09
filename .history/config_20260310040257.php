<?php
// config.php

// 1. Environment Detection
// Cek apakah skrip dijalankan di server produksi berdasarkan nama domain.
$is_production = ($_SERVER['HTTP_HOST'] ?? '') === 'sewa.frontphotobooth.com';

if ($is_production) {
    // --- PENGATURAN PRODUKSI ---
    ini_set('display_errors', 0); // Matikan tampilan error di produksi
    error_reporting(0);

    define('DB_HOST', 'localhost'); // Biasanya 'localhost' di Hostinger
    define('DB_NAME', 'u830768701_Photobooth');
    define('DB_USER', 'u830768701_Front');
    define('DB_PASS', 'Merdeka313');
    define('BASE_URL', 'https://sewa.frontphotobooth.com');

} else {
    // --- PENGATURAN LOKAL (DEVELOPMENT) ---
    ini_set('display_errors', 1); // Tampilkan semua error di lokal
    error_reporting(E_ALL);

    // --- PENGATURAN DATABASE LOKAL (MENGGUNAKAN SQLITE) ---
    // Tidak perlu install MySQL!
    $sqlite_path = __DIR__ . '/storage/local.sqlite';
    define('DB_DSN', 'sqlite:' . $sqlite_path);
    define('DB_USER', null); // Tidak perlu untuk SQLite
    define('DB_PASS', null); // Tidak perlu untuk SQLite
    
    // --- URL Dinamis untuk Lokal ---
    // Ini akan berfungsi baik untuk XAMPP (http://localhost/folder) maupun server bawaan PHP (http://localhost:8000).
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script_name = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Hapus nama file (misal: /index.php) untuk mendapatkan path dasar.
    $base_path = dirname($script_name);
    // Jika di root, dirname akan menjadi '/', kita ubah jadi string kosong.
    $base_path = ($base_path === '/' || $base_path === '\\') ? '' : $base_path;
    define('BASE_URL', $protocol . '://' . $host . $base_path);
}

// 2. Konstanta Situs (yang sama untuk kedua environment)
define('WA_NUMBER', '6281234567890'); // Ganti dengan nomor WhatsApp aktual

// 3. Koneksi Database
$pdo = null;
try {
    if ($is_production) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    } else {
        $dsn = DB_DSN;
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    if ($is_production) {
        error_log("Database Connection Error: " . $e->getMessage());
        http_response_code(503);
        die("Situs sedang dalam perbaikan. Silakan coba lagi nanti.");
    } else {
        die("Koneksi database gagal: " . $e->getMessage());
    }
}

// 4. Cache Pengaturan
// Ambil semua pengaturan sekali dan simpan di variabel global untuk menghindari query berulang.
$APP_SETTINGS = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
        $settings_from_db = $stmt->fetchAll();
        foreach ($settings_from_db as $setting) {
            $APP_SETTINGS[$setting['setting_key']] = $setting['setting_value'];
        }
    } catch (Throwable $e) {
        // Tabel mungkin belum ada saat pertama kali dijalankan, tidak apa-apa.
    }
}

// 5. Fungsi Bantuan

function get_setting($key, $default = '')
{
    global $APP_SETTINGS;
    return isset($APP_SETTINGS[$key]) && $APP_SETTINGS[$key] !== '' ? $APP_SETTINGS[$key] : $default;
}

function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function asset($path)
{
    // Jika sudah berupa URL lengkap, kembalikan apa adanya.
    if (strpos($path, 'http') === 0) {
        return $path;
    }
    // Hapus garis miring di awal jika ada untuk menghindari duplikasi
    $path = ltrim($path, '/');
    // Gabungkan dengan BASE_URL
    return BASE_URL . '/' . $path;
}

/**
 * Simple event logger.
 * Logs messages to /storage/logs/events.log
 */
function log_event(string $message): void
{
    $logDir = __DIR__ . '/storage/logs/';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    error_log("[" . date("Y-m-d H:i:s") . "] " . $message . PHP_EOL, 3, $logDir . 'events.log');
}