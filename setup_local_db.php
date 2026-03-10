<?php
/**
 * Setup database SQLite lokal.
 * Akses: http://localhost:8000/setup_local_db.php
 */

require_once __DIR__ . '/config.php';

if ($is_production) {
    die('This script is for local development only.');
}

$db_path = __DIR__ . '/storage/local.sqlite';
$storage_dir = __DIR__ . '/storage';

header('Content-Type: text/plain');

if (isset($_GET['reset']) && $_GET['reset'] === 'true') {
    if (file_exists($db_path)) {
        if (unlink($db_path)) {
            echo "✓ Database file deleted for reset.\n\n";
        } else {
            die("✗ Failed to delete database file.\n");
        }
    }
}

if (!is_dir($storage_dir)) {
    if (!mkdir($storage_dir, 0755, true)) {
        die("✗ Failed to create storage directory.\n");
    }
    echo "✓ Directory 'storage' created.\n";
}

if (!is_writable($storage_dir)) {
    die("✗ 'storage' directory is not writable.\n");
}

if (!$pdo) {
    die("✗ PDO not initialized. Check config.php.\n");
}

try {
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    if ($stmt->fetchColumn() && (!isset($_GET['reset']) || $_GET['reset'] !== 'true')) {
        echo "Database already initialized.\n";
        echo "Use ?reset=true for fresh setup.\n";
        exit;
    }
} catch (Throwable $e) {
}

echo "Creating SQLite tables...\n\n";

try {
    $pdo->exec("\n        CREATE TABLE IF NOT EXISTS settings (\n            setting_key TEXT PRIMARY KEY,\n            setting_value TEXT,\n            setting_type TEXT DEFAULT 'text' CHECK(setting_type IN ('text', 'image', 'html')),\n            description TEXT,\n            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP\n        )\n    ");
    echo "✓ Table 'settings' created.\n";

    $pdo->exec("\n        CREATE TABLE IF NOT EXISTS users (\n            id INTEGER PRIMARY KEY AUTOINCREMENT,\n            username TEXT NOT NULL UNIQUE,\n            password_hash TEXT NOT NULL,\n            created_at DATETIME DEFAULT CURRENT_TIMESTAMP\n        )\n    ");
    echo "✓ Table 'users' created.\n";

    $pdo->exec("\n        CREATE TABLE IF NOT EXISTS analytics (\n            id INTEGER PRIMARY KEY AUTOINCREMENT,\n            session_id TEXT NOT NULL,\n            ip_address TEXT,\n            event_type TEXT NOT NULL,\n            event_value INTEGER DEFAULT 0,\n            page_url TEXT,\n            created_at DATETIME DEFAULT CURRENT_TIMESTAMP\n        )\n    ");
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_analytics_event_type ON analytics (event_type)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_analytics_session_id ON analytics (session_id)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_analytics_created_at ON analytics (created_at)');
    echo "✓ Table 'analytics' created.\n";

    $pdo->exec("\n        CREATE TABLE IF NOT EXISTS blog_posts (\n            id INTEGER PRIMARY KEY AUTOINCREMENT,\n            title TEXT NOT NULL,\n            slug TEXT NOT NULL UNIQUE,\n            excerpt TEXT,\n            content TEXT NOT NULL,\n            cover_image TEXT,\n            is_published INTEGER NOT NULL DEFAULT 0,\n            published_at DATETIME,\n            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,\n            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP\n        )\n    ");
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_blog_posts_published ON blog_posts (is_published, published_at)');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_blog_posts_slug ON blog_posts (slug)');
    echo "✓ Table 'blog_posts' created.\n";

    $pdo->exec("\n        CREATE TABLE IF NOT EXISTS testimonials (\n            id INTEGER PRIMARY KEY AUTOINCREMENT,\n            instagram_url TEXT NOT NULL,\n            caption TEXT,\n            sort_order INTEGER NOT NULL DEFAULT 0,\n            is_active INTEGER NOT NULL DEFAULT 1,\n            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,\n            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP\n        )\n    ");
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_testimonials_active ON testimonials (is_active, sort_order, id)');
    echo "✓ Table 'testimonials' created.\n";

    // Hapus arsitektur lama
    $pdo->exec('DROP TABLE IF EXISTS leads');
    echo "✓ Legacy table 'leads' removed (if existed).\n";

    seed_cms_settings($pdo);
    echo "✓ CMS default settings seeded.\n";

    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO users (username, password_hash) VALUES (?, ?)');
    $stmt->execute(['admin', $hash]);
    echo "✓ Default admin ready (admin / admin123).\n";

    echo "\n✅ Setup complete.\n";
} catch (Throwable $e) {
    echo '✗ Setup error: ' . $e->getMessage() . "\n";
    if (file_exists($db_path)) {
        @unlink($db_path);
    }
}
