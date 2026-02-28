<?php
$page_title = 'Inspirasi Photobooth Event';
require_once __DIR__ . '/header.php';
?>

<!-- Minimal Header / Wave Divider -->
<section class="bg-orange-gradient position-relative" style="padding: 6rem 0 8rem 0;">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-10 text-center text-white">
        <span class="badge bg-white text-orange mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="ph-bold ph-lightbulb"></i> Ide Seru
        </span>
        <h1 class="display-3 fw-bold mb-3 text-shadow-sm">Inspirasi Desain Frame</h1>
        <p class="lead fw-medium opacity-75">Liat-liat dulu hasil karya Front Photobooth untuk berbagai macam event.</p>
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
                <button class="nav-link active bg-orange-gradient shadow text-white rounded-pill px-4">All
                    Style</button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4">Wedding / Engagement</button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4">Birthday</button>
            </li>
            <li class="nav-item">
                <button class="nav-link text-muted bg-alt rounded-pill px-4">Corporate</button>
            </li>
        </ul>

        <!-- Grid Cards -->
        <div class="row g-4 align-items-center justify-content-center mt-3">

            <div class="col-md-4 js-scroll">
                <div class="card-playful p-2 shadow-sm border tilt-1" style="border-radius:12px;">
                    <div class="bg-alt rounded d-flex align-items-center justify-content-center overflow-hidden"
                        style="height: 250px;">
                        <img src="<?= asset(get_setting('insp_1', 'https://picsum.photos/seed/fp_insp1/400/400')) ?>"
                            alt="Floral Minimalist" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <h6 class="fw-bold mt-3 mb-1 text-dark">Floral Minimalist Wedding</h6>
                    <p class="small text-muted mb-2">Photostrip 2R Template</p>
                </div>
            </div>

            <div class="col-md-4 js-scroll">
                <div class="card-playful p-2 shadow-lg border text-center"
                    style="border-radius:12px; transform:scale(1.05);">
                    <div class="bg-dark rounded d-flex align-items-center justify-content-center overflow-hidden"
                        style="height: 250px;">
                        <img src="<?= asset(get_setting('insp_2', 'https://picsum.photos/seed/fp_insp2/400/400')) ?>"
                            alt="Corporate Event" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <h6 class="fw-bold mt-3 mb-1 text-dark">Telkomsel Gala Dinner</h6>
                    <p class="small text-muted mb-2">Dark Corporate Postcard</p>
                </div>
            </div>

            <div class="col-md-4 js-scroll">
                <div class="card-playful p-2 shadow-sm border tilt-4" style="border-radius:12px;">
                    <div class="bg-alt rounded d-flex align-items-center justify-content-center overflow-hidden"
                        style="height: 250px;">
                        <img src="<?= asset(get_setting('insp_3', 'https://picsum.photos/seed/fp_insp3/400/400')) ?>"
                            alt="Birthday Sweet 17" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <h6 class="fw-bold mt-3 mb-1 text-dark">Aesthetics Sweet 17th</h6>
                    <p class="small text-muted mb-2">Polaroid Funky Style</p>
                </div>
            </div>

        </div>

        <div class="mt-5 pt-5 text-center js-scroll">
            <h4 class="mb-4">Udah Kebayang Konsep Eventnya?</h4>
            <a href="https://wa.me/<?= WA_NUMBER ?>" class="btn btn-outline-playful">Diskusi Konsep Sekarang</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>