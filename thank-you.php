<?php
require_once __DIR__ . '/config.php';

$order = $_SESSION['last_order'] ?? null;
$waUrl = $order['wa'] ?? 'https://wa.me/' . WA_NUMBER;

// Auto-redirect to WA after 5s if order exists
$autoRedirect = ($order !== null);
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pesanan Tercatat | Ozverligsportwear</title>
  <meta name="robots" content="noindex, nofollow">

  <?php if ($autoRedirect): ?>
    <meta http-equiv="refresh" content="8;url=<?= h($waUrl) ?>">
  <?php endif; ?>

  <link rel="stylesheet" href="<?= h(BASE_PATH) ?>/assets/css/style.css">

  <!-- Tracking config -->
  <script>
    window.__T__ = {
      ga4: "<?= h(GA4_ID) ?>",
      gadsAw: "<?= h(GADS_AW_ID) ?>",
      gadsLabel: "<?= h(GADS_CONV_LABEL) ?>",
      meta: "<?= h(META_PIXEL_ID) ?>"
    };
  </script>
</head>

<body>
  <main class="section">
    <div class="container ty-container">
      <div class="card ty-card">
        <div style="margin-bottom:1.25rem">
          <span class="badge badge--red" style="font-size:1rem;padding:.4rem .8rem">✓ Pesanan Tercatat</span>
        </div>

        <h1 class="ty-title">
          Satu langkah lagi — konfirmasi via WhatsApp
        </h1>
        <p class="ty-lead">
          Data pesanan Anda sudah kami terima. Klik tombol WhatsApp di bawah untuk konfirmasi dan proses pembayaran DP.
        </p>

        <?php if ($order): ?>
          <div class="ty-order-box">
            <div class="ty-order-row">
              <span class="ty-order-lbl">Nama</span>
              <strong class="ty-order-val"><?= h($order['name'] ?? '-') ?></strong>
            </div>
            <div class="ty-order-row">
              <span class="ty-order-lbl">Desain</span>
              <strong class="ty-order-val"><?= h($order['design'] ?? '-') ?></strong>
            </div>
            <div class="ty-order-row">
              <span class="ty-order-lbl">Ukuran</span>
              <strong class="ty-order-val"><?= h($order['size'] ?? '-') ?></strong>
            </div>
            <div class="ty-order-row">
              <span class="ty-order-lbl">Jumlah</span>
              <strong class="ty-order-val"><?= (int) ($order['qty'] ?? 1) ?> pcs</strong>
            </div>
          </div>
        <?php endif; ?>

        <div class="ty-actions">
          <a class="btn btn--wa" href="<?= h($waUrl) ?>" target="_blank" rel="noopener noreferrer"
            data-track="wa_contact">
            <span>💬 Konfirmasi via WhatsApp</span>
          </a>
          <a class="btn btn--ghost" href="<?= h(BASE_PATH) ?>/" data-track="back_home">
            <span>← Kembali ke Halaman Utama</span>
          </a>
        </div>

        <?php if ($autoRedirect): ?>
          <p class="ty-redirect">
            Anda akan diarahkan otomatis ke WhatsApp dalam 8 detik…
          </p>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <!-- Conversion tracking (inline, deferred) -->
  <script>
    (function () {
      var T = window.__T__ || {};

      function load(src, cb) {
        var s = document.createElement('script'); s.src = src; s.async = true;
        if (cb) s.onload = cb; document.head.appendChild(s);
      }

      // GA4 Lead
      if (T.ga4) {
        load('https://www.googletagmanager.com/gtag/js?id=' + T.ga4, function () {
          window.dataLayer = window.dataLayer || [];
          window.gtag = function () { window.dataLayer.push(arguments) };
          gtag('js', new Date()); gtag('config', T.ga4);
          gtag('event', 'generate_lead', { value: 100000, currency: 'IDR' });
          gtag('event', 'purchase', { value: 100000, currency: 'IDR', transaction_id: Date.now() });
        });
      }

      // Google Ads conversion
      if (T.gadsAw && T.gadsLabel) {
        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function () { window.dataLayer.push(arguments) };
        load('https://www.googletagmanager.com/gtag/js?id=' + T.gadsAw, function () {
          gtag('js', new Date()); gtag('config', T.gadsAw);
          gtag('event', 'conversion', { send_to: T.gadsAw + '/' + T.gadsLabel, value: 100000, currency: 'IDR' });
        });
      }

      // Meta Pixel Lead
      if (T.meta) {
        !function (f, b, e, v, n, t, s) {
          if (f.fbq) return; n = f.fbq = function () { n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments) };
          if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
          n.queue = []; t = b.createElement(e); t.async = !0; t.src = v; s = b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t, s)
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', T.meta);
        fbq('track', 'PageView');
        fbq('track', 'Lead', { value: 100000, currency: 'IDR' });
      }
    })();
  </script>

  <script src="<?= h(BASE_PATH) ?>/assets/js/app.js" defer></script>
</body>

</html>
<?php unset($_SESSION['last_order']); ?>