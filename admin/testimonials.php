<?php
require_once __DIR__ . '/../config.php';
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

ensure_testimonials_table_exists($pdo);

$edit = null;
$editId = (int) ($_GET['edit'] ?? 0);
if ($editId > 0 && $pdo) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM testimonials WHERE id = ? LIMIT 1');
        $stmt->execute([$editId]);
        $edit = $stmt->fetch();
    } catch (Throwable $e) {
        $edit = null;
    }
}

$items = [];
if ($pdo) {
    try {
        $stmt = $pdo->query('SELECT id, instagram_url, caption, sort_order, is_active, created_at FROM testimonials ORDER BY sort_order ASC, id DESC');
        $items = $stmt->fetchAll();
    } catch (Throwable $e) {
        $items = [];
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manage Testimoni | Admin Front Photobooth</title>
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
                <a href="index.php">Analytics</a>
                <a href="settings.php">Website Content</a>
                <a href="blog.php">Blog</a>
                <a href="testimonials.php" class="active">Testimoni</a>
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
                <h2 class="panel-title"><?= $edit ? 'Edit Testimoni Instagram' : 'Tambah Testimoni Instagram' ?></h2>
                <form action="actions.php" method="POST">
                    <input type="hidden" name="action" value="save_testimonial">
                    <input type="hidden" name="id" value="<?= $edit ? (int) $edit['id'] : 0 ?>">

                    <div class="form-grid cols-2">
                        <div class="field">
                            <label for="instagram_url">Instagram Post URL</label>
                            <input id="instagram_url" type="text" name="instagram_url" required
                                placeholder="https://www.instagram.com/p/xxxxxxxx/"
                                value="<?= h($edit['instagram_url'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label for="sort_order">Urutan Tampil</label>
                            <input id="sort_order" type="number" step="1" name="sort_order" value="<?= h((string) ($edit['sort_order'] ?? 0)) ?>">
                        </div>
                    </div>

                    <div class="field">
                        <label for="caption">Label Singkat (opsional)</label>
                        <input id="caption" type="text" name="caption" maxlength="255"
                            value="<?= h($edit['caption'] ?? '') ?>" placeholder="Contoh: Wedding - Bandung">
                    </div>

                    <div class="field">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="is_active" value="1" <?= !isset($edit['is_active']) || (int) $edit['is_active'] === 1 ? 'checked' : '' ?>>
                            Tampilkan di website
                        </label>
                    </div>

                    <div class="admin-actions">
                        <button type="submit" class="btn btn-primary">Simpan Testimoni</button>
                        <?php if ($edit): ?>
                            <a href="testimonials.php" class="btn btn-secondary">Batal Edit</a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="panel">
                <h2 class="panel-title">Daftar Testimoni Instagram</h2>
                <?php if (empty($items)): ?>
                    <div class="empty-state">Belum ada testimoni. Tambahkan URL post Instagram untuk tampil di section social proof.</div>
                <?php else: ?>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>URL Instagram</th>
                                    <th>Label</th>
                                    <th>Urutan</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= h($item['instagram_url']) ?>" target="_blank" rel="noopener noreferrer">
                                                <?= h($item['instagram_url']) ?>
                                            </a>
                                        </td>
                                        <td><?= h($item['caption'] ?: '-') ?></td>
                                        <td><?= (int) $item['sort_order'] ?></td>
                                        <td>
                                            <?php if ((int) $item['is_active'] === 1): ?>
                                                <span class="badge badge-pub">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-draft">Hidden</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= !empty($item['created_at']) ? date('d M Y H:i', strtotime((string) $item['created_at'])) : '-' ?></td>
                                        <td>
                                            <div class="inline-actions">
                                                <a href="testimonials.php?edit=<?= (int) $item['id'] ?>" class="btn btn-secondary">Edit</a>
                                                <form action="actions.php" method="POST" onsubmit="return confirm('Hapus testimoni ini?');">
                                                    <input type="hidden" name="action" value="delete_testimonial">
                                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
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
</body>

</html>
