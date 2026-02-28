<?php require_once __DIR__ . '/config.php'; ?>
<?php
$page_title = 'Home';
require_once __DIR__ . '/header.php';
?>

<!-- 1. HERO SECTION -->
<section class="hero-section bg-orange-gradient">
  <div class="hero-overlay"></div>

  <!-- Decorative organic background blob -->
  <div class="shape-blob bg-white float-anim"
    style="width: 400px; height: 400px; top: -100px; left: -100px; opacity: 0.1;"></div>

  <div class="container hero-content">
    <div class="row align-items-center">

      <div class="col-lg-7 text-center text-lg-start mb-5 mb-lg-0 js-scroll">
        <span
          class="badge bg-white text-dark mb-4 px-4 py-2 rounded-pill letter-spacing-1 fw-bold text-uppercase shadow-sm">
          <i class="ph ph-camera-rotate align-middle me-1 text-orange"></i>
          <?= h(get_setting('home_hero_badge', 'We Capture Energy, Not Just Photos')) ?>
        </span>
        <h1 class="display-3 mb-4 text-white text-shadow-hero" style="line-height: 1.15;">
          <?= nl2br(h(get_setting('home_hero_title', "Bukan Sekadar Foto.\nIni Pengalaman Seru di Event Kamu."))) ?>
        </h1>
        <p class="lead mb-5 text-white fw-medium text-shadow-sm"
          style="opacity: 0.95; max-width: 550px; margin: 0 auto 0 0;">
          <?= h(get_setting('home_hero_desc', 'Photobooth modern hasil instan + props premium, bikin semua tamu betah bergaya. Waktunya buat acaramu lebih hidup!')) ?>
        </p>
        <div class="d-flex justify-content-center justify-content-lg-start flex-wrap gap-3">
          <a href="<?= htmlspecialchars(get_setting('home_hero_cta_link', 'https://frontphotobooth.com')) ?>"
            data-track="click_cta_booking" class="btn btn-playful bg-white text-dark shadow-lg">
            <?= h(get_setting('home_hero_cta_text', 'Cek Ketersediaan Event')) ?> <i class="ph-bold ph-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- Photobooth Strip Illustration & Floating Elements -->
      <div class="col-lg-5 position-relative d-none d-lg-block js-scroll">
        <i class="ph-fill ph-shooting-star text-white position-absolute float-anim z-10"
          style="font-size: 3rem; top: -20px; right: 40px; opacity: 0.8; transform: rotate(15deg);"></i>
        <i class="ph-fill ph-camera text-white position-absolute float-anim-delay"
          style="font-size: 4rem; bottom: -20px; left: 10px; opacity: 0.2; transform: rotate(-15deg);"></i>

        <div class="d-flex justify-content-center position-relative">
          <div class="photo-strip tilt-2 float-anim z-20 mx-auto" style="border-radius: 8px;">
            <div class="tape"></div>
            <div class="d-flex align-items-center justify-content-center bg-dark rounded text-white-50 overflow-hidden"
              style="width: 190px; height: 140px;">
              <img src="<?= asset(get_setting('home_hero_1', 'https://picsum.photos/seed/fp_home1/300/200')) ?>"
                class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Hero">
            </div>
            <div class="d-flex align-items-center justify-content-center bg-dark rounded text-white-50 overflow-hidden"
              style="width: 190px; height: 140px;">
              <img src="<?= asset(get_setting('home_hero_2', 'https://picsum.photos/seed/fp_home2/300/200')) ?>"
                class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Hero">
            </div>
            <div class="d-flex align-items-center justify-content-center bg-dark rounded text-white-50 overflow-hidden"
              style="width: 190px; height: 140px;">
              <img src="<?= asset(get_setting('home_hero_3', 'https://picsum.photos/seed/fp_home3/300/200')) ?>"
                class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Hero">
            </div>
            <div class="mt-2 text-center"
              style="font-family: 'Outfit'; font-weight: 800; font-size: 0.85rem; color: #111; letter-spacing: 2px;">
              FRONT PHOTOBOOTH
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Wave Bottom (White) -->
  <div class="wave-divider">
    <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
      <path fill="#ffffff"
        d="M0,64L80,69.3C160,75,320,85,480,74.7C640,64,800,32,960,26.7C1120,21,1280,43,1360,53.3L1440,64V120H0Z">
      </path>
    </svg>
  </div>
