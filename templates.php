<?php
$page_title = 'Pilihan Layout Frame Templates';
require_once __DIR__ . '/header.php';
?>

<!-- Minimal Header / Wave Divider -->
<section class="bg-orange-gradient position-relative page-hero">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-10 text-center text-white">
        <span class="badge bg-white text-orange mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="ph-bold ph-layout"></i> <?= h(get_setting('templates_hero_badge', 'Pilih Style Foto Lo')) ?>
        </span>
        <h1 class="display-3 fw-bold mb-3 text-shadow-sm"><?= h(get_setting('templates_hero_title', 'Template Layout Frame')) ?></h1>
        <p class="lead fw-medium opacity-75"><?= h(get_setting('templates_hero_desc', 'Suka cetakan besar? Atau model strip lucu? Kita sedia semuanya!')) ?></p>
    </div>
    <div class="wave-divider">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,32L80,37.3C160,43,320,53,480,53.3C640,53,800,43,960,32C1120,21,1280,11,1360,5.3L1440,0V60H0Z">
            </path>
        </svg>
    </div>
</section>

<!-- Templates Content -->
<section class="section-padding bg-white pt-5">
    <div class="container text-center">
        <!-- Filter Tabs Concept -->
        <ul class="nav nav-pills justify-content-center mb-5 pb-3 gap-2" id="template-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active bg-orange-gradient shadow text-white rounded-pill px-4"
                    data-bs-toggle="pill"><?= h(get_setting('templates_tab_1', 'Postcard 4R')) ?></button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4" data-bs-toggle="pill"><?= h(get_setting('templates_tab_2', 'Photostrip 2R')) ?></button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4" data-bs-toggle="pill"><?= h(get_setting('templates_tab_3', 'Polaroid Model')) ?></button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4" data-bs-toggle="pill"><?= h(get_setting('templates_tab_4', 'Trio Strip')) ?></button>
            </li>
        </ul>

        <!-- Layout Cards -->
        <div class="row g-5 align-items-center justify-content-center mt-3">

            <div class="col-md-6 col-lg-4 js-scroll">
                <div class="card-playful p-4 shadow tilt-2 border">
                    <h5 class="fw-bold mb-3"><i class="ph-bold ph-frame-corners text-orange"></i> <?= h(get_setting('templates_card_1_title', 'Single 4R (1 Frame)')) ?>
                    </h5>
                    <div class="bg-alt p-0 rounded text-center media-uniform"
                        style="border: 2px dashed #DDD;">
                        <img src="<?= asset(get_setting('templates_card_1_img', 'assets/img/placeholder-plain.svg')) ?>"
                            alt="Satu Frame" class="w-100 h-100 object-fit-cover"
                            style="object-fit: cover;">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 js-scroll">
                <div class="card-playful p-4 shadow tilt-4 border">
                    <h5 class="fw-bold mb-3"><i class="ph-bold ph-columns text-orange"></i> <?= h(get_setting('templates_card_2_title', 'Grid 3 (4R)')) ?></h5>
                    <div class="bg-alt p-0 rounded media-uniform" style="border: 2px dashed #DDD;">
                        <img src="<?= asset(get_setting('templates_card_2_img', 'assets/img/placeholder-plain.svg')) ?>"
                            class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Grid">
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 js-scroll">
                <div class="card-playful p-4 shadow tilt-1 border">
                    <h5 class="fw-bold mb-3"><i class="ph-bold ph-squares-four text-orange"></i> <?= h(get_setting('templates_card_3_title', 'Grid 4 (4R)')) ?></h5>
                    <div class="bg-alt p-0 rounded media-uniform" style="border: 2px dashed #DDD;">
                        <img src="<?= asset(get_setting('templates_card_3_img', 'assets/img/placeholder-plain.svg')) ?>"
                            class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Grid">
                    </div>
                </div>
            </div>

        </div>

        <p class="text-muted-custom mt-5 pt-4 js-scroll">
            <em><?= h(get_setting('templates_note', 'Semua template bisa di-kustomisasi menggunakan overlay brand/nama kamu!')) ?></em>
        </p>

    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
