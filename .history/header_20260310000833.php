<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?>Front Photobooth - Premium Fun Photobooth</title>
    <meta name="description"
        content="Front Photobooth mengubah event kamu menjadi momen seru, interaktif, dan tak terlupakan dengan layanan premium.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <link rel="stylesheet" href="<?= asset('assets/css/frontphotobooth.css') ?>">
</head>

<body class="bg-white">

    <!-- NAVIGATION BAR -->
    <nav id="mainNav" class="navbar navbar-expand-lg navbar-playful fixed-top py-3 bg-transparent">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark d-flex align-items-center gap-2" href="index.php">
                <div class="icon-circle shadow-sm m-0"
                    style="width:40px; height:40px; background:var(--fp-gradient); color:#FFF;">
                    <i class="ph-fill ph-camera fs-5"></i>
                </div>
                <span style="font-family:'Outfit', sans-serif; letter-spacing: -0.5px;">FRONT PHOTOBOOTH</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="ph-bold ph-list fs-2 text-dark"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-1 gap-lg-3 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>"
                            href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'gallery.php') ? 'active' : '' ?>"
                            href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'templates.php') ? 'active' : '' ?>"
                            href="templates.php">Frame Template</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'inspirasi.php') ? 'active' : '' ?>"
                            href="inspirasi.php">Inspirasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'pricelist.php') ? 'active' : '' ?>"
                            href="pricelist.php">Pricelist</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <a href="snap.php" class="btn-outline-oval">
                        <span>Snap & Smile</span>
                        <span style="color: #f77b0f;">✨</span>
                    </a>
                    <a href="https://frontphotobooth.com" class="btn-solid-oval">Booking <i
                            class="ph-bold ph-calendar-check"></i></a>
                </div>
            </div>
        </div>
    </nav>