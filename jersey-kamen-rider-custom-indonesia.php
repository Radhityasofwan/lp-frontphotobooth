<?php
require_once __DIR__ . '/config.php';

// Fallback keamanan XSS
if (!function_exists('h')) {
    function h($string)
    {
        return htmlspecialchars((string) $string, ENT_QUOTES, 'UTF-8');
    }
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jersey Kamen Rider Custom Indonesia | Ozverligsportwear x Kemalikart</title>
    <meta name="description"
        content="Mengapa jersey kamen rider custom sedang booming di Indonesia? Temukan inspirasi desain, nostalgia Satria Baja Hitam, dan kualitas apparel komunitas rider.">
    <meta name="keywords"
        content="jersey kamen rider custom, jersey kamen rider indonesia, jersey satria baja hitam, jersey tokusatsu indonesia, jersey komunitas rider, jersey fantasy kamen rider, jersey anime custom indonesia">

    <meta property="og:title" content="Jersey Kamen Rider Custom Indonesia">
    <meta property="og:description"
        content="Temukan alasan kenapa jersey kamen rider edisi khusus ini wajib dimiliki oleh komunitas tokusatsu tanah air.">
    <meta property="og:image" content="<?= h(asset('assets/img/hero.webp')) ?>">
    <meta property="og:url" content="<?= h(BASE_URL) ?>/jersey-kamen-rider-custom-indonesia.php">
    <meta name="theme-color" content="#050505">

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        onerror="this.onerror=null;this.href='<?= h(asset('assets/vendor/bootstrap/bootstrap.min.css')) ?>';">

    <style>
        :root {
            --bg-dark: #050505;
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

        h1,
        h2,
        h3,
        .font-rajdhani {
            font-family: 'Rajdhani', system-ui, sans-serif;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .text-gradient {
            background: linear-gradient(90deg, #fff, #999);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-red {
            background: linear-gradient(135deg, #ff1e27 0%, #d00f18 100%);
            color: #fff;
            border: none;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-red:hover {
            background: linear-gradient(135deg, #ff333b 0%, #e6101a 100%);
            color: #fff;
            transform: translateY(-2px);
        }

        .article-content {
            line-height: 1.8;
            font-size: 1.1rem;
        }
    </style>

    <?php if (!empty(META_PIXEL_ID)): ?>
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return; n = f.fbq = function () {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                }; if (!f._fbq) f._fbq = n;
                n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async = !0;
                t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s)
            }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

            fbq('init', '<?= h(META_PIXEL_ID) ?>');
            fbq('track', 'PageView');
            fbq('track', 'ViewContent', { content_name: 'Artikel SEO - Kamen Rider', content_category: 'Article' });
        </script>
    <?php endif; ?>

    <?php if (!empty(GA4_ID)): ?>
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

    <header class="sticky-top bg-dark border-bottom border-dark py-2"
        style="background: rgba(6,6,8,0.95) !important; backdrop-filter: blur(10px);">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <a href="<?= h(BASE_PATH) ?>/" class="text-decoration-none d-flex align-items-center gap-2">
                    <img src="<?= h(asset('assets/img/logo-ozverlig.webp')) ?>" alt="Logo Ozverligsportwear" width="36"
                        height="36">
                    <div class="lh-1">
                        <div class="font-rajdhani text-white fs-6">Ozverligsportwear</div>
                        <div class="text-secondary" style="font-size:0.75rem;">x Kemalikart</div>
                    </div>
                </a>
            </div>
            <a href="<?= h(BASE_PATH) ?>/#order" class="btn btn-red btn-sm px-3 skew-btn"><span>Pesan Jersey</span></a>
        </div>
    </header>

    <main class="py-5">
        <div class="container" style="max-width: 800px;">

            <div class="mb-4">
                <a href="<?= h(BASE_PATH) ?>/" class="text-secondary text-decoration-none small">← Kembali ke Halaman
                    Utama</a>
            </div>

            <h1 class="display-5 font-rajdhani fw-bold mb-4">Geliat <span class="text-gradient">Jersey Kamen Rider
                    Custom</span> di Indonesia</h1>

            <img src="<?= h(asset('assets/img/hero.webp')) ?>" class="img-fluid rounded mb-5 w-100"
                alt="Jersey Kamen Rider Custom" style="object-fit: cover; max-height: 400px;">

            <article class="article-content text-secondary">
                <p class="mb-4 text-white">Di kalangan penggemar <strong>tokusatsu Indonesia</strong>, fenomena apparel
                    bertema pahlawan super Jepang sedang mencapai puncaknya. Tidak lagi sekadar mengoleksi mainan atau
                    kaset lama, kini komunitas berekspresi melalui <strong>jersey kamen rider custom</strong>.</p>

                <h2 class="h3 font-rajdhani fw-bold text-white mt-5 mb-3">Kenapa Jersey Rider Booming?</h2>
                <p class="mb-4">Alasan utamanya adalah nostalgia. Karakter legendaris dari era Showa, terkhusus
                    <strong>Satria Baja Hitam (Kamen Rider Black)</strong> maupun sang pelopor <strong>Kamen Rider
                        Ichigo</strong>, memiliki tempat istimewa di hati anak-anak Indonesia yang tumbuh di tahun 90an.
                    Menggabungkan unsur fiksi tersebut ke dalam <strong>jersey anime custom Indonesia</strong> membuat
                    penggunanya bisa memancarkan identitas superhero favorit mereka sambil tetap tampil kasual dan
                    sporty.</p>

                <h2 class="h3 font-rajdhani fw-bold text-white mt-5 mb-3">Inspirasi Desain: Elegan dan Agresif</h2>
                <p class="mb-4">Desain <strong>jersey fantasy kamen rider</strong> kini tidak lagi kekanak-kanakan.
                    Melalui kolaborasi desainer lokal sekelas Ozverligsportwear dan Kemalikart, pola armor pahlawan
                    ditransformasikan menjadi garis-garis aerodinamis yang menyatu sempurna. Penggunaan palet warna
                    gelap (deep black) yang dipadukan dengan aksen merah menyala khas mata Rider memberikan kesan
                    premium. Ini bukan sekadar kaos, ini adalah identitas kecepatan.</p>

                <h2 class="h3 font-rajdhani fw-bold text-white mt-5 mb-3">Pilihan Utama Komunitas Riding</h2>
                <p class="mb-4">Bagi para penggila roda dua, menggunakan <strong>jersey komunitas rider</strong> dengan
                    tema tokusatsu memberikan kebanggaan ganda: menyalurkan hobi riding sekaligus memamerkan karakter
                    pahlawan roda dua favorit. Material <em>Andromax Sublimasi</em> menjamin jersey tetap sejuk diterpa
                    angin jalanan, mudah kering dari keringat, dan warnanya tak gampang pudar tertembak matahari.</p>

                <hr class="border-secondary my-5">

                <div class="bg-dark p-4 p-md-5 rounded border border-secondary text-center">
                    <h3 class="font-rajdhani text-white mb-3">Miliki Jersey Kamen Rider Kustom Anda Sekarang</h3>
                    <p class="mb-4">Tertarik untuk menjadi bagian dari tren ini? Kami sedang membuka masa Pre-Order
                        eksklusif untuk koleksi terbatas <strong>Kamen Rider Ichigo &amp; Black</strong> edisi pertama.
                    </p>
                    <a href="<?= h(BASE_PATH) ?>/#order" class="btn btn-red btn-lg px-5 py-3 fs-5 w-100 w-md-auto">
                        <span class="d-block font-rajdhani fw-bold">LIHAT KATALOG & PESAN SEKARANG</span>
                    </a>
                </div>
            </article>

        </div>
    </main>

    <footer class="py-4 bg-black border-top border-dark mt-5">
        <div class="container text-center">
            <div class="font-rajdhani fw-bold text-white fs-5">Ozverligsportwear x Kemalikart</div>
            <div class="text-secondary small mt-1">Jersey Series Fantasy Kamen Rider — Edisi 1 &copy; 2026</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>