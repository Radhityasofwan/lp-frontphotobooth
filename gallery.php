<?php
$page_title = 'Gallery Momen Seru';
require_once __DIR__ . '/header.php';
?>

<!-- Minimal Header / Wave Divider -->
<section class="bg-orange-gradient position-relative page-hero">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-10 text-center text-white">
        <span class="badge bg-white text-orange mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="ph-bold ph-images"></i> <?= h(get_setting('gallery_hero_badge', 'The Print Wall')) ?>
        </span>
        <h1 class="display-3 fw-bold mb-3 text-shadow-sm"><?= h(get_setting('gallery_hero_title', 'Gallery Momen Seru')) ?></h1>
        <p class="lead fw-medium opacity-75"><?= h(get_setting('gallery_hero_desc', 'Nggak ada tamu yang kaku di depan kamera Front Photobooth!')) ?></p>
    </div>
    <div class="wave-divider">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path fill="#ffffff"
                d="M0,32L80,37.3C160,43,320,53,480,53.3C640,53,800,43,960,32C1120,21,1280,11,1360,5.3L1440,0V60H0Z">
            </path>
        </svg>
    </div>
</section>

<!-- Scrapbook Gallery Board -->
<section class="section-padding bg-white pt-4">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-4 js-scroll">
                <div class="media-uniform">
                    <img src="<?= asset(get_setting('gallery_img_1', 'assets/img/placeholder-plain.svg')) ?>" alt="Gallery 1"
                        class="w-100 object-fit-cover">
                </div>
            </div>
            <div class="col-6 col-md-4 js-scroll">
                <div class="media-uniform">
                    <img src="<?= asset(get_setting('gallery_img_2', 'assets/img/placeholder-plain.svg')) ?>" alt="Gallery 2"
                        class="w-100 object-fit-cover">
                </div>
            </div>
            <div class="col-6 col-md-4 js-scroll">
                <div class="media-uniform">
                    <img src="<?= asset(get_setting('gallery_img_3', 'assets/img/placeholder-plain.svg')) ?>" alt="Gallery 3"
                        class="w-100 object-fit-cover">
                </div>
            </div>
            <div class="col-6 col-md-4 js-scroll">
                <div class="media-uniform">
                    <img src="<?= asset(get_setting('gallery_img_4', 'assets/img/placeholder-plain.svg')) ?>" alt="Gallery 4"
                        class="w-100 object-fit-cover">
                </div>
            </div>
            <div class="col-6 col-md-4 js-scroll">
                <div class="media-uniform">
                    <img src="<?= asset(get_setting('gallery_img_5', 'assets/img/placeholder-plain.svg')) ?>" alt="Gallery 5"
                        class="w-100 object-fit-cover">
                </div>
            </div>
            <div class="col-6 col-md-4 js-scroll">
                <div class="media-uniform">
                    <img src="<?= asset(get_setting('gallery_img_6', 'assets/img/placeholder-plain.svg')) ?>" alt="Gallery 6"
                        class="w-100 object-fit-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to action inside page -->
<section class="section-padding bg-alt position-relative overflow-hidden">
    <div class="container text-center position-relative z-10">
        <h2 class="display-6 fw-bold mb-4"><?= h(get_setting('gallery_cta_title', 'Pengen Acaramu Seseru Mereka?')) ?></h2>
        <a href="<?= h(get_setting('gallery_cta_link', 'https://wa.me/' . WA_NUMBER)) ?>" class="btn btn-playful">
            <i class="ph-bold ph-whatsapp-logo"></i> <?= h(get_setting('gallery_cta_text', 'Ngobrol Sama Mimim')) ?>
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
