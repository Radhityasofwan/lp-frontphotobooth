<?php
// config.php

// 1. Error Reporting (Baik untuk development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Koneksi Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'frontphotobooth_db'); // Sesuaikan dengan nama database Anda
define('DB_USER', 'root');
define('DB_PASS', ''); // Ganti dengan password MySQL Anda jika ada. Kosongkan jika tidak diset.

$pdo = null;
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// 3. Konstanta Situs
define('BASE_URL', 'http://localhost:8000'); // Disesuaikan untuk PHP built-in server. Port 8000 bisa diganti.
define('WA_NUMBER', '6281234567890'); // Ganti dengan nomor WhatsApp aktual

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