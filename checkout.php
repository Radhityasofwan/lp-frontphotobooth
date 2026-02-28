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
    content="Halaman Checkout Aman - Jersey Kamen Rider Ichigo & Black. Isi detail pemesanan Anda dengan tenang.">
  <meta name="robots" content="noindex,nofollow">
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
      background-image: radial-gradient(circle at 50% 0%, rgba(30, 30, 35, 1) 0%, var(--bg-dark) 80%);
      background-attachment: fixed;
      color: var(--text-main);
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Fixed topbar layout for mobile */
    .topbar-logo {
      width: 36px;
      height: 36px;
    }

    .topbar-brand {
      font-size: 1rem;
    }

    .topbar-sub {
      font-size: 0.65rem;
    }

    .topbar-btn {
      padding: 0.4rem 0.8rem !important;
      font-size: 0.85rem !important;
    }

    .topbar-btn span {
      font-size: 0.85rem !important;
    }

    @media (min-width: 768px) {
      .topbar-logo {
        width: 48px;
        height: 48px;
      }

      .topbar-brand {
        font-size: 1.1rem;
      }

      .topbar-sub {
        font-size: 0.8rem;
      }

      .topbar-btn {
        padding: 0.85rem 2rem !important;
        font-size: 1.1rem !important;
      }

      .topbar-btn span {
        font-size: 1.1rem !important;
      }
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
  <header class="sticky-top bg-dark border-bottom border-dark py-2 py-md-4"
    style="background: rgba(6,6,8,0.95) !important; backdrop-filter: blur(10px);">
    <div class="container d-flex justify-content-between align-items-center gap-1 gap-md-4">
      <a href="<?= h(BASE_URL) ?>/" class="d-flex align-items-center gap-2 gap-md-3 text-decoration-none">
        <img src="<?= h(asset('assets/img/logo-ozverlig.webp')) ?>" alt="Logo Ozverligsportwear" loading="eager"
          class="rounded-circle border border-secondary shadow-sm topbar-logo">
        <div class="lh-sm">
          <div class="font-rajdhani text-white fw-bold topbar-brand" style="letter-spacing: 0.5px;">Ozverligsportwear
          </div>
          <div class="text-brand-red fw-bold topbar-sub" style="letter-spacing: 1px;">X KEMALIKART</div>
        </div>
      </a>

      <div class="d-flex align-items-center gap-2 gap-md-4">
        <nav class="d-none d-md-flex gap-4 align-items-center font-rajdhani text-secondary fw-bold fs-6">
          <a href="<?= h(BASE_URL) ?>/#produk" class="text-decoration-none text-secondary text-hover-white">Produk</a>
          <a href="<?= h(BASE_URL) ?>/#harga" class="text-decoration-none text-secondary text-hover-white">Harga</a>
          <a href="<?= h(BASE_URL) ?>/#faq" class="text-decoration-none text-secondary text-hover-white">FAQ</a>
        </nav>
        <a href="<?= h(BASE_URL) ?>" class="btn btn-outline-light skew-btn topbar-btn"><span
            class="fw-bold">Kembali</span></a>
      </div>
    </div>
  </header>

  <main>

    <!-- Sections Removed for Privacy Container -->

    <!-- ── ORDER FORM ── -->
    <section class="py-5 border-top border-dark text-center" id="order"
      style="background: rgba(6,6,8,0.6); backdrop-filter: blur(10px);">
      <div class="container d-flex flex-column align-items-center">
        <div class="text-center mb-5">
          <h2 class="display-6 font-rajdhani fw-bold text-white mb-2">
            <?= get_setting('order_title', 'Form Pemesanan') ?></h2>
          <p class="lead text-secondary mb-3">
            <?= get_setting('order_desc', 'Isi form di bawah. Setelah submit, Anda diarahkan ke WhatsApp untuk konfirmasi.') ?>
          </p>
          <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mt-2"
            style="background: rgba(0, 230, 91, 0.1); border: 1px solid rgba(0, 230, 91, 0.3);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--brand-green)"
              class="bi bi-shield-check" viewBox="0 0 16 16">
              <path
                d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.655-.197-1.534-.446-2.837-.856C9.525 1.15 8.36 1 8 1s-1.525.15-2.662.59zM8 14.5c-.066 0-.16-.03-.254-.08a9.8 9.8 0 0 1-2.01-1.667C4.6 11.136 3.4 8.246 3.84 4.54a59 59 0 0 1 2.508-.75C7.23 3.63 7.85 3.5 8 3.5c.15 0 .77.13 1.652.29a59 59 0 0 1 2.508.75c.44 3.706-.76 6.596-1.896 8.213a9.8 9.8 0 0 1-2.01 1.667c-.094.05-.188.08-.254.08z" />
              <path
                d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
            </svg>
            <span class="text-brand-green fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;">Checkout Aman &
              Terenkripsi</span>
          </div>
        </div>

        <div class="row justify-content-center w-100 text-start">
          <div class="col-lg-8">
            <div class="card card-dark p-4 p-md-5 rounded-0 shadow-lg">
              <div id="formError" class="text-danger mb-3 d-none fw-bold"></div>

              <form id="orderForm" method="post" action="<?= h(BASE_PATH) ?>/order.php" enctype="multipart/form-data"
                class="needs-validation" novalidate>
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
                  <input id="inp_name" type="text" name="name" value="<?= h($f_name) ?>"
                    class="form-control bg-dark text-white border-secondary rounded-0" required maxlength="80"
                    placeholder="Budi Santoso" autocomplete="name">
                  <div class="invalid-feedback text-brand-red fw-bold">⚠️ Nama wajib diisi.</div>
                </div>

                <div class="mb-4">
                  <label for="inp_address" class="form-label text-white fw-bold">2. Alamat Lengkap Pemesan</label>
                  <textarea id="inp_address" name="address"
                    class="form-control bg-dark text-white border-secondary rounded-0" rows="3" required maxlength="300"
                    placeholder="Jalan, Kelurahan, Kecamatan, Kota, Provinsi, Kodepos"><?= h($f_address) ?></textarea>
                  <div class="invalid-feedback text-brand-red fw-bold">⚠️ Detail alamat domisili wajib disertakan.</div>
                </div>

                <div class="mb-4">
                  <label for="inp_phone" class="form-label text-white fw-bold">3. Nomor Telepon (Whatsapp)</label>
                  <input id="inp_phone" type="tel" name="phone" value="<?= h($f_phone) ?>"
                    class="form-control bg-dark text-white border-secondary rounded-0 mb-1" required maxlength="20"
                    placeholder="0812xxxxxxx" autocomplete="tel">
                  <div class="invalid-feedback text-brand-red fw-bold">⚠️ Nomor HP/WhatsApp wajib diisi valid.</div>
                  <div class="form-text text-secondary">(Untuk konfirmasi & pengiriman invoice)</div>
                </div>

                <div class="mb-4">
                  <label for="inp_design" class="form-label text-white fw-bold">Pilih Desain (Edisi 1)</label>
                  <select id="inp_design" name="design"
                    class="form-select bg-dark text-white border-secondary rounded-0" required>
                    <option value="">— Pilih desain —</option>
                    <option value="Ichigo" <?= $f_design === 'Ichigo' ? 'selected' : '' ?>>Kamen Rider Ichigo</option>
                    <option value="Black" <?= $f_design === 'Black' ? 'selected' : '' ?>>Kamen Rider Black</option>
                    <option value="Ichigo + Black (Paket Doble)" <?= $f_design === 'Ichigo + Black (Paket Doble)' ? 'selected' : '' ?>>Paket Doble – Ichigo + Black</option>
                  </select>
                  <div class="invalid-feedback text-brand-red fw-bold">⚠️ Mohon pilih salah satu desain/paket.</div>
                </div>

                <div class="row g-3 mb-4">
                  <div class="col-md-8">
                    <label for="inp_size" class="form-label text-white fw-bold">4. Ukuran Jersey</label>
                    <select id="inp_size" name="size" class="form-select bg-dark text-white border-secondary rounded-0"
                      required>
                      <option value="">— Pilih ukuran —</option>
                      <option value="S" <?= $f_size === 'S' ? 'selected' : '' ?>>S (49x70CM)</option>
                      <option value="M" <?= $f_size === 'M' ? 'selected' : '' ?>>M (51x72CM)</option>
                      <option value="L" <?= $f_size === 'L' ? 'selected' : '' ?>>L (53x74CM)</option>
                      <option value="XL" <?= $f_size === 'XL' ? 'selected' : '' ?>>XL (55x76CM)</option>
                      <option value="XXL" <?= $f_size === 'XXL' ? 'selected' : '' ?>>XXL (57x78CM) (+20.000)</option>
                      <option value="3XL" <?= $f_size === '3XL' ? 'selected' : '' ?>>3XL (59x80CM) (+20.000)</option>
                      <option value="4XL" <?= $f_size === '4XL' ? 'selected' : '' ?>>4XL (61x82CM) (+20.000)</option>
                      <option value="5XL" <?= $f_size === '5XL' ? 'selected' : '' ?>>5XL (63x84CM) (+20.000)</option>
                    </select>
                    <div class="invalid-feedback text-brand-red fw-bold">⚠️ Wajib pilih ukuran.</div>
                  </div>
                  <div class="col-md-4">
                    <label for="inp_qty" class="form-label text-white fw-bold">Jumlah</label>
                    <input id="inp_qty" type="number" name="qty"
                      class="form-control bg-dark text-white border-secondary rounded-0" min="1" max="20"
                      value="<?= h($f_qty) ?>" required>
                  </div>
                </div>

                <div class="mb-4">
                  <label for="inp_note" class="form-label text-white fw-bold">5. Note (Catatan Tambahan)</label>
                  <textarea id="inp_note" name="note" class="form-control bg-dark text-white border-secondary rounded-0"
                    rows="2" maxlength="300"
                    placeholder="Misal: packing aman, request warna, dll."><?= h($f_note) ?></textarea>
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
                    <label for="inp_proof" class="form-label fw-bold text-white mb-3">Upload Bukti Pembayaran <span
                        class="text-danger">*</span></label>
                    <div class="position-relative">
                      <input id="inp_proof" type="file" name="payment_proof" accept="image/*"
                        class="form-control bg-dark text-white border-secondary rounded-0 position-absolute"
                        style="opacity: 0; width: 100%; height: 100%; top: 0; left: 0; z-index: 2; cursor: pointer;"
                        required>
                      <label for="inp_proof"
                        class="d-flex flex-column align-items-center justify-content-center p-4 border border-secondary rounded-0 bg-dark text-center"
                        style="border-style: dashed !important; border-width: 2px !important;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                          class="bi bi-cloud-arrow-up text-brand-red mb-2" viewBox="0 0 16 16">
                          <path fill-rule="evenodd"
                            d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708z" />
                          <path
                            d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.984 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                        </svg>
                        <span class="text-white fw-bold mb-1" id="file_name_display">Klik atau Tarik File Kesini</span>
                        <span class="text-secondary small">Format: JPG, PNG, WEBP (Max: 5MB)</span>
                      </label>
                      <div class="invalid-feedback text-brand-red fw-bold text-center mt-2 d-block" id="proof_feedback"
                        style="display: none!important;">
                        ⚠️ Bukti transfer wajib di-upload untuk melanjutkan pesanan.
                      </div>
                    </div>

                    <script>
                      // Minimal JS to display selected file name
                      const inpProof = document.getElementById('inp_proof');
                      const nameDisplay = document.getElementById('file_name_display');
                      if (inpProof && nameDisplay) {
                        inpProof.addEventListener('change', function () {
                          if (this.files && this.files[0]) {
                            nameDisplay.textContent = this.files[0].name;
                            nameDisplay.classList.add('text-brand-green');
                          } else {
                            nameDisplay.textContent = 'Klik atau Tarik File Kesini';
                            nameDisplay.classList.remove('text-brand-green');
                          }
                          const fb = document.getElementById('proof_feedback');
                          if (fb) fb.style.setProperty('display', this.files.length ? 'none' : 'block', 'important');
                        });
                      }
                    </script>
                  </div>
                </div>

                <!-- Dynamic Pricing Display -->
                <div class="row gx-0 mb-4 bg-black p-3 rounded" style="border: 1px solid rgba(255, 30, 39, 0.3);">
                  <div class="col-12 mb-3 pb-3 border-bottom border-dark text-secondary small lh-lg">
                    <div class="d-flex justify-content-between">
                      <span>HARGA JERSEY</span>
                      <span class="text-white fw-bold">IDR. 225.000,-</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="text-brand-red fw-bold">HARGA JERSEY PAKET DOBLE</span>
                      <span class="text-white fw-bold">IDR. 400.000,-</span>
                    </div>
                    <div class="d-flex justify-content-between mt-1 pt-1 border-top border-secondary">
                      <span>DP MINIMAL</span>
                      <span class="text-white">IDR. 100.000,- / JERSEY</span>
                    </div>
                  </div>
                  <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                    <span class="text-secondary small fw-bold">TOTAL HARGA:</span>
                    <strong class="text-white fs-5 font-rajdhani" id="ui_total_price">Rp 0</strong>
                  </div>
                  <div class="col-12 d-flex justify-content-between align-items-center mt-2">
                    <div>
                      <span class="text-brand-green small d-block fw-bold">DP MINIMAL:</span>
                      <span class="text-secondary" style="font-size: 0.70rem;">(Bisa ditransfer lunas)</span>
                    </div>
                    <div class="text-end">
                      <strong class="text-brand-green fs-3 font-rajdhani" id="ui_dp_price">Rp 0</strong>
                    </div>
                  </div>
                </div>

                <div class="form-check mb-4">
                  <input class="form-check-input bg-dark border-secondary" type="checkbox" id="agree_dp" name="agree_dp"
                    value="1" required>
                  <label class="form-check-label text-secondary small" for="agree_dp">
                    Saya setuju mengirimkan dp/lunas sejumlah tagihan di atas dan memahami timeline produksi tertera.
                  </label>
                </div>

                <p class="text-white text-center mb-2 <?= $myOrder ? 'd-block' : 'd-none' ?>">Anda sedeng mengedit
                  pesanan sebelumnya.</p>
                <button class="btn btn-red w-100 py-3 skew-btn fs-5 mb-3" type="submit" id="btnSubmit">
                  <span><?= $myOrder ? 'Update Pesanan & Bukti Transfer' : 'Kirim Pesanan & Bukti Transfer' ?></span>
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

    <!-- FAQ Removed -->

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

        // Removed unused countdown logic

        // ── Dynamic Pricing ──
        const inpDesign = document.getElementById('inp_design');
        const inpSize = document.getElementById('inp_size');
        const inpQty = document.getElementById('inp_qty');
        const uiTotal = document.getElementById('ui_total_price');

        const uiDp = document.getElementById('ui_dp_price');

        function calcPrice() {
          if (!inpDesign || !inpSize || !inpQty || !uiTotal) return;
          let qty = parseInt(inpQty.value) || 1;
          let totalQty = qty;

          // Force Paket Doble quantity handling
          if (inpDesign.value === 'Ichigo + Black (Paket Doble)') {
            totalQty = qty * 2;
          }

          // Automatically give promo metric if they requested 2 pieces manually
          let pairs = Math.floor(totalQty / 2);
          let singles = totalQty % 2;
          let basePrice = (pairs * window.__T__.price2) + (singles * window.__T__.price1);

          let surchargeQty = 0;
          if (['XXL', '3XL', '4XL', '5XL'].includes(inpSize.value.toUpperCase())) {
            surchargeQty = window.__T__.surcharge * totalQty;
          }

          let finalPrice = basePrice + surchargeQty;
          uiTotal.innerText = new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
          }).format(finalPrice);

          if (uiDp) {
            uiDp.innerText = new Intl.NumberFormat('id-ID', {
              style: 'currency', currency: 'IDR', minimumFractionDigits: 0
            }).format(totalQty * 100000);
          }
        }

        if (inpDesign) inpDesign.addEventListener('change', calcPrice);
        if (inpSize) inpSize.addEventListener('change', calcPrice);
        if (inpQty) {
          inpQty.addEventListener('input', calcPrice);
          inpQty.addEventListener('change', calcPrice);
        }
        calcPrice();

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
  </script>

  <script>
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

      pingTracker('view_checkout');

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

</body>

</html>