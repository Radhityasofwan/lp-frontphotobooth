<?php
$page_title = 'Pilihan Layout Frame Templates';
require_once __DIR__ . '/header.php';
?>

<!-- Minimal Header / Wave Divider -->
<section class="bg-orange-gradient position-relative" style="padding: 6rem 0 8rem 0;">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-10 text-center text-white">
        <span class="badge bg-white text-orange mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="ph-bold ph-layout"></i> Pilih Style Foto Lo
        </span>
        <h1 class="display-3 fw-bold mb-3 text-shadow-sm">Template Layout Frame</h1>
        <p class="lead fw-medium opacity-75">Suka cetakan besar? Atau model strip lucu? Kita sedia semuanya!</p>
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
                    data-bs-toggle="pill">Postcard 4R</button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4" data-bs-toggle="pill">Photostrip
                    2R</button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4" data-bs-toggle="pill">Polaroid
                    Model</button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4" data-bs-toggle="pill">Trio Strip</button>
            </li>
        </ul>

        <!-- Layout Cards -->
        <div class="row g-5 align-items-center justify-content-center mt-3">

            <div class="col-md-6 col-lg-4 js-scroll">
                <div class="card-playful p-4 shadow tilt-2 border">
                    <h5 class="fw-bold mb-3"><i class="ph-bold ph-frame-corners text-orange"></i> Single 4R (1 Frame)
                    </h5>
                    <div class="bg-alt p-0 rounded text-center overflow-hidden position-relative"
                        style="height: 180px; border: 2px dashed #DDD;">
                        <img src="<?= asset(get_setting('template_1', 'https://picsum.photos/seed/fp_tpl1/400/400')) ?>"
                            alt="Satu Frame" class="w-100 h-100 object-fit-cover"
                            style="object-fit: cover; opacity: 0.9;">
                        <div
                            class="position-absolute bottom-0 start-50 translate-middle-x bg-dark text-white px-2 py-1 rounded-top small mb-0 opacity-75">
                            1 Foto Besar</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 js-scroll">
                <div class="card-playful p-4 shadow tilt-4 border">
                    <h5 class="fw-bold mb-3"><i class="ph-bold ph-columns text-orange"></i> Grid 3 (4R)</h5>
                    <div class="bg-alt p-0 rounded overflow-hidden" style="height: 180px; border: 2px dashed #DDD;">
                        <div class="row g-1 h-100 flex-nowrap flex-column">
                            <div class="col-12 h-50 bg-light border-bottom overflow-hidden p-0"><img
                                    src="<?= asset(get_setting('template_2a', 'https://picsum.photos/seed/fp_tpl2a/400/200')) ?>"
                                    class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Grid"></div>
                            <div class="col-12 h-50 d-flex p-0 gap-1">
                                <div class="w-50 h-100 bg-light border-end overflow-hidden p-0"><img
                                        src="<?= asset(get_setting('template_2b', 'https://picsum.photos/seed/fp_tpl2b/200/200')) ?>"
                                        class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Grid"></div>
                                <div class="w-50 h-100 bg-light overflow-hidden p-0"><img
                                        src="<?= asset(get_setting('template_2c', 'https://picsum.photos/seed/fp_tpl2c/200/200')) ?>"
                                        class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Grid"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 js-scroll">
                <div class="card-playful p-4 shadow tilt-1 border">
                    <h5 class="fw-bold mb-3"><i class="ph-bold ph-squares-four text-orange"></i> Grid 4 (4R)</h5>
                    <div class="bg-alt p-0 rounded overflow-hidden" style="height: 180px; border: 2px dashed #DDD;">
                        <div class="row h-100 g-1 flex-nowrap flex-column">
                            <div class="col-12 h-50 d-flex p-0 gap-1 mb-1">
                                <div class="w-50 h-100 bg-light border-bottom border-end overflow-hidden p-0"><img
                                        src="<?= asset(get_setting('template_3a', 'https://picsum.photos/seed/fp_tpl3a/200/200')) ?>"
                                        class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Grid"></div>
                                <div class="w-50 h-100 bg-light border-bottom overflow-hidden p-0"><img
                                        src="<?= asset(get_setting('template_3b', 'https://picsum.photos/seed/fp_tpl3b/200/200')) ?>"
                                        class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Grid"></div>
                            </div>
                            <div class="col-12 h-50 d-flex p-0 gap-1">
                                <div class="w-50 h-100 bg-light border-end overflow-hidden p-0"><img
                                        src="<?= asset(get_setting('template_3c', 'https://picsum.photos/seed/fp_tpl3c/200/200')) ?>"
                                        class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Grid"></div>
                                <div class="w-50 h-100 bg-light overflow-hidden p-0"><img
                                        src="<?= asset(get_setting('template_3d', 'https://picsum.photos/seed/fp_tpl3d/200/200')) ?>"
                                        class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Grid"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <p class="text-muted-custom mt-5 pt-4 js-scroll">
            <em>Semua template bisa di-kustomisasi menggunakan overlay brand/nama kamu!</em>
        </p>

    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>