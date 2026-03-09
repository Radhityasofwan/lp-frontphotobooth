<?php
/**
 * Jalankan file ini SEKALI SAJA dari browser untuk membuat database SQLite lokal.
 * Akses: http://localhost:8000/setup_local_db.php
 * Setelah berhasil, Anda bisa menghapus file ini.
 */

require_once __DIR__ . '/config.php';

if ($is_production) {
    die("This script is for local development only and cannot be run on production.");
}

$db_path = __DIR__ . '/storage/local.sqlite';
$storage_dir = __DIR__ . '/storage';

header('Content-Type: text/plain');

if (!is_dir($storage_dir)) {
    if (mkdir($storage_dir, 0755, true)) {
        echo "✓ Directory 'storage' created.\n";
    } else {
        die("✗ FAILED to create 'storage' directory. Please check permissions.\n");
    }
}

if (!$pdo) {
    die("✗ FAILED to initialize PDO connection. Check your config.php.\n");
}

// Check if setup has already been run by looking for the 'users' table.
// This is more reliable than checking for the file, which PDO creates automatically.
try {
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    if ($stmt->fetchColumn()) {
        echo "Database appears to be already set up (table 'users' found).\n";
        echo "No action taken.\n\n";
        echo "If you want to reset, please delete the file 'storage/local.sqlite' manually and run this script again.\n";
        exit;
    }
} catch (PDOException $e) {
    // This shouldn't happen on a clean setup, but we can proceed.
}

echo "PDO connection to SQLite successful. Creating tables...\n\n";

try {
    // Tabel `settings`
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `settings` (
          `setting_key`   VARCHAR(255) NOT NULL PRIMARY KEY,
          `setting_value` TEXT DEFAULT NULL,
          `setting_type`  VARCHAR(50) DEFAULT 'text',
          `description`   VARCHAR(255) DEFAULT NULL
        );
    ");
    echo "✓ Table 'settings' created successfully.\n";

    // Tabel `users`
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id`            INTEGER PRIMARY KEY AUTOINCREMENT,
            `username`      VARCHAR(50)  NOT NULL UNIQUE,
            `password_hash` VARCHAR(255) NOT NULL,
            `created_at`    DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");
    echo "✓ Table 'users' created successfully.\n";

    // Tabel `leads` (for CRM) - SQLite version
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `leads` (
            `id`            INTEGER PRIMARY KEY AUTOINCREMENT,
            `name`          TEXT NOT NULL,
            `phone`         TEXT NOT NULL,
            `address`       TEXT NOT NULL,
            `design`        TEXT NOT NULL,
            `size`          TEXT NOT NULL,
            `quantity`      INTEGER NOT NULL DEFAULT 1,
            `total_price`   INTEGER NOT NULL DEFAULT 0,
            `note`          TEXT,
            `payment_proof` TEXT,
            `order_token`   TEXT,
            `status`        TEXT DEFAULT 'pending' CHECK(status IN ('pending','contacted','paid','cancelled')),
            `utm_source`    TEXT,
            `utm_medium`    TEXT,
            `utm_campaign`  TEXT,
            `utm_content`   TEXT,
            `utm_term`      TEXT,
            `fbclid`        TEXT,
            `gclid`         TEXT,
            `wbraid`        TEXT,
            `gbraid`        TEXT,
            `referrer`      TEXT,
            `created_at`    DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at`    DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");
    echo "✓ Table 'leads' created successfully.\n";

    // Tabel `analytics` (for tracking) - SQLite version
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `analytics` (
            `id`          INTEGER PRIMARY KEY AUTOINCREMENT,
            `session_id`  TEXT NOT NULL,
            `ip_address`  TEXT,
            `event_type`  TEXT NOT NULL,
            `event_value` INTEGER,
            `page_url`    TEXT,
            `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");
    echo "✓ Table 'analytics' created successfully.\n";

    // Insert initial settings for the new client section
    $client_settings = [
        ['home_clients_title', 'Telah Dipercaya Oleh', 'text', 'Judul Section Klien'],
        ['home_clients_desc', 'Kami bangga telah menjadi bagian dari momen spesial berbagai brand ternama dan acara personal yang tak terlupakan.', 'text', 'Deskripsi Section Klien'],
    ];
    for ($i = 1; $i <= 8; $i++) {
        $client_settings[] = ['client_logo_' . $i, 'https://placehold.co/150x60/EAEAEA/999999?text=Client+' . $i, 'image', 'Logo Klien ' . $i];
        $client_settings[] = ['client_name_' . $i, 'Nama Klien ' . $i, 'text', 'Nama Klien ' . $i];
    }

    $stmt_settings = $pdo->prepare("INSERT OR IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES (?, ?, ?, ?)");
    foreach ($client_settings as $setting) {
        try {
            $stmt_settings->execute($setting);
        } catch (PDOException $e) {
            // Ignore if it fails, maybe the key exists from a previous partial run
        }
    }
    echo "✓ Initial client settings populated.\n";

    // Masukkan admin default
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->execute(['admin', $hash]);
    echo "✓ Default admin user created (admin / admin123).\n";

    echo "\n\n✅ SETUP COMPLETE! You can now access your local site.\n";
    echo "Please consider deleting this file (setup_local_db.php) for security.\n";

} catch (PDOException $e) {
    echo "✗ An error occurred during table creation: " . $e->getMessage() . "\n";
    // Hapus file db jika gagal agar bisa diulang
    unlink($db_path);
}