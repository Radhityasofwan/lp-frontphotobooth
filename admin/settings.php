<?php
require_once __DIR__ . '/../config.php';
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch all settings + merge with CMS definitions
$settings_raw = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM settings ORDER BY setting_key ASC");
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
    $settings[$s['setting_key']] = $s;
}
ksort($settings);

// Helper to determine group
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
    <style>
        body {
            font-family: -apple-system, system-ui, sans-serif;
            background: #f4f4f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
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
            cursor: pointer;
        }

        .btn:hover {
            background: #333;
        }

        .btn-success {
            background: #10b981;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            margin-bottom: 24px;
        }

        .card-header {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            color: #f77b0f;
        }

        .setting-row {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 1px dashed #e5e7eb;
            align-items: center;
        }

        .setting-row:last-child {
            border-bottom: none;
        }

        .setting-label {
            font-weight: 600;
            color: #374151;
        }

        .setting-desc {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 4px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .img-preview {
            width: 120px;
            object-fit: cover;
            aspect-ratio: 2/3;
            border-radius: 4px;
            display: block;
        }

        .upload-group {
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        .img-placeholder {
            border: 1px dashed #d1d5db;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 0.75rem;
            color: #6b7280;
            padding: 10px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Front Photobooth <span style="color:#f77b0f">CMS</span></h1>
            <div>
                <a href="../index.php" target="_blank" class="btn"
                    style="background:#f3f4f6; color:#111; border:1px solid #ccc;">Lihat Website</a>
                <a href="actions.php?action=logout" class="btn">Logout</a>
            </div>
        </div>

        <div class="nav-tabs">
            <a href="settings.php" class="active">Website Content</a>
            <a href="blog.php">Blog</a>
            <a href="index.php">Analytics</a>
        </div>

        <?php if (isset($_SESSION['msg'])): ?>
            <div style="padding:15px; background:#d1fae5; color:#065f46; border-radius:6px; margin-bottom:20px;">
                <?= $_SESSION['msg'];
                unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <form action="actions.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_settings">

            <?php foreach (['General', 'Navigation', 'Home - Hero', 'Home - Problem', 'Home - Core Ideas', 'Home - Signature Exp', 'Home - Services', 'Home - Package', 'Home - Scrapbook', 'Home - Our Clients', 'Home - Trust', 'Home - Scarcity', 'Home - Close CTA', 'Pricelist Page', 'Gallery Page', 'Template Page', 'Inspirasi Page', 'Blog Page', 'Footer'] as $groupName): ?>
                <?php if (empty($grouped_settings[$groupName]))
                    continue; ?>

                <div class="card">
                    <div class="card-header"><?= $groupName ?></div>

                    <?php foreach ($grouped_settings[$groupName] as $s): ?>
                        <div class="setting-row">
                            <div>
                                <span
                                    class="setting-label"><?= htmlspecialchars($s['description'] ?: $s['setting_key']) ?></span>
                                <span class="setting-desc">Key: <code><?= htmlspecialchars($s['setting_key']) ?></code></span>
                            </div>

                            <?php if ($s['setting_type'] === 'image'): ?>
                                <div class="upload-group">
                                    <?php if ($s['setting_value']): ?>
                                        <img src="<?= htmlspecialchars(strpos($s['setting_value'], 'http') === 0 ? $s['setting_value'] : '../' . $s['setting_value']) ?>"
                                            class="img-preview" alt="Preview">
                                    <?php else: ?>
                                        <div class="img-preview img-placeholder">
                                            Upload foto Anda di sini (Pastikan rasio sesuai Ukuran 4R / 4:6)
                                        </div>
                                    <?php endif; ?>

                                    <div style="flex-grow:1;">
                                        <input type="file" name="upload_<?= $s['setting_key'] ?>" class="form-control"
                                            accept="image/*">
                                        <div style="font-size:0.75rem; color:#9ca3af; margin-top:4px;">Pilih file baru untuk
                                            mengganti gambar di atas.</div>
                                    </div>
                                    <input type="hidden" name="settings_text[<?= $s['setting_key'] ?>]"
                                        value="<?= htmlspecialchars($s['setting_value']) ?>">
                                </div>
                            <?php else: ?>
                                <?php $rows = ($s['setting_type'] === 'html') ? 5 : 2; ?>
                                <textarea name="settings_text[<?= $s['setting_key'] ?>]"
                                    class="form-control" rows="<?= $rows ?>"><?= htmlspecialchars($s['setting_value']) ?></textarea>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <div
                style="position: sticky; bottom: 20px; text-align: right; background: rgba(255,255,255,0.9); padding: 15px; border-radius: 8px; box-shadow: 0 -4px 10px rgba(0,0,0,0.05); border: 1px solid #eee;">
                <button type="submit" class="btn btn-success" style="font-size: 1.1rem; padding: 10px 30px;">Save All
                    Changes</button>
            </div>
        </form>
    </div>
</body>

</html>