</section>

<!-- 2. PROBLEM & 3. CORE IDEA (White Background) -->
<section class="section-padding-bottom-0 pt-5 bg-white position-relative">
  <div class="container text-center">

    <!-- Background decorative shape -->
    <div class="shape-blob"
      style="width:300px; height:300px; background:var(--fp-primary); right:-100px; top:200px; opacity:0.05;"></div>

    <!-- Problem -->
    <div class="mb-5 pb-5 js-scroll">
      <h2 class="display-5 mb-5 align-items-center justify-content-center d-flex gap-3">
        <?= h(get_setting('home_prob_title', 'Event Ramai, Tapi Kurang Berkesan?')) ?> <i
          class="ph-fill ph-smiley-sad text-muted opacity-50"></i>
      </h2>
      <div class="d-inline-block p-4 bg-alt rounded-4 tilt-3 mb-4 shadow-sm border border-light position-relative">
        <i class="ph-fill ph-quotes text-orange position-absolute"
          style="font-size: 2rem; top:-15px; left:-15px; opacity:0.8;"></i>
        <h4 class="text-muted-custom fst-italic m-0 px-3 py-1 text-dark">
          <?= h(get_setting('home_prob_quote', 'Datang. Makan. Pulang.')) ?>
        </h4>
      </div>
      <p class="h4 text-orange mb-3 mt-4 fw-bold">
        <?= h(get_setting('home_prob_sub', 'Front Photobooth siap merubah suasana!')) ?>
      </p>
    </div>

    <h2 class="display-6 mb-5 mt-5 js-scroll">
      <?= get_setting('home_core_title', 'Kami Tidak Menjual Foto.<br><span class="text-orange">Kami Menciptakan Serunya Momen!</span>') ?>
    </h2>

    <div class="row g-4 mt-2 justify-content-center">
      <div class="col-md-4 js-scroll">
        <div class="card-playful text-center tilt-1">
          <div class="tape"></div>
          <div class="icon-circle shadow-sm"><i class="ph-fill ph-chats-circle ph-icon-xl"></i></div>
          <h3 class="mb-3"><?= h(get_setting('home_core_1_title', 'Interaction')) ?></h3>
          <p class="text-muted-custom mb-0 small">
            <?= h(get_setting('home_core_1_desc', 'Memecah rasa canggung, buat tamu lebih berani dan lepas buat tampil interaktif!')) ?>
          </p>
        </div>
      </div>
      <div class="col-md-4 js-scroll z-10">
        <div class="card-playful text-center shadow-lg" style="transform: scale(1.05);">
          <div class="icon-circle shadow-sm" style="background:var(--fp-gradient); color:#FFF;"><i
              class="ph-fill ph-sparkle ph-icon-xl"></i></div>
          <h3 class="mb-3"><?= h(get_setting('home_core_2_title', 'Experience')) ?></h3>
          <p class="text-muted-custom mb-0 small">
            <?= h(get_setting('home_core_2_desc', 'Hiburan utama yang bikin semua tamu dandan rapi merasa sangat dihargai keberadaannya.')) ?>
          </p>
        </div>
      </div>
      <div class="col-md-4 js-scroll">
        <div class="card-playful text-center tilt-2">
          <div class="tape"></div>
          <div class="icon-circle shadow-sm"><i class="ph-fill ph-polaroid-camera ph-icon-xl"></i></div>
          <h3 class="mb-3"><?= h(get_setting('home_core_3_title', 'Memory')) ?></h3>
          <p class="text-muted-custom mb-0 small">
            <?= h(get_setting('home_core_3_desc', 'Suvenir fisik premium yang bakal dipajang dan disimpan terus bertahun-tahun.')) ?>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Padding before wave -->
  <div style="height: 120px;"></div>

  <!-- Wave Bottom (Light Soft Orange) -->
  <div class="wave-divider">
    <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
      <path fill="var(--fp-bg-alt)"
        d="M0,32L60,37.3C120,43,240,53,360,53.3C480,53,600,43,720,37.3C840,32,960,32,1080,37.3C1200,43,1320,53,1380,58.7L1440,64V120H0Z">
      </path>
    </svg>
  </div>
