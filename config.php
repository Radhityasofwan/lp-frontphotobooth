<?php
/**
 * config.php – Central configuration
 * Ozverligsportwear x Kemalikart | Kamen Riders
 */

// ─────────────────────────────────────────────
// TIMEZONE
// ─────────────────────────────────────────────
date_default_timezone_set('Asia/Jakarta');

// ─────────────────────────────────────────────
// BRAND
// ─────────────────────────────────────────────
define('BRAND_NAME', 'Ozverligsportwear');
define('COLLAB_NAME', 'Kemalikart');
define('WA_NUMBER', '6281617260666');  // E.164 without +

// ─────────────────────────────────────────────
// BASE URL (auto-detect; works with/without subfolder)
// ─────────────────────────────────────────────
(function () {
    $proto = 'http';
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $proto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https' ? 'https' : 'http';
    } elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $proto = 'https';
    }
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $base = ($dir === '' || $dir === '/') ? '' : $dir;
    define('BASE_PATH', $base);
    define('BASE_URL', $proto . '://' . $host . $base);
})();

// ─────────────────────────────────────────────
// PROMO / PRE-ORDER DEADLINE
// ─────────────────────────────────────────────
define('PROMO_DEADLINE', '2026-03-08 23:59:59'); // Asia/Jakarta

// ─────────────────────────────────────────────
// PRICING
// ─────────────────────────────────────────────
define('PRICE_ORIGINAL_1', 275000);  // strikethrough price for 1 pcs
define('PRICE_PROMO_1', 225000);  // promo price for 1 pcs
define('PRICE_ORIGINAL_2', 500000);  // strikethrough price for 2 pcs
define('PRICE_PROMO_2', 400000);  // promo price for 2 pcs
define('PRICE_DP', 100000);
define('PRICE_SURCHARGE', 20000);

// Uploads
define('UPLOAD_DIR', __DIR__ . '/storage/uploads/');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB  // minimum down-payment per jersey

// Load local secrets if exists (DB + Tracking)
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
}

// ─────────────────────────────────────────────
// TRACKING IDs  (fill before going live)
// ─────────────────────────────────────────────
if (!defined('GA4_ID'))
    define('GA4_ID', '');
if (!defined('GADS_AW_ID'))
    define('GADS_AW_ID', '');
if (!defined('GADS_CONV_LABEL'))
    define('GADS_CONV_LABEL', '');
if (!defined('META_PIXEL_ID'))
    define('META_PIXEL_ID', '');

