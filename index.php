<?php
require_once __DIR__ . '/config.php';

$canonical = rtrim(BASE_URL, '/') . '/';
$ogImage = asset('assets/img/og-cover.webp');

// Promo deadline as ISO 8601 for JS (Asia/Jakarta → UTC offset +07:00)
$dt = new DateTimeImmutable(PROMO_DEADLINE, new DateTimeZone('Asia/Jakarta'));
$promoDeadlineISO = $dt->format('c'); // e.g. 2026-03-08T23:59:59+07:00

$schemaJson = json_encode([
  '@context' => 'https://schema.org',
  '@graph' => [
    [
      '@type' => 'Organization',
      'name' => BRAND_NAME,
      'url' => $canonical,
      'logo' => asset('assets/img/logo-ozverlig.webp'),
      'sameAs' => ['https://www.instagram.com/ozverlig', 'https://www.instagram.com/kemalikart'],
    ],
    [
      '@type' => 'Product',
      'name' => 'Jersey Series Fantasy Kamen Rider Ichigo & Black (Edisi 1)',
      'brand' => ['@type' => 'Brand', 'name' => BRAND_NAME],
      'description' => 'Nostalgia 90an terinspirasi Satria Baja Hitam. Pre-order ' . BRAND_NAME . ' x ' . COLLAB_NAME . '. Periode 27 Feb – 08 Mar 2026.',
      'image' => [asset('assets/img/hero.webp')],
      'offers' => [
        [
          '@type' => 'Offer',
          'priceCurrency' => 'IDR',
          'price' => (string) PRICE_PROMO_1,
          'availability' => 'https://schema.org/PreOrder',
          'url' => $canonical,
          'priceValidUntil' => '2026-03-08',
          'seller' => ['@type' => 'Organization', 'name' => BRAND_NAME]
        ],
        [
          '@type' => 'Offer',
          'priceCurrency' => 'IDR',
          'price' => (string) PRICE_PROMO_2,
          'availability' => 'https://schema.org/PreOrder',
          'url' => $canonical,
          'priceValidUntil' => '2026-03-08',
          'seller' => ['@type' => 'Organization', 'name' => BRAND_NAME]
        ],
      ],
    ],
    [
      '@type' => 'BreadcrumbList',
      'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => $canonical]],
    ],
    [
      '@type' => 'FAQPage',
      'mainEntity' => [
        [
          '@type' => 'Question',
          'name' => 'Berapa harga jersey?',
          'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Harga promo 1 jersey IDR 225.000, paket 2 jersey IDR 400.000. DP minimal IDR 100.000 per jersey.']
        ],
        [
          '@type' => 'Question',
          'name' => 'Kapan periode pre-order?',
          'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Pre-order 27 Februari – 08 Maret 2026. Produksi 09–21 Maret 2026. Pengiriman setelah produksi.']
        ],
        [
          '@type' => 'Question',
          'name' => 'Apa spesifikasi jersey?',
          'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Material Andromax Sublimation; Crest 3D Tatami/Polyflock; Apparel Crest 3D HD; Collar/Cuff Rib Knit; Size Tag DTF.']
        ],
        [
          '@type' => 'Question',
          'name' => 'Bagaimana cara pesan?',
          'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Isi form pemesanan di halaman ini lalu konfirmasi via WhatsApp.']
        ],
      ],
    ],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$criticalCSS = <<<'CSS'
