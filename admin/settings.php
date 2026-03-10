<?php
require_once __DIR__ . '/../config.php';
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$settings_raw = [];
if ($pdo) {
    try {
        $stmt = $pdo->query('SELECT * FROM settings ORDER BY setting_key ASC');
        $settings_raw = $stmt->fetchAll();
    } catch (Throwable $e) {
    }
}

$definitions = get_cms_setting_definitions();
$existing_map = [];
foreach ($settings_raw as $row) {
    $existing_map[$row['setting_key']] = true;
}

foreach ($definitions as $key => $meta) {
    if (!isset($existing_map[$key])) {
        $settings_raw[] = [
            'setting_key' => $key,
            'setting_value' => $meta[0],
            'setting_type' => $meta[1],
            'description' => $meta[2]
        ];
    }
}

$settings = [];
foreach ($settings_raw as $s) {
    if (isset($definitions[$s['setting_key']])) {
        $s['setting_type'] = $definitions[$s['setting_key']][1];
        if (empty($s['description'])) {
            $s['description'] = $definitions[$s['setting_key']][2];
        }
    }
    $settings[$s['setting_key']] = $s;
}
ksort($settings);

function getGroup($key)
{
    if (strpos($key, 'nav_') === 0)
        return 'Navigation';
    if (strpos($key, 'home_hero') === 0)
        return 'Home - Hero';
    if (strpos($key, 'home_prob') === 0)
        return 'Home - Problem';
    if (strpos($key, 'home_core') === 0)
        return 'Home - Core Ideas';
    if (strpos($key, 'home_props') === 0 || strpos($key, 'home_sig') === 0)
        return 'Home - Signature Exp';
    if (strpos($key, 'home_srv') === 0)
        return 'Home - Services';
    if (strpos($key, 'home_pkg') === 0)
        return 'Home - Package';
    if (strpos($key, 'home_scrap') === 0)
        return 'Home - Scrapbook';
    if (strpos($key, 'home_trust') === 0)
        return 'Home - Trust';
    if (strpos($key, 'home_scarcity') === 0)
        return 'Home - Scarcity';
    if (strpos($key, 'home_close') === 0)
        return 'Home - Close CTA';
    if (strpos($key, 'home_clients') === 0 || strpos($key, 'client_') === 0)
        return 'Home - Our Clients';
    if (strpos($key, 'footer_') === 0)
        return 'Footer';
    if (strpos($key, 'gallery_') === 0)
        return 'Gallery Page';
    if (strpos($key, 'price_') === 0)
        return 'Pricelist Page';
    if (strpos($key, 'templates_') === 0)
        return 'Template Page';
    if (strpos($key, 'insp_') === 0)
        return 'Inspirasi Page';
    if (strpos($key, 'blog_') === 0)
        return 'Blog Page';
    return 'General';
}

$grouped_settings = [];
foreach ($settings as $key => $s) {
    $group = getGroup($key);
    $grouped_settings[$group][] = $s;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manage Content | Admin Front Photobooth</title>
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
                <a href="settings.php" class="active">Website Content</a>
                <a href="blog.php">Blog</a>
                <a href="index.php">Analytics</a>
            </nav>

            <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert alert-success">
                    <?= h($_SESSION['msg']);
                    unset($_SESSION['msg']); ?>
                </div>
            <?php endif; ?>

            <form action="actions.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="save_settings">

                <?php foreach (['General', 'Navigation', 'Home - Hero', 'Home - Problem', 'Home - Core Ideas', 'Home - Signature Exp', 'Home - Services', 'Home - Package', 'Home - Scrapbook', 'Home - Our Clients', 'Home - Trust', 'Home - Scarcity', 'Home - Close CTA', 'Pricelist Page', 'Gallery Page', 'Template Page', 'Inspirasi Page', 'Blog Page', 'Footer'] as $groupName): ?>
                    <?php if (empty($grouped_settings[$groupName]))
                        continue; ?>

                    <section class="panel">
                        <h2 class="group-title"><?= h($groupName) ?></h2>

                        <?php foreach ($grouped_settings[$groupName] as $s): ?>
                            <div class="setting-row">
                                <div>
                                    <span class="setting-label"><?= h($s['description'] ?: $s['setting_key']) ?></span>
                                    <span class="setting-key">Key: <code><?= h($s['setting_key']) ?></code></span>
                                </div>

                                <?php if ($s['setting_type'] === 'image'): ?>
                                    <div class="upload-group">
                                        <?php if ($s['setting_value']): ?>
                                            <img src="<?= h(asset($s['setting_value'])) ?>" class="img-preview" alt="Preview">
                                        <?php else: ?>
                                            <div class="img-preview img-placeholder">Upload gambar dari device Anda.</div>
                                        <?php endif; ?>

                                        <div>
                                            <input type="file" name="upload_<?= h($s['setting_key']) ?>" accept="image/*">
                                            <div class="muted">File baru akan mengganti gambar lama.</div>
                                        </div>
                                        <input type="hidden" name="settings_text[<?= h($s['setting_key']) ?>]"
                                            value="<?= h($s['setting_value']) ?>">
                                    </div>
                                <?php else: ?>
                                    <?php $rows = ($s['setting_type'] === 'html') ? 5 : 2; ?>
                                    <textarea name="settings_text[<?= h($s['setting_key']) ?>]" rows="<?= $rows ?>"><?= h($s['setting_value']) ?></textarea>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endforeach; ?>

                <div class="admin-savebar">
                    <button type="submit" class="btn btn-primary">Save All Changes</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
