<?php
/**
 * sync_db.php – One-time database sync script (MySQL only).
 *
 * Run once on production:
 * https://your-domain/sync_db.php?token=front_sync_2026
 *
 * Delete this file after successful run.
 */

define('SYNC_TOKEN', 'front_sync_2026');
if (($_GET['token'] ?? '') !== SYNC_TOKEN) {
    http_response_code(403);
    die('<b>403 Forbidden.</b> Append ?token=front_sync_2026 to run this script.');
}

require_once __DIR__ . '/config.php';

if (!$pdo) {
    die('<b>ERROR:</b> Could not connect to database.');
}

$ok = [];
$errors = [];

function run_query(PDO $pdo, string $sql, string $label): void
{
    global $ok, $errors;
    try {
        $pdo->exec($sql);
        $ok[] = "✓ {$label}";
    } catch (Throwable $e) {
        $errors[] = "✗ {$label} — " . $e->getMessage();
    }
}

run_query($pdo, "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
", 'Create table users');

run_query($pdo, "
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text','image','html') DEFAULT 'text',
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
", 'Create table settings');

run_query($pdo, "
CREATE TABLE IF NOT EXISTS analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL,
    ip_address VARCHAR(45),
    event_type VARCHAR(50) NOT NULL,
    event_value INT DEFAULT 0,
    page_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_analytics_event_type (event_type),
    INDEX idx_analytics_session_id (session_id),
    INDEX idx_analytics_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
", 'Create table analytics');

run_query($pdo, "
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    cover_image TEXT,
    is_published TINYINT(1) NOT NULL DEFAULT 0,
    published_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_blog_posts_published (is_published, published_at),
    INDEX idx_blog_posts_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
", 'Create table blog_posts');

run_query($pdo, "
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instagram_url VARCHAR(500) NOT NULL,
    caption VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_testimonials_active (is_active, sort_order, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
", 'Create table testimonials');

run_query($pdo, 'DROP TABLE IF EXISTS leads;', 'Drop legacy table leads');

try {
    seed_cms_settings($pdo);
    $ok[] = '✓ Seed CMS settings';
} catch (Throwable $e) {
    $errors[] = '✗ Seed CMS settings — ' . $e->getMessage();
}

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    if ((int) $stmt->fetchColumn() === 0) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
        $insert = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
        $insert->execute(['admin', $hash]);
        $ok[] = '✓ Insert default admin (admin / admin123)';
    } else {
        $ok[] = '– Default admin already exists';
    }
} catch (Throwable $e) {
    $errors[] = '✗ Ensure default admin — ' . $e->getMessage();
}

$uploadDir = __DIR__ . '/storage/uploads/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

$allOk = empty($errors);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DB Sync</title>
    <style>
        body { font-family: monospace; background: #111; color: #eee; padding: 2rem; }
        h1 { color: #f77b0f; }
        ul { list-style: none; padding: 0; }
        li { margin: .4rem 0; }
        .ok { color: #00ff66; }
        .err { color: #ff5d5d; }
        .warn { background: #f77b0f; color: #111; padding: .25rem .75rem; display: inline-block; margin-top: 1rem; border-radius: 4px; }
    </style>
</head>

<body>
    <h1>Database Sync - Front Photobooth</h1>

    <ul>
        <?php foreach ($ok as $msg): ?>
            <li class="ok"><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
        <?php foreach ($errors as $msg): ?>
            <li class="err"><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
    </ul>

    <?php if ($allOk): ?>
        <p class="ok">✅ Database sync complete.</p>
    <?php else: ?>
        <p class="err">⚠ Some steps failed, please review logs.</p>
    <?php endif; ?>

    <p class="warn">Delete this file after use for security.</p>
</body>

</html>
