<?php
// header.php
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

<nav id="mainNav" class="navbar navbar-expand-lg navbar-playful fixed-top py-3 bg-transparent">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
            <img src="<?= asset(get_setting('logo_path', 'assets/img/logo.png')) ?>" alt="Logo" height="30">
            Front Photobooth
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto my-2 my-lg-0">
                <li class="nav-item"><a class="nav-link" href="#paket">Paket</a></li>
                <li class="nav-item"><a class="nav-link" href="#galeri">Galeri</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
            </ul>
            <a href="<?= htmlspecialchars(get_setting('home_hero_cta_link', 'https://wa.me/' . WA_NUMBER)) ?>" class="btn btn-solid-oval ms-lg-4 mt-3 mt-lg-0">
                <i class="ph-bold ph-whatsapp-logo"></i> Booking
            </a>
        </div>
    </div>
</nav>