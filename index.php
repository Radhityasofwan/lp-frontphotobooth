<?php
require_once __DIR__ . '/config.php';
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Canonical halaman: BASE_URL + "/"
$canonical = rtrim(BASE_URL, '/') . '/';
$ogImage = rtrim(BASE_URL, '/') . '/assets/img/og-cover.webp';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Jersey Kamen Rider Ichigo & Black Edisi 1 | Jersey Satria Baja Hitam - Ozverligsportwear</title>
  <meta name="description" content="Open Pre-Order Jersey Series Fantasy Kamen Rider Ichigo & Black (Edisi 1). Nostalgia 90an terinspirasi Satria Baja Hitam. Harga IDR 225.000 / paket 2 IDR 400.000. DP minimal IDR 100.000.">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <link rel="canonical" href="<?= h($canonical) ?>">

  <!-- Open Graph -->
  <meta property="og:locale" content="id_ID">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Jersey Kamen Rider Ichigo & Black – Edisi 1">
  <meta property="og:description" content="Open Pre-Order. Nostalgia 90an terinspirasi Satria Baja Hitam. Diproduksi Ozverligsportwear x Kemalikart.">
  <meta property="og:url" content="<?= h($canonical) ?>">
  <meta property="og:image" content="<?= h($ogImage) ?>">
  <meta property="og:image:alt" content="Jersey Kamen Rider Ichigo & Black Edisi 1">

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image">

  <!-- CSS -->
  <link rel="preload" href="<?= h(BASE_PATH) ?>/assets/css/style.css" as="style">
  <link rel="stylesheet" href="<?= h(BASE_PATH) ?>/assets/css/style.css">

  <!-- Structured Data -->
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@graph":[
      {
        "@type":"Organization",
        "name":"<?= h(BRAND_NAME) ?>",
        "url":"<?= h($canonical) ?>",
        "logo":"<?= h(rtrim(BASE_URL,'/')) ?>/assets/img/logo-ozverlig.webp",
        "sameAs":[
          "https://www.instagram.com/ozverlig",
          "https://www.instagram.com/kemalikart"
        ]
      },
      {
        "@type":"Product",
        "name":"Jersey Series Fantasy Kamen Rider Ichigo & Black (Edisi 1)",
        "brand":{"@type":"Brand","name":"<?= h(BRAND_NAME) ?>"},
        "description":"Nostalgia 90an terinspirasi Satria Baja Hitam. Kolaborasi produksi Ozverligsportwear x Kemalikart. Open pre-order periode 27 Februari - 08 Maret 2026.",
        "image":[
          "<?= h(rtrim(BASE_URL,'/')) ?>/assets/img/hero.webp",
          "<?= h(rtrim(BASE_URL,'/')) ?>/assets/img/ichigo.webp",
          "<?= h(rtrim(BASE_URL,'/')) ?>/assets/img/black.webp"
        ],
        "offers":[
          {"@type":"Offer","priceCurrency":"IDR","price":"225000","availability":"https://schema.org/PreOrder","url":"<?= h($canonical) ?>"},
          {"@type":"Offer","priceCurrency":"IDR","price":"400000","availability":"https://schema.org/PreOrder","url":"<?= h($canonical) ?>"}
        ]
      }
    ]
  }
  </script>

  <!-- Tracking Config -->
  <script>
    window.__TRACKING__ = {
      basePath: "<?= h(BASE_PATH) ?>",
      ga4: "<?= h(GA4_ID) ?>",
      gadsAw: "<?= h(GADS_AW_ID) ?>",
      gadsLabel: "<?= h(GADS_CONV_LABEL) ?>",
      metaPixel: "<?= h(META_PIXEL_ID) ?>"
    };
  </script>
</head>

