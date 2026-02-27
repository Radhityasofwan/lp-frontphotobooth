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

  <!-- DNS Prefetch / Preconnect -->
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    onerror="this.onerror=null;this.href='<?= h(rtrim(BASE_URL, '/')) ?>/assets/vendor/bootstrap/bootstrap.min.css';">

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap JS Local Fallback -->
  <script>window.bootstrap || document.write('<script src="<?= h(rtrim(BASE_URL, '/')) ?>/assets/vendor/bootstrap/bootstrap.bundle.min.js"><\/script>');</script>

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

    .btn-outline-light,
    .btn-outline-secondary {
      border: 1px solid rgba(255, 255, 255, 0.2) !important;
      backdrop-filter: blur(5px);
      color: var(--text-muted);
      transition: all 0.3s ease;
    }

    .btn-outline-light:hover,
    .btn-outline-secondary:hover {
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
  </style>

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

<body class="bg-dark d-flex align-items-center min-vh-100 py-5">
  <main class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card card-dark p-4 p-md-5 rounded-0 shadow-lg text-center border-secondary">

          <div class="mb-4">
            <span class="badge bg-brand-red px-3 py-2 fs-6 rounded-1">✓ Pesanan Tercatat</span>
          </div>

          <h1 class="display-6 font-rajdhani fw-bold text-white mb-3">Satu langkah lagi — konfirmasi via WhatsApp</h1>
          <p class="text-secondary mb-4">
            Data pesanan Anda sudah kami terima. Klik tombol WhatsApp di bawah untuk konfirmasi dan proses pembayaran
            DP.
          </p>

          <?php if ($order): ?>
            <div class="bg-black border border-secondary p-3 text-start mb-4 rounded-1">
              <div class="row mb-2">
                <div class="col-4 text-secondary small fw-bold text-uppercase">Nama</div>
                <div class="col-8 text-white fw-medium"><?= h($order['name'] ?? '-') ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-secondary small fw-bold text-uppercase">Desain</div>
                <div class="col-8 text-white fw-medium"><?= h($order['design'] ?? '-') ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-4 text-secondary small fw-bold text-uppercase">Ukuran</div>
                <div class="col-8 text-white fw-medium"><?= h($order['size'] ?? '-') ?></div>
              </div>
              <div class="row">
                <div class="col-4 text-secondary small fw-bold text-uppercase">Jumlah</div>
                <div class="col-8 text-brand-green fw-bold"><?= (int) ($order['qty'] ?? 1) ?> pcs</div>
              </div>
            </div>
          <?php endif; ?>

          <div class="d-grid gap-3">
            <a class="btn btn-red py-3 skew-btn fs-5" href="<?= h($waUrl) ?>" target="_blank" rel="noopener noreferrer"
              data-track="wa_contact">
              <span>💬 Konfirmasi via WhatsApp</span>
            </a>
            <a class="btn btn-outline-secondary py-2 font-rajdhani fw-bold text-uppercase"
              href="<?= h(defined('BASE_PATH') ? BASE_PATH : '') ?>/" data-track="back_home">
              ← Kembali ke Halaman Utama
            </a>
          </div>

          <?php if ($autoRedirect): ?>
            <p class="text-secondary small mt-4 mb-0">
              Anda akan diarahkan otomatis ke WhatsApp dalam <?= $redirectDelay ?> detik…
            </p>
          <?php endif; ?>

        </div>
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
    })();
  </script>
</body>

</html>
<?php
// Hapus session order setelah dirender untuk menghindari duplicate tracking jika direfresh
if (isset($_SESSION['last_order'])) {
  unset($_SESSION['last_order']);
}
?>