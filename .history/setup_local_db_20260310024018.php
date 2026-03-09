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

if (file_exists($db_path)) {
    echo "Database file 'storage/local.sqlite' already exists. No action taken.\n";
    echo "If you want to reset, please delete the file 'storage/local.sqlite' manually and run this script again.\n";
    exit;
}

if (!is_dir($storage_dir)) {
    if (mkdir($storage_dir, 0755)) {
        echo "✓ Directory 'storage' created.\n";
    } else {
        die("✗ FAILED to create 'storage' directory. Please check permissions.\n");
    }
}

if (!$pdo) {
    die("✗ FAILED to initialize PDO connection. Check your config.php.\n");
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