</section>

<!-- 4. SIGNATURE EXPERIENCE & 5. SERVICE BREAKDOWN (Light Orange Bg) -->
<section class="section-padding bg-alt position-relative">
  <div class="container">
    <!-- Floating decor -->
    <i class="ph-duotone ph-film-strip text-orange position-absolute float-anim"
      style="font-size: 8rem; top: 10%; right: 5%; opacity: 0.05;"></i>

    <div class="row align-items-center g-5">
      <div class="col-lg-6 js-scroll position-relative pe-lg-5">

        <div class="polaroid tilt-3 shadow-lg d-none d-md-inline-block float-anim"
          style="position:absolute; right:-30px; top:-50px; z-index:15;">
          <div class="tape"></div>
          <div class="d-flex align-items-center justify-content-center bg-dark text-white rounded-2 overflow-hidden"
            style="width:220px; height:220px;">
            <img src="<?= asset(get_setting('home_props', 'https://picsum.photos/seed/fp_home4/400/400')) ?>"
              class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Props">
          </div>
        </div>

        <div class="card-playful shadow-sm p-4 pt-5 pb-5">
          <span class="badge bg-white text-orange border border-warning-subtle mb-3 px-3 py-2 rounded-pill shadow-sm">
            <i class="ph-bold ph-star"></i> <?= h(get_setting('home_sig_badge', 'The Front Way')) ?>
          </span>
          <h2 class="mb-4 display-6"><?= h(get_setting('home_sig_title', 'Signature Experience')) ?></h2>
          <ul class="checklist-custom fs-6 mb-4">
            <li><i class="ph-bold ph-check-circle"></i>
              <?= h(get_setting('home_sig_list_1', 'System driven: Alur teratur, antrian rapih')) ?></li>
            <li><i class="ph-bold ph-check-circle"></i>
              <?= h(get_setting('home_sig_list_2', 'Consistent quality: Studio Lighting mantap, foto anti kusam!')) ?>
            </li>
            <li><i class="ph-bold ph-check-circle"></i>
              <?= h(get_setting('home_sig_list_3', 'Curated team: Kru asik & pro-aktif bantu arahin gaya')) ?></li>
            <li><i class="ph-bold ph-check-circle"></i>
              <?= h(get_setting('home_sig_list_4', 'Instant Print: Cetakan kilat & warna solid')) ?></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-6 js-scroll ps-lg-5 position-relative z-10">
        <h3 class="mb-4 display-6"><?= h(get_setting('home_srv_title', 'Service Breakdown')) ?></h3>
        <p class="text-muted-custom mb-5">
          <?= h(get_setting('home_srv_desc', 'Sudah include semuanya, tinggal masuk *frame* dan siapkan posenya.')) ?>
        </p>
        <div class="d-flex flex-wrap gap-3 mb-5">
          <span class="badge bg-white text-dark border p-2 px-3 rounded-pill shadow-sm fs-6 fw-medium"><i
              class="ph-fill ph-camera align-middle me-1 text-orange"></i>
            <?= h(get_setting('home_srv_badge_1', 'Mesin Photobooth Pro')) ?></span>
          <span class="badge bg-white text-dark border p-2 px-3 rounded-pill shadow-sm fs-6 fw-medium"><i
              class="ph-fill ph-users align-middle me-1 text-orange"></i>
            <?= h(get_setting('home_srv_badge_2', 'Kru Interaktif')) ?></span>
          <span class="badge bg-white text-dark border p-2 px-3 rounded-pill shadow-sm fs-6 fw-medium"><i
              class="ph-fill ph-printer align-middle me-1 text-orange"></i>
            <?= h(get_setting('home_srv_badge_3', 'Cetakan 4R/Strip')) ?></span>
          <span class="badge bg-white text-dark border p-2 px-3 rounded-pill shadow-sm fs-6 fw-medium"><i
              class="ph-fill ph-palette align-middle me-1 text-orange"></i>
            <?= h(get_setting('home_srv_badge_4', 'Frame Custom Theme')) ?></span>
          <span class="badge bg-white text-dark border p-2 px-3 rounded-pill shadow-sm fs-6 fw-medium"><i
              class="ph-fill ph-cloud-arrow-down align-middle me-1 text-orange"></i>
            <?= h(get_setting('home_srv_badge_5', 'Softcopy G-Drive/QR')) ?></span>
          <span class="badge bg-white text-dark border p-2 px-3 rounded-pill shadow-sm fs-6 fw-medium"><i
              class="ph-fill ph-sunglasses align-middle me-1 text-orange"></i>
            <?= h(get_setting('home_srv_badge_6', 'Free Fun Props')) ?></span>
        </div>
        <div class="d-flex align-items-center gap-3">
          <div class="icon-circle m-0 bg-white shadow-sm" style="width:50px; height:50px;"><i
              class="ph-bold ph-lightning text-orange fs-4"></i></div>
          <h4 class="text-orange fs-5 m-0 lh-base">
            <?= nl2br(h(get_setting('home_srv_quote', "Kamu urus tamu yang datang.\nKami urus keceriaannya!"))) ?>
          </h4>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Diagonal Transition -->