// ─────────────────────────────────────────────
// DATABASE (PDO)
// ─────────────────────────────────────────────
// Fallback defaults if config.local.php is missing (Not recommended for Prod)
defined('DB_HOST') or define('DB_HOST', 'localhost');
defined('DB_USER') or define('DB_USER', 'root');
defined('DB_PASS') or define('DB_PASS', '');
defined('DB_NAME') or define('DB_NAME', 'kamenriders');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    // Auto-create Analytics table if missing
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS analytics (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(64) NOT NULL,
            ip_address VARCHAR(45),
            event_type VARCHAR(50) NOT NULL,
            event_value INT DEFAULT 0,
            page_url VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX (event_type),
            INDEX (session_id)
        )
    ");

    // Auto-create Settings table for Admin CMS
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT,
            setting_type ENUM('text', 'image', 'html') DEFAULT 'text',
            description VARCHAR(255),
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // Seed initial static CMS values
    $pdo->exec("
        INSERT IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES
        ('hero_badge_1', 'Open Pre-Order', 'text', 'Badge 1 di atas Judul Utama'),
        ('hero_badge_2', 'Limited Edition', 'text', 'Badge 2 di atas Judul Utama'),
        ('hero_badge_3', 'Edisi 1', 'text', 'Badge 3 di atas Judul Utama'),
        ('hero_title_1', 'Jersey ', 'text', 'Baris 1 Judul Utama'),
        ('hero_title_2', 'Kamen Rider', 'text', 'Warna Gradasi Judul Utama'),
        ('hero_title_3', 'Ichigo &amp; Black', 'text', 'Baris 2 Judul Utama'),
        ('hero_desc', 'Nostalgia di tahun 90an, terinspirasi dari film <strong>Satria Baja Hitam</strong>. Jersey sporty premium bergaya jagoan masa kecil kita.<br>Diproduksi oleh <strong>Ozverligsportwear</strong> berkolaborasi dengan <strong>Kemalikart</strong>.', 'html', 'Teks deskripsi di bawah Judul Utama'),
        ('hero_dp', 'IDR 100.000', 'text', 'Nilai DP Minimal Hero Section'),
        ('hero_bg_image', 'assets/img/hero.webp', 'image', 'Gambar utama Hero (Kanan)'),
        ('promo_title', '🔥 EARLY ACCESS PRICE', 'text', 'Judul Pita Promo Banner'),
        ('showcase_title', 'Our Showcase', 'text', 'Judul Bagian Showcase Instagram'),
        ('showcase_desc', 'Detail dan tampilan nyata karya kami di Instagram.', 'text', 'Deskripsi Bagian Showcase Instagram'),
        ('showcase_ig_ichigo', 'https://www.instagram.com/p/DVRdmS_E9Kq/', 'text', 'Link Embed Instagram Ichigo'),
        ('showcase_ig_black', 'https://www.instagram.com/p/DVRd5kNE0B4/', 'text', 'Link Embed Instagram Black'),
        ('product_title', '2 Desain Edisi Perdana', 'text', 'Judul Bagian Produk'),
        ('product_desc', 'Jersey Series Fantasy – nuansa sporty premium, nostalgia 90an.', 'text', 'Deskripsi Bagian Produk'),
        ('spec_title', 'Spesifikasi Jersey', 'text', 'Judul Bagian Spesifikasi'),
        ('spec_alert', 'Dirancang khusus untuk kenyamanan maksimal dan tampilan yang rapi. Memiliki fitting sporty yang ergonomis sehingga sangat relevan digunakan — baik untuk aktivitas harian santai maupun kebutuhan riding touring jauh Anda.', 'html', 'Teks Alert di Bagian Spesifikasi'),
        ('price_title', 'Harga', 'text', 'Judul Bagian Harga'),
        ('price_desc', 'Promo terbatas selama periode pre-order.', 'text', 'Deskripsi Bagian Harga'),
        ('schedule_title', 'Jadwal Pre-Order', 'text', 'Judul Bagian Jadwal'),
        ('schedule_period_1', '27 Februari – 08 Maret 2026', 'text', 'Tanggal Periode Pemesanan'),
        ('schedule_period_2', '09 – 21 Maret 2026', 'text', 'Tanggal Periode Produksi'),
        ('schedule_alert', '<strong class=\"text-brand-red\">Pre-order ditutup sesuai periode.</strong> Amankan slot segera — jumlah produksi terbatas.', 'html', 'Teks Alert di Bagian Jadwal'),
        ('trust_title', 'Kepercayaan &amp; Kualitas', 'text', 'Judul Bagian Social Proof'),
        ('order_title', 'Form Pemesanan', 'text', 'Judul Bagian Form Order'),
        ('order_desc', 'Isi form di bawah. Setelah submit, Anda diarahkan ke WhatsApp untuk konfirmasi.', 'text', 'Deskripsi Bagian Form Order'),
        ('hero_label_1', 'Pre-Order', 'text', 'Label Pre-Order Hero'),
        ('hero_value_1', '27 Feb – 08 Mar', 'text', 'Nilai Tanggal Pre-Order Hero'),
        ('hero_label_2', 'Produksi', 'text', 'Label Produksi Hero'),
        ('hero_value_2', '09 – 21 Mar', 'text', 'Nilai Tanggal Produksi Hero'),
        ('hero_label_3', 'DP Minimal', 'text', 'Label DP Minimal Hero'),
        ('promo_badge', 'Batch 1', 'text', 'Tulisan Badge Promo Navbar'),
        ('promo_price_label', 'kini', 'text', 'Label Harga Promo'),
        ('promo_timer_label', 'Sisa waktu:', 'text', 'Label Timer Promo'),
        ('promo_ended', 'Promo Berakhir', 'text', 'Pesan Promo Berakhir'),
        ('product1_title', 'Fantasy Kamen Rider Ichigo v.01', 'text', 'Judul Produk 1 (Kiri)'),
        ('product1_desc', 'Kolaborasi Ozverligsportwear x Kemalikart. Cocok untuk komunitas, daily wear, dan riding.', 'text', 'Deskripsi Produk 1'),
        ('product2_title', 'Fantasy Kamen Rider Black v.01', 'text', 'Judul Produk 2 (Kanan)'),
        ('product2_desc', 'Karakter kuat, tegas, clean. Limited drop — raih sebelum kehabisan.', 'text', 'Deskripsi Produk 2'),
        ('sizechart_title', 'Size Chart', 'text', 'Judul Panduan Ukuran'),
        ('sizechart_desc', 'Panduan ukuran untuk mendapatkan fitting terbaik.', 'text', 'Deskripsi Panduan Ukuran'),
        ('faq_q1', 'Berapa harga jersey?', 'text', 'Pertanyaan FAQ 1'),
        ('faq_a1', 'Harga promo 1 jersey IDR 225.000, paket doble 2 jersey IDR 400.000. DP minimal IDR 100.000 per jersey. Ongkir ditanggung pemesan.', 'html', 'Jawaban FAQ 1'),
        ('faq_q2', 'Kapan periode pemesanan dan produksi?', 'text', 'Pertanyaan FAQ 2'),
        ('faq_a2', 'Pre-order: 27 Februari – 08 Maret 2026. Produksi: 09 – 21 Maret 2026. Pengiriman dilakukan setelah produksi selesai.', 'html', 'Jawaban FAQ 2'),
        ('faq_q3', 'Apa saja spesifikasi jersey?', 'text', 'Pertanyaan FAQ 3'),
        ('faq_a3', 'Material Andromax Sublimation; Crest 3D Tatami/Polyflock; Apparel Crest 3D HD; Collar/Cuff Rib Knit; Size Tag DTF.', 'html', 'Jawaban FAQ 3'),
        ('faq_q4', 'Bagaimana cara pemesanan?', 'text', 'Pertanyaan FAQ 4'),
        ('faq_a4', 'Isi form pemesanan di halaman ini, lalu lanjutkan konfirmasi dan pembayaran DP via WhatsApp.', 'html', 'Jawaban FAQ 4'),
        ('faq_q5', 'Apakah bisa request ukuran custom?', 'text', 'Pertanyaan FAQ 5'),
        ('faq_a5', 'Ukuran yang tersedia S, M, L, XL, XXL, 3XL, 4XL, 5XL. Untuk request khusus, lengkapi catatan (note) saat mengisi form.', 'html', 'Jawaban FAQ 5'),
        ('footer_brand', 'Ozverligsportwear x Kemalikart', 'text', 'Footer: Brand Name'),
        ('footer_copyright', 'Jersey Series Fantasy Kamen Rider — Edisi 1 &copy; 2026', 'html', 'Footer: Info Copyright'),
        ('seo_title', 'Jersey Kamen Rider Custom untuk Komunitas Rider Indonesia', 'text', 'Judul Artikel SEO'),
        ('seo_content', '<p class=\"mb-3\">Desain <strong>jersey kamen rider custom</strong> yang sedang booming kini telah hadir untuk pencinta tokusatsu tanah air! Bernostalgia bersama <strong>jersey satria baja hitam</strong> dan pahlawan abad ke-90an kini terasa lebih autentik dan eksklusif dengan rilisan limited edition ini.</p><p class=\"mb-3\">Diproduksi secara matang oleh <em>Ozverligsportwear</em> berkolaborasi dengan komunitas seni <em>Kemalikart</em>, setiap balutan <strong>jersey fantasy kamen rider</strong> kami dirancang untuk menemani gaya hidup aktif Anda. Dari <strong>jersey anime custom indonesia</strong> hingga kebutuhan apparel harian saat riding akhir pekan, kualitas material premium (Andromax Sublimasi) kami dijamin tahan terhadap cuaca.</p><p class=\"mb-0\">Bagi para die-hard fans, sebuah <strong>jersey komunitas rider</strong> tak lengkap tanpa detil sempurna layaknya pahlawan itu sendiri. Jadikan <strong>jersey tokusatsu indonesia</strong> ini pelengkap koleksi utama Anda. Tunggu apa lagi? Lengkapi hari-harimu dengan gaya nostalgia <strong>jersey kamen rider indonesia</strong> yang membalut karakter gagah jagoan idola.</p>', 'html', 'Konten Artikel SEO di Bawah')
    ");

} catch (PDOException $e) {
    $pdo = null; // fallback to CSV if DB unavailable
    error_log(
        '[' . date('c') . '] DB connect fail: ' . $e->getMessage(),
        3,
        __DIR__ . '/storage/events.log'
    );
}

