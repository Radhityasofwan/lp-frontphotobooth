<?php
/**
 * sync_db.php – One-time database setup / synchronization script.
 * 
 * Run this ONCE by visiting: https://kamenriders.ozverligsportwear.com/sync_db.php
 * DELETE this file immediately after running.
 * 
 * Ozverligsportwear x Kemalikart | Kamen Riders
 */

// ── Security: allow only if a secret token is passed ──
define('SYNC_TOKEN', 'ozverlig_sync_2026');
if (($_GET['token'] ?? '') !== SYNC_TOKEN) {
    http_response_code(403);
    die('<b>403 Forbidden.</b> Append ?token=ozverlig_sync_2026 to the URL to run this script.');
}

require_once __DIR__ . '/config.php';

if (!$pdo) {
    die('<b>ERROR:</b> Could NOT connect to the database. Check DB_HOST, DB_USER, DB_PASS, DB_NAME in config.php.<br>Last error in storage/events.log.');
}

$ok = [];
$errors = [];

// ── Helper ──
function run(PDO $pdo, string $sql, string $label): void
{
    global $ok, $errors;
    try {
        $pdo->exec($sql);
        $ok[] = "✓ $label";
    } catch (PDOException $e) {
        $errors[] = "✗ $label — " . $e->getMessage();
    }
}

// ── 1. Create `users` table ──
run($pdo, "
CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT AUTO_INCREMENT PRIMARY KEY,
    `username`      VARCHAR(50)  NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `created_at`    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
", 'Create table: users');

// ── 2. Create `leads` table ──
run($pdo, "
CREATE TABLE IF NOT EXISTS `leads` (
    `id`            INT AUTO_INCREMENT PRIMARY KEY,
    `name`          VARCHAR(100) NOT NULL,
    `phone`         VARCHAR(30)  NOT NULL,
    `address`       TEXT         NOT NULL,
    `design`        VARCHAR(60)  NOT NULL,
    `size`          VARCHAR(10)  NOT NULL,
    `quantity`      INT          NOT NULL DEFAULT 1,
    `total_price`   INT          NOT NULL DEFAULT 0,
    `note`          TEXT,
    `payment_proof` VARCHAR(255),
    `order_token`   VARCHAR(64),
    `status`        ENUM('pending','contacted','paid','cancelled') DEFAULT 'pending',
    `utm_source`    VARCHAR(100),
    `utm_medium`    VARCHAR(100),
    `utm_campaign`  VARCHAR(100),
    `utm_content`   VARCHAR(255),
    `utm_term`      VARCHAR(255),
    `fbclid`        VARCHAR(255),
    `gclid`         VARCHAR(255),
    `wbraid`        VARCHAR(255),
    `gbraid`        VARCHAR(255),
    `referrer`      TEXT,
    `created_at`    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
", 'Create table: leads');

// ── 3. Add missing columns (safe ALTER — will not fail if already exists) ──
$columns_to_add = [
    ['leads', 'total_price', "INT NOT NULL DEFAULT 0 AFTER `quantity`"],
    ['leads', 'payment_proof', "VARCHAR(255) AFTER `note`"],
    ['leads', 'order_token', "VARCHAR(64) AFTER `payment_proof`"],
    ['leads', 'utm_content', "VARCHAR(255) AFTER `utm_campaign`"],
    ['leads', 'utm_term', "VARCHAR(255) AFTER `utm_content`"],
    ['leads', 'wbraid', "VARCHAR(255) AFTER `gclid`"],
    ['leads', 'gbraid', "VARCHAR(255) AFTER `wbraid`"],
];

foreach ($columns_to_add as [$table, $col, $def]) {
    // Check if column exists first
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?");
    $stmt->execute([DB_NAME, $table, $col]);
    $exists = (bool) $stmt->fetchColumn();
    if (!$exists) {
        run($pdo, "ALTER TABLE `{$table}` ADD COLUMN `{$col}` {$def}", "ALTER {$table}: add {$col}");
    } else {
        $ok[] = "– Column `{$col}` already exists in `{$table}` (skipped)";
    }
}

// ── 4. Insert default admin user if not present ──
$stmt = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE username = 'admin'");
$stmt->execute();
if (!(bool) $stmt->fetchColumn()) {
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    run($pdo, "INSERT INTO `users` (username, password_hash) VALUES ('admin', '{$hash}')", 'Insert default admin user (admin / admin123)');
} else {
    $ok[] = '– Admin user already exists (skipped)';
}

// ── 5. Create uploads directory ──
$uploadDir = __DIR__ . '/storage/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
    $ok[] = '✓ Created storage/uploads/ directory';
} else {
    $ok[] = '– storage/uploads/ already exists (skipped)';
}

// ── Report ──
$allOk = empty($errors);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DB Sync | Kamen Riders</title>
    <style>
        body {
            font-family: monospace;
            background: #111;
            color: #eee;
            padding: 2rem;
        }

        h1 {
            color: #f0131e;
            font-size: 1.4rem;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: .4rem 0;
        }

        .ok {
            color: #00ff66;
        }

        .err {
            color: #f0131e;
            font-weight: bold;
        }

        .warn {
            background: #f0131e;
            color: #fff;
            padding: .25rem .75rem;
            display: inline-block;
            margin-top: 1rem;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <h1>🗄️ Database Sync — Kamen Riders</h1>

    <ul>
        <?php foreach ($ok as $msg): ?>
            <li class="ok"><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
        <?php foreach ($errors as $msg): ?>
            <li class="err"><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
    </ul>

    <?php if ($allOk): ?>
        <p class="ok" style="margin-top:1.5rem;font-size:1.1rem;">
            ✅ All done! Database is ready. Form submissions will now save to MySQL.
        </p>
    <?php else: ?>
        <p class="err" style="margin-top:1.5rem;">⚠ Some steps failed. See errors above.</p>
    <?php endif; ?>

    <p class="warn">⚠️ DELETE THIS FILE (sync_db.php) from your server now for security.</p>
</body>

</html>