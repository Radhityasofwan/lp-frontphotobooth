<?php
require_once __DIR__ . '/config.php';
session_start();

$order = $_SESSION['last_order'] ?? null;
$wa = $order['wa'] ?? ("https://wa.me/" . WA_NUMBER);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pesanan Tercatat | Jersey Kamen Rider - Ozverligsportwear</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="<?= htmlspecialchars(BASE_PATH) ?>/assets/css/style.css">

  <script>
    window.__TRACKING__ = {
      basePath: "<?= htmlspecialchars(BASE_PATH) ?>",
      ga4: "<?= htmlspecialchars(GA4_ID) ?>",
      gadsAw: "<?= htmlspecialchars(GADS_AW_ID) ?>",
      gadsLabel: "<?= htmlspecialchars(GADS_CONV_LABEL) ?>",
      metaPixel: "<?= htmlspecialchars(META_PIXEL_ID) ?>"
    };
  </script>
</head>
<body>
  <main class="section">
    <div class="container">
      <div class="card" style="padding:18px">
        <h1 style="margin:0 0 8px; font-size:28px">Pesanan Anda Sudah Tercatat</h1>
        <p class="sublead" style="margin:0 0 14px">
          Langkah terakhir: klik tombol WhatsApp untuk konfirmasi ke admin. Ini mempercepat proses dan memastikan slot pre-order aman.
        </p>

        <div class="proof-bar" style="margin-top:0">
          <div class="proof-bar__item">
            <div class="proof-bar__k">Nama</div>
            <div class="proof-bar__v"><?= htmlspecialchars($order['name'] ?? '-') ?></div>
          </div>
          <div class="proof-bar__item">
            <div class="proof-bar__k">Desain</div>
            <div class="proof-bar__v"><?= htmlspecialchars($order['design'] ?? '-') ?></div>
          </div>
          <div class="proof-bar__item">
            <div class="proof-bar__k">Ukuran / Jumlah</div>
            <div class="proof-bar__v"><?= htmlspecialchars(($order['size'] ?? '-') . " / " . ($order['qty'] ?? '-')) ?></div>
          </div>
        </div>

        <div class="cta-row" style="margin-top:14px">
          <a class="btn" href="<?= htmlspecialchars($wa) ?>" data-track="thankyou_wa">Konfirmasi via WhatsApp</a>
          <a class="btn btn--ghost" href="<?= htmlspecialchars(BASE_PATH) ?>/" data-track="thankyou_back">Kembali ke Halaman Utama</a>
        </div>

        <p class="note" style="margin-top:8px">
          Jika WhatsApp tidak terbuka otomatis, klik tombol di atas.
        </p>
      </div>
    </div>
  </main>

  <script>
    (function(){
      const T = window.__TRACKING__ || {};
      const hasGA4 = !!(T.ga4 && T.ga4.trim());
      const hasGAds = !!(T.gadsAw && T.gadsAw.trim());
      const hasMeta = !!(T.metaPixel && T.metaPixel.trim());

      function load(src){
        const s=document.createElement('script'); s.async=true; s.src=src; document.head.appendChild(s);
      }

      if (hasGA4) {
        load('https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(T.ga4));
        window.dataLayer = window.dataLayer || [];
        window.gtag = function(){ window.dataLayer.push(arguments); };
        window.gtag('js', new Date());
        window.gtag('config', T.ga4, { anonymize_ip: true });
        window.gtag('event', 'form_submit', { event_category:'conversion', event_label:'jersey_order' });
      }

      if (hasGAds) {
        load('https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(T.gadsAw));
        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function(){ window.dataLayer.push(arguments); };
        window.gtag('js', new Date());
        window.gtag('config', T.gadsAw);

        if (T.gadsLabel) {
          window.gtag('event', 'conversion', { 'send_to': T.gadsAw + '/' + T.gadsLabel });
        }
      }

      if (hasMeta) {
        !function(f,b,e,v,n,t,s){
          if(f.fbq)return; n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};
          if(!f._fbq)f._fbq=n; n.push=n; n.loaded=!0; n.version='2.0';
          n.queue=[]; t=b.createElement(e); t.async=!0;
          t.src=v; s=b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t,s)
        }(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
        window.fbq('init', T.metaPixel);
        window.fbq('track', 'PageView');
        window.fbq('track', 'Lead');
      }
    })();
  </script>
</body>
</html>