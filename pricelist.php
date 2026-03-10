<?php
$page_title = 'Harga Paket Premium';
require_once __DIR__ . '/header.php';
?>

<!-- Minimal Header / Wave Divider -->
<section class="bg-orange-gradient position-relative page-hero">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-10 text-center text-white">
        <span class="badge bg-white text-orange mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="ph-bold ph-tag"></i> <?= h(get_setting('price_hero_badge', 'Harga Transparan')) ?>
        </span>
        <h1 class="display-3 fw-bold mb-3 text-shadow-sm"><?= h(get_setting('price_hero_title', 'Choose Your Experience')) ?></h1>
        <p class="lead fw-medium opacity-75"><?= h(get_setting('price_hero_desc', 'Nggak ada biaya siluman. Nggak ada hidden fee. Semua all-in beres.')) ?></p>
    </div>
    <div class="wave-divider">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,32L80,37.3C160,43,320,53,480,53.3C640,53,800,43,960,32C1120,21,1280,11,1360,5.3L1440,0V60H0Z">
            </path>
        </svg>
    </div>
</section>

<!-- Package Content -->
<section class="section-padding bg-white pt-5 pb-5">
    <div class="container">

        <div class="row g-4 justify-content-center align-items-stretch position-relative">
            <!-- Background Decoration Box behind cards -->
            <div
                class="position-absolute top-50 start-50 translate-middle bg-alt rounded-4 w-75 h-75 z-0 d-none d-lg-block">
            </div>

            <div class="col-lg-4 js-scroll z-10">
                <div class="card-playful tilt-2 border-0 shadow-sm px-4">
                    <div class="tape"></div>
                    <div class="text-center mb-4">
                        <div class="icon-circle shadow-sm" style="width:60px; height:60px;"><i
                                class="ph-fill ph-coffee fs-3"></i></div>
                        <h4 class="mt-3"><?= h(get_setting('price_pkg_1_title', 'Basic Experience')) ?></h4>
                        <p class="text-muted-custom small mb-0 mt-2"><?= h(get_setting('price_pkg_1_desc', 'Sempurna buat chill intimate party yang cozy.')) ?></p>
                    </div>
                    <hr class="opacity-10 mb-4">
                    <ul class="checklist-custom small mb-4 font-weight-bold">
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_1_list_1', 'Durasi santai 2–3 Jam')) ?></li>
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_1_list_2', 'Print sesuai kuota')) ?></li>
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_1_list_3', '1 Operator Friendly')) ?></li>
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_1_list_4', 'Kacamata Props Standard')) ?></li>
                    </ul>
                    <a href="<?= h(get_setting('price_pkg_1_btn_link', 'https://wa.me/' . WA_NUMBER)) ?>"
                        class="btn btn-outline-playful w-100 justify-content-center"><?= h(get_setting('price_pkg_1_btn_text', 'Pilih Paket Ini')) ?></a>
                </div>
            </div>

            <div class="col-lg-4 js-scroll z-20">
                <div class="card-playful border border-warning-subtle shadow-lg px-4">
                    <div class="package-badge"><i class="ph-fill ph-star me-1"></i> <?= h(get_setting('price_pkg_2_badge', 'Most Popular')) ?></div>
                    <div class="text-center mb-4 mt-2">
                        <div class="icon-circle shadow"
                            style="width:70px; height:70px; background:var(--fp-gradient); color:#FFF;"><i
                                class="ph-fill ph-rocket-launch fs-2"></i></div>
                        <h4 class="mt-3"><?= h(get_setting('price_pkg_2_title', 'Full Experience')) ?></h4>
                        <p class="text-muted-custom small mb-0 mt-2"><?= h(get_setting('price_pkg_2_desc', 'Maksimal buat birthday megah atau wedding rame!')) ?></p>
                    </div>
                    <hr class="opacity-10 border-orange mb-4">
                    <ul class="checklist-custom small mb-4">
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_2_list_1', 'Gas terus 3–4 Jam')) ?></li>
                        <li class="bg-alt p-2 rounded fw-bold text-dark"><i
                                class="ph-bold ph-infinity text-orange me-2"></i> <?= h(get_setting('price_pkg_2_list_2', 'Unlimited Print, Bebas!')) ?></li>
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_2_list_3', '2 Operator (Tukang foto + asisten)')) ?></li>
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_2_list_4', 'Desain Custom Overlay Bebas')) ?></li>
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_2_list_5', 'Props Lucu Super Lengkap')) ?></li>
                    </ul>
                    <a href="<?= h(get_setting('price_pkg_2_btn_link', 'https://wa.me/' . WA_NUMBER)) ?>" data-track="click_cta_booking"
                        class="btn btn-playful w-100 justify-content-center border-0 shadow-lg"><?= h(get_setting('price_pkg_2_btn_text', 'Booking Sekarang')) ?></a>
                </div>
            </div>

            <div class="col-lg-4 js-scroll z-10">
                <div class="card-playful tilt-3 border-0 shadow-sm px-4">
                    <div class="tape"></div>
                    <div class="text-center mb-4">
                        <div class="icon-circle shadow-sm" style="width:60px; height:60px;"><i
                                class="ph-fill ph-buildings fs-3"></i></div>
                        <h4 class="mt-3"><?= h(get_setting('price_pkg_3_title', 'Brand Experience')) ?></h4>
                        <p class="text-muted-custom small mb-0 mt-2"><?= h(get_setting('price_pkg_3_desc', 'Khusus corporate activation, brand launch.')) ?></p>
                    </div>
                    <hr class="opacity-10 mb-4">
                    <ul class="checklist-custom small mb-4 font-weight-bold">
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_3_list_1', 'Waktu Custom via Run-down')) ?></li>
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_3_list_2', 'Mesin & Frame Full Branding')) ?></li>
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_3_list_3', 'Tim Sangat Dedicated')) ?></li>
                        <li><i class="ph-bold ph-check text-orange"></i> <?= h(get_setting('price_pkg_3_list_4', 'Lead Data Capture & Scan QR')) ?></li>
                    </ul>
                    <a href="<?= h(get_setting('price_pkg_3_btn_link', 'https://wa.me/' . WA_NUMBER)) ?>"
                        class="btn btn-outline-playful w-100 justify-content-center"><?= h(get_setting('price_pkg_3_btn_text', 'Diskusi Konsep')) ?></a>
                </div>
            </div>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
