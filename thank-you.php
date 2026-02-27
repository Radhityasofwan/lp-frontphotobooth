<?php
require_once __DIR__ . '/config.php';

// Pastikan session sudah berjalan untuk akses $_SESSION yang stabil
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Fallback keamanan XSS jika fungsi h() belum didefinisikan di config.php
if (!function_exists('h')) {
  function h($string)
  {
    return htmlspecialchars((string) $string, ENT_QUOTES, 'UTF-8');
  }
}

$order = $_SESSION['last_order'] ?? null;
// Gunakan constant WA_NUMBER dengan pengecekan untuk stabilitas
$defaultWa = defined('WA_NUMBER') ? WA_NUMBER : '';
$waUrl = $order['wa'] ?? 'https://wa.me/' . $defaultWa;

// Konfigurasi Redirect
$autoRedirect = ($order !== null);
$redirectDelay = 8; // Waktu tunggu dalam detik
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pesanan Tercatat | Ozverligsportwear</title>
  <meta name="robots" content="noindex, nofollow">
  <meta name="theme-color" content="#ffffff">

  <?php if ($autoRedirect): ?>
    <meta http-equiv="refresh" content="<?= $redirectDelay ?>;url=<?= h($waUrl) ?>">
  <?php endif; ?>

  <link rel="stylesheet" href="<?= h(defined('BASE_PATH') ? BASE_PATH : '') ?>/assets/css/style.css">

  <!-- Tracking config -->
  <script>
    window.__T__ = {
      ga4: "<?= h(defined('GA4_ID') ? GA4_ID : '') ?>",
      gadsAw: "<?= h(defined('GADS_AW_ID') ? GADS_AW_ID : '') ?>",
      gadsLabel: "<?= h(defined('GADS_CONV_LABEL') ? GADS_CONV_LABEL : '') ?>",
      meta: "<?= h(defined('META_PIXEL_ID') ? META_PIXEL_ID : '') ?>"
    };
  </script>
</head>

<body>
  <main class="section">
    <div class="container ty-container">
      <div class="card ty-card">
        <div style="margin-bottom:1.25rem">
          <span class="badge badge--red" style="font-size:1rem;padding:.4rem .8rem; border-radius: 4px;">✓ Pesanan
            Tercatat</span>
        </div>

        <h1 class="ty-title">Satu langkah lagi — konfirmasi via WhatsApp</h1>
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
          <a class="btn btn--ghost" href="<?= h(defined('BASE_PATH') ? BASE_PATH : '') ?>/" data-track="back_home">
            <span>← Kembali ke Halaman Utama</span>
          </a>
        </div>

        <?php if ($autoRedirect): ?>
          <p class="ty-redirect">
            Anda akan diarahkan otomatis ke WhatsApp dalam <?= $redirectDelay ?> detik…
          </p>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <!-- Conversion tracking (Modern ES6, Deferred) -->
  <script>
    (() => {
      const ObjectConfig = window.__T__ || {};

      const loadScript = (src, callback) => {
        const script = document.createElement('script');
        script.src = src;
        script.async = true;
        if (callback) script.onload = callback;
        document.head.appendChild(script);
      };

      // Setup Google Analytics 4 (GA4)
      if (ObjectConfig.ga4) {
        loadScript(`https://www.googletagmanager.com/gtag/js?id=${ObjectConfig.ga4}`, () => {
          window.dataLayer = window.dataLayer || [];
          window.gtag = function () { window.dataLayer.push(arguments); };
          gtag('js', new Date());
          gtag('config', ObjectConfig.ga4);

          // Trigger Events
          gtag('event', 'generate_lead', { value: 100000, currency: 'IDR' });
          gtag('event', 'purchase', {
            value: 100000,
            currency: 'IDR',
            transaction_id: `TRX-${Date.now()}`
          });
        });
      }

      // Setup Google Ads Conversion
      if (ObjectConfig.gadsAw && ObjectConfig.gadsLabel) {
        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
        loadScript(`https://www.googletagmanager.com/gtag/js?id=${ObjectConfig.gadsAw}`, () => {
          gtag('js', new Date());
          gtag('config', ObjectConfig.gadsAw);
          gtag('event', 'conversion', {
            send_to: `${ObjectConfig.gadsAw}/${ObjectConfig.gadsLabel}`,
            value: 100000,
            currency: 'IDR'
          });
        });
      }

      // Setup Meta (Facebook) Pixel
      if (ObjectConfig.meta) {
        !function (f, b, e, v, n, t, s) {
          if (f.fbq) return; n = f.fbq = function () { n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments) };
          if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
          n.queue = []; t = b.createElement(e); t.async = !0; t.src = v; s = b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t, s)
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', ObjectConfig.meta);
        fbq('track', 'PageView');
        fbq('track', 'Lead', { value: 100000, currency: 'IDR' });
      }
    })();
  </script>

  <script src="<?= h(defined('BASE_PATH') ? BASE_PATH : '') ?>/assets/js/app.js" defer></script>
</body>

</html>
<?php
// Hapus session order setelah dirender untuk menghindari duplicate tracking jika direfresh
if (isset($_SESSION['last_order'])) {
  unset($_SESSION['last_order']);
}
?>