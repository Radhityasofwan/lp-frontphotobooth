<?php
require_once __DIR__ . '/config.php';

$canonical = rtrim(BASE_URL, '/') . '/';
$ogImage = rtrim(BASE_URL, '/') . '/assets/img/og-cover.webp';

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
      'logo' => rtrim(BASE_URL, '/') . '/assets/img/logo-ozverlig.webp',
      'sameAs' => ['https://www.instagram.com/ozverlig', 'https://www.instagram.com/kemalikart'],
    ],
    [
      '@type' => 'Product',
      'name' => 'Jersey Series Fantasy Kamen Rider Ichigo & Black (Edisi 1)',
      'brand' => ['@type' => 'Brand', 'name' => BRAND_NAME],
      'description' => 'Nostalgia 90an terinspirasi Satria Baja Hitam. Pre-order ' . BRAND_NAME . ' x ' . COLLAB_NAME . '. Periode 27 Feb – 08 Mar 2026.',
      'image' => [rtrim(BASE_URL, '/') . '/assets/img/hero.webp'],
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

// ── Inline critical CSS ──────────────────────────────────────────────────────
$criticalCSS = <<<'CSS'
:root{--bg:#0b0b0b;--red:#e62429;--text:#f0f0f0;--muted:#8a8a8a;--border:#2a2a2a;--ff-head:'Barlow Condensed',system-ui,sans-serif;--ff-body:'Inter',system-ui,-apple-system,sans-serif}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:var(--ff-body);background:var(--bg);color:var(--text);line-height:1.65;-webkit-font-smoothing:antialiased}
.container{width:100%;max-width:1040px;margin-inline:auto;padding-inline:1rem}
.topbar{background:rgba(0,0,0,.85);backdrop-filter:blur(10px);padding:.6rem 0;position:sticky;top:0;z-index:100;border-bottom:1px solid var(--border)}
.topbar__inner{display:flex;justify-content:space-between;align-items:center;gap:1rem}
.brand{display:flex;align-items:center;gap:.6rem}
.brand__title{font-family:var(--ff-head);font-weight:700;text-transform:uppercase;letter-spacing:1px;line-height:1.1}
.hero{padding:3.5rem 0 4rem}
h1{font-family:var(--ff-head);font-weight:700;text-transform:uppercase;line-height:1.15;font-size:clamp(2.2rem,7vw,4rem)}
.btn{display:inline-flex;align-items:center;justify-content:center;background:var(--red);color:#fff;padding:.85rem 1.75rem;font-family:var(--ff-head);font-size:1.05rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;border:none;border-radius:6px;cursor:pointer;text-decoration:none}
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

  <!-- Inline critical CSS -->
  <style>
    <?= $criticalCSS ?>
  </style>

  <!-- Preload hero image -->
  <link rel="preload" href="<?= h(BASE_PATH) ?>/assets/img/hero.webp" as="image" type="image/webp">

  <!-- Main stylesheet (non-critical, preload swap) -->
  <link rel="preload" href="<?= h(BASE_PATH) ?>/assets/css/style.css" as="style" onload="this.rel='stylesheet'">
  <noscript>
    <link rel="stylesheet" href="<?= h(BASE_PATH) ?>/assets/css/style.css">
  </noscript>

  <!-- Structured Data -->
  <script type="application/ld+json"><?= $schemaJson ?></script>

  <!-- Tracking config (no external call here) -->
  <script>
    window.__T__ = {
      ga4: "<?= h(GA4_ID) ?>",
      gadsAw: "<?= h(GADS_AW_ID) ?>",
      gadsLabel: "<?= h(GADS_CONV_LABEL) ?>",
      meta: "<?= h(META_PIXEL_ID) ?>",
      promoDeadline: "<?= h($promoDeadlineISO) ?>"
    };
  </script>
</head>

<body>

  <!-- ── TOPBAR ── -->
  <header class="topbar">
    <div class="container topbar__inner">
      <div class="brand">
        <img class="brand__img" src="<?= h(BASE_PATH) ?>/assets/img/logo-ozverlig.webp" alt="Logo Ozverligsportwear"
          width="34" height="34" loading="eager">
        <div>
          <div class="brand__title">Ozverligsportwear</div>
          <div class="brand__sub">x Kemalikart</div>
        </div>
      </div>
      <nav aria-label="Navigasi utama">
        <a href="#produk">Produk</a>
        <a href="#harga">Harga</a>
        <a href="#jadwal">Jadwal</a>
        <a href="#faq">FAQ</a>
        <a href="#order" class="btn btn--sm" data-track="initiate_checkout" id="btnOrder">Pesan</a>
      </nav>
    </div>
  </header>

  <main>

    <!-- ── HERO ── -->
    <section class="hero" id="home">
      <div class="container hero__grid">
        <div class="hero__content">
          <div class="badge-row">
            <span class="badge badge--red">Open Pre-Order</span>
            <span class="badge badge--yellow">Limited Edition</span>
            <span class="badge badge--dark">Edisi 1</span>
          </div>

          <h1>Jersey Kamen Rider<br>Ichigo &amp; Black</h1>

          <p class="lead">
            Nostalgia di tahun 90an, terinspirasi dari film <strong>Satria Baja Hitam</strong> — jersey sporty premium
            bergaya jagoan masa kecil kita.
            Diproduksi oleh <strong>Ozverligsportwear</strong> berkolaborasi dengan <strong>Kemalikart</strong>.
          </p>

          <div class="cta-row">
            <a class="btn" href="#order" data-track="initiate_checkout" id="btnOrder2">Pesan Sekarang</a>
            <a class="btn btn--ghost" href="#harga">Lihat Harga</a>
          </div>

          <div class="micro-trust">
            <div>
              <div class="micro-trust__lbl">Pre-Order</div>
              <div class="micro-trust__val">27 Feb – 08 Mar</div>
            </div>
            <div>
              <div class="micro-trust__lbl">Produksi</div>
              <div class="micro-trust__val">09 – 21 Mar</div>
            </div>
            <div>
              <div class="micro-trust__lbl">DP Minimal</div>
              <div class="micro-trust__val">IDR 100.000</div>
            </div>
          </div>

          <!-- IG Social Proof -->
          <div class="ig-strip" aria-label="Foto produk dari Instagram">
            <div class="ig-placeholder" data-url="https://www.instagram.com/p/DVQnp81AaRn/" role="img"
              aria-label="Post Instagram Ozverligsportwear">
              <div class="ig-skeleton"></div>
              <span>Memuat foto produk…</span>
            </div>
            <div class="ig-placeholder" data-url="https://www.instagram.com/p/DVQoZK4AaiL/" role="img"
              aria-label="Post Instagram Ozverligsportwear">
              <div class="ig-skeleton"></div>
              <span>Memuat foto produk…</span>
            </div>
          </div>
        </div>

        <div class="hero__media">
          <img class="hero__img" src="<?= h(BASE_PATH) ?>/assets/img/hero.webp"
            alt="Jersey Series Fantasy Kamen Rider Ichigo dan Black Edisi 1 – Ozverligsportwear" width="900"
            height="900" loading="eager" decoding="async" fetchpriority="high">
        </div>
      </div>
    </section>

    <!-- ── PROMO COUNTDOWN BAND ── -->
    <div class="promo-band">
      <div class="container promo-band__inner">
        <div class="promo-label">🔥 <span>Promo Terbatas</span> – Pre-order berakhir dalam:</div>
        <div id="countdown-wrap" class="countdown" aria-live="polite">
          <div class="countdown__block"><span class="countdown__num" id="cd-h">--</span><span
              class="countdown__lbl">Jam</span></div>
          <span class="countdown__sep" aria-hidden="true">:</span>
          <div class="countdown__block"><span class="countdown__num" id="cd-m">--</span><span
              class="countdown__lbl">Menit</span></div>
          <span class="countdown__sep" aria-hidden="true">:</span>
          <div class="countdown__block"><span class="countdown__num" id="cd-s">--</span><span
              class="countdown__lbl">Detik</span></div>
        </div>
        <div id="promo-ended" class="promo-ended">Pre-order sudah berakhir.</div>
      </div>
    </div>

    <!-- ── PRODUK ── -->
    <section class="section" id="produk">
      <div class="container">
        <h2>2 Desain Edisi Perdana</h2>
        <p class="sublead">Jersey Series Fantasy – nuansa sporty premium, nostalgia 90an.</p>

        <div class="grid-2">
          <article class="card">
            <div class="card__img">
              <img src="<?= h(BASE_PATH) ?>/assets/img/ichigo.webp" alt="Fantasy Kamen Rider Ichigo v.01" width="800"
                height="800" loading="lazy" decoding="async">
            </div>
            <div class="card__body">
              <h3 class="card__title">Fantasy Kamen Rider Ichigo v.01</h3>
              <p class="card__desc">Kolaborasi Ozverligsportwear x Kemalikart. Cocok untuk komunitas, daily wear, dan
                riding.</p>
            </div>
          </article>

          <article class="card">
            <div class="card__img">
              <img src="<?= h(BASE_PATH) ?>/assets/img/black.webp" alt="Fantasy Kamen Rider Black v.01" width="800"
                height="800" loading="lazy" decoding="async">
            </div>
            <div class="card__body">
              <h3 class="card__title">Fantasy Kamen Rider Black v.01</h3>
              <p class="card__desc">Karakter kuat, tegas, clean. Limited drop — raih sebelum kehabisan.</p>
            </div>
          </article>
        </div>

        <div class="proof-bar">
          <div class="proof-bar__item">
            <div class="proof-bar__k">Diproduksi oleh</div>
            <div class="proof-bar__v">Ozverligsportwear</div>
          </div>
          <div class="proof-bar__item">
            <div class="proof-bar__k">Kolaborasi desain</div>
            <div class="proof-bar__v">Kemalikart</div>
          </div>
          <div class="proof-bar__item">
            <div class="proof-bar__k">Model pemesanan</div>
            <div class="proof-bar__v">Pre-Order</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── SPESIFIKASI ── -->
    <section class="section section--alt" id="spesifikasi">
      <div class="container">
        <h2>Spesifikasi Jersey</h2>
        <ul class="spec-list">
          <li><strong>Material Fabric</strong> Andromax Sublimation</li>
          <li><strong>Crest</strong> 3D Tatami / Polyflock</li>
          <li><strong>Apparel Crest</strong> 3D HD</li>
          <li><strong>Collar / Cuff</strong> Rib Knit</li>
          <li><strong>Size Tag</strong> DTF</li>
        </ul>
        <div class="spec-note">
          <p>Dirancang untuk kenyamanan dan tampilan rapi. Sporty dan relevan — cocok harian maupun riding.</p>
        </div>
      </div>
    </section>

    <!-- ── HARGA ── -->
    <section class="section" id="harga">
      <div class="container">
        <h2>Harga &amp; DP</h2>
        <p class="sublead" style="text-align:center">Promo terbatas selama periode pre-order.</p>

        <div class="price-grid">
          <!-- 1 pcs -->
          <div class="price-card">
            <div class="price-card__name">1 Jersey</div>
            <div class="price-card__original"><?= idr(PRICE_ORIGINAL_1) ?></div>
            <div class="price-card__promo"><?= idr(PRICE_PROMO_1) ?></div>
            <div class="price-card__dp">DP minimal <?= idr(PRICE_DP) ?> / jersey</div>
          </div>

          <!-- 2 pcs (featured) -->
          <div class="price-card price-card--featured">
            <div class="price-card__ribbon">Best Value</div>
            <div class="price-card__name">Paket 2 Jersey</div>
            <div class="price-card__original"><?= idr(PRICE_ORIGINAL_2) ?></div>
            <div class="price-card__promo"><?= idr(PRICE_PROMO_2) ?></div>
            <div class="price-card__dp">Hemat <?= idr(PRICE_ORIGINAL_2 - PRICE_PROMO_2) ?> — koleksi 2 desain!</div>
          </div>
        </div>

        <p class="price-note">Ongkir ditanggung pemesan.</p>
      </div>
    </section>

    <!-- ── JADWAL ── -->
    <section class="section section--alt" id="jadwal">
      <div class="container">
        <h2>Jadwal Pre-Order</h2>
        <div class="timeline">
          <div class="timeline__item">
            <div class="timeline__k">Periode Pemesanan</div>
            <div class="timeline__v">27 Februari – 08 Maret 2026</div>
          </div>
          <div class="timeline__item">
            <div class="timeline__k">Periode Produksi</div>
            <div class="timeline__v">09 – 21 Maret 2026</div>
          </div>
          <div class="timeline__item">
            <div class="timeline__k">Pengiriman</div>
            <div class="timeline__v">Dilakukan setelah produksi selesai</div>
          </div>
        </div>
        <div class="callout">
          <strong>Pre-order ditutup sesuai periode.</strong> Amankan slot segera — jumlah produksi terbatas.
        </div>
      </div>
    </section>

    <!-- ── SOCIAL PROOF ── -->
    <section class="section" id="bukti">
      <div class="container">
        <h2>Kepercayaan &amp; Kualitas</h2>
        <div class="grid-3">
          <div class="mini-card">
            <div class="mini-card__t">Produsen</div>
            <div class="mini-card__v">Ozverligsportwear</div>
            <div class="mini-card__s">Produksi jersey custom & komunitas</div>
          </div>
          <div class="mini-card">
            <div class="mini-card__t">Kolaborasi</div>
            <div class="mini-card__v">Kemalikart</div>
            <div class="mini-card__s">Konsep visual & artwork</div>
          </div>
          <div class="mini-card">
            <div class="mini-card__t">Pemesanan</div>
            <div class="mini-card__v">Pre-Order</div>
            <div class="mini-card__s">Batch produksi terjadwal</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ── ORDER FORM ── -->
    <section class="section section--alt" id="order">
      <div class="container">
        <h2>Form Pemesanan</h2>
        <p class="sublead">Isi form di bawah. Setelah submit, Anda diarahkan ke WhatsApp untuk konfirmasi.</p>

        <div class="form-wrap">
          <div id="formError" class="form-error"></div>
          <form id="orderForm" method="post" action="<?= h(BASE_PATH) ?>/order.php" novalidate>
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

            <div class="form-grid">
              <div class="form-field">
                <label for="inp_name">Nama Lengkap</label>
                <input id="inp_name" type="text" name="name" required maxlength="80" placeholder="Budi Santoso"
                  autocomplete="name">
              </div>

              <div class="form-field">
                <label for="inp_phone">Nomor WhatsApp</label>
                <input id="inp_phone" type="tel" name="phone" required maxlength="20" placeholder="0812xxxxxxx"
                  autocomplete="tel">
              </div>

              <div class="form-field full">
                <label for="inp_address">Alamat Lengkap</label>
                <textarea id="inp_address" name="address" required maxlength="300"
                  placeholder="Jalan, Kelurahan, Kecamatan, Kota, Provinsi, Kodepos"></textarea>
              </div>

              <div class="form-field">
                <label for="inp_design">Pilih Desain</label>
                <select id="inp_design" name="design" required>
                  <option value="">— Pilih desain —</option>
                  <option value="Ichigo">Kamen Rider Ichigo</option>
                  <option value="Black">Kamen Rider Black</option>
                  <option value="Ichigo + Black (Paket Doble)">Paket Doble – Ichigo + Black</option>
                </select>
              </div>

              <div class="form-field">
                <label for="inp_size">Ukuran</label>
                <select id="inp_size" name="size" required>
                  <option value="">— Pilih ukuran —</option>
                  <option>S</option>
                  <option>M</option>
                  <option>L</option>
                  <option>XL</option>
                  <option>XXL</option>
                  <option>XXXL</option>
                </select>
              </div>

              <div class="form-field">
                <label for="inp_qty">Jumlah</label>
                <input id="inp_qty" type="number" name="qty" min="1" max="20" value="1" required>
              </div>

              <div class="form-field full">
                <label for="inp_note">Catatan (opsional)</label>
                <textarea id="inp_note" name="note" maxlength="300"
                  placeholder="Misal: packing aman, request warna, dll."></textarea>
              </div>
            </div>

            <div class="form-check">
              <input type="checkbox" id="agree_dp" name="agree_dp" value="1" required>
              <label for="agree_dp">Saya setuju DP minimal <?= idr(PRICE_DP) ?> / jersey dan memahami timeline
                produksi.</label>
            </div>

            <div style="margin-top:1.25rem">
              <button class="btn btn--block" type="submit" id="btnSubmit">Kirim Pesanan</button>
            </div>

            <div class="form-helper">
              Butuh info? <a href="https://wa.me/<?= h(WA_NUMBER) ?>" target="_blank" rel="noopener noreferrer" data-wa
                data-track="wa_contact">WhatsApp +62816-1726-0666</a>
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- ── FAQ ── -->
    <section class="section" id="faq">
      <div class="container">
        <h2>FAQ</h2>
        <div class="faq-list">
          <details>
            <summary>Berapa harga jersey?</summary>
            <div>Harga promo 1 jersey IDR 225.000, paket doble 2 jersey IDR 400.000. DP minimal IDR 100.000 per jersey.
              Ongkir ditanggung pemesan.</div>
          </details>
          <details>
            <summary>Kapan periode pemesanan dan produksi?</summary>
            <div>Pre-order: 27 Februari – 08 Maret 2026. Produksi: 09 – 21 Maret 2026. Pengiriman dilakukan setelah
              produksi selesai.</div>
          </details>
          <details>
            <summary>Apa saja spesifikasi jersey?</summary>
            <div>Material Andromax Sublimation; Crest 3D Tatami/Polyflock; Apparel Crest 3D HD; Collar/Cuff Rib Knit;
              Size Tag DTF.</div>
          </details>
          <details>
            <summary>Bagaimana cara pemesanan?</summary>
            <div>Isi form pemesanan di halaman ini, lalu lanjutkan konfirmasi dan pembayaran DP via WhatsApp.</div>
          </details>
          <details>
            <summary>Apakah bisa request ukuran custom?</summary>
            <div>Ukuran yang tersedia S, M, L, XL, XXL, XXXL. Untuk request khusus, hubungi admin via WhatsApp.</div>
          </details>
        </div>
      </div>
    </section>

    <!-- ── SEO Content ── -->
    <section class="section section--alt" id="seo-content">
      <div class="container">
        <h2>Jersey Kamen Rider untuk Penggemar Tokusatsu Indonesia</h2>
        <div class="seo-text" style="max-width:700px">
          <p>Jersey Kamen Rider merupakan apparel yang diminati komunitas tokusatsu Indonesia. <strong>Jersey Series
              Fantasy Kamen Rider Ichigo &amp; Black (Edisi 1)</strong> terinspirasi era 90an dan film <strong>Satria
              Baja Hitam</strong>, diwujudkan menjadi jersey sporty premium bergaya jagoan masa kecil.</p>
          <p>Diproduksi oleh <strong>Ozverligsportwear</strong> berkolaborasi dengan <strong>Kemalikart</strong> —
            mengutamakan tampilan modern, cocok harian, komunitas, maupun riding. Sistem <strong>pre-order</strong>
            memastikan produksi terjadwal dan kualitas terjaga.</p>
        </div>
      </div>
    </section>

  </main>

  <!-- ── FOOTER ── -->
  <footer class="footer">
    <div class="container footer__grid">
      <div>
        <div class="footer__brand">Ozverligsportwear x Kemalikart</div>
        <div class="footer__muted">Jersey Series Fantasy Kamen Rider — Edisi 1 &copy; 2026</div>
      </div>
      <nav class="footer__links" aria-label="Footer nav">
        <a href="#order">Pesan</a>
        <a href="#faq">FAQ</a>
        <a href="<?= h(BASE_PATH) ?>/sitemap.xml">Sitemap</a>
      </nav>
    </div>
  </footer>

  <!-- ── Sticky CTA (mobile) ── -->
  <div class="sticky-cta" role="region" aria-label="Quick actions">
    <a class="btn btn--ghost" href="https://wa.me/<?= h(WA_NUMBER) ?>" target="_blank" rel="noopener noreferrer" data-wa
      data-track="wa_contact">WhatsApp</a>
    <a class="btn" href="#order" data-track="initiate_checkout">Pesan</a>
  </div>

  <!-- Deferred JS -->
  <script src="<?= h(BASE_PATH) ?>/assets/js/app.js" defer></script>

</body>

</html>