<div class="position-relative" style="height: 100px; background: #FFFFFF; clip-path: polygon(0 0, 100% 100%, 100% 0);">
  <div style="background: var(--fp-bg-alt); width: 100%; height: 100%;"></div>
</div>

<!-- 6. PACKAGE -->
<section id="paket" class="section-padding bg-white pb-5">
  <div class="container">

    <div class="shape-blob"
      style="width:400px; height:400px; background:var(--fp-primary); left:-150px; top:50px; opacity:0.04;"></div>

    <div class="text-center mb-5 js-scroll pb-4">
      <h2 class="display-4 mb-3"><?= h(get_setting('home_pkg_title', 'Pilihan Service Kita')) ?></h2>
      <p class="lead text-muted-custom">
        <?= h(get_setting('home_pkg_desc', 'Bisa custom sesuka hati sesuai skala & konsep acara kamu.')) ?>
      </p>
    </div>

    <div class="row g-4 justify-content-center align-items-stretch position-relative">
      <!-- Background Decoration Box behind cards -->
      <div class="position-absolute top-50 start-50 translate-middle bg-alt rounded-4 w-75 h-75 z-0 d-none d-lg-block">
      </div>

      <div class="col-lg-4 js-scroll z-10">
        <div class="card-playful tilt-2 border-0 shadow-sm px-4">
          <div class="tape"></div>
          <div class="text-center mb-4">
            <div class="icon-circle shadow-sm" style="width:60px; height:60px;"><i class="ph-fill ph-coffee fs-3"></i>
            </div>
            <h4 class="mt-3"><?= h(get_setting('home_pkg_1_title', 'Basic Experience')) ?></h4>
            <p class="text-muted-custom small mb-0 mt-2">
              <?= h(get_setting('home_pkg_1_desc', 'Sempurna buat chill intimate party yang cozy.')) ?>
            </p>
          </div>
          <hr class="opacity-10 mb-4">
          <ul class="checklist-custom small mb-4 font-weight-bold">
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_1_list_1', 'Durasi santai 2–3 Jam')) ?></li>
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_1_list_2', 'Print sesuai kuota')) ?></li>
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_1_list_3', '1 Operator Friendly')) ?></li>
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_1_list_4', 'Kacamata Props Standard')) ?></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-4 js-scroll z-20">
        <div class="card-playful border border-warning-subtle shadow-lg px-4" style="transform: scale(1.08);">
          <div class="package-badge"><i class="ph-fill ph-star me-1"></i>
            <?= h(get_setting('home_pkg_2_badge', 'Most Popular')) ?></div>
          <div class="text-center mb-4 mt-2">
            <div class="icon-circle shadow" style="width:70px; height:70px; background:var(--fp-gradient); color:#FFF;">
              <i class="ph-fill ph-rocket-launch fs-2"></i>
            </div>
            <h4 class="mt-3"><?= h(get_setting('home_pkg_2_title', 'Full Experience')) ?></h4>
            <p class="text-muted-custom small mb-0 mt-2">
              <?= h(get_setting('home_pkg_2_desc', 'Maksimal buat birthday megah atau wedding rame!')) ?>
            </p>
          </div>
          <hr class="opacity-10 border-orange mb-4">
          <ul class="checklist-custom small mb-4">
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_2_list_1', 'Gas terus 3–4 Jam')) ?></li>
            <li class="bg-alt p-2 rounded fw-bold text-dark"><i class="ph-bold ph-infinity text-orange me-2"></i>
              <?= h(get_setting('home_pkg_2_list_2', 'Unlimited Print, Bebas!')) ?></li>
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_2_list_3', '2 Operator (Tukang foto + asisten)')) ?></li>
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_2_list_4', 'Desain Custom Overlay Bebas')) ?></li>
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_2_list_5', 'Props Lucu Super Lengkap')) ?></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-4 js-scroll z-10">
        <div class="card-playful tilt-3 border-0 shadow-sm px-4">
          <div class="tape"></div>
          <div class="text-center mb-4">
            <div class="icon-circle shadow-sm" style="width:60px; height:60px;"><i
                class="ph-fill ph-buildings fs-3"></i></div>
            <h4 class="mt-3"><?= h(get_setting('home_pkg_3_title', 'Brand Experience')) ?></h4>
            <p class="text-muted-custom small mb-0 mt-2">
              <?= h(get_setting('home_pkg_3_desc', 'Khusus corporate activation, brand launch.')) ?>
            </p>
          </div>
          <hr class="opacity-10 mb-4">
          <ul class="checklist-custom small mb-4 font-weight-bold">
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_3_list_1', 'Waktu Custom via Run-down')) ?></li>
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_3_list_2', 'Mesin & Frame Full Branding')) ?></li>
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_3_list_3', 'Tim Sangat Dedicated')) ?></li>
            <li><i class="ph-bold ph-check text-orange"></i>
              <?= h(get_setting('home_pkg_3_list_4', 'Lead Data Capture & Scan QR')) ?></li>
          </ul>
        </div>
      </div>

    </div>

    <div class="text-center mt-5 pt-5 js-scroll">
      <a href="<?= htmlspecialchars(get_setting('home_pkg_cta_link', 'https://wa.me/' . WA_NUMBER)) ?>" target="_blank"
        data-track="click_cta_whatsapp" class="btn btn-playful">
        <i class="ph-bold ph-whatsapp-logo"></i>
        <?= h(get_setting('home_pkg_cta_text', 'Diskusi Konsep via WhatsApp')) ?>
      </a>
    </div>
  </div>
