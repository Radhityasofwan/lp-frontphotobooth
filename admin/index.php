<?php
require_once __DIR__ . '/../config.php';
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$summary = [
    'settings_total' => 0,
    'blog_total' => 0,
    'blog_published' => 0,
    'analytics_total' => 0,
    'analytics_today' => 0,
];
$topEvents = [];

if ($pdo) {
    try {
        $summary['settings_total'] = (int) $pdo->query('SELECT COUNT(*) FROM settings')->fetchColumn();
    } catch (Throwable $e) {
    }

    try {
        $summary['blog_total'] = (int) $pdo->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn();
        $summary['blog_published'] = (int) $pdo->query('SELECT COUNT(*) FROM blog_posts WHERE is_published = 1')->fetchColumn();
    } catch (Throwable $e) {
        ensure_blog_table_exists($pdo);
    }

    try {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $todayExpr = $driver === 'sqlite' ? "DATE(created_at) = DATE('now')" : 'DATE(created_at) = CURDATE()';

        $summary['analytics_total'] = (int) $pdo->query('SELECT COUNT(*) FROM analytics')->fetchColumn();
        $summary['analytics_today'] = (int) $pdo->query("SELECT COUNT(*) FROM analytics WHERE {$todayExpr}")->fetchColumn();

        $stmt = $pdo->query('SELECT event_type, COUNT(*) AS total FROM analytics GROUP BY event_type ORDER BY total DESC LIMIT 10');
        $topEvents = $stmt->fetchAll();
    } catch (Throwable $e) {
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Analytics Dashboard | Admin Front Photobooth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: -apple-system, system-ui, sans-serif;
            background: #f4f4f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 12px;
            flex-wrap: wrap;
        }

        h1 {
            margin: 0;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .nav-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            flex-wrap: wrap;
        }

        .nav-tabs a {
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            color: #6b7280;
            font-weight: 600;
        }

        .nav-tabs a.active {
            background: #111;
            color: #fff;
        }

        .nav-tabs a:hover:not(.active) {
            background: #e5e7eb;
        }

        .btn {
            padding: 8px 16px;
            background: #111;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
        }

        .cards {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            margin-bottom: 22px;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
        }

        .label {
            color: #6b7280;
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 8px;
        }

        .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #111;
            line-height: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
        }

        th {
            font-size: .78rem;
            text-transform: uppercase;
            color: #6b7280;
            background: #f8fafc;
        }

        td:last-child,
        th:last-child {
            text-align: right;
        }

        .empty {
            padding: 20px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Front Photobooth <span style="color:#f77b0f">CMS</span></h1>
            <div>
                <a href="../index.php" target="_blank" class="btn" style="background:#f3f4f6;color:#111;border:1px solid #d1d5db;">Lihat Website</a>
                <a href="actions.php?action=logout" class="btn">Logout</a>
            </div>
        </div>

        <div class="nav-tabs">
            <a href="index.php" class="active">Analytics</a>
            <a href="settings.php">Website Content</a>
            <a href="blog.php">Blog</a>
        </div>

        <div class="cards">
            <div class="card">
                <div class="label">Total Settings CMS</div>
                <div class="value"><?= (int) $summary['settings_total'] ?></div>
            </div>
            <div class="card">
                <div class="label">Total Artikel Blog</div>
                <div class="value"><?= (int) $summary['blog_total'] ?></div>
            </div>
            <div class="card">
                <div class="label">Blog Published</div>
                <div class="value"><?= (int) $summary['blog_published'] ?></div>
            </div>
            <div class="card">
                <div class="label">Analytics Event Hari Ini</div>
                <div class="value"><?= (int) $summary['analytics_today'] ?></div>
            </div>
            <div class="card">
                <div class="label">Total Analytics Event</div>
                <div class="value"><?= (int) $summary['analytics_total'] ?></div>
            </div>
        </div>

        <h2 style="font-size:1.1rem; margin:0 0 10px 0;">Top Event Tracking</h2>
        <?php if (empty($topEvents)): ?>
            <div class="empty">Belum ada data analytics.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topEvents as $event): ?>
                        <tr>
                            <td><code><?= h($event['event_type']) ?></code></td>
                            <td><?= (int) $event['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>
