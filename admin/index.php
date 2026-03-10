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
    'testimonials_total' => 0,
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
        ensure_testimonials_table_exists($pdo);
        $summary['testimonials_total'] = (int) $pdo->query('SELECT COUNT(*) FROM testimonials')->fetchColumn();
    } catch (Throwable $e) {
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
    <link rel="stylesheet" href="<?= asset('admin/assets/admin.css') ?>">
</head>

<body>
    <main class="admin-page">
        <div class="container-admin">
            <header class="admin-topbar">
                <h1 class="admin-brand">Front Photobooth <strong>CMS</strong></h1>
                <div class="admin-actions">
                    <a href="../index.php" target="_blank" class="btn btn-secondary">Lihat Website</a>
                    <a href="actions.php?action=logout" class="btn btn-dark">Logout</a>
                </div>
            </header>

            <nav class="admin-tabs" aria-label="Navigasi admin">
                <a href="index.php" class="active">Analytics</a>
                <a href="settings.php">Website Content</a>
                <a href="blog.php">Blog</a>
                <a href="testimonials.php">Testimoni</a>
            </nav>

            <section class="stats-grid" aria-label="Ringkasan metrik">
                <article class="stat-card">
                    <div class="stat-label">Total Settings CMS</div>
                    <div class="stat-value"><?= (int) $summary['settings_total'] ?></div>
                </article>
                <article class="stat-card">
                    <div class="stat-label">Total Artikel Blog</div>
                    <div class="stat-value"><?= (int) $summary['blog_total'] ?></div>
                </article>
                <article class="stat-card">
                    <div class="stat-label">Blog Published</div>
                    <div class="stat-value"><?= (int) $summary['blog_published'] ?></div>
                </article>
                <article class="stat-card">
                    <div class="stat-label">Total Testimoni</div>
                    <div class="stat-value"><?= (int) $summary['testimonials_total'] ?></div>
                </article>
                <article class="stat-card">
                    <div class="stat-label">Event Hari Ini</div>
                    <div class="stat-value"><?= (int) $summary['analytics_today'] ?></div>
                </article>
                <article class="stat-card">
                    <div class="stat-label">Total Analytics Event</div>
                    <div class="stat-value"><?= (int) $summary['analytics_total'] ?></div>
                </article>
            </section>

            <section class="panel">
                <h2 class="panel-title">Top Event Tracking</h2>
                <?php if (empty($topEvents)): ?>
                    <div class="empty-state">Belum ada data analytics.</div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table class="table">
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
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</body>

</html>