</section>

<!-- 7. PROOF / GALLERY (SCRAPBOOK STYLE) -->
<section class="section-padding bg-alt overflow-hidden position-relative border-top border-bottom border-light">
  <!-- Top Curve Divider -->
  <div class="wave-divider-top" style="top: -2px;">
    <svg viewBox="0 0 1440 40" preserveAspectRatio="none">
      <path fill="#ffffff" d="M0,0L1440,20L1440,40L0,40Z"></path>
    </svg>
  </div>

  <div class="container text-center position-relative z-10 mt-4">
    <h2 class="display-5 mb-3 text-dark js-scroll"><?= h(get_setting('home_scrap_title', 'Scrapbook Momen Seru')) ?>
    </h2>
    <p class="text-muted-custom mb-5 js-scroll">
      <?= h(get_setting('home_scrap_desc', 'Nggak ada tamu yang kaku. Semua pasti keluar karakter aslinya!')) ?>
    </p>

    <div class="row g-4 justify-content-center align-items-center js-scroll position-relative"
      style="min-height:350px;">
      <!-- Simple Masonry Organic Illusion -->
      <div class="col-6 col-md-3">
        <div class="polaroid tilt-4 shadow float-anim">
          <div class="tape"></div>
          <div class="d-flex align-items-center justify-content-center bg-dark text-white rounded-1 overflow-hidden"
            style="height:160px;">
            <img src="<?= asset(get_setting('home_scrap_1', 'https://picsum.photos/seed/fp_home5/300/300')) ?>"
              class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Engagement">
          </div>
          <div class="polaroid-text"><?= h(get_setting('home_scrap_1_text', 'Engagement')) ?></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="polaroid tilt-1 mt-4 shadow-lg float-anim-delay">
          <div class="tape"></div>
          <div class="d-flex align-items-center justify-content-center bg-dark text-white rounded-1 overflow-hidden"
            style="height:210px;">
            <img src="<?= asset(get_setting('home_scrap_2', 'https://picsum.photos/seed/fp_home6/300/400')) ?>"
              class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Gala Dinner">
          </div>
          <div class="polaroid-text"><?= h(get_setting('home_scrap_2_text', 'Gala Dinner')) ?></div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="photo-strip shadow-lg mx-auto tilt-3 float-anim z-20" style="margin-top: -20px;">
          <div class="tape"></div>
          <div class="d-flex align-items-center justify-content-center bg-dark text-white-50 rounded overflow-hidden"
            style="width:130px; height:110px;">
            <img src="<?= asset(get_setting('home_scrap_3', 'https://picsum.photos/seed/fp_home7/200/200')) ?>"
              class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Pic 1">
          </div>
          <div class="d-flex align-items-center justify-content-center bg-dark text-white-50 rounded overflow-hidden"
            style="width:130px; height:110px;">
            <img src="<?= asset(get_setting('home_scrap_4', 'https://picsum.photos/seed/fp_home8/200/200')) ?>"
              class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Pic 2">
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="polaroid tilt-2 shadow mt-3 float-anim-delay">
          <div class="tape"></div>
          <div class="d-flex align-items-center justify-content-center bg-dark text-white rounded-1 overflow-hidden"
            style="height:180px;">
            <img src="<?= asset(get_setting('home_scrap_5', 'https://picsum.photos/seed/fp_home9/300/300')) ?>"
              class="w-100 h-100 object-fit-cover" style="object-fit:cover;" alt="Sweet 17th">
          </div>
          <div class="polaroid-text"><?= h(get_setting('home_scrap_5_text', 'Sweet 17th')) ?></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 8. TRUST BUILDER & 9. SCARCITY -->