<body>
  <!-- NOTE: isi body Anda tetap seperti sebelumnya, tidak saya ubah -->
  <!-- PENTING: pastikan semua src/href assets pakai <?= h(BASE_PATH) ?> prefix -->

  <header class="topbar">
    <div class="container topbar__inner">
      <div class="brand">
        <img class="brand__logo" src="<?= h(BASE_PATH) ?>/assets/img/logo-ozverlig.webp" alt="Ozverligsportwear" width="32" height="32" loading="eager">
        <div class="brand__text">
          <div class="brand__title">Ozverligsportwear</div>
          <div class="brand__sub">Collab with Kemalikart</div>
        </div>
      </div>
      <nav class="nav">
        <a href="#produk">Produk</a>
        <a href="#spesifikasi">Spesifikasi</a>
        <a href="#harga">Harga</a>
        <a href="#jadwal">Jadwal</a>
        <a href="#order" class="btn btn--small">Pesan</a>
      </nav>
    </div>
  </header>

  <main>
    <!-- HERO -->
    <section class="hero" id="home">
      <div class="container hero__grid">
        <div class="hero__content">
          <div class="badge-row">
            <span class="badge badge--red">Open Pre-Order</span>
            <span class="badge badge--dark">Limited Edition</span>
          </div>

          <h1>Jersey Kamen Rider Ichigo & Black – Edisi 1</h1>

          <p class="lead">
            Nostalgia ditahun 90an, terinspirasi dari film <strong>Satria Baja Hitam</strong> untuk menjadikannya sebuah jersey bernuansa jagoan kita dulu.
            Diproduksi oleh <strong>Ozverligsportwear</strong> berkolaborasi dengan <strong>Kemalikart</strong>.
          </p>

          <div class="cta-row">
            <a class="btn" href="#order" data-track="cta_primary">Pesan Sekarang</a>
            <a class="btn btn--ghost" href="#harga" data-track="cta_secondary">Lihat Harga</a>
          </div>

          <div class="micro-trust">
            <div class="micro-trust__item">
              <div class="micro-trust__label">Pemesanan</div>
              <div class="micro-trust__value">27 Feb – 08 Mar 2026</div>
            </div>
            <div class="micro-trust__item">
              <div class="micro-trust__label">Produksi</div>
              <div class="micro-trust__value">09 – 21 Mar 2026</div>
            </div>
            <div class="micro-trust__item">
              <div class="micro-trust__label">DP Minimal</div>
              <div class="micro-trust__value">IDR 100.000 / jersey</div>
            </div>
          </div>
        </div>

        <div class="hero__media">
          <img
            class="hero__img"
            src="<?= h(BASE_PATH) ?>/assets/img/hero.webp"
            alt="Jersey Series Fantasy Kamen Rider Ichigo & Black Edisi 1"
            width="900"
            height="900"
            loading="eager"
            decoding="async"
          >
          <p class="media-note">Gambar produk: ganti file di assets/img (WebP) untuk hasil paling tajam & cepat.</p>
        </div>
      </div>
    </section>

    <!-- PRODUCT SHOWCASE -->
    <section class="section" id="produk">
      <div class="container">
        <h2>2 Desain Edisi Perdana</h2>
        <p class="sublead">Jersey Series Fantasy Kamen Rider <strong>Ichigo</strong> & <strong>Black</strong>. Nuansa sporty modern, tetap membawa rasa nostalgia.</p>

        <div class="grid-2">
          <article class="card">
            <div class="card__img">
              <img src="<?= h(BASE_PATH) ?>/assets/img/ichigo.webp" alt="Fantasy Kamen Rider Ichigo v.01" width="800" height="800" loading="lazy" decoding="async">
            </div>
            <div class="card__body">
              <h3>Fantasy Kamen Rider Ichigo v.01</h3>
              <p>Collaboration with Kemalikart. Cocok untuk komunitas, daily wear, dan riding.</p>
            </div>
          </article>

          <article class="card">
            <div class="card__img">
              <img src="<?= h(BASE_PATH) ?>/assets/img/black.webp" alt="Fantasy Kamen Rider Black v.01" width="800" height="800" loading="lazy" decoding="async">
            </div>
            <div class="card__body">
              <h3>Fantasy Kamen Rider Black v.01</h3>
              <p>Karakter kuat, tegas, dan tetap clean. Limited drop.</p>
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

    <!-- SPEC -->
    <section class="section section--alt" id="spesifikasi">
      <div class="container">
        <h2>Spesifikasi Jersey</h2>
        <div class="spec">
          <ul class="spec__list">
            <li><strong>Material Fabric:</strong> Andromax Sublimation</li>
            <li><strong>Crest:</strong> 3D Tatami / Polyflock</li>
            <li><strong>Apparel Crest:</strong> 3D HD</li>
            <li><strong>Collar/Cuff:</strong> Rib Knit</li>
            <li><strong>Size Tag:</strong> DTF</li>
          </ul>
          <div class="spec__note">
            <h3>Catatan kualitas</h3>
            <p>Dirancang untuk kenyamanan dan tampilan yang rapi. Tetap sporty, tetap relevan, cocok dipakai harian maupun riding.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- PRICE -->
    <section class="section" id="harga">
      <div class="container">
        <h2>Harga & DP</h2>

        <div class="price-grid">
          <div class="price-card">
            <div class="price-card__title">1 Jersey</div>
            <div class="price-card__price">IDR 225.000</div>
            <div class="price-card__meta">DP minimal IDR 100.000 / jersey</div>
          </div>
          <div class="price-card price-card--featured">
            <div class="price-card__title">Paket Doble (2 Jersey)</div>
            <div class="price-card__price">IDR 400.000</div>
            <div class="price-card__meta">Lebih hemat untuk koleksi 2 desain</div>
          </div>
        </div>

        <p class="note">Note: Ongkir ditanggung pemesan.</p>
      </div>
    </section>

    <!-- SCHEDULE -->
    <section class="section section--alt" id="jadwal">
      <div class="container">
        <h2>Periode Pemesanan, Produksi, Pengiriman</h2>
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
          <strong>Pre-order ditutup sesuai periode.</strong> Amankan slot lebih awal untuk memastikan pesanan tercatat dengan rapi.
        </div>
      </div>
    </section>

    <!-- SOCIAL PROOF -->
    <section class="section" id="bukti">
      <div class="container">
        <h2>Kepercayaan & Bukti Sosial</h2>
        <p class="sublead">
          Tambahkan screenshot testimoni/DM/order masuk di bagian ini (optional) untuk meningkatkan trust dan konversi.
        </p>

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

    <!-- ORDER FORM -->
    <section class="section section--alt" id="order">
      <div class="container">
        <h2>Form Pemesanan</h2>
        <p class="sublead">
          Isi form berikut. Setelah submit, Anda akan diarahkan otomatis ke WhatsApp untuk konfirmasi pesanan.
        </p>

        <form class="form" method="post" action="<?= h(BASE_PATH) ?>/order.php" id="orderForm" novalidate>
          <input type="hidden" name="utm_source" id="utm_source">
          <input type="hidden" name="utm_medium" id="utm_medium">
          <input type="hidden" name="utm_campaign" id="utm_campaign">
          <input type="hidden" name="utm_content" id="utm_content">
          <input type="hidden" name="utm_term" id="utm_term">

          <div class="form__grid">
            <label>
              <span>Nama Lengkap</span>
              <input type="text" name="name" required maxlength="80" placeholder="Contoh: Budi Santoso">
            </label>

            <label>
              <span>Nomor WhatsApp</span>
              <input type="tel" name="phone" required maxlength="20" placeholder="Contoh: 0812xxxxxxx">
            </label>

            <label class="full">
              <span>Alamat Lengkap</span>
              <textarea name="address" required maxlength="240" placeholder="Jalan, Kecamatan, Kota/Kab, Provinsi"></textarea>
            </label>

            <label>
              <span>Pilih Desain</span>
              <select name="design" required>
                <option value="">Pilih</option>
                <option value="Ichigo">Ichigo</option>
                <option value="Black">Black</option>
                <option value="Ichigo + Black (Paket 2)">Ichigo + Black (Paket 2)</option>
              </select>
            </label>

            <label>
              <span>Ukuran</span>
              <select name="size" required>
                <option value="">Pilih</option>
                <option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option><option>XXXL</option>
              </select>
            </label>

            <label>
              <span>Jumlah</span>
              <input type="number" name="qty" required min="1" max="20" value="1">
            </label>

            <label class="full">
              <span>Catatan (opsional)</span>
              <textarea name="note" maxlength="240" placeholder="Contoh: minta kirim packing aman, warna dominan, dll"></textarea>
            </label>
          </div>

          <label class="check">
            <input type="checkbox" name="agree_dp" value="1" required>
            <span>Saya setuju DP minimal IDR 100.000 / jersey</span>
          </label>

          <button class="btn btn--block" type="submit" data-track="form_submit">Kirim Pesanan</button>

          <div class="helper">
            Informasi & pertanyaan lebih lanjut: <a href="#" id="waLink" data-track="wa_click">WhatsApp +62816-1726-0666</a>
          </div>
        </form>
      </div>
    </section>

    <!-- FAQ -->
    <section class="section" id="faq">
      <div class="container">
        <h2>FAQ</h2>
        <div class="faq">
          <details>
            <summary>Berapa harga jersey?</summary>
            <div>Harga 1 jersey IDR 225.000 dan paket doble (2 jersey) IDR 400.000. DP minimal IDR 100.000 per jersey.</div>
          </details>
          <details>
            <summary>Kapan periode pemesanan dan produksi?</summary>
            <div>Pre-order 27 Februari – 08 Maret 2026. Produksi 09 – 21 Maret 2026. Pengiriman setelah produksi selesai.</div>
          </details>
          <details>
            <summary>Spesifikasi jersey apa saja?</summary>
            <div>Material Andromax Sublimation; Crest 3D Tatami/Polyflock; Apparel Crest 3D HD; Collar/Cuff Rib Knit; Size Tag DTF.</div>
          </details>
          <details>
            <summary>Bagaimana cara pemesanan?</summary>
            <div>Isi form pemesanan di halaman ini, lalu lanjutkan konfirmasi melalui WhatsApp.</div>
          </details>
        </div>
      </div>
    </section>

    <!-- SEO Content Block -->
    <section class="section section--alt" id="seo">
      <div class="container">
        <h2>Jersey Kamen Rider untuk Penggemar Tokusatsu Indonesia</h2>
        <div class="seo-text">
          <p>
            Jersey Kamen Rider merupakan apparel yang banyak diminati oleh penggemar tokusatsu di Indonesia. Produk <strong>Jersey Series Fantasy Kamen Rider Ichigo & Black (Edisi 1)</strong> ini terinspirasi dari era 90an dan film <strong>Satria Baja Hitam</strong>, lalu diwujudkan menjadi jersey dengan nuansa jagoan masa kecil kita.
          </p>
          <p>
            Diproduksi oleh <strong>Ozverligsportwear</strong> dan berkolaborasi dengan <strong>Kemalikart</strong>, jersey ini mengutamakan tampilan sporty modern, cocok digunakan untuk aktivitas harian, komunitas, maupun riding. Sistem pemesanan menggunakan <strong>pre-order</strong> agar produksi dapat terjadwal dan kualitas terjaga.
          </p>
          <p>
            Informasi harga, periode pemesanan, produksi, dan pengiriman telah dicantumkan dengan jelas di halaman ini. Untuk pemesanan, silakan isi form dan lanjutkan konfirmasi melalui WhatsApp.
          </p>
        </div>
      </div>
    </section>

    <footer class="footer">
      <div class="container footer__grid">
        <div>
          <div class="footer__brand">Ozverligsportwear x Kemalikart</div>
          <div class="footer__muted">Jersey Series Fantasy Kamen Rider Ichigo & Black — Edisi 1</div>
        </div>
        <div class="footer__links">
          <a href="#order">Pesan</a>
          <a href="#faq">FAQ</a>
          <a href="<?= h(BASE_PATH) ?>/sitemap.xml">Sitemap</a>
        </div>
      </div>
    </footer>

    <!-- Sticky bottom CTA (mobile) -->
    <div class="sticky-cta" role="region" aria-label="Quick actions">
      <a class="btn btn--small" href="#order" data-track="sticky_order">Pesan</a>
      <a class="btn btn--small btn--ghost" href="#" id="waSticky" data-track="sticky_wa">WhatsApp</a>
    </div>
  </main>

   <script src="<?= h(BASE_PATH) ?>/assets/js/app.js" defer></script>
</body>
</html>