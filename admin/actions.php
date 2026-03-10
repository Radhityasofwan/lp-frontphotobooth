<?php
require_once __DIR__ . '/../config.php';
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit('Forbidden');
}

$action = $_POST['action'] ?? '';

if (!function_exists('slugify_text')) {
    function slugify_text(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text) ?? '';
        $text = trim($text, '-');
        return $text !== '' ? $text : 'artikel';
    }
}

if ($action === 'save_settings') {
    if ($pdo) {
        seed_cms_settings($pdo);
    }

    // 1. Save all text based fields
    if (!empty($_POST['settings_text']) && $pdo) {
        $definitions = get_cms_setting_definitions();
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $stmtUpsertText = $pdo->prepare("
                INSERT INTO settings (setting_key, setting_value, setting_type, description)
                VALUES (?, ?, ?, ?)
                ON CONFLICT(setting_key) DO UPDATE SET
                    setting_value = excluded.setting_value,
                    setting_type = excluded.setting_type,
                    description = excluded.description
            ");
        } else {
            $stmtUpsertText = $pdo->prepare("
                INSERT INTO settings (setting_key, setting_value, setting_type, description)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    setting_value = VALUES(setting_value),
                    setting_type = VALUES(setting_type),
                    description = VALUES(description)
            ");
        }

        foreach ($_POST['settings_text'] as $key => $val) {
            $desc = $definitions[$key][2] ?? $key;
            $type = $definitions[$key][1] ?? 'text';
            $stmtUpsertText->execute([$key, $val, $type, $desc]);
        }
    }

    // 2. Process image uploads if exists
    $uploadDir = __DIR__ . '/../storage/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach ($_FILES as $inputName => $file) {
        if (strpos($inputName, 'upload_') === 0 && $file['error'] === UPLOAD_ERR_OK) {
            $settingKey = str_replace('upload_', '', $inputName);

            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (strpos($mime, 'image/') === 0) {
                // Generate a unique filename while preserving extension
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg';
                $newFilename = 'cms_' . $settingKey . '_' . time() . '.' . $ext;
                $targetFile = $uploadDir . $newFilename;

                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    // Update the DB to point to the new image URL relative to root
                    $publicPath = 'storage/uploads/' . $newFilename;
                    if ($pdo) {
                        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
                        $definitions = get_cms_setting_definitions();
                        $desc = $definitions[$settingKey][2] ?? $settingKey;

                        if ($driver === 'sqlite') {
                            $stmtUpsertImage = $pdo->prepare("
                                INSERT INTO settings (setting_key, setting_value, setting_type, description)
                                VALUES (?, ?, ?, ?)
                                ON CONFLICT(setting_key) DO UPDATE SET
                                    setting_value = excluded.setting_value,
                                    setting_type = excluded.setting_type,
                                    description = excluded.description
                            ");
                        } else {
                            $stmtUpsertImage = $pdo->prepare("
                                INSERT INTO settings (setting_key, setting_value, setting_type, description)
                                VALUES (?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE
                                    setting_value = VALUES(setting_value),
                                    setting_type = VALUES(setting_type),
                                    description = VALUES(description)
                            ");
                        }
                        $stmtUpsertImage->execute([$settingKey, $publicPath, 'image', $desc]);
                    }
                }
            }
        }
    }

    $_SESSION['msg'] = 'Settings saved successfully!';
    header('Location: settings.php');
    exit;
}

if ($action === 'save_blog_post') {
    ensure_blog_table_exists($pdo);
    if (!$pdo) {
        $_SESSION['msg_err'] = 'Database tidak tersedia.';
        header('Location: blog.php');
        exit;
    }

    $id = (int) ($_POST['id'] ?? 0);
    $title = trim((string) ($_POST['title'] ?? ''));
    $slugInput = trim((string) ($_POST['slug'] ?? ''));
    $excerpt = trim((string) ($_POST['excerpt'] ?? ''));
    $content = trim((string) ($_POST['content'] ?? ''));
    $currentCoverImage = trim((string) ($_POST['current_cover_image'] ?? ''));
    $coverImage = $currentCoverImage;
    $isPublished = isset($_POST['is_published']) ? 1 : 0;
    $publishedAt = trim((string) ($_POST['published_at'] ?? ''));

    if (!empty($_FILES['cover_image_upload']) && ($_FILES['cover_image_upload']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $coverFile = $_FILES['cover_image_upload'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $coverFile['tmp_name']);
        finfo_close($finfo);

        if (strpos((string) $mime, 'image/') === 0) {
            $uploadDir = __DIR__ . '/../storage/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = strtolower(pathinfo($coverFile['name'], PATHINFO_EXTENSION) ?: 'jpg');
            $filename = 'blog_cover_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            $target = $uploadDir . $filename;

            if (move_uploaded_file($coverFile['tmp_name'], $target)) {
                $coverImage = 'storage/uploads/' . $filename;
            }
        }
    }

    if ($title === '' || $content === '') {
        $_SESSION['msg_err'] = 'Judul dan isi artikel wajib diisi.';
        header('Location: blog.php' . ($id > 0 ? '?edit=' . $id : ''));
        exit;
    }

    $baseSlug = slugify_text($slugInput !== '' ? $slugInput : $title);
    $slug = $baseSlug;
    $suffix = 2;
    while (true) {
        $stmt = $pdo->prepare("SELECT id FROM blog_posts WHERE slug = ? AND id != ? LIMIT 1");
        $stmt->execute([$slug, $id]);
        if (!$stmt->fetch()) {
            break;
        }
        $slug = $baseSlug . '-' . $suffix;
        $suffix++;
    }

    if ($publishedAt === '') {
        $publishedAt = $isPublished ? date('Y-m-d H:i:s') : null;
    } else {
        $publishedAt = date('Y-m-d H:i:s', strtotime($publishedAt));
    }

    if ($id > 0) {
        $stmt = $pdo->prepare("
            UPDATE blog_posts
            SET title = ?, slug = ?, excerpt = ?, content = ?, cover_image = ?, is_published = ?, published_at = ?
            WHERE id = ?
        ");
        $stmt->execute([$title, $slug, $excerpt, $content, $coverImage, $isPublished, $publishedAt, $id]);
        $_SESSION['msg'] = 'Artikel berhasil diperbarui.';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO blog_posts (title, slug, excerpt, content, cover_image, is_published, published_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$title, $slug, $excerpt, $content, $coverImage, $isPublished, $publishedAt]);
        $_SESSION['msg'] = 'Artikel berhasil dibuat.';
    }

    header('Location: blog.php');
    exit;
}

if ($action === 'delete_blog_post') {
    ensure_blog_table_exists($pdo);
    if ($pdo) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['msg'] = 'Artikel berhasil dihapus.';
        }
    }
    header('Location: blog.php');
    exit;
}

if ($action === 'save_testimonial') {
    ensure_testimonials_table_exists($pdo);
    if (!$pdo) {
        $_SESSION['msg_err'] = 'Database tidak tersedia.';
        header('Location: testimonials.php');
        exit;
    }

    $id = (int) ($_POST['id'] ?? 0);
    $instagramUrlRaw = trim((string) ($_POST['instagram_url'] ?? ''));
    $instagramUrl = normalize_instagram_permalink($instagramUrlRaw);
    $caption = trim((string) ($_POST['caption'] ?? ''));
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($instagramUrl === '') {
        $_SESSION['msg_err'] = 'URL Instagram tidak valid. Gunakan URL post seperti /p/..., /reel/... atau /tv/....';
        header('Location: testimonials.php' . ($id > 0 ? '?edit=' . $id : ''));
        exit;
    }

    $sortOrder = max(-999, min($sortOrder, 999));
    if (strlen($caption) > 255) {
        $caption = substr($caption, 0, 255);
    }

    if ($id > 0) {
        $stmt = $pdo->prepare("
            UPDATE testimonials
            SET instagram_url = ?, caption = ?, sort_order = ?, is_active = ?
            WHERE id = ?
        ");
        $stmt->execute([$instagramUrl, $caption, $sortOrder, $isActive, $id]);
        $_SESSION['msg'] = 'Testimoni berhasil diperbarui.';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO testimonials (instagram_url, caption, sort_order, is_active)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$instagramUrl, $caption, $sortOrder, $isActive]);
        $_SESSION['msg'] = 'Testimoni berhasil ditambahkan.';
    }

    header('Location: testimonials.php');
    exit;
}

if ($action === 'delete_testimonial') {
    ensure_testimonials_table_exists($pdo);
    if ($pdo) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM testimonials WHERE id = ?');
            $stmt->execute([$id]);
            $_SESSION['msg'] = 'Testimoni berhasil dihapus.';
        }
    }
    header('Location: testimonials.php');
    exit;
}

header('Location: settings.php');
exit;
