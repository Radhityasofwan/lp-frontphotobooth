<?php
require_once __DIR__ . '/config.php';

// Check for existing session token
$orderToken = $_COOKIE['order_session'] ?? '';
$myOrder = null;

if ($orderToken && $pdo) {
  try {
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE order_token = ? LIMIT 1");
    $stmt->execute([$orderToken]);
    $myOrder = $stmt->fetch();
  } catch (PDOException $e) {
    $myOrder = null;
  }
}

// Prefill values
$f_name = $myOrder ? $myOrder['name'] : '';
$f_phone = $myOrder ? $myOrder['phone'] : '';
$f_address = $myOrder ? $myOrder['address'] : '';
$f_design = $myOrder ? $myOrder['design'] : '';
$f_size = $myOrder ? $myOrder['size'] : '';
$f_qty = $myOrder ? $myOrder['quantity'] : '1';
$f_note = $myOrder ? $myOrder['note'] : '';

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
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -1;
      background-image:
        linear-gradient(rgba(5, 5, 5, 0.75), rgba(5, 5, 5, 0.85)),
        radial-gradient(circle at 15% 30%, rgba(0, 230, 91, 0.4) 0%, transparent 60%),
        radial-gradient(circle at 85% 30%, rgba(255, 30, 39, 0.4) 0%, transparent 60%),
        radial-gradient(ellipse at 50% 100%, rgba(200, 200, 220, 0.3) 0%, transparent 50%),
        url('<?= h(asset('assets/img/hero.webp')) ?>');
      background-size: cover, 100% 100%, 100% 100%, 100% 100%, cover;
      background-position: center, center, center, center, top center;
      background-blend-mode: normal, screen, screen, screen, overlay;
      pointer-events: none;
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

    /* Red & Green accents */
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

    .border-brand-green {
      border-color: var(--brand-green) !important;
    }

    /* Dark cards with glassy touch */
    .card-dark {
      background-color: rgba(14, 14, 18, 0.75);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .card-dark:hover {
      transform: translateY(-5px);
    }

    /* Custom colored card glows */
    .glow-green {
      box-shadow: 0 0 25px rgba(0, 230, 91, 0.15);
      border: 1px solid rgba(0, 230, 91, 0.3);
    }

    .glow-green:hover {
      box-shadow: 0 0 35px rgba(0, 230, 91, 0.3);
      border-color: rgba(0, 230, 91, 0.6);
    }

    .glow-red {
      box-shadow: 0 0 25px rgba(255, 30, 39, 0.15);
      border: 1px solid rgba(255, 30, 39, 0.3);
    }

    .glow-red:hover {
      box-shadow: 0 0 35px rgba(255, 30, 39, 0.3);
      border-color: rgba(255, 30, 39, 0.6);
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

    /* Floating WA Widget */
    .float-wa {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 60px;
      height: 60px;
      background-color: #25d366;
      color: #FFF;
      border-radius: 50px;
      text-align: center;
      font-size: 30px;
      box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .float-wa:hover,
    .float-wa:focus {
      background-color: #128C7E;
      color: #fff;
      transform: translateY(-5px);
      box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
    }

    @media(max-width: 767px) {
      .float-wa {
        bottom: 80px;
        /* offset for mobile sticky CTA */
      }
    }

    /* WA Modal Adjustments */
    .wa-modal-header {
      background-color: #075e54;
      color: white;
      border-bottom: none;
    }

    .wa-modal-body {
      background-color: #e5ddd5;
      padding: 1.5rem;
    }

    .wa-chat-bubble {
      background: white;
      padding: 0.8rem 1rem;
      border-radius: 8px;
      border-top-left-radius: 0;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
      margin-bottom: 1rem;
      position: relative;
      color: #303030;
      font-size: 0.95rem;
    }

    .wa-chat-bubble::before {
      content: '';
      position: absolute;
      top: 0;
      left: -10px;
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 0 10px 10px 0;
      border-color: transparent white transparent transparent;
    }

    .wa-reply-btn {
      display: block;
      width: 100%;
      text-align: left;
      background: #dcf8c6;
      border: 1px solid #c8e6b1;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-bottom: 0.5rem;
      color: #303030;
      font-weight: 500;
      text-decoration: none;
      transition: background 0.2s;
    }

    .wa-reply-btn:hover {
      background: #c8e6b1;
      color: #202020;
    }

    .wa-reply-btn i {
      color: #128C7E;
      margin-right: 0.5rem;
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
      !function (f, b, e, v, n, t, s) {
        if (f.fbq) return; n = f.fbq = function () {
          n.callMethod ?
            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        }; if (!f._fbq) f._fbq = n;
        n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async = !0;
        t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s)
      }(window,
        document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '<?= h(META_PIXEL_ID) ?>');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=<?= h(META_PIXEL_ID) ?>&ev=PageView&noscript=1" /></noscript>
  <?php endif; ?>

  <?php if (!empty(GA4_ID)): ?>
    <!-- Google tag (gtag.js) GA4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= h(GA4_ID) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() { dataLayer.push(arguments); }
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
  <header class="sticky-top bg-dark border-bottom border-dark py-3 py-md-4"
    style="background: rgba(6,6,8,0.95) !important; backdrop-filter: blur(10px);">
    <div class="container d-flex justify-content-between align-items-center gap-2 gap-md-4">
      <a href="<?= h(BASE_URL) ?>/" class="d-flex align-items-center gap-2 gap-md-3 text-decoration-none">
        <img src="<?= h(asset('assets/img/logo-ozverlig.webp')) ?>" alt="Logo Ozverligsportwear" width="48" height="48"
          loading="eager" class="rounded-circle border border-secondary shadow-sm">
        <div class="lh-sm">
          <div class="font-rajdhani text-white fs-5 fw-bold" style="letter-spacing: 0.5px;">Ozverligsportwear</div>
          <div class="text-brand-red fw-bold" style="font-size:0.8rem; letter-spacing: 1px;">X KEMALIKART</div>
        </div>
      </a>

      <div class="d-flex align-items-center gap-4">
        <nav class="d-none d-md-flex gap-4 align-items-center font-rajdhani text-secondary fw-bold fs-6">
          <a href="#produk" class="text-decoration-none text-secondary text-hover-white">Produk</a>
          <a href="#harga" class="text-decoration-none text-secondary text-hover-white">Harga</a>
          <a href="#faq" class="text-decoration-none text-secondary text-hover-white">FAQ</a>
        </nav>
        <a href="<?= h(BASE_URL) ?>/checkout.php" class="btn btn-red btn-sm px-4 py-2 skew-btn"
          data-track="initiate_checkout" id="btnOrder"><span class="fw-bold fs-6">Pesan</span></a>
      </div>
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

            <h1 class="display-4 fw-bold mb-3 text-white text-center text-lg-start">Jersey <span
                class="text-gradient">Kamen Rider</span><br>Ichigo &amp; Black</h1>

            <p class="lead text-secondary mb-4 text-center text-lg-start">
              Nostalgia di tahun 90an, terinspirasi dari film <strong>Satria Baja Hitam</strong>. Jersey sporty premium
              bergaya jagoan masa kecil kita.<br>
              Diproduksi oleh <strong>Ozverligsportwear</strong> berkolaborasi dengan <strong>Kemalikart</strong>.
            </p>

            <div class="d-flex gap-3 mb-5 flex-wrap justify-content-center justify-content-lg-start">
              <a class="btn btn-red px-4 py-2 skew-btn fs-5" href="<?= h(BASE_URL) ?>/checkout.php"
                data-track="initiate_checkout" id="btnOrder2"><span>Pesan Sekarang</span></a>
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
            Batch 1
          </span>
        </div>
        <div class="d-flex align-items-center gap-2 bg-dark px-3 py-2 rounded">
          <span class="price-strike text-secondary fs-6"><?= idr(PRICE_ORIGINAL_1) ?></span>
          <span class="text-brand-green fs-5">kini <?= idr(PRICE_PROMO_1) ?></span>
        </div>
        <div class="d-flex flex-column text-md-start lh-1">
          <span class="text-white small mb-1" style="font-size: 0.8rem;">Sisa waktu:</span>
          <div id="promo-cd" class="d-flex gap-1 fs-6">
            <div class="bg-white text-black fw-bold rounded px-2 py-1"><span id="cd-h">--</span><span
                class="small text-secondary fw-normal ms-1">J</span></div>
            <div class="bg-white text-black fw-bold rounded px-2 py-1"><span id="cd-m">--</span><span
                class="small text-secondary fw-normal ms-1">M</span></div>
            <div class="bg-white text-black fw-bold rounded px-2 py-1"><span id="cd-s">--</span><span
                class="small text-secondary fw-normal ms-1">D</span></div>
          </div>
          <div id="promo-ended" class="text-black fw-bold bg-warning px-2 py-1 rounded d-none" style="font-size:0.85rem">Promo
            Berakhir</div>
        </div>
      </div>
    </div>

    <!-- ── INSTAGRAM SHOWCASES ── -->
    <section class="py-5 border-bottom border-dark position-relative z-1" id="showcase">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="display-6 font-rajdhani fw-bold text-white">Our Showcase</h2>
          <p class="text-secondary">Detail dan tampilan nyata karya kami di Instagram.</p>
        </div>
        <div class="row g-4 justify-content-center">
          <!-- Ichigo Embed (Green Glow) -->
          <div class="col-md-6 col-lg-5">
            <div class="card card-dark h-100 rounded text-center p-3 glow-green border border-dark">
              <h5 class="font-rajdhani fw-bold text-brand-green mb-3">#Ichigo Edition</h5>
              <!-- Using 4:5 aspect ratio padding for IG portraits (125% = 5/4) -->
              <div class="w-100 overflow-hidden rounded bg-black d-flex justify-content-center" style="position: relative; padding-top: 125%;">
                <blockquote class="instagram-media h-100 w-100 border-0 m-0 bg-transparent position-absolute top-0 start-0" data-instgrm-permalink="https://www.instagram.com/p/DVRdmS_E9Kq/?utm_source=ig_web_button_share_sheet&igsh=MzRlODBiNWFlZA==" data-instgrm-version="14"></blockquote>
              </div>
            </div>
          </div>

          <!-- Black Embed (Red Glow) -->
          <div class="col-md-6 col-lg-5">
            <div class="card card-dark h-100 rounded text-center p-3 glow-red border border-dark">
              <h5 class="font-rajdhani fw-bold text-brand-red mb-3">#Black Edition</h5>
              <!-- Using 4:5 aspect ratio padding for IG portraits (125% = 5/4) -->
              <div class="w-100 overflow-hidden rounded bg-black d-flex justify-content-center" style="position: relative; padding-top: 125%;">
                <blockquote class="instagram-media h-100 w-100 border-0 m-0 bg-transparent position-absolute top-0 start-0" data-instgrm-permalink="https://www.instagram.com/p/DVRd5kNE0B4/?utm_source=ig_web_button_share_sheet&igsh=MzRlODBiNWFlZA==" data-instgrm-version="14"></blockquote>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script async src="//www.instagram.com/embed.js"></script>

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
    <section class="py-5 text-center" id="spesifikasi"
      style="background: rgba(6,6,8,0.4); backdrop-filter: blur(5px); border-top: 1px solid rgba(255,255,255,0.05);">
      <div class="container d-flex flex-column align-items-center">
        <h2 class="display-6 font-rajdhani fw-bold text-white mb-5">Spesifikasi Jersey</h2>
        <ul class="list-group list-group-flush border-top border-dark mb-4 w-100" style="max-width: 700px;">
          <li
            class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-4 fs-5">
            <strong>Material Fabric</strong> <span class="text-secondary text-end">Andromax Sublimation</span>
          </li>
          <li
            class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-4 fs-5">
            <strong>Crest</strong> <span class="text-secondary text-end">3D Tatami / Polyflock</span>
          </li>
          <li
            class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-4 fs-5">
            <strong>Apparel Crest</strong> <span class="text-secondary text-end">3D HD</span>
          </li>
          <li
            class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-4 fs-5">
            <strong>Collar / Cuff</strong> <span class="text-secondary text-end">Rib Knit</span>
          </li>
          <li
            class="list-group-item bg-transparent text-white border-dark d-flex justify-content-between px-0 py-4 fs-5">
            <strong>Size Tag</strong> <span class="text-secondary text-end">DTF</span>
          </li>
        </ul>
        <div class="alert alert-dark bg-transparent border-secondary text-secondary p-4 mt-3"
          style="max-width: 700px; line-height: 1.8;" role="alert">
          Dirancang khusus untuk kenyamanan maksimal dan tampilan yang rapi. Memiliki fitting sporty yang ergonomis
          sehingga sangat relevan digunakan — baik untuk aktivitas harian santai maupun kebutuhan riding touring jauh
          Anda.
        </div>
      </div>
    </section>

    <!-- ── HARGA ── -->
    <section class="py-5 bg-dark border-top border-dark" id="harga" style="background: rgba(6,6,8,0.8) !important;">
      <div class="container text-center">
        <h2 class="display-6 font-rajdhani fw-bold text-white mb-2 w-100">Harga</h2>
        <p class="lead text-secondary mb-5">Promo terbatas selama periode pre-order.</p>

        <div class="row g-4 justify-content-center mb-4">
          <!-- 1 pcs (Left - Green) -->
          <div class="col-md-5 col-lg-4">
            <div class="card card-dark h-100 rounded-0 text-center py-5 px-3 glow-green">
              <div class="font-rajdhani fs-4 text-white mb-3">HARGA JERSEY</div>
              <div class="price-strike mb-2"><?= idr(PRICE_ORIGINAL_1) ?></div>
              <div class="display-5 fw-bold text-brand-green mb-4 font-rajdhani"><?= idr(PRICE_PROMO_1) ?></div>
              <div class="text-secondary small">DP MINIMAL <?= idr(PRICE_DP) ?> / JERSEY</div>
            </div>
          </div>

          <!-- 2 pcs (Right - Red) -->
          <div class="col-md-5 col-lg-4">
            <div class="card card-dark h-100 rounded-0 text-center py-5 px-3 glow-red position-relative"
              style="transform: scale(1.05); z-index:2;">
              <span
                class="position-absolute top-0 start-50 translate-middle badge bg-brand-red px-3 py-2 text-uppercase letter-spacing-1">Best
                Value</span>
              <div class="font-rajdhani fs-4 text-white mb-3">HARGA JERSEY PAKET DOBLE</div>
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
    <section class="py-5 text-center" id="jadwal"
      style="background: rgba(6,6,8,0.4); backdrop-filter: blur(5px); border-top: 1px solid rgba(255,255,255,0.05);">
      <div class="container d-flex flex-column align-items-center">
        <h2 class="display-6 font-rajdhani fw-bold text-white mb-4">Jadwal Pre-Order</h2>

        <div class="row w-100 g-0 border-start border-end border-brand-red border-3 mx-auto mb-4"
          style="max-width: 600px; text-align: center;">
          <div class="col-12 ps-4 py-3 border-bottom border-dark position-relative">
            <div class="text-secondary small text-uppercase fw-bold mb-1">Periode Pemesanan</div>
            <div class="text-white fs-5">27 Februari – 08 Maret 2026</div>
          </div>
          <div class="col-12 ps-4 py-3 border-bottom border-dark position-relative">
            <div class="text-secondary small text-uppercase fw-bold mb-1">Periode Produksi</div>
            <div class="text-white fs-5">09 – 21 Maret 2026</div>
          </div>
          <div class="col-12 py-3 position-relative">
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
    <section class="py-5 bg-dark" id="bukti"
      style="background: rgba(6,6,8,0.8) !important; border-top: 1px solid rgba(255,255,255,0.05);">
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

    <!-- ── SIZE CHART ── -->
    <section class="py-5 text-center" id="size-chart"
      style="background: rgba(10,10,14,0.6); backdrop-filter: blur(5px); border-top: 1px solid rgba(255,255,255,0.05);">
      <div class="container d-flex flex-column align-items-center">
        <h2 class="display-6 font-rajdhani fw-bold text-white mb-4">Size Chart</h2>
        <p class="lead text-secondary mb-5">Panduan ukuran untuk mendapatkan fitting terbaik.</p>
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">
            <img src="<?= h(asset('assets/img/size-chart.png')) ?>" alt="Size Chart Jersey Kamen Rider"
              class="img-fluid rounded border border-secondary drop-shadow"
              style="filter: drop-shadow(0 0 15px rgba(255, 30, 39, 0.2));" loading="lazy">
          </div>
        </div>
      </div>
    </section>

    <!-- Formulir Pemesanan Removed, Transferred to checkout.php -->

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
        <h2 class="h4 font-rajdhani fw-bold text-secondary mb-4">Jersey Kamen Rider Custom untuk Komunitas Rider
          Indonesia</h2>
        <div class="text-secondary small" style="line-height: 1.8;">
          <p class="mb-3">Desain <strong>jersey kamen rider custom</strong> yang sedang booming kini telah hadir untuk
            pencinta tokusatsu tanah air! Bernostalgia bersama <strong>jersey satria baja hitam</strong> dan pahlawan
            abad ke-90an kini terasa lebih autentik dan eksklusif dengan rilisan limited edition ini.</p>
          <p class="mb-3">Diproduksi secara matang oleh <em>Ozverligsportwear</em> berkolaborasi dengan komunitas seni
            <em>Kemalikart</em>, setiap balutan <strong>jersey fantasy kamen rider</strong> kami dirancang untuk
            menemani gaya hidup aktif Anda. Dari <strong>jersey anime custom indonesia</strong> hingga kebutuhan apparel
            harian saat riding akhir pekan, kualitas material premium (Andromax Sublimasi) kami dijamin tahan terhadap
            cuaca.
          </p>
          <p class="mb-0">Bagi para die-hard fans, sebuah <strong>jersey komunitas rider</strong> tak lengkap tanpa
            detil sempurna layaknya pahlawan itu sendiri. Jadikan <strong>jersey tokusatsu indonesia</strong> ini
            pelengkap koleksi utama Anda. Tunggu apa lagi? Lengkapi hari-harimu dengan gaya nostalgia <strong>jersey
              kamen rider indonesia</strong> yang membalut karakter gagah jagoan idola.</p>
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
      </nav>
    </div>
  </footer>

  <!-- ── Sticky CTA (mobile) ── -->
  <div class="fixed-bottom d-md-none bg-dark border-top border-dark p-2"
    style="z-index: 9998; padding-bottom: max(env(safe-area-inset-bottom), 12px) !important;">
    <div class="container d-flex gap-2">
      <a class="btn btn-outline-light flex-grow-1 py-2 font-rajdhani fw-bold text-uppercase"
        href="https://wa.me/<?= h(WA_NUMBER) ?>" target="_blank" rel="noopener noreferrer" data-wa
        data-track="wa_contact">WhatsApp</a>
      <a class="btn btn-red flex-grow-1 py-2 font-rajdhani fw-bold text-uppercase" href="#order"
        data-track="initiate_checkout">Pesan</a>
    </div>
  </div>

  <!-- ── Floating WhatsApp Button & Modal ── -->
  <button type="button" class="float-wa border-0" data-bs-toggle="modal" data-bs-target="#waModal"
    aria-label="Chat WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-whatsapp"
      viewBox="0 0 16 16">
      <path
        d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
    </svg>
  </button>

  <!-- WA Modal -->
  <div class="modal fade" id="waModal" tabindex="-1" aria-labelledby="waModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content border-0 overflow-hidden shadow">
        <div class="modal-header wa-modal-header py-3">
          <div class="d-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-whatsapp"
              viewBox="0 0 16 16">
              <path
                d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
            </svg>
            <div>
              <h5 class="modal-title fs-6 font-rajdhani fw-bold" id="waModalLabel">CS Ozverligsportwear</h5>
              <div class="small" style="font-size: 0.75rem; opacity: 0.8;">Online</div>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body wa-modal-body">
          <div class="wa-chat-bubble fw-bold">
            Halo! Ada yang bisa kami bantu terkait pesanan Jersey Kamen Rider Anda? Silakan pilih opsi di bawah ini:
          </div>

          <div class="d-flex flex-column gap-2">
            <a href="https://wa.me/<?= h(WA_NUMBER) ?>?text=Halo%20Admin,%20saya%20ingin%20bertanya%20detail%20ukuran%20(size%20chart)%20Jersey%20Kamen%20Rider."
              class="wa-reply-btn" target="_blank" rel="noopener">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-rulers me-2" viewBox="0 0 16 16">
                <path
                  d="M1 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h5v-1H2v-1h4v-1H4v-1h2v-1H2v-1h4V9H4V8h2V7H2V6h4V2h1v4h1V4h1v2h1V2h1v4h1V4h1v2h1V2h1v4h1V1a1 1 0 0 0-1-1z" />
              </svg>
              Konsultasi Ukuran (Size)
            </a>
            <a href="https://wa.me/<?= h(WA_NUMBER) ?>?text=Halo%20Admin,%20saya%20butuh%20bantuan%20cara%20pemesanan%20/%20pengisian%20form%20Jersey%20Kamen%20Rider."
              class="wa-reply-btn" target="_blank" rel="noopener">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-cart-question me-2" viewBox="0 0 16 16">
                <path
                  d="M11.5 5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zM3 14a2 2 0 1 1 0 4 2 2 0 0 1 0-4m10 0a2 2 0 1 1 0 4 2 2 0 0 1 0-4M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M6.5 7h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1" />
              </svg>
              Cara Pesan / Form
            </a>
            <a href="https://wa.me/<?= h(WA_NUMBER) ?>?text=Halo%20Admin,%20saya%20ingin%20bertanya%20seputar%20pengiriman%20dan%20produksi%20Jersey%20Kamen%20Rider."
              class="wa-reply-btn" target="_blank" rel="noopener">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-truck me-2" viewBox="0 0 16 16">
                <path
                  d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
              </svg>
              Jadwal Produksi & Ongkir
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (bundle includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap JS Local Fallback -->
  <script>window.bootstrap || document.write('<script src="<?= h(asset('assets/vendor/bootstrap/bootstrap.bundle.min.js')) ?>"><\/script>');</script>

  <!-- Custom logic -->
  <script>
    /**
     * Runtime Variables from PHP config
     */
    window.__T__ = {
      promoDeadline: "<?= h(PROMO_DEADLINE) ?>",
      price1: <?= PRICE_PROMO_1 ?>,
      price2: <?= PRICE_PROMO_2 ?>,
      surcharge: <?= PRICE_SURCHARGE ?>
    };

    /**
     * Minimal JS (UTM, Countdown, Dynamic Pricing)
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

        // Countdown Timer logic: 24h Rolling Reset
        const cdWrap = document.getElementById('promo-cd');
        if (!cdWrap) return;

        let end = localStorage.getItem('cd_end');
        let nowTime = new Date().getTime();

        // If no existing rolling deadline or it has already passed, set it to +24hrs from now
        if (!end || nowTime > parseInt(end, 10)) {
          end = nowTime + (24 * 60 * 60 * 1000);
          localStorage.setItem('cd_end', end);
        } else {
          end = parseInt(end, 10);
        }

        const tick = setInterval(() => {
          const now = new Date().getTime();
          let d = end - now;

          if (d <= 0) {
            // Auto-reset back to 24 hours seamlessly
            end = now + (24 * 60 * 60 * 1000);
            localStorage.setItem('cd_end', end);
            d = end - now;
          }

          document.getElementById('cd-h').innerText = String(Math.floor((d % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
          document.getElementById('cd-m').innerText = String(Math.floor((d % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
          document.getElementById('cd-s').innerText = String(Math.floor((d % (1000 * 60)) / 1000)).padStart(2, '0');
        }, 1000);

        // ── Dynamic Pricing ──
        const inpDesign = document.getElementById('inp_design');
        const inpSize = document.getElementById('inp_size');
        const inpQty = document.getElementById('inp_qty');
        const uiTotal = document.getElementById('ui_total_price');

        const uiDp = document.getElementById('ui_dp_price');

        <!-- Checkout JS Removed, Located in checkout.php -->

        // ── Form Validation (Frontend) ──
        if (form) {
          form.addEventListener('submit', function (event) {
            // Check Bootstrap native validation
            if (!form.checkValidity()) {
              event.preventDefault();
              event.stopPropagation();
              form.classList.add('was-validated');

              // Scroll to first invalid element
              const firstInvalid = form.querySelector(':invalid');
              if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
              }
              return;
            }

            // Check File Size
            const proofInput = document.getElementById('inp_proof');
            if (proofInput && proofInput.files.length > 0) {
              const fileSize = proofInput.files[0].size / 1024 / 1024; // MB
              if (fileSize > 5) {
                event.preventDefault();
                event.stopPropagation();
                alert('Ukuran file maksimal 5MB. Silakan kompres gambar Anda terlebih dahulu.');
                proofInput.value = ''; // clear
                const nameDisplay = document.getElementById('file_name_display');
                if (nameDisplay) {
                  nameDisplay.textContent = 'Klik atau Tarik File Kesini';
                  nameDisplay.classList.remove('text-brand-green');
                }
                return;
              }
            }

            // Fire Meta Lead Event if valid
            if (typeof fbq !== 'undefined') {
              fbq('track', 'Lead');
            }
          }, false);
        }

        // ── Meta Ads Tracking Funnel ──
        if (typeof fbq !== 'undefined') {
          // 1. ViewContent
          fbq('track', 'ViewContent', { content_name: 'Jersey Kamen Rider', content_category: 'Jersey' });

          // 2. InitiateCheckout
          document.querySelectorAll('[data-track="initiate_checkout"]').forEach(btn => {
            btn.addEventListener('click', () => { fbq('track', 'InitiateCheckout'); });
          });

          // 3. Lead
          if (form) {
            form.addEventListener('submit', () => { fbq('track', 'Lead'); });
          }
        }
      });
    })();

    // ── CUSTOM IN-HOUSE ANALYTICS ENGINE (Batch 20) ──
    (function () {
      function getSessionId() {
        let sid = sessionStorage.getItem('__trk_sid');
        if (!sid) {
          sid = 'sid_' + Math.random().toString(36).substring(2) + Date.now().toString(36);
          sessionStorage.setItem('__trk_sid', sid);
        }
        return sid;
      }

      function pingTracker(type, val = 0) {
        fetch('<?= h(BASE_URL) ?>/track.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            session_id: getSessionId(),
            event_type: type,
            event_value: val,
            page_url: window.location.pathname
          })
        }).catch(e => console.warn('Telemetry error', e));
      }

      pingTracker('view_landing');

      document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-track]');
        if (btn) pingTracker('click_' + btn.getAttribute('data-track'), 1);
      });

      let seconds = 0;
      setInterval(() => {
        seconds += 10;
        pingTracker('time_spent', seconds);
      }, 10000);
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