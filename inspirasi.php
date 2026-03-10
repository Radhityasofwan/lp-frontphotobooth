<?php
$page_title = 'Inspirasi Photobooth Event';
require_once __DIR__ . '/header.php';
?>

<!-- Minimal Header / Wave Divider -->
<section class="bg-orange-gradient position-relative page-hero">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-10 text-center text-white">
        <span class="badge bg-white text-orange mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="ph-bold ph-lightbulb"></i> <?= h(get_setting('insp_hero_badge', 'Ide Seru')) ?>
        </span>
        <h1 class="display-3 fw-bold mb-3 text-shadow-sm"><?= h(get_setting('insp_hero_title', 'Inspirasi Desain Frame')) ?></h1>
        <p class="lead fw-medium opacity-75"><?= h(get_setting('insp_hero_desc', 'Liat-liat dulu hasil karya Front Photobooth untuk berbagai macam event.')) ?></p>
    </div>
    <div class="wave-divider">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,32L80,37.3C160,43,320,53,480,53.3C640,53,800,43,960,32C1120,21,1280,11,1360,5.3L1440,0V60H0Z">
            </path>
        </svg>
    </div>
</section>

<!-- Inspiration Content -->
<section class="section-padding bg-white pt-5">
    <div class="container text-center">

        <!-- Filter Tabs Concept -->
        <ul class="nav nav-pills justify-content-center mb-5 pb-3 gap-2" role="tablist">
            <li class="nav-item">
                <button class="nav-link active bg-orange-gradient shadow text-white rounded-pill px-4"><?= h(get_setting('insp_tab_1', 'All Style')) ?></button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4"><?= h(get_setting('insp_tab_2', 'Wedding / Engagement')) ?></button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4"><?= h(get_setting('insp_tab_3', 'Birthday')) ?></button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4"><?= h(get_setting('insp_tab_4', 'Corporate')) ?></button>
            </li>
        </ul>

        <!-- Grid Cards -->
        <div class="row g-4 align-items-center justify-content-center mt-3">

            <div class="col-md-4 js-scroll">
                <div class="card-playful p-2 shadow-sm border tilt-1" style="border-radius:12px;">
                    <div class="bg-alt rounded d-flex align-items-center justify-content-center media-uniform">
                        <img src="<?= asset(get_setting('insp_card_1_img', 'assets/img/placeholder-plain.svg')) ?>"
                            alt="Floral Minimalist" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <h6 class="fw-bold mt-3 mb-1 text-dark"><?= h(get_setting('insp_card_1_title', 'Floral Minimalist Wedding')) ?></h6>
                    <p class="small text-muted mb-2"><?= h(get_setting('insp_card_1_subtitle', 'Photostrip 2R Template')) ?></p>
                </div>
            </div>

            <div class="col-md-4 js-scroll">
                <div class="card-playful p-2 shadow-lg border text-center"
                    style="border-radius:12px;">
                    <div class="bg-dark rounded d-flex align-items-center justify-content-center media-uniform">
                        <img src="<?= asset(get_setting('insp_card_2_img', 'assets/img/placeholder-plain.svg')) ?>"
                            alt="Corporate Event" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <h6 class="fw-bold mt-3 mb-1 text-dark"><?= h(get_setting('insp_card_2_title', 'Telkomsel Gala Dinner')) ?></h6>
                    <p class="small text-muted mb-2"><?= h(get_setting('insp_card_2_subtitle', 'Dark Corporate Postcard')) ?></p>
                </div>
            </div>

            <div class="col-md-4 js-scroll">
                <div class="card-playful p-2 shadow-sm border tilt-4" style="border-radius:12px;">
                    <div class="bg-alt rounded d-flex align-items-center justify-content-center media-uniform">
                        <img src="<?= asset(get_setting('insp_card_3_img', 'assets/img/placeholder-plain.svg')) ?>"
                            alt="Birthday Sweet 17" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <h6 class="fw-bold mt-3 mb-1 text-dark"><?= h(get_setting('insp_card_3_title', 'Aesthetics Sweet 17th')) ?></h6>
                    <p class="small text-muted mb-2"><?= h(get_setting('insp_card_3_subtitle', 'Polaroid Funky Style')) ?></p>
                </div>
            </div>

        </div>

        <div class="mt-5 pt-5 text-center js-scroll">
            <h4 class="mb-4"><?= h(get_setting('insp_cta_title', 'Udah Kebayang Konsep Eventnya?')) ?></h4>
            <a href="<?= h(get_setting('insp_cta_link', 'https://wa.me/' . WA_NUMBER)) ?>" class="btn btn-outline-playful"><?= h(get_setting('insp_cta_text', 'Diskusi Konsep Sekarang')) ?></a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
