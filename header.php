<?php
// header.php
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($page_title ?? 'Front Photobooth') ?> | <?= h(get_setting('seo_title', 'Front Photobooth')) ?></title>
    <meta name="description" content="<?= h(get_setting('seo_desc', 'Photobooth modern untuk event Anda.')) ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Google Fonts (dari index.php) -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&display=swap" rel="stylesheet">

    <!-- Anda bisa membuat file CSS kustom di sini -->
    <link rel="stylesheet" href="<?= asset('assets/css/frontphotobooth.css') ?>">
</head>
<body>
<?php
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$isHome = $currentPage === 'index.php';
$navLogo = get_setting('nav_logo', 'assets/img/placeholder-plain.svg');
$navBrand = get_setting('nav_brand_text', 'Front Photobooth');
$navCtaText = get_setting('nav_cta_text', 'Booking');
$navCtaLink = get_setting('nav_cta_link', get_setting('home_hero_cta_link', 'https://wa.me/' . WA_NUMBER));
?>

<nav id="mainNav" class="navbar navbar-expand-lg navbar-playful fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
            <img src="<?= asset($navLogo) ?>" alt="Logo" height="30">
            <?= h($navBrand) ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto my-2 my-lg-0">
                <li class="nav-item"><a class="nav-link <?= $isHome ? 'active' : '' ?>" href="<?= BASE_URL ?>"><?= h(get_setting('nav_home_text', 'Beranda')) ?></a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'pricelist.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/pricelist.php"><?= h(get_setting('nav_paket_text', 'Paket')) ?></a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'templates.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/templates.php"><?= h(get_setting('nav_templates_text', 'Templates')) ?></a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'inspirasi.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/inspirasi.php"><?= h(get_setting('nav_inspirasi_text', 'Inspirasi')) ?></a></li>
                <li class="nav-item"><a class="nav-link <?= $currentPage === 'gallery.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/gallery.php"><?= h(get_setting('nav_gallery_text', 'Galeri')) ?></a></li>
                <li class="nav-item"><a class="nav-link <?= ($currentPage === 'blog.php' || $currentPage === 'blog-detail.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>/blog.php"><?= h(get_setting('nav_blog_text', 'Blog')) ?></a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $isHome ? '#kontak' : BASE_URL . '/index.php#kontak' ?>"><?= h(get_setting('nav_contact_text', 'Kontak')) ?></a></li>
            </ul>
            <a href="<?= htmlspecialchars($navCtaLink) ?>" class="btn btn-solid-oval ms-lg-4 mt-3 mt-lg-0">
                <i class="ph-bold ph-whatsapp-logo"></i> <?= h($navCtaText) ?>
            </a>
        </div>
    </div>
</nav>
