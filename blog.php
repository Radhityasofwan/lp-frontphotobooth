<?php
require_once __DIR__ . '/config.php';
ensure_blog_table_exists($pdo);

$page_title = 'Blog';
require_once __DIR__ . '/header.php';

$posts = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT id, title, slug, excerpt, content, cover_image, published_at, created_at
            FROM blog_posts
            WHERE is_published = 1
            ORDER BY COALESCE(published_at, created_at) DESC, id DESC
        ");
        $posts = $stmt->fetchAll();
    } catch (Throwable $e) {
        $posts = [];
    }
}
?>

<section class="bg-orange-gradient position-relative page-hero">
    <div class="container position-relative z-10 text-center text-white">
        <span class="badge bg-white text-orange mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="ph-bold ph-newspaper"></i> <?= h(get_setting('blog_hero_badge', 'Front Photobooth Blog')) ?>
        </span>
        <h1 class="display-4 fw-bold mb-3 text-shadow-sm"><?= h(get_setting('blog_hero_title', 'Insight & Cerita Event')) ?></h1>
        <p class="lead fw-medium opacity-75"><?= h(get_setting('blog_hero_desc', 'Tips event, inspirasi konsep, dan update terbaru dari tim kami.')) ?></p>
    </div>
</section>

<section class="section-padding pt-4">
    <div class="container">
        <?php if (empty($posts)): ?>
            <div class="text-center py-5">
                <h3 class="mb-3"><?= h(get_setting('blog_empty_title', 'Belum ada artikel.')) ?></h3>
                <p class="text-muted-custom mb-0"><?= h(get_setting('blog_empty_desc', 'Artikel blog akan tampil di sini setelah dipublish dari admin.')) ?></p>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($posts as $post): ?>
                    <?php
                    $excerpt = trim((string) ($post['excerpt'] ?? ''));
                    if ($excerpt === '') {
                        $contentText = trim((string) $post['content']);
                        $excerpt = function_exists('mb_substr')
                            ? mb_substr($contentText, 0, 140) . '...'
                            : substr($contentText, 0, 140) . '...';
                    }
                    $cover = trim((string) ($post['cover_image'] ?? ''));
                    if ($cover === '') {
                        $cover = 'assets/img/placeholder-plain.svg';
                    }
                    ?>
                    <div class="col-md-6 col-lg-4 js-scroll">
                        <article class="card-playful h-100">
                            <a href="<?= BASE_URL . '/blog-detail.php?slug=' . urlencode($post['slug']) ?>" class="text-decoration-none">
                                <div class="media-uniform rounded mb-3">
                                    <img src="<?= asset($cover) ?>" alt="<?= h($post['title']) ?>">
                                </div>
                            </a>
                            <div class="small text-muted-custom mb-2">
                                <?= date('d M Y', strtotime($post['published_at'] ?: $post['created_at'])) ?>
                            </div>
                            <h3 class="h4 mb-2"><?= h($post['title']) ?></h3>
                            <p class="text-muted-custom mb-3"><?= h($excerpt) ?></p>
                            <a class="btn btn-outline-playful" href="<?= BASE_URL . '/blog-detail.php?slug=' . urlencode($post['slug']) ?>">
                                <?= h(get_setting('blog_readmore_text', 'Baca Selengkapnya')) ?>
                            </a>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
