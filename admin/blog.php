<?php
require_once __DIR__ . '/../config.php';
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

ensure_blog_table_exists($pdo);

$edit = null;
$editId = (int) ($_GET['edit'] ?? 0);
if ($editId > 0 && $pdo) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM blog_posts WHERE id = ? LIMIT 1');
        $stmt->execute([$editId]);
        $edit = $stmt->fetch();
    } catch (Throwable $e) {
        $edit = null;
    }
}

$posts = [];
if ($pdo) {
    try {
        $stmt = $pdo->query('SELECT id, title, slug, is_published, published_at, created_at FROM blog_posts ORDER BY id DESC');
        $posts = $stmt->fetchAll();
    } catch (Throwable $e) {
        $posts = [];
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manage Blog | Admin Front Photobooth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require __DIR__ . '/partials/head-assets.php'; ?>
</head>

<body>
    <main class="admin-page">
        <div class="container-admin">
            <header class="admin-topbar">
                <h1 class="admin-brand">Front Photobooth <strong>CMS</strong></h1>
                <div class="admin-actions">
                    <a href="../blog.php" target="_blank" class="btn btn-secondary">Lihat Blog</a>
                    <a href="actions.php?action=logout" class="btn btn-dark">Logout</a>
                </div>
            </header>

            <nav class="admin-tabs" aria-label="Navigasi admin">
                <a href="index.php">Analytics</a>
                <a href="settings.php">Website Content</a>
                <a href="blog.php" class="active">Blog</a>
                <a href="testimonials.php">Testimoni</a>
            </nav>

            <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert alert-success"><?= h($_SESSION['msg']);
                unset($_SESSION['msg']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['msg_err'])): ?>
                <div class="alert alert-error"><?= h($_SESSION['msg_err']);
                unset($_SESSION['msg_err']); ?></div>
            <?php endif; ?>

            <section class="panel">
                <h2 class="panel-title"><?= $edit ? 'Edit Artikel' : 'Buat Artikel Baru' ?></h2>

                <form action="actions.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="save_blog_post">
                    <input type="hidden" name="id" value="<?= $edit ? (int) $edit['id'] : 0 ?>">
                    <input type="hidden" name="current_cover_image" value="<?= h($edit['cover_image'] ?? '') ?>">

                    <div class="form-grid cols-2">
                        <div class="field">
                            <label for="title">Judul</label>
                            <input id="title" type="text" name="title" required value="<?= h($edit['title'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label for="slug">Slug (opsional)</label>
                            <input id="slug" type="text" name="slug" value="<?= h($edit['slug'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-grid cols-2">
                        <div class="field">
                            <label for="cover_image_upload">Cover Image (Upload)</label>
                            <input id="cover_image_upload" type="file" name="cover_image_upload" accept="image/*">
                            <?php if (!empty($edit['cover_image'])): ?>
                                <div class="mt-10">
                                    <img class="img-preview" src="<?= h(asset($edit['cover_image'])) ?>" alt="Cover preview">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="field">
                            <label for="published_at">Tanggal Publish (opsional)</label>
                            <input id="published_at" type="datetime-local" name="published_at"
                                value="<?= !empty($edit['published_at']) ? date('Y-m-d\TH:i', strtotime($edit['published_at'])) : '' ?>">
                        </div>
                    </div>

                    <div class="field">
                        <label for="excerpt">Excerpt Ringkas</label>
                        <textarea id="excerpt" name="excerpt" class="textarea-sm"><?= h($edit['excerpt'] ?? '') ?></textarea>
                    </div>

                    <div class="field">
                        <label for="content">Isi Artikel</label>
                        <textarea id="content" name="content" required><?= h($edit['content'] ?? '') ?></textarea>
                    </div>

                    <div class="field">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="is_published" value="1" <?= !empty($edit['is_published']) ? 'checked' : '' ?>>
                            Publish artikel ini
                        </label>
                    </div>

                    <div class="admin-actions">
                        <button type="submit" class="btn btn-primary">Simpan Artikel</button>
                        <?php if ($edit): ?>
                            <a href="blog.php" class="btn btn-secondary">Batal Edit</a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="panel">
                <h2 class="panel-title">Daftar Artikel</h2>
                <?php if (empty($posts)): ?>
                    <div class="empty-state">Belum ada artikel.</div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Publish</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $p): ?>
                                    <tr>
                                        <td><?= h($p['title']) ?></td>
                                        <td><code><?= h($p['slug']) ?></code></td>
                                        <td>
                                            <?php if ((int) $p['is_published'] === 1): ?>
                                                <span class="badge badge-pub">Published</span>
                                            <?php else: ?>
                                                <span class="badge badge-draft">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $p['published_at'] ? date('d M Y H:i', strtotime($p['published_at'])) : '-' ?></td>
                                        <td>
                                            <div class="inline-actions">
                                                <a href="blog.php?edit=<?= (int) $p['id'] ?>" class="btn btn-secondary">Edit</a>
                                                <form action="actions.php" method="POST" onsubmit="return confirm('Hapus artikel ini?');">
                                                    <input type="hidden" name="action" value="delete_blog_post">
                                                    <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
    <?php require __DIR__ . '/partials/footer-scripts.php'; ?>
</body>

</html>
