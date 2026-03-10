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
        $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ? LIMIT 1");
        $stmt->execute([$editId]);
        $edit = $stmt->fetch();
    } catch (Throwable $e) {
        $edit = null;
    }
}

$posts = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT id, title, slug, is_published, published_at, created_at
            FROM blog_posts
            ORDER BY id DESC
        ");
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
    <style>
        body { font-family: -apple-system, system-ui, sans-serif; background: #f4f4f5; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1200px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        h1 { margin: 0; font-size: 1.5rem; text-transform: uppercase; letter-spacing: .5px; }
        .nav-tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
        .nav-tabs a { text-decoration: none; padding: 8px 16px; border-radius: 6px; color: #6b7280; font-weight: 600; }
        .nav-tabs a.active { background: #111; color: #fff; }
        .nav-tabs a:hover:not(.active) { background: #e5e7eb; }
        .card { background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 20px; }
        .grid { display: grid; gap: 14px; grid-template-columns: 1fr; }
        @media (min-width: 860px) { .grid-2 { grid-template-columns: 1fr 1fr; } }
        label { font-size: .85rem; font-weight: 700; color: #374151; display: block; margin-bottom: 6px; }
        input[type="text"], input[type="datetime-local"], textarea {
            width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 10px; box-sizing: border-box;
        }
        textarea { min-height: 180px; resize: vertical; }
        .btn { padding: 10px 14px; border: 0; border-radius: 6px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary { background: #111; color: #fff; }
        .btn-secondary { background: #e5e7eb; color: #111; }
        .btn-danger { background: #ef4444; color: #fff; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #f1f5f9; text-align: left; vertical-align: top; }
        th { font-size: .78rem; text-transform: uppercase; color: #6b7280; background: #f8fafc; }
        .badge { font-size: .72rem; font-weight: 700; padding: 3px 8px; border-radius: 999px; display: inline-block; }
        .badge-pub { background: #d1fae5; color: #065f46; }
        .badge-draft { background: #fee2e2; color: #991b1b; }
        .msg { margin-bottom: 16px; padding: 12px; border-radius: 6px; }
        .msg-ok { background: #d1fae5; color: #065f46; }
        .msg-err { background: #fee2e2; color: #991b1b; }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Front Photobooth <span style="color:#f77b0f">CMS</span></h1>
            <div>
                <a href="../blog.php" target="_blank" class="btn btn-secondary">Lihat Blog</a>
                <a href="actions.php?action=logout" class="btn btn-primary">Logout</a>
            </div>
        </div>

        <div class="nav-tabs">
            <a href="index.php">Analytics</a>
            <a href="settings.php">Website Content</a>
            <a href="blog.php" class="active">Blog</a>
        </div>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="msg msg-ok"><?= h($_SESSION['msg']); unset($_SESSION['msg']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['msg_err'])): ?>
            <div class="msg msg-err"><?= h($_SESSION['msg_err']); unset($_SESSION['msg_err']); ?></div>
        <?php endif; ?>

        <div class="card">
            <h2 style="margin-top:0;"><?= $edit ? 'Edit Artikel' : 'Buat Artikel Baru' ?></h2>
            <form action="actions.php" method="POST">
                <input type="hidden" name="action" value="save_blog_post">
                <input type="hidden" name="id" value="<?= $edit ? (int) $edit['id'] : 0 ?>">

                <div class="grid grid-2">
                    <div>
                        <label for="title">Judul</label>
                        <input id="title" type="text" name="title" required value="<?= h($edit['title'] ?? '') ?>">
                    </div>
                    <div>
                        <label for="slug">Slug (opsional)</label>
                        <input id="slug" type="text" name="slug" value="<?= h($edit['slug'] ?? '') ?>">
                    </div>
                </div>

                <div class="grid grid-2" style="margin-top: 12px;">
                    <div>
                        <label for="cover_image">Cover Image URL / Path (opsional)</label>
                        <input id="cover_image" type="text" name="cover_image" value="<?= h($edit['cover_image'] ?? '') ?>">
                    </div>
                    <div>
                        <label for="published_at">Tanggal Publish (opsional)</label>
                        <input id="published_at" type="datetime-local" name="published_at"
                               value="<?= !empty($edit['published_at']) ? date('Y-m-d\TH:i', strtotime($edit['published_at'])) : '' ?>">
                    </div>
                </div>

                <div style="margin-top: 12px;">
                    <label for="excerpt">Excerpt Ringkas</label>
                    <textarea id="excerpt" name="excerpt" style="min-height: 80px;"><?= h($edit['excerpt'] ?? '') ?></textarea>
                </div>
                <div style="margin-top: 12px;">
                    <label for="content">Isi Artikel</label>
                    <textarea id="content" name="content" required><?= h($edit['content'] ?? '') ?></textarea>
                </div>

                <div style="margin-top: 12px;">
                    <label style="display:flex; align-items:center; gap:8px; font-weight:600;">
                        <input type="checkbox" name="is_published" value="1" <?= !empty($edit['is_published']) ? 'checked' : '' ?>>
                        Publish artikel ini
                    </label>
                </div>

                <div style="margin-top: 16px; display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary">Simpan Artikel</button>
                    <?php if ($edit): ?>
                        <a href="blog.php" class="btn btn-secondary">Batal Edit</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <h2 style="margin-top:0;">Daftar Artikel</h2>
            <div style="overflow-x:auto;">
                <table>
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
                        <?php if (empty($posts)): ?>
                            <tr><td colspan="5" style="text-align:center; color:#6b7280;">Belum ada artikel.</td></tr>
                        <?php else: ?>
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
                                    <td style="white-space:nowrap;">
                                        <a href="blog.php?edit=<?= (int) $p['id'] ?>" class="btn btn-secondary">Edit</a>
                                        <form action="actions.php" method="POST" style="display:inline-block;"
                                              onsubmit="return confirm('Hapus artikel ini?');">
                                            <input type="hidden" name="action" value="delete_blog_post">
                                            <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