// ─────────────────────────────────────────────
// STORAGE PATHS
// ─────────────────────────────────────────────
define('LEADS_CSV', __DIR__ . '/storage/leads.csv');
define('EVENTS_LOG', __DIR__ . '/storage/events.log');

// ─────────────────────────────────────────────
// RATE LIMIT (seconds between submissions per session)
// ─────────────────────────────────────────────
define('RATE_LIMIT_SECONDS', 15);

// ─────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────
function h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function idr(int $n): string
{
    return 'IDR ' . number_format($n, 0, ',', '.');
}

function clean(string $s, int $max = 255): string
{
    $s = trim(preg_replace('/\s+/', ' ', $s));
    return mb_substr($s, 0, $max);
}

function norm_phone(string $p): string
{
    $p = preg_replace('/[^0-9]/', '', $p);
    if (str_starts_with($p, '0'))
        $p = '62' . substr($p, 1);
    if (!str_starts_with($p, '62'))
        $p = '62' . $p;
    return $p;
}

function log_event(string $msg): void
{
    error_log('[' . date('c') . '] ' . $msg . PHP_EOL, 3, EVENTS_LOG);
}

function asset(string $path): string
{
    $base = rtrim(BASE_URL, '/');
    $filePath = __DIR__ . '/' . ltrim($path, '/');
    $v = file_exists($filePath) ? filemtime($filePath) : 0;
    if ($v === 0) {
        error_log('[' . date('c') . '] asset missing: ' . $filePath . PHP_EOL, 3, __DIR__ . '/storage/events.log');
    }
    return $base . '/' . ltrim($path, '/') . ($v > 0 ? '?v=' . $v : '');
}

/**
 * Get a specific setting from the DB
 */
function get_setting(string $key, string $default = ''): string
{
    global $pdo;
    if (!$pdo)
        return $default;

    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? (string) $row['setting_value'] : $default;
    } catch (Throwable $e) {
        return $default;
    }
}

/**
 * Bulk grab all settings as key-value pairs
 */
function get_all_settings(): array
{
    global $pdo;
    if (!$pdo)
        return [];

    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value, setting_type, description FROM settings");
        return $stmt->fetchAll() ?: [];
    } catch (Throwable $e) {
        return [];
    }
}