<section class="section-padding bg-white position-relative">
  <div class="container position-relative z-10">

    <div class="card-playful shadow-sm p-4 p-md-5 text-center mx-auto js-scroll mb-5"
      style="max-width: 950px; border-radius: 30px;">
      <h2 class="mb-5 text-dark">
        <?= get_setting('home_trust_title', 'Kenapa Banyak Client <span class="text-orange">Repeat Order?</span>') ?>
      </h2>
      <div class="row text-center">
        <div class="col-6 col-md-3 mb-4">
          <div class="icon-circle shadow-sm" style="width: 55px; height: 55px;"><i class="ph-bold ph-clock fs-4"></i>
          </div>
          <h5 class="fw-bold text-dark mt-3"><?= h(get_setting('home_trust_1_title', 'Always On-Time')) ?></h5>
          <p class="text-muted-custom small mb-0 lh-sm">
            <?= h(get_setting('home_trust_1_desc', 'Dateng duluan selalu aman sebelum acara mulai.')) ?>
          </p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <div class="icon-circle shadow-sm" style="width: 55px; height: 55px;"><i class="ph-bold ph-wrench fs-4"></i>
          </div>
          <h5 class="fw-bold text-dark mt-3"><?= h(get_setting('home_trust_2_title', 'Zero Ribet')) ?></h5>
          <p class="text-muted-custom small mb-0 lh-sm">
            <?= h(get_setting('home_trust_2_desc', 'Mandiri pasang alat & rapih 100% tanpa merepotkan WO.')) ?>
          </p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <div class="icon-circle shadow-sm" style="width: 55px; height: 55px;"><i class="ph-bold ph-aperture fs-4"></i>
          </div>
          <h5 class="fw-bold text-dark mt-3"><?= h(get_setting('home_trust_3_title', 'Hasil Jernih')) ?></h5>
          <p class="text-muted-custom small mb-0 lh-sm">
            <?= h(get_setting('home_trust_3_desc', 'Flash kamera DSLR pro nggak bikin muka abu-abu pucat.')) ?>
          </p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <div class="icon-circle shadow-sm" style="width: 55px; height: 55px;"><i class="ph-bold ph-receipt fs-4"></i>
          </div>
          <h5 class="fw-bold text-dark mt-3"><?= h(get_setting('home_trust_4_title', 'Harga Transparan')) ?></h5>
          <p class="text-muted-custom small mb-0 lh-sm">
            <?= h(get_setting('home_trust_4_desc', 'Sesuai invoice, bebas biaya siluman mendadak.')) ?>
          </p>
        </div>
      </div>
    </div>

    <div class="js-scroll text-center mt-5 pt-4">
      <div
        class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-dark text-white rounded-pill fw-bold small mb-3 shadow-sm">
        <i class="ph-fill ph-warning-circle text-warning fs-5"></i>
        <?= h(get_setting('home_scarcity_badge', 'PENTING BANGET')) ?>
      </div>
      <h3 class="display-6 mx-auto mb-3 text-dark max-w-700" style="max-width: 700px;">
        <?= nl2br(h(get_setting('home_scarcity_title', "Kita Batasin Event\nDalam 1 Hari!"))) ?>
      </h3>
      <p class="lead text-muted-custom mb-5 mx-auto" style="max-width: 650px;">
        <?= get_setting('home_scarcity_desc', 'Biar kualitas tetap maksimal dan kru tetap prima, <strong>kami nggak ngambil overbooking</strong>. Kalau slot tanggal event kamu udah keisi, mohon maaf banget kita tutup pintu 🙏') ?>
      </p>
      <a href="<?= htmlspecialchars(get_setting('home_scarcity_cta_link', 'https://frontphotobooth.com')) ?>"
        data-track="click_cta_booking" class="btn btn-playful px-4">
        <i class="ph-bold ph-calendar-check text-dark"></i>
        <?= h(get_setting('home_scarcity_cta_text', 'Amankan Tanggal Sekarang')) ?>
      </a>
    </div>

  </div>
</section>

<!-- 10. FINAL CLOSE -->
<section class="section-padding bg-orange-gradient text-center position-relative overflow-hidden"
  style="padding: 8rem 0;">
  <!-- Top Curve Wave White -> Orange -->
  <div class="wave-divider-top" style="top: -1px;">
    <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
      <path fill="#ffffff"
        d="M0,32L80,37.3C160,43,320,53,480,53.3C640,53,800,43,960,32C1120,21,1280,11,1360,5.3L1440,0V60H0Z"></path>
    </svg>
  </div>

  <div class="hero-overlay"></div>
  <div class="container js-scroll position-relative z-10 mt-4">
    <h2 class="display-4 fw-bold mb-4 text-white text-shadow-sm">
      <?= h(get_setting('home_close_title', 'Ayo, Ramaikan Acaramu!')) ?>
    </h2>
    <p class="fs-5 text-white mb-5 mx-auto fw-medium" style="max-width: 600px; opacity: 0.95;">
      <?= h(get_setting('home_close_desc', 'Jangan biarin tamu cuman duduk main HP di meja. Bikin mereka gabung, gaya gokil, dan bawa kenangan fisiknya pulang!')) ?>
    </p>

    <div class="d-flex justify-content-center flex-wrap gap-4">
      <a href="<?= htmlspecialchars(get_setting('home_close_cta1_link', 'https://frontphotobooth.com')) ?>"
        data-track="click_cta_booking"
        class="btn btn-playful bg-white text-dark border-0 px-5 py-3 fs-5 fw-bold shadow-lg"
        style="box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;">
        <?= h(get_setting('home_close_cta1_text', 'Booking Langsung!')) ?> <i class="ph-bold ph-arrow-right"></i>
      </a>
      <a href="<?= htmlspecialchars(get_setting('home_close_cta2_link', 'https://wa.me/' . WA_NUMBER)) ?>"
        target="_blank" data-track="click_cta_whatsapp" class="btn btn-outline-dark-playful px-4 py-3 fs-5 fw-bold">
        <i class="ph-bold ph-whatsapp-logo"></i> <?= h(get_setting('home_close_cta2_text', 'Ngobrol Santai via WA')) ?>
      </a>
    </div>
  </div>
</section>



<?php require_once __DIR__ . '/footer.php'; ?>