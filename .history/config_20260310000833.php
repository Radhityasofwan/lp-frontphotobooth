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
define('BRAND_NAME', 'Front Photobooth');
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
// (Removed old jersey promo deadlines)

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
defined('DB_NAME') or define('DB_NAME', 'frontphotobooth');

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
        ('seo_title', 'Front Photobooth - Premium Experience', 'text', 'Judul Website'),
        ('seo_desc', 'Photobooth modern hasil instan + props premium.', 'text', 'Deskripsi Website'),
                ('home_hero_badge', 'We Capture Energy, Not Just Photos', 'text', 'Text Badge di Hero'),
            ('home_hero_title', 'Bukan Sekadar Foto. Ini Pengalaman Seru di Event Kamu.', 'text', 'Judul Hero Utama'),
            ('home_hero_desc', 'Photobooth modern hasil instan + props premium, bikin semua tamu betah bergaya. Waktunya buat acaramu lebih hidup!', 'text', 'Deskripsi Hero'),
            ('home_hero_cta_text', 'Cek Ketersediaan Event', 'text', 'Teks Tombol CTA Hero'),
            ('home_hero_cta_link', 'https://frontphotobooth.com', 'text', 'Link Tombol CTA Hero'),
            ('home_hero_1', '', 'image', 'Hero Image 1 (Kiri Atas)'),
            ('home_hero_2', '', 'image', 'Hero Image 2 (Tengah)'),
            ('home_hero_3', '', 'image', 'Hero Image 3 (Kanan Bawah)'),
            
            ('home_prob_title', 'Event Ramai, Tapi Kurang Berkesan?', 'text', 'Judul Section Problem'),
            ('home_prob_quote', 'Datang. Makan. Pulang.', 'text', 'Quote Problem'),
            ('home_prob_sub', 'Front Photobooth siap merubah suasana!', 'text', 'Subtext Problem'),
            
            ('home_core_title', 'Kami Tidak Menjual Foto. Kami Menciptakan Serunya Momen!', 'text', 'Judul Core Idea'),
            ('home_core_1_title', 'Interaction', 'text', 'Core 1 Judul'),
            ('home_core_1_desc', 'Memecah rasa canggung, buat tamu lebih berani dan lepas buat tampil interaktif!', 'text', 'Core 1 Deskripsi'),
            ('home_core_2_title', 'Experience', 'text', 'Core 2 Judul'),
            ('home_core_2_desc', 'Hiburan utama yang bikin semua tamu dandan rapi merasa sangat dihargai keberadaannya.', 'text', 'Core 2 Deskripsi'),
            ('home_core_3_title', 'Memory', 'text', 'Core 3 Judul'),
            ('home_core_3_desc', 'Suvenir fisik premium yang bakal dipajang dan disimpan terus bertahun-tahun.', 'text', 'Core 3 Deskripsi'),
            
            ('home_props', '', 'image', 'Image Props / Signature'),
            ('home_sig_badge', 'The Front Way', 'text', 'Badge Signature'),
            ('home_sig_title', 'Signature Experience', 'text', 'Judul Signature'),
            ('home_sig_list_1', 'System driven: Alur teratur, antrian rapih', 'text', 'Signature List 1'),
            ('home_sig_list_2', 'Consistent quality: Studio Lighting mantap, foto anti kusam!', 'text', 'Signature List 2'),
            ('home_sig_list_3', 'Curated team: Kru asik & pro-aktif bantu arahin gaya', 'text', 'Signature List 3'),
            ('home_sig_list_4', 'Instant Print: Cetakan kilat & warna solid', 'text', 'Signature List 4'),
            
            ('home_srv_title', 'Service Breakdown', 'text', 'Judul Service Breakdown'),
            ('home_srv_desc', 'Sudah include semuanya, tinggal masuk frame dan siapkan posenya.', 'text', 'Subtext Service Breakdown'),
            ('home_srv_badge_1', 'Mesin Photobooth Pro', 'text', 'Service Badge 1'),
            ('home_srv_badge_2', 'Kru Interaktif', 'text', 'Service Badge 2'),
            ('home_srv_badge_3', 'Cetakan 4R/Strip', 'text', 'Service Badge 3'),
            ('home_srv_badge_4', 'Frame Custom Theme', 'text', 'Service Badge 4'),
            ('home_srv_badge_5', 'Softcopy G-Drive/QR', 'text', 'Service Badge 5'),
            ('home_srv_badge_6', 'Free Fun Props', 'text', 'Service Badge 6'),
            ('home_srv_quote', 'Kamu urus tamu yang datang. Kami urus keceriaannya!', 'text', 'Quote Service Breakdown'),
            
            ('home_pkg_title', 'Pilihan Service Kita', 'text', 'Judul Section Paket'),
            ('home_pkg_desc', 'Bisa custom sesuka hati sesuai skala & konsep acara kamu.', 'text', 'Subtext Section Paket'),
            ('home_pkg_1_title', 'Basic Experience', 'text', 'Paket 1 Judul'),
            ('home_pkg_1_desc', 'Sempurna buat chill intimate party yang cozy.', 'text', 'Paket 1 Deskripsi'),
            ('home_pkg_1_list_1', 'Durasi santai 2–3 Jam', 'text', 'Paket 1 List 1'),
            ('home_pkg_1_list_2', 'Print sesuai kuota', 'text', 'Paket 1 List 2'),
            ('home_pkg_1_list_3', '1 Operator Friendly', 'text', 'Paket 1 List 3'),
            ('home_pkg_1_list_4', 'Kacamata Props Standard', 'text', 'Paket 1 List 4'),
            ('home_pkg_2_badge', 'Most Popular', 'text', 'Paket 2 Badge Label'),
            ('home_pkg_2_title', 'Full Experience', 'text', 'Paket 2 Judul'),
            ('home_pkg_2_desc', 'Maksimal buat birthday megah atau wedding rame!', 'text', 'Paket 2 Deskripsi'),
            ('home_pkg_2_list_1', 'Gas terus 3–4 Jam', 'text', 'Paket 2 List 1'),
            ('home_pkg_2_list_2', 'Unlimited Print, Bebas!', 'text', 'Paket 2 List 2'),
            ('home_pkg_2_list_3', '2 Operator (Tukang foto + asisten)', 'text', 'Paket 2 List 3'),
            ('home_pkg_2_list_4', 'Desain Custom Overlay Bebas', 'text', 'Paket 2 List 4'),
            ('home_pkg_2_list_5', 'Props Lucu Super Lengkap', 'text', 'Paket 2 List 5'),
            ('home_pkg_3_title', 'Brand Experience', 'text', 'Paket 3 Judul'),
            ('home_pkg_3_desc', 'Khusus corporate activation, brand launch.', 'text', 'Paket 3 Deskripsi'),
            ('home_pkg_3_list_1', 'Waktu Custom via Run-down', 'text', 'Paket 3 List 1'),
            ('home_pkg_3_list_2', 'Mesin & Frame Full Branding', 'text', 'Paket 3 List 2'),
            ('home_pkg_3_list_3', 'Tim Sangat Dedicated', 'text', 'Paket 3 List 3'),
            ('home_pkg_3_list_4', 'Lead Data Capture & Scan QR', 'text', 'Paket 3 List 4'),
            ('home_pkg_cta_text', 'Diskusi Konsep via WhatsApp', 'text', 'Teks Tombol CTA Paket'),
            ('home_pkg_cta_link', 'https://wa.me/6281617260666', 'text', 'Link Tombol CTA Paket'),
            
            ('home_scrap_title', 'Scrapbook Momen Seru', 'text', 'Judul Section Scrapbook'),
            ('home_scrap_desc', 'Nggak ada tamu yang kaku. Semua pasti keluar karakter aslinya!', 'text', 'Subtext Section Scrapbook'),
            ('home_scrap_1', '', 'image', 'Scrapbook 1'),
            ('home_scrap_1_text', 'Engagement', 'text', 'Teks Scrapbook 1'),
            ('home_scrap_2', '', 'image', 'Scrapbook 2'),
            ('home_scrap_2_text', 'Gala Dinner', 'text', 'Teks Scrapbook 2'),
            ('home_scrap_3', '', 'image', 'Scrapbook 3'),
            ('home_scrap_4', '', 'image', 'Scrapbook 4'),
            ('home_scrap_5', '', 'image', 'Scrapbook 5'),
            ('home_scrap_5_text', 'Sweet 17th', 'text', 'Teks Scrapbook 5'),

            ('home_trust_title', 'Kenapa Banyak Client Repeat Order?', 'text', 'Judul Section Trust'),
            ('home_trust_1_title', 'Always On-Time', 'text', 'Trust 1 Judul'),
            ('home_trust_1_desc', 'Dateng duluan selalu aman sebelum acara mulai.', 'text', 'Trust 1 Deskripsi'),
            ('home_trust_2_title', 'Zero Ribet', 'text', 'Trust 2 Judul'),
            ('home_trust_2_desc', 'Mandiri pasang alat & rapih 100% tanpa merepotkan WO.', 'text', 'Trust 2 Deskripsi'),
            ('home_trust_3_title', 'Hasil Jernih', 'text', 'Trust 3 Judul'),
            ('home_trust_3_desc', 'Flash kamera DSLR pro nggak bikin muka abu-abu pucat.', 'text', 'Trust 3 Deskripsi'),
            ('home_trust_4_title', 'Harga Transparan', 'text', 'Trust 4 Judul'),
            ('home_trust_4_desc', 'Sesuai invoice, bebas biaya siluman mendadak.', 'text', 'Trust 4 Deskripsi'),
            
            ('home_scarcity_badge', 'PENTING BANGET', 'text', 'Badge Scarcity'),
            ('home_scarcity_title', 'Kita Batasin Event Dalam 1 Hari!', 'text', 'Judul Scarcity'),
            ('home_scarcity_desc', 'Biar kualitas tetap maksimal dan kru tetap prima, kami nggak ngambil overbooking. Kalau slot tanggal event kamu udah keisi, mohon maaf banget kita tutup pintu', 'text', 'Subtext Scarcity'),
            ('home_scarcity_cta_text', 'Amankan Tanggal Sekarang', 'text', 'Teks Tombol CTA Scarcity'),
            ('home_scarcity_cta_link', 'https://frontphotobooth.com', 'text', 'Link Tombol CTA Scarcity'),
            
            ('home_close_title', 'Ayo, Ramaikan Acaramu!', 'text', 'Judul Final Close CTA'),
            ('home_close_desc', 'Jangan biarin tamu cuman duduk main HP di meja. Bikin mereka gabung, gaya gokil, dan bawa kenangan fisiknya pulang!', 'text', 'Subtext Final Close CTA'),
            ('home_close_cta1_text', 'Booking Langsung!', 'text', 'Teks Tombol CTA Akhir 1'),
            ('home_close_cta1_link', 'https://frontphotobooth.com', 'text', 'Link Tombol CTA Akhir 1'),
            ('home_close_cta2_text', 'Ngobrol Santai via WA', 'text', 'Teks Tombol CTA Akhir 2'),
            ('home_close_cta2_link', 'https://wa.me/6281617260666', 'text', 'Link Tombol CTA Akhir 2'),
            
            ('footer_title', 'FRONT PHOTOBOOTH', 'text', 'Judul Footer Brands'),
            ('footer_copyright', 'Bikin Eventmu Jadi Legenda. All Rights Reserved.', 'text', 'Teks Hak Cipta Footer'),
        
        ('gallery_1', '', 'image', 'Gallery Image 1'),
        ('gallery_2', '', 'image', 'Gallery Image 2'),
        ('template_1', '', 'image', 'Template Preview 1'),
        ('insp_1', '', 'image', 'Inspirasi Event 1')
    ");

} catch (PDOException $e) {
    // Graceful fallback to SQLite for local development & zero-config installs
    try {
        $sqlitePath = __DIR__ . '/storage/database.sqlite';
        $pdo = new PDO('sqlite:' . $sqlitePath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS analytics (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id TEXT NOT NULL,
                ip_address TEXT,
                event_type TEXT NOT NULL,
                event_value INTEGER DEFAULT 0,
                page_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS settings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                setting_key TEXT NOT NULL UNIQUE,
                setting_value TEXT,
                setting_type TEXT DEFAULT 'text',
                description TEXT,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $pdo->exec("
            INSERT OR IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES
            ('seo_title', 'Front Photobooth - Premium Experience', 'text', 'Judul Website'),
            ('seo_desc', 'Photobooth modern hasil instan + props premium.', 'text', 'Deskripsi Website'),
            
            ('home_hero_badge', 'We Capture Energy, Not Just Photos', 'text', 'Text Badge di Hero'),
            ('home_hero_title', 'Bukan Sekadar Foto. Ini Pengalaman Seru di Event Kamu.', 'text', 'Judul Hero Utama'),
            ('home_hero_desc', 'Photobooth modern hasil instan + props premium, bikin semua tamu betah bergaya. Waktunya buat acaramu lebih hidup!', 'text', 'Deskripsi Hero'),
            ('home_hero_cta_text', 'Cek Ketersediaan Event', 'text', 'Teks Tombol CTA Hero'),
            ('home_hero_cta_link', 'https://frontphotobooth.com', 'text', 'Link Tombol CTA Hero'),
            ('home_hero_1', '', 'image', 'Hero Image 1 (Kiri Atas)'),
            ('home_hero_2', '', 'image', 'Hero Image 2 (Tengah)'),
            ('home_hero_3', '', 'image', 'Hero Image 3 (Kanan Bawah)'),
            
            ('home_prob_title', 'Event Ramai, Tapi Kurang Berkesan?', 'text', 'Judul Section Problem'),
            ('home_prob_quote', 'Datang. Makan. Pulang.', 'text', 'Quote Problem'),
            ('home_prob_sub', 'Front Photobooth siap merubah suasana!', 'text', 'Subtext Problem'),
            
            ('home_core_title', 'Kami Tidak Menjual Foto. Kami Menciptakan Serunya Momen!', 'text', 'Judul Core Idea'),
            ('home_core_1_title', 'Interaction', 'text', 'Core 1 Judul'),
            ('home_core_1_desc', 'Memecah rasa canggung, buat tamu lebih berani dan lepas buat tampil interaktif!', 'text', 'Core 1 Deskripsi'),
            ('home_core_2_title', 'Experience', 'text', 'Core 2 Judul'),
            ('home_core_2_desc', 'Hiburan utama yang bikin semua tamu dandan rapi merasa sangat dihargai keberadaannya.', 'text', 'Core 2 Deskripsi'),
            ('home_core_3_title', 'Memory', 'text', 'Core 3 Judul'),
            ('home_core_3_desc', 'Suvenir fisik premium yang bakal dipajang dan disimpan terus bertahun-tahun.', 'text', 'Core 3 Deskripsi'),
            
            ('home_props', '', 'image', 'Image Props / Signature'),
            ('home_sig_badge', 'The Front Way', 'text', 'Badge Signature'),
            ('home_sig_title', 'Signature Experience', 'text', 'Judul Signature'),
            ('home_sig_list_1', 'System driven: Alur teratur, antrian rapih', 'text', 'Signature List 1'),
            ('home_sig_list_2', 'Consistent quality: Studio Lighting mantap, foto anti kusam!', 'text', 'Signature List 2'),
            ('home_sig_list_3', 'Curated team: Kru asik & pro-aktif bantu arahin gaya', 'text', 'Signature List 3'),
            ('home_sig_list_4', 'Instant Print: Cetakan kilat & warna solid', 'text', 'Signature List 4'),
            
            ('home_srv_title', 'Service Breakdown', 'text', 'Judul Service Breakdown'),
            ('home_srv_desc', 'Sudah include semuanya, tinggal masuk frame dan siapkan posenya.', 'text', 'Subtext Service Breakdown'),
            ('home_srv_badge_1', 'Mesin Photobooth Pro', 'text', 'Service Badge 1'),
            ('home_srv_badge_2', 'Kru Interaktif', 'text', 'Service Badge 2'),
            ('home_srv_badge_3', 'Cetakan 4R/Strip', 'text', 'Service Badge 3'),
            ('home_srv_badge_4', 'Frame Custom Theme', 'text', 'Service Badge 4'),
            ('home_srv_badge_5', 'Softcopy G-Drive/QR', 'text', 'Service Badge 5'),
            ('home_srv_badge_6', 'Free Fun Props', 'text', 'Service Badge 6'),
            ('home_srv_quote', 'Kamu urus tamu yang datang. Kami urus keceriaannya!', 'text', 'Quote Service Breakdown'),
            
            ('home_pkg_title', 'Pilihan Service Kita', 'text', 'Judul Section Paket'),
            ('home_pkg_desc', 'Bisa custom sesuka hati sesuai skala & konsep acara kamu.', 'text', 'Subtext Section Paket'),
            ('home_pkg_1_title', 'Basic Experience', 'text', 'Paket 1 Judul'),
            ('home_pkg_1_desc', 'Sempurna buat chill intimate party yang cozy.', 'text', 'Paket 1 Deskripsi'),
            ('home_pkg_1_list_1', 'Durasi santai 2–3 Jam', 'text', 'Paket 1 List 1'),
            ('home_pkg_1_list_2', 'Print sesuai kuota', 'text', 'Paket 1 List 2'),
            ('home_pkg_1_list_3', '1 Operator Friendly', 'text', 'Paket 1 List 3'),
            ('home_pkg_1_list_4', 'Kacamata Props Standard', 'text', 'Paket 1 List 4'),
            ('home_pkg_2_badge', 'Most Popular', 'text', 'Paket 2 Badge Label'),
            ('home_pkg_2_title', 'Full Experience', 'text', 'Paket 2 Judul'),
            ('home_pkg_2_desc', 'Maksimal buat birthday megah atau wedding rame!', 'text', 'Paket 2 Deskripsi'),
            ('home_pkg_2_list_1', 'Gas terus 3–4 Jam', 'text', 'Paket 2 List 1'),
            ('home_pkg_2_list_2', 'Unlimited Print, Bebas!', 'text', 'Paket 2 List 2'),
            ('home_pkg_2_list_3', '2 Operator (Tukang foto + asisten)', 'text', 'Paket 2 List 3'),
            ('home_pkg_2_list_4', 'Desain Custom Overlay Bebas', 'text', 'Paket 2 List 4'),
            ('home_pkg_2_list_5', 'Props Lucu Super Lengkap', 'text', 'Paket 2 List 5'),
            ('home_pkg_3_title', 'Brand Experience', 'text', 'Paket 3 Judul'),
            ('home_pkg_3_desc', 'Khusus corporate activation, brand launch.', 'text', 'Paket 3 Deskripsi'),
            ('home_pkg_3_list_1', 'Waktu Custom via Run-down', 'text', 'Paket 3 List 1'),
            ('home_pkg_3_list_2', 'Mesin & Frame Full Branding', 'text', 'Paket 3 List 2'),
            ('home_pkg_3_list_3', 'Tim Sangat Dedicated', 'text', 'Paket 3 List 3'),
            ('home_pkg_3_list_4', 'Lead Data Capture & Scan QR', 'text', 'Paket 3 List 4'),
            ('home_pkg_cta_text', 'Diskusi Konsep via WhatsApp', 'text', 'Teks Tombol CTA Paket'),
            ('home_pkg_cta_link', 'https://wa.me/6281617260666', 'text', 'Link Tombol CTA Paket'),
            
            ('home_scrap_title', 'Scrapbook Momen Seru', 'text', 'Judul Section Scrapbook'),
            ('home_scrap_desc', 'Nggak ada tamu yang kaku. Semua pasti keluar karakter aslinya!', 'text', 'Subtext Section Scrapbook'),
            ('home_scrap_1', '', 'image', 'Scrapbook 1'),
            ('home_scrap_1_text', 'Engagement', 'text', 'Teks Scrapbook 1'),
            ('home_scrap_2', '', 'image', 'Scrapbook 2'),
            ('home_scrap_2_text', 'Gala Dinner', 'text', 'Teks Scrapbook 2'),
            ('home_scrap_3', '', 'image', 'Scrapbook 3'),
            ('home_scrap_4', '', 'image', 'Scrapbook 4'),
            ('home_scrap_5', '', 'image', 'Scrapbook 5'),
            ('home_scrap_5_text', 'Sweet 17th', 'text', 'Teks Scrapbook 5'),

            ('home_trust_title', 'Kenapa Banyak Client Repeat Order?', 'text', 'Judul Section Trust'),
            ('home_trust_1_title', 'Always On-Time', 'text', 'Trust 1 Judul'),
            ('home_trust_1_desc', 'Dateng duluan selalu aman sebelum acara mulai.', 'text', 'Trust 1 Deskripsi'),
            ('home_trust_2_title', 'Zero Ribet', 'text', 'Trust 2 Judul'),
            ('home_trust_2_desc', 'Mandiri pasang alat & rapih 100% tanpa merepotkan WO.', 'text', 'Trust 2 Deskripsi'),
            ('home_trust_3_title', 'Hasil Jernih', 'text', 'Trust 3 Judul'),
            ('home_trust_3_desc', 'Flash kamera DSLR pro nggak bikin muka abu-abu pucat.', 'text', 'Trust 3 Deskripsi'),
            ('home_trust_4_title', 'Harga Transparan', 'text', 'Trust 4 Judul'),
            ('home_trust_4_desc', 'Sesuai invoice, bebas biaya siluman mendadak.', 'text', 'Trust 4 Deskripsi'),
            
            ('home_scarcity_badge', 'PENTING BANGET', 'text', 'Badge Scarcity'),
            ('home_scarcity_title', 'Kita Batasin Event Dalam 1 Hari!', 'text', 'Judul Scarcity'),
            ('home_scarcity_desc', 'Biar kualitas tetap maksimal dan kru tetap prima, kami nggak ngambil overbooking. Kalau slot tanggal event kamu udah keisi, mohon maaf banget kita tutup pintu', 'text', 'Subtext Scarcity'),
            ('home_scarcity_cta_text', 'Amankan Tanggal Sekarang', 'text', 'Teks Tombol CTA Scarcity'),
            ('home_scarcity_cta_link', 'https://frontphotobooth.com', 'text', 'Link Tombol CTA Scarcity'),
            
            ('home_close_title', 'Ayo, Ramaikan Acaramu!', 'text', 'Judul Final Close CTA'),
            ('home_close_desc', 'Jangan biarin tamu cuman duduk main HP di meja. Bikin mereka gabung, gaya gokil, dan bawa kenangan fisiknya pulang!', 'text', 'Subtext Final Close CTA'),
            ('home_close_cta1_text', 'Booking Langsung!', 'text', 'Teks Tombol CTA Akhir 1'),
            ('home_close_cta1_link', 'https://frontphotobooth.com', 'text', 'Link Tombol CTA Akhir 1'),
            ('home_close_cta2_text', 'Ngobrol Santai via WA', 'text', 'Teks Tombol CTA Akhir 2'),
            ('home_close_cta2_link', 'https://wa.me/6281617260666', 'text', 'Link Tombol CTA Akhir 2'),
            
            ('footer_title', 'FRONT PHOTOBOOTH', 'text', 'Judul Footer Brands'),
            ('footer_copyright', 'Bikin Eventmu Jadi Legenda. All Rights Reserved.', 'text', 'Teks Hak Cipta Footer'),
            
            ('gallery_1', '', 'image', 'Gallery Image 1'),
            ('gallery_2', '', 'image', 'Gallery Image 2'),
            ('template_1', '', 'image', 'Template Preview 1'),
            ('insp_1', '', 'image', 'Inspirasi Event 1')
        ");

    } catch (PDOException $sqliteEx) {
        $pdo = null;
        error_log(
            '[' . date('c') . '] SQLite Fallback fail: ' . $sqliteEx->getMessage(),
            3,
            __DIR__ . '/storage/events.log'
        );
    }
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