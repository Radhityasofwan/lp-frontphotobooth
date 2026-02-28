<?php
$page_title = 'Gallery Momen Seru';
require_once __DIR__ . '/header.php';
?>

<!-- Minimal Header / Wave Divider -->
<section class="bg-orange-gradient position-relative" style="padding: 6rem 0 8rem 0;">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-10 text-center text-white">
        <span class="badge bg-white text-orange mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="ph-bold ph-images"></i> The Print Wall
        </span>
        <h1 class="display-3 fw-bold mb-3 text-shadow-sm">Gallery Momen Seru</h1>
        <p class="lead fw-medium opacity-75">Nggak ada tamu yang kaku di depan kamera Front Photobooth!</p>
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

        <div class="row g-5 align-items-center justify-content-center">

            <!-- Col 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="polaroid tilt-2 shadow-lg mb-5 js-scroll">
                    <div class="tape"></div>
                    <div class="d-flex align-items-center justify-content-center bg-dark text-white rounded-2 overflow-hidden"
                        style="height:250px;">
                        <img src="<?= asset(get_setting('gallery_1', 'https://picsum.photos/seed/fp_bday/400/400')) ?>"
                            alt="Birthday setup" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <div class="polaroid-text">Birthday Bash 17th</div>
                </div>

                <div class="photo-strip tilt-3 shadow float-anim ms-auto mb-4 js-scroll">
                    <div class="tape"></div>
                    <div class="d-flex align-items-center justify-content-center bg-alt rounded overflow-hidden"
                        style="width:200px; height:180px;">
                        <img src="<?= asset(get_setting('gallery_2', 'https://picsum.photos/seed/fp_smile1/300/300')) ?>"
                            alt="Smile" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <div class="d-flex align-items-center justify-content-center bg-alt rounded overflow-hidden"
                        style="width:200px; height:180px;">
                        <img src="<?= asset(get_setting('gallery_3', 'https://picsum.photos/seed/fp_smile2/300/300')) ?>"
                            alt="Fun" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                </div>
            </div>

            <!-- Col 2 -->
            <div class="col-md-6 col-lg-4 mt-lg-5">
                <div class="polaroid tilt-4 shadow-sm mb-5 float-anim-delay mx-auto js-scroll">
                    <div class="tape"></div>
                    <div class="d-flex align-items-center justify-content-center bg-secondary text-white rounded-2 overflow-hidden"
                        style="height:320px;">
                        <img src="<?= asset(get_setting('gallery_4', 'https://picsum.photos/seed/fp_gala/400/600')) ?>"
                            alt="Gala Dinner" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <div class="polaroid-text">Gala Dinner 2026</div>
                </div>

                <div class="polaroid tilt-1 shadow-lg mx-auto js-scroll">
                    <div class="tape"></div>
                    <div class="d-flex align-items-center justify-content-center bg-dark text-white rounded-2 overflow-hidden"
                        style="height:200px;">
                        <img src="<?= asset(get_setting('gallery_5', 'https://picsum.photos/seed/fp_outing/400/300')) ?>"
                            alt="Company Outing" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <div class="polaroid-text">Company Outing</div>
                </div>
            </div>

            <!-- Col 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="photo-strip tilt-2 shadow-lg float-anim ms-4 mb-5 js-scroll">
                    <div class="tape"></div>
                    <div class="d-flex align-items-center justify-content-center bg-dark rounded overflow-hidden"
                        style="width:160px; height:140px;">
                        <img src="<?= asset(get_setting('gallery_6', 'https://picsum.photos/seed/fp_wed1/300/300')) ?>"
                            alt="Wedding 1" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <div class="d-flex align-items-center justify-content-center bg-dark rounded overflow-hidden"
                        style="width:160px; height:140px;">
                        <img src="<?= asset(get_setting('gallery_7', 'https://picsum.photos/seed/fp_wed2/300/300')) ?>"
                            alt="Wedding 2" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <div class="d-flex align-items-center justify-content-center bg-dark rounded overflow-hidden"
                        style="width:160px; height:140px;">
                        <img src="<?= asset(get_setting('gallery_8', 'https://picsum.photos/seed/fp_wed3/300/300')) ?>"
                            alt="Wedding 3" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <div class="mt-1 text-center font-monospace fw-bold small text-muted">WEDDING</div>
                </div>

                <div class="polaroid tilt-4 shadow float-anim-delay js-scroll">
                    <div class="tape"></div>
                    <div class="d-flex align-items-center justify-content-center bg-alt text-dark rounded-2 border overflow-hidden"
                        style="height:220px;">
                        <img src="<?= asset(get_setting('gallery_9', 'https://picsum.photos/seed/fp_reunion/400/400')) ?>"
                            alt="Reunion" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                    </div>
                    <div class="polaroid-text">Reunion Vibes</div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Call to action inside page -->
<section class="section-padding bg-alt position-relative overflow-hidden">
    <div class="container text-center position-relative z-10">
        <h2 class="display-6 fw-bold mb-4">Pengen Acaramu Seseru Mereka?</h2>
        <a href="https://wa.me/<?= WA_NUMBER ?>" class="btn btn-playful">
            <i class="ph-bold ph-whatsapp-logo"></i> Ngobrol Sama Mimim
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>