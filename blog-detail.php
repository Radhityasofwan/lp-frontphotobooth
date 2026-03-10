<?php
require_once __DIR__ . '/config.php';
ensure_blog_table_exists($pdo);

$slug = trim((string) ($_GET['slug'] ?? ''));
$post = null;

if ($pdo && $slug !== '') {
    try {
        $stmt = $pdo->prepare("
            SELECT id, title, slug, excerpt, content, cover_image, published_at, created_at
            FROM blog_posts
            WHERE slug = ? AND is_published = 1
            LIMIT 1
        ");
        $stmt->execute([$slug]);
        $post = $stmt->fetch();
    } catch (Throwable $e) {
        $post = null;
    }
}

if (!$post) {
    http_response_code(404);
}

$notFoundTitle = get_setting('blog_not_found_title', 'Artikel Tidak Ditemukan');
$backText = get_setting('blog_back_text', 'Kembali ke Blog');
$page_title = $post ? $post['title'] : $notFoundTitle;
require_once __DIR__ . '/header.php';
?>

<section class="bg-orange-gradient position-relative page-hero">
    <div class="container position-relative z-10 text-center text-white">
        <span class="badge bg-white text-orange mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="ph-bold ph-article"></i> <?= h(get_setting('blog_detail_badge', 'Detail Artikel')) ?>
        </span>
        <h1 class="display-5 fw-bold mb-3 text-shadow-sm">
            <?= $post ? h($post['title']) : h($notFoundTitle) ?>
        </h1>
        <?php if ($post): ?>
            <p class="lead fw-medium opacity-75">
                <?= date('d M Y', strtotime($post['published_at'] ?: $post['created_at'])) ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<section class="section-padding pt-4">
    <div class="container" style="max-width: 900px;">
        <?php if (!$post): ?>
            <div class="text-center py-5">
                <p class="mb-4"><?= h(get_setting('blog_not_found_desc', 'Artikel tidak ditemukan atau belum dipublish.')) ?></p>
                <a class="btn btn-outline-playful" href="<?= BASE_URL . '/blog.php' ?>"><?= h($backText) ?></a>
            </div>
        <?php else: ?>
            <?php
            $cover = trim((string) ($post['cover_image'] ?? ''));
            if ($cover === '') {
                $cover = 'assets/img/placeholder-plain.svg';
            }
            ?>
            <article class="card-playful">
                <div class="media-uniform rounded mb-4">
                    <img src="<?= asset($cover) ?>" alt="<?= h($post['title']) ?>">
                </div>
                <?php if (!empty($post['excerpt'])): ?>
                    <p class="lead text-muted-custom mb-4"><?= h($post['excerpt']) ?></p>
                <?php endif; ?>
                <div class="text-dark" style="white-space: pre-line; line-height: 1.8;">
                    <?= h($post['content']) ?>
                </div>
            </article>
            <div class="mt-4">
                <a class="btn btn-outline-playful" href="<?= BASE_URL . '/blog.php' ?>">← <?= h($backText) ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