:root{--bg:#060608;--bg-card:#0e0e12;--red:#f0131e;--red-glow:rgba(240, 19, 30, 0.4);--green:#00ff66;--text:#f4f4f5;--muted:#9ca3af;--border:#22222a;--ff-head:'Rajdhani',system-ui,sans-serif;--ff-body:'Inter',system-ui,-apple-system,sans-serif;--radius:0px;--skew:-8deg}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--ff-body);background-color:var(--bg);background-image:linear-gradient(rgba(255,255,255,0.015) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.015) 1px,transparent 1px);background-size:40px 40px;color:var(--text);line-height:1.6;-webkit-font-smoothing:antialiased}
.container{width:100%;max-width:1040px;margin-inline:auto;padding-inline:1.25rem}
.topbar{background:rgba(6,6,8,.9);backdrop-filter:blur(12px);padding:.75rem 0;position:sticky;top:0;z-index:100;border-bottom:1px solid var(--border)}
.topbar__inner{display:flex;justify-content:space-between;align-items:center}
.brand{display:flex;align-items:center;gap:.75rem}
.brand__title{font-family:var(--ff-head);font-weight:700;font-size:1.1rem;text-transform:uppercase;letter-spacing:2px;line-height:1;color:var(--text)}
.hero{padding:4rem 0 5rem}
h1,h2,h3{font-family:var(--ff-head);font-weight:700;text-transform:uppercase;line-height:1.1;letter-spacing:1px}
h1{font-size:clamp(2.5rem,8vw,4.5rem);color:var(--text);text-shadow:0 0 20px rgba(255,255,255,0.1);margin-bottom:0.5rem}
h2{font-size:clamp(1.8rem,5vw,2.8rem);display:inline-block;margin-bottom:1.5rem;position:relative;padding-bottom:0.5rem;line-height:1.2}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;background:var(--red);color:#fff;padding:.85rem 2rem;font-family:var(--ff-head);font-size:1.1rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;border:1px solid var(--red);cursor:pointer;transform:skewX(var(--skew));text-decoration:none}
.btn>span{transform:skewX(calc(var(--skew)*-1));display:inline-block}
CSS;
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Jersey Kamen Rider Ichigo &amp; Black – Edisi 1 | Ozverligsportwear</title>
  <meta name="description"
    content="Open Pre-Order Jersey Series Fantasy Kamen Rider Ichigo & Black Edisi 1. Harga promo IDR 225.000. DP minimal IDR 100.000. Produksi Ozverligsportwear x Kemalikart.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="<?= h($canonical) ?>">

  <!-- Open Graph -->
  <meta property="og:locale" content="id_ID">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Jersey Kamen Rider Ichigo & Black – Edisi 1">
  <meta property="og:description"
    content="Open Pre-Order. Harga promo mulai IDR 225.000. Ozverligsportwear x Kemalikart.">
  <meta property="og:url" content="<?= h($canonical) ?>">
  <meta property="og:image" content="<?= h($ogImage) ?>">
  <meta property="og:image:alt" content="Jersey Kamen Rider Ichigo & Black Edisi 1">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card" content="summary_large_image">

  <!-- DNS Prefetch / Preconnect -->
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    onerror="this.onerror=null;this.href='<?= h(asset('assets/vendor/bootstrap/bootstrap.min.css')) ?>';">

  <style>
    /* 
    * Minimal Overrides (Bootstrap 5 controls the rest)
    * Ozverligsportwear x Kemalikart
    */

    :root {
      --bg-dark: #050505;
      --bg-card: #0a0a0d;
      --brand-red: #ff1e27;
      --brand-green: #00e65b;
      --text-main: #f8f9fa;
      --text-muted: #8b8f97;
    }

    body {
      background-color: var(--bg-dark);
      color: var(--text-main);
      /* BS light */
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      overflow-x: hidden;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .font-rajdhani {
      font-family: 'Rajdhani', system-ui, sans-serif;
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: 1px;
    }

    /* Premium gradient texts */
    .text-gradient {
      background: linear-gradient(90deg, #fff, #999);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    /* Red accents */
    .text-brand-red {
      color: var(--brand-red) !important;
    }

    .bg-brand-red {
      background-color: var(--brand-red) !important;
      color: #fff;
    }

    .border-brand-red {
      border-color: var(--brand-red) !important;
    }

    /* Dark cards with glassy touch */
    .card-dark {
      background-color: rgba(14, 14, 18, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .card-dark:hover {
      transform: translateY(-5px);
      border-color: rgba(255, 30, 39, 0.3);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    }

    /* Custom Btn */
    .btn-red {
      background: linear-gradient(135deg, #ff1e27 0%, #d00f18 100%);
      color: #fff;
      border: none;
      font-family: 'Rajdhani', sans-serif;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      box-shadow: 0 4px 15px rgba(255, 30, 39, 0.4);
      transition: all 0.3s ease;
    }

    .btn-red:hover {
      background: linear-gradient(135deg, #ff333b 0%, #e6101a 100%);
      color: #fff;
      box-shadow: 0 6px 20px rgba(255, 30, 39, 0.6);
      transform: translateY(-2px);
    }

    .btn-outline-light {
      border: 1px solid rgba(255, 255, 255, 0.2) !important;
      backdrop-filter: blur(5px);
      transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
      background: rgba(255, 255, 255, 0.1) !important;
      border-color: rgba(255, 255, 255, 0.4) !important;
      color: #fff !important;
    }

    /* Skew util */
    .skew-btn {
      transform: skewX(-8deg);
    }

    .skew-btn>span {
      display: inline-block;
      transform: skewX(8deg);
    }

    /* Strikethrough price */
    .price-strike {
      text-decoration: line-through;
      color: var(--text-muted);
      font-size: 0.9em;
    }

    /* Payment box */
    .payment-box {
      background: linear-gradient(145deg, #0a0a0d, #111116);
      border: 1px solid rgba(255, 255, 255, 0.05);
      padding: 1.5rem;
      border-radius: 0.5rem;
      box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.5);
    }

    /* Form Fields Styling */
    .form-control,
    .form-select {
      background-color: rgba(0, 0, 0, 0.4) !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      color: #fff !important;
      transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--brand-red) !important;
      box-shadow: 0 0 0 0.25rem rgba(255, 30, 39, 0.25) !important;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.3) !important;
    }
  </style>

  <!-- Preload hero image -->
  <link rel="preload" href="<?= h(asset('assets/img/hero.webp')) ?>" as="image" type="image/webp">

  <!-- Structured Data -->
  <script type="application/ld+json"><?= $schemaJson ?></script>

  <!-- Tracking config -->
  <script>
    window.__T__ = {
      ga4: "<?= h(GA4_ID) ?>",
      gadsAw: "<?= h(GADS_AW_ID) ?>",
      gadsLabel: "<?= h(GADS_CONV_LABEL) ?>",
      meta: "<?= h(META_PIXEL_ID) ?>",
      promoDeadline: "<?= h($promoDeadlineISO) ?>"
    };
  </script>

  <?php if (!empty(META_PIXEL_ID)): ?>
    <!-- Meta Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
      n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
      document,'script','https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '<?= h(META_PIXEL_ID) ?>');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= h(META_PIXEL_ID) ?>&ev=PageView&noscript=1"/></noscript>
  <?php endif; ?>

  <?php if (!empty(GA4_ID)): ?>
    <!-- Google tag (gtag.js) GA4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= h(GA4_ID) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= h(GA4_ID) ?>');
      <?php if (!empty(GADS_AW_ID)): ?>
      gtag('config', '<?= h(GADS_AW_ID) ?>');
      <?php endif; ?>
    </script>
  <?php endif; ?>
</head>

<body>

  <!-- ── TOPBAR ── -->
  <header class="sticky-top bg-dark border-bottom border-dark py-2"
    style="background: rgba(6,6,8,0.95) !important; backdrop-filter: blur(10px);">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <img src="<?= h(asset('assets/img/logo-ozverlig.webp')) ?>" alt="Logo Ozverligsportwear" width="36" height="36"
          loading="eager">
        <div class="lh-1">
          <div class="font-rajdhani text-white fs-6">Ozverligsportwear</div>
          <div class="text-secondary" style="font-size:0.75rem;">x Kemalikart</div>
        </div>
        <nav class="d-none d-md-flex gap-3 align-items-center font-rajdhani text-secondary fw-bold">
          <a href="#produk" class="text-decoration-none text-secondary text-hover-white">Produk</a>
          <a href="#harga" class="text-decoration-none text-secondary text-hover-white">Harga</a>
          <a href="#faq" class="text-decoration-none text-secondary text-hover-white">FAQ</a>
        </nav>
        <a href="#order" class="btn btn-red btn-sm px-3 skew-btn" data-track="initiate_checkout"
          id="btnOrder"><span>Pesan</span></a>
      </div>
  </header>

  <main>

    <!-- ── HERO ── -->
    <section class="py-5" id="home">
      <div class="container">
        <div class="row align-items-center gy-5">
          <div class="col-lg-6 order-2 order-lg-1">
            <div class="d-flex gap-2 mb-3 flex-wrap justify-content-center justify-content-lg-start">
              <span class="badge bg-brand-red">Open Pre-Order</span>
              <span class="badge bg-warning text-dark">Limited Edition</span>
              <span class="badge bg-dark border border-secondary">Edisi 1</span>
            </div>

            <h1 class="display-4 fw-bold mb-3 text-white text-center text-lg-start">Jersey <span class="text-gradient">Kamen Rider</span><br>Ichigo &amp; Black</h1>

            <p class="lead text-secondary mb-4 text-center text-lg-start">
              Nostalgia di tahun 90an, terinspirasi dari film <strong>Satria Baja Hitam</strong> — jersey sporty premium
              bergaya jagoan masa kecil kita.<br>
              Diproduksi oleh <strong>Ozverligsportwear</strong> berkolaborasi dengan <strong>Kemalikart</strong>.
            </p>

            <div class="d-flex gap-3 mb-5 flex-wrap justify-content-center justify-content-lg-start">
              <a class="btn btn-red px-4 py-2 skew-btn fs-5" href="#order" data-track="initiate_checkout"
                id="btnOrder2"><span>Pesan Sekarang</span></a>
              <a class="btn btn-outline-light px-4 py-2 font-rajdhani fw-bold text-uppercase rounded-0"
                style="letter-spacing:1px;" href="#harga">Lihat Harga</a>
            </div>

            <div class="row text-center text-lg-start g-3">
              <div class="col-4">
                <div class="text-secondary small text-uppercase fw-bold">Pre-Order</div>
                <div class="text-white fw-medium">27 Feb – 08 Mar</div>
              </div>
              <div class="col-4">
                <div class="text-secondary small text-uppercase fw-bold">Produksi</div>
                <div class="text-white fw-medium">09 – 21 Mar</div>
              </div>
              <div class="col-4">
                <div class="text-secondary small text-uppercase fw-bold">DP Minimal</div>
                <div class="text-white fw-medium">IDR 100.000</div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 order-1 order-lg-2 text-center">
            <img class="img-fluid drop-shadow" src="<?= h(asset('assets/img/hero.webp')) ?>"
              alt="Jersey Series Fantasy Kamen Rider Ichigo dan Black Edisi 1" loading="eager" fetchpriority="high"
              style="filter: drop-shadow(0 0 30px rgba(240,19,30,0.3)); max-width:90%">
          </div>
        </div>
      </div>
    </section>

    <!-- ── PROMO LAUNCH BAND ── -->
    <div class="bg-brand-red text-center py-3 px-2 shadow-sm">
      <div
        class="container fw-bold font-rajdhani fs-5 d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
        <div class="d-flex flex-column text-md-end lh-1">
          <span class="text-white fs-5">🔥 EARLY ACCESS PRICE</span>
          <span class="badge border border-light rounded-pill px-2 py-1 mt-1"
            style="font-size: 0.70rem; background: rgba(0,0,0,0.3);">
            Hanya 3 Hari Pertama!
          </span>
        </div>
        <div class="d-flex align-items-center gap-2 bg-dark px-3 py-2 rounded">
          <span class="price-strike text-secondary fs-6"><?= idr(PRICE_ORIGINAL_1) ?></span>
          <span class="text-brand-green fs-5">kini <?= idr(PRICE_PROMO_1) ?></span>
        </div>
        <div class="d-flex flex-column text-md-start lh-1">
          <span class="text-white small mb-1" style="font-size: 0.8rem;">Sisa waktu:</span>
          <div id="promo-cd" class="d-flex gap-1 fs-6">
            <div class="bg-white text-dark rounded px-2 py-1"><span id="cd-h">--</span><span
                class="small text-secondary ms-1">J</span></div>
            <div class="bg-white text-dark rounded px-2 py-1"><span id="cd-m">--</span><span
                class="small text-secondary ms-1">M</span></div>
            <div class="bg-white text-dark rounded px-2 py-1"><span id="cd-s">--</span><span
                class="small text-secondary ms-1">D</span></div>
          </div>
          <div id="promo-ended" class="text-dark bg-warning px-2 py-1 rounded d-none" style="font-size:0.85rem">Promo
            Berakhir</div>
        </div>
      </div>
    </div>

    <!-- ── PRODUK ── -->
    <section class="py-5 bg-dark border-bottom border-dark" id="produk">
      <div class="container">
        <h2 class="display-6 font-rajdhani fw-bold text-white mb-2">2 Desain Edisi Perdana</h2>
        <p class="lead text-secondary mb-5">Jersey Series Fantasy – nuansa sporty premium, nostalgia 90an.</p>

        <div class="row g-4 mb-5">
          <div class="col-md-6">
            <article class="card card-dark h-100 rounded-0">
              <img src="<?= h(asset('assets/img/ichigo.webp')) ?>" class="card-img-top rounded-0"
                alt="Fantasy Kamen Rider Ichigo v.01" loading="lazy" decoding="async">
              <div class="card-body p-4">
                <h3 class="card-title font-rajdhani text-white fs-4">Fantasy Kamen Rider Ichigo v.01</h3>
                <p class="card-text text-secondary">Kolaborasi Ozverligsportwear x Kemalikart. Cocok untuk komunitas,
                  daily wear, dan riding.</p>
              </div>
            </article>
          </div>

          <div class="col-md-6">
            <article class="card card-dark h-100 rounded-0">
              <img src="<?= h(asset('assets/img/black.webp')) ?>" class="card-img-top rounded-0"
                alt="Fantasy Kamen Rider Black v.01" loading="lazy" decoding="async">
              <div class="card-body p-4">
                <h3 class="card-title font-rajdhani text-white fs-4">Fantasy Kamen Rider Black v.01</h3>
                <p class="card-text text-secondary">Karakter kuat, tegas, clean. Limited drop — raih sebelum kehabisan.
                </p>
              </div>
            </article>
          </div>
        </div>

        <div class="row g-3 text-center border-top border-dark pt-4">
          <div class="col-md-4">
            <div class="text-secondary small text-uppercase fw-bold">Diproduksi oleh</div>
            <div class="text-white fw-medium">Ozverligsportwear</div>
          </div>
          <div class="col-md-4 border-start border-end border-dark">
            <div class="text-secondary small text-uppercase fw-bold">Kolaborasi desain</div>
            <div class="text-white fw-medium">Kemalikart</div>
          </div>
          <div class="col-md-4">
            <div class="text-secondary small text-uppercase fw-bold">Model pemesanan</div>
            <div class="text-white fw-medium">Pre-Order</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── SPESIFIKASI ── -->
    <section class="py-5" id="spesifikasi" style="background: linear-gradient(180deg, #0a0a0e 0%, #060608 100%);">
      <div class="container">
        <h2 class="display-6 font-rajdhani fw-bold text-white mb-4">Spesifikasi Jersey</h2>
        <ul class="list-group list-group-flush border-top border-dark mb-4" style="max-width: 600px;">
          <li class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-3">
            <strong>Material Fabric</strong> <span class="text-secondary">Andromax Sublimation</span>
          </li>
          <li class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-3">
            <strong>Crest</strong> <span class="text-secondary">3D Tatami / Polyflock</span>
          </li>
          <li class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-3">
            <strong>Apparel Crest</strong> <span class="text-secondary">3D HD</span>
          </li>
          <li class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-3">
            <strong>Collar / Cuff</strong> <span class="text-secondary">Rib Knit</span>
          </li>
          <li class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-3">
            <strong>Size Tag</strong> <span class="text-secondary">DTF</span>
          </li>
        </ul>
        <div class="alert alert-dark bg-transparent border-secondary text-secondary" style="max-width: 600px;"
          role="alert">
          Dirancang untuk kenyamanan dan tampilan rapi. Sporty dan relevan — cocok harian maupun riding.
        </div>
      </div>
    </section>

    <!-- ── HARGA ── -->
    <section class="py-5 bg-dark border-top border-dark" id="harga">
      <div class="container text-center text-md-start">
        <h2 class="display-6 font-rajdhani fw-bold text-white mb-2">Harga &amp; DP</h2>
        <p class="lead text-secondary mb-5">Promo terbatas selama periode pre-order.</p>

        <div class="row g-4 justify-content-center justify-content-md-start mb-4">
          <!-- 1 pcs -->
          <div class="col-md-5 col-lg-4">
            <div class="card card-dark h-100 rounded-0 text-center py-5 px-3">
              <div class="font-rajdhani fs-4 text-white mb-3">1 Jersey</div>
              <div class="price-strike mb-2"><?= idr(PRICE_ORIGINAL_1) ?></div>
              <div class="display-5 fw-bold text-brand-green mb-4 font-rajdhani"><?= idr(PRICE_PROMO_1) ?></div>
              <div class="text-secondary small">DP minimal <?= idr(PRICE_DP) ?> / jersey</div>
            </div>
          </div>

          <!-- 2 pcs (featured) -->
          <div class="col-md-5 col-lg-4">
            <div class="card card-dark h-100 rounded-0 text-center py-5 px-3 border-brand-red position-relative shadow"
              style="transform: scale(1.05); z-index:2;">
              <span
                class="position-absolute top-0 start-50 translate-middle badge bg-brand-red px-3 py-2 text-uppercase letter-spacing-1">Best
                Value</span>
              <div class="font-rajdhani fs-4 text-white mb-3">Paket 2 Jersey</div>
              <div class="price-strike mb-2"><?= idr(PRICE_ORIGINAL_2) ?></div>
              <div class="display-5 fw-bold text-brand-green mb-4 font-rajdhani"><?= idr(PRICE_PROMO_2) ?></div>
              <div class="text-white small fw-bold">Hemat <?= idr(PRICE_ORIGINAL_2 - PRICE_PROMO_2) ?> — koleksi 2
                desain!</div>
            </div>
          </div>
        </div>

        <p class="text-secondary small"><i class="text-danger">*</i> Ongkir ditanggung pemesan.</p>
      </div>
    </section>

    <!-- ── JADWAL ── -->
    <section class="py-5" id="jadwal" style="background: linear-gradient(180deg, #0a0a0e 0%, #060608 100%);">
      <div class="container">
        <h2 class="display-6 font-rajdhani fw-bold text-white mb-4">Jadwal Pre-Order</h2>

        <div class="row g-0 border-start border-brand-red border-3 ms-3 mb-4">
          <div class="col-12 ps-4 py-3 border-bottom border-dark position-relative">
            <div class="text-secondary small text-uppercase fw-bold mb-1">Periode Pemesanan</div>
            <div class="text-white fs-5">27 Februari – 08 Maret 2026</div>
          </div>
          <div class="col-12 ps-4 py-3 border-bottom border-dark position-relative">
            <div class="text-secondary small text-uppercase fw-bold mb-1">Periode Produksi</div>
            <div class="text-white fs-5">09 – 21 Maret 2026</div>
          </div>
          <div class="col-12 ps-4 py-3 position-relative">
            <div class="text-secondary small text-uppercase fw-bold mb-1">Pengiriman</div>
            <div class="text-white fs-5">Dilakukan setelah produksi selesai</div>
          </div>
        </div>

        <div class="alert alert-danger bg-dark border-brand-red text-white" role="alert">
          <strong class="text-brand-red">Pre-order ditutup sesuai periode.</strong> Amankan slot segera — jumlah
          produksi terbatas.
        </div>
      </div>
    </section>

    <!-- ── SOCIAL PROOF ── -->
    <section class="py-5 bg-dark" id="bukti">
      <div class="container text-center">
        <h2 class="display-6 font-rajdhani fw-bold text-white mb-5">Kepercayaan &amp; Kualitas</h2>
        <div class="row g-4 justify-content-center">
          <div class="col-md-4">
            <div class="card bg-transparent border-secondary h-100 p-4 rounded-0">
              <div class="text-secondary small text-uppercase fw-bold mb-2">Produsen</div>
              <div class="text-white fs-5 font-rajdhani fw-bold mb-2">Ozverligsportwear</div>
              <div class="text-secondary small">Produksi jersey custom & komunitas</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-transparent border-secondary h-100 p-4 rounded-0">
              <div class="text-secondary small text-uppercase fw-bold mb-2">Kolaborasi</div>
              <div class="text-white fs-5 font-rajdhani fw-bold mb-2">Kemalikart</div>
              <div class="text-secondary small">Konsep visual & artwork</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-transparent border-secondary h-100 p-4 rounded-0">
              <div class="text-secondary small text-uppercase fw-bold mb-2">Pemesanan</div>
              <div class="text-white fs-5 font-rajdhani fw-bold mb-2">Pre-Order</div>
              <div class="text-secondary small">Batch produksi terjadwal</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── ORDER FORM ── -->
    <section class="py-5 border-top border-dark" id="order"
      style="background: linear-gradient(180deg, #0a0a0e 0%, #060608 100%);">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="display-6 font-rajdhani fw-bold text-white mb-2">Form Pemesanan</h2>
          <p class="lead text-secondary">Isi form di bawah. Setelah submit, Anda diarahkan ke WhatsApp untuk konfirmasi.
          </p>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card card-dark p-4 p-md-5 rounded-0 shadow-lg">
              <div id="formError" class="text-danger mb-3 d-none fw-bold"></div>

              <form id="orderForm" method="post" action="<?= h(BASE_PATH) ?>/order.php" enctype="multipart/form-data"
                novalidate>
                <!-- Hidden tracking fields -->
                <input type="hidden" name="utm_source" id="f_utm_source">
                <input type="hidden" name="utm_medium" id="f_utm_medium">
                <input type="hidden" name="utm_campaign" id="f_utm_campaign">
                <input type="hidden" name="utm_content" id="f_utm_content">
                <input type="hidden" name="utm_term" id="f_utm_term">
                <input type="hidden" name="fbclid" id="f_fbclid">
                <input type="hidden" name="gclid" id="f_gclid">
                <input type="hidden" name="wbraid" id="f_wbraid">
                <input type="hidden" name="gbraid" id="f_gbraid">
                <input type="hidden" name="referrer" id="f_referrer">

                <div class="mb-4">
                  <label for="inp_name" class="form-label text-white fw-bold">1. Nama Lengkap Pemesan</label>
                  <input id="inp_name" type="text" name="name"
                    class="form-control bg-dark text-white border-secondary rounded-0" required maxlength="80"
                    placeholder="Budi Santoso" autocomplete="name">
                </div>

                <div class="mb-4">
                  <label for="inp_address" class="form-label text-white fw-bold">2. Alamat Lengkap Pemesan</label>
                  <textarea id="inp_address" name="address"
                    class="form-control bg-dark text-white border-secondary rounded-0" rows="3" required maxlength="300"
                    placeholder="Jalan, Kelurahan, Kecamatan, Kota, Provinsi, Kodepos"></textarea>
                </div>

                <div class="mb-4">
                  <label for="inp_phone" class="form-label text-white fw-bold">3. Nomor Telepon (Whatsapp)</label>
                  <input id="inp_phone" type="tel" name="phone"
                    class="form-control bg-dark text-white border-secondary rounded-0 mb-1" required maxlength="20"
                    placeholder="0812xxxxxxx" autocomplete="tel">
                  <div class="form-text text-secondary">(Untuk konfirmasi & pengiriman invoice)</div>
                </div>

                <div class="mb-4">
                  <label for="inp_design" class="form-label text-white fw-bold">Pilih Desain (Edisi 1)</label>
                  <select id="inp_design" name="design"
                    class="form-select bg-dark text-white border-secondary rounded-0" required>
                    <option value="">— Pilih desain —</option>
                    <option value="Ichigo">Kamen Rider Ichigo</option>
                    <option value="Black">Kamen Rider Black</option>
                    <option value="Ichigo + Black (Paket Doble)">Paket Doble – Ichigo + Black</option>
                  </select>
                </div>

                <div class="row g-3 mb-4">
                  <div class="col-md-8">
                    <label for="inp_size" class="form-label text-white fw-bold">4. Ukuran Jersey</label>
                    <select id="inp_size" name="size" class="form-select bg-dark text-white border-secondary rounded-0"
                      required>
                      <option value="">— Pilih ukuran —</option>
                      <option value="S">S (49x70CM)</option>
                      <option value="M">M (51x72CM)</option>
                      <option value="L">L (53x74CM)</option>
                      <option value="XL">XL (55x76CM)</option>
                      <option value="XXL">XXL (57x78CM) (+20.000)</option>
                      <option value="3XL">3XL (59x80CM) (+20.000)</option>
                      <option value="4XL">4XL (61x82CM) (+20.000)</option>
                      <option value="5XL">5XL (63x84CM) (+20.000)</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label for="inp_qty" class="form-label text-white fw-bold">Jumlah</label>
                    <input id="inp_qty" type="number" name="qty"
                      class="form-control bg-dark text-white border-secondary rounded-0" min="1" max="20" value="1"
                      required>
                  </div>
                </div>

                <div class="mb-4">
                  <label for="inp_note" class="form-label text-white fw-bold">5. Note (Catatan Tambahan)</label>
                  <textarea id="inp_note" name="note" class="form-control bg-dark text-white border-secondary rounded-0"
                    rows="2" maxlength="300" placeholder="Misal: packing aman, request warna, dll."></textarea>
                </div>

                <!-- Payment Info Box -->
                <div class="payment-box mb-4">
                  <strong class="d-block text-brand-red mb-2">PEMBAYARAN MELALUI REKENING:</strong>
                  <div class="fs-4 font-rajdhani fw-bold text-white tracking-widest">BCA 6930242827</div>
                  <div class="text-white">a/n Bambang Kurniawan</div>
                  <div class="text-secondary small mb-3">(SERTAKAN BUKTI TRANSFER PEMBAYARAN)</div>

                  <div class="border-top border-secondary pt-3 mt-3">
                    <div class="text-white mb-2"><strong>Nomor WA Konfirmasi:</strong> <span
                        class="text-brand-green">0816-1726-0666</span></div>
                    <label for="inp_proof" class="form-label fw-bold text-white">Upload Bukti Pembayaran</label>
                    <input id="inp_proof" type="file" name="payment_proof" accept="image/*"
                      class="form-control bg-dark text-white border-secondary rounded-0" required>
                  </div>
                </div>

                <div class="form-check mb-4">
                  <input class="form-check-input bg-dark border-secondary" type="checkbox" id="agree_dp" name="agree_dp"
                    value="1" required>
                  <label class="form-check-label text-secondary small" for="agree_dp">
                    Saya setuju DP minimal <?= idr(PRICE_DP) ?> / jersey dan memahami timeline produksi.
                  </label>
                </div>

                <button class="btn btn-red w-100 py-3 skew-btn fs-5 mb-3" type="submit" id="btnSubmit">
                  <span>Kirim Pesanan & Bukti Transfer</span>
                </button>

                <div class="text-center text-secondary small">
                  Butuh panduan? <a href="https://wa.me/<?= h(WA_NUMBER) ?>"
                    class="text-brand-green text-decoration-none fw-bold" target="_blank" rel="noopener noreferrer"
                    data-wa data-track="wa_contact">WhatsApp Admin</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── FAQ ── -->
    <section class="py-5 bg-dark border-top border-dark" id="faq">
      <div class="container">
        <h2 class="display-6 font-rajdhani fw-bold text-white text-center mb-5">FAQ</h2>

        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="accordion accordion-dark" id="faqAccordion">

              <!-- FAQ 1 -->
              <div class="accordion-item bg-transparent border-secondary mb-3 rounded-0">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button collapsed bg-transparent text-white fw-bold shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                    aria-controls="collapseOne">
                    Berapa harga jersey?
                  </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                  data-bs-parent="#faqAccordion">
                  <div class="accordion-body text-secondary border-top border-secondary">
                    Harga promo 1 jersey IDR 225.000, paket doble 2 jersey IDR 400.000. DP minimal IDR 100.000 per
                    jersey. Ongkir ditanggung pemesan.
                  </div>
                </div>
              </div>

              <!-- FAQ 2 -->
              <div class="accordion-item bg-transparent border-secondary mb-3 rounded-0">
                <h2 class="accordion-header" id="headingTwo">
                  <button class="accordion-button collapsed bg-transparent text-white fw-bold shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                    aria-controls="collapseTwo">
                    Kapan periode pemesanan dan produksi?
                  </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                  data-bs-parent="#faqAccordion">
                  <div class="accordion-body text-secondary border-top border-secondary">
                    Pre-order: 27 Februari – 08 Maret 2026. Produksi: 09 – 21 Maret 2026. Pengiriman dilakukan setelah
                    produksi selesai.
                  </div>
                </div>
              </div>

              <!-- FAQ 3 -->
              <div class="accordion-item bg-transparent border-secondary mb-3 rounded-0">
                <h2 class="accordion-header" id="headingThree">
                  <button class="accordion-button collapsed bg-transparent text-white fw-bold shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                    aria-controls="collapseThree">
                    Apa saja spesifikasi jersey?
                  </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                  data-bs-parent="#faqAccordion">
                  <div class="accordion-body text-secondary border-top border-secondary">
                    Material Andromax Sublimation; Crest 3D Tatami/Polyflock; Apparel Crest 3D HD; Collar/Cuff Rib Knit;
                    Size Tag DTF.
                  </div>
                </div>
              </div>

              <!-- FAQ 4 -->
              <div class="accordion-item bg-transparent border-secondary mb-3 rounded-0">
                <h2 class="accordion-header" id="headingFour">
                  <button class="accordion-button collapsed bg-transparent text-white fw-bold shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false"
                    aria-controls="collapseFour">
                    Bagaimana cara pemesanan?
                  </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                  data-bs-parent="#faqAccordion">
                  <div class="accordion-body text-secondary border-top border-secondary">
                    Isi form pemesanan di halaman ini, lalu lanjutkan konfirmasi dan pembayaran DP via WhatsApp.
                  </div>
                </div>
              </div>

              <!-- FAQ 5 -->
              <div class="accordion-item bg-transparent border-secondary mb-3 rounded-0">
                <h2 class="accordion-header" id="headingFive">
                  <button class="accordion-button collapsed bg-transparent text-white fw-bold shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false"
                    aria-controls="collapseFive">
                    Apakah bisa request ukuran custom?
                  </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                  data-bs-parent="#faqAccordion">
                  <div class="accordion-body text-secondary border-top border-secondary">
                    Ukuran yang tersedia S, M, L, XL, XXL, 3XL, 4XL, 5XL. Untuk request khusus, lengkapi catatan (note)
                    saat mengisi form.
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- ── SEO Content ── -->
    <section class="py-5 border-top border-dark" id="seo-content"
      style="background: linear-gradient(180deg, #0a0a0e 0%, #060608 100%);">
      <div class="container" style="max-width: 800px;">
        <h2 class="h5 font-rajdhani fw-bold text-secondary mb-3">Jersey Kamen Rider untuk Penggemar Tokusatsu Indonesia
        </h2>
        <div class="text-secondary small" style="line-height: 1.8;">
          <p class="mb-3">Jersey Kamen Rider merupakan apparel yang diminati komunitas tokusatsu Indonesia.
            <strong>Jersey Series Fantasy Kamen Rider Ichigo &amp; Black (Edisi 1)</strong> terinspirasi era 90an dan
            film <strong>Satria Baja Hitam</strong>, diwujudkan menjadi jersey sporty premium bergaya jagoan masa kecil.
          </p>
          <p class="mb-0">Diproduksi oleh <strong>Ozverligsportwear</strong> berkolaborasi dengan
            <strong>Kemalikart</strong> — mengutamakan tampilan modern, cocok harian, komunitas, maupun riding. Sistem
            <strong>pre-order</strong> memastikan produksi terjadwal dan kualitas terjaga.
          </p>
        </div>
      </div>
    </section>

  </main>

  <!-- ── FOOTER ── -->
  <footer class="py-4 bg-black border-top border-dark">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
      <div class="text-center text-md-start">
        <div class="font-rajdhani fw-bold text-white fs-5">Ozverligsportwear x Kemalikart</div>
        <div class="text-secondary small">Jersey Series Fantasy Kamen Rider — Edisi 1 &copy; 2026</div>
      </div>
      <nav class="d-flex gap-3 align-items-center font-rajdhani fw-bold">
        <a href="#order" class="text-decoration-none text-secondary">Pesan</a>
        <a href="#faq" class="text-decoration-none text-secondary">FAQ</a>
        <a href="<?= h(BASE_PATH) ?>/sitemap.xml" class="text-decoration-none text-secondary">Sitemap</a>
      </nav>
    </div>
  </footer>

  <!-- ── Sticky CTA (mobile) ── -->
  <div class="fixed-bottom d-md-none bg-dark border-top border-dark p-2" style="z-index: 1040;">
    <div class="container d-flex gap-2">
      <a class="btn btn-outline-light flex-grow-1 py-2 font-rajdhani fw-bold text-uppercase"
        href="https://wa.me/<?= h(WA_NUMBER) ?>" target="_blank" rel="noopener noreferrer" data-wa
        data-track="wa_contact">WhatsApp</a>
      <a class="btn btn-red flex-grow-1 py-2 font-rajdhani fw-bold text-uppercase" href="#order"
        data-track="initiate_checkout">Pesan</a>
    </div>
  </div>

  <!-- Bootstrap JS (bundle includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap JS Local Fallback -->
  <script>window.bootstrap || document.write('<script src="<?= h(asset('assets/vendor/bootstrap/bootstrap.bundle.min.js')) ?>"><\/script>');</script>

  <!-- Custom logic -->
  <script>
    /**
     * Minimal JS (UTM & Countdown)
     */
    (function () {
      // Capture UTMs
      const params = new URLSearchParams(location.search);
      const fields = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'wbraid', 'gbraid'];

      fields.forEach(f => {
        if (params.has(f)) sessionStorage.setItem(f, params.get(f));
      });
      if (document.referrer && !sessionStorage.getItem('referrer')) {
        sessionStorage.setItem('referrer', document.referrer);
      }

      // Populate hidden form on dom load
      document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('orderForm');
        if (form) {
          fields.forEach(f => {
            let val = sessionStorage.getItem(f);
            let inp = document.getElementById('f_' + f);
            if (inp && val) inp.value = val;
          });
          let ref = sessionStorage.getItem('referrer');
          if (ref && document.getElementById('f_referrer')) {
            document.getElementById('f_referrer').value = ref;
          }
        }

        // Countdown Timer logic
        const cdWrap = document.getElementById('promo-cd');
        const endedWrap = document.getElementById('promo-ended');
        if (!cdWrap || typeof window.__T__.promoDeadline === 'undefined') return;

        const end = new Date(window.__T__.promoDeadline).getTime();

        function tick() {
          const now = new Date().getTime();
          const diff = end - now;

          if (diff <= 0) {
            cdWrap.style.display = 'none';
            if (endedWrap) endedWrap.classList.remove('d-none');
            return;
          }

          const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
          const s = Math.floor((diff % (1000 * 60)) / 1000);

          const eh = document.getElementById('cd-h');
          const em = document.getElementById('cd-m');
          const es = document.getElementById('cd-s');

          if (eh) eh.innerText = h.toString().padStart(2, '0');
          if (em) em.innerText = m.toString().padStart(2, '0');
          if (es) es.innerText = s.toString().padStart(2, '0');

          setTimeout(tick, 1000);
        }
        tick();
      });
    })();
  </script>

  <style>
    /* Utility class for BS5 Accordion custom styling */
    .accordion-button::after {
      filter: invert(1) grayscale(100%) brightness(200%);
    }

    .accordion-button:not(.collapsed)::after {
      filter: invert(1) grayscale(100%) brightness(200%);
    }
  </style>

</body>

</html>