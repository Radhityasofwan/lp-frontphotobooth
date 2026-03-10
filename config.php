<?php
// config.php

// 1. Environment Detection
$is_production = ($_SERVER['HTTP_HOST'] ?? '') === 'sewa.frontphotobooth.com';

if ($is_production) {
    // --- PRODUKSI ---
    ini_set('display_errors', 0);
    error_reporting(0);

    define('DB_HOST', 'localhost');
    define('DB_NAME', 'u830768701_front');
    define('DB_USER', 'u830768701_landingpage');
    define('DB_PASS', 'Merdeka313');
    define('BASE_URL', 'https://sewa.frontphotobooth.com');
} else {
    // --- LOKAL ---
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $sqlite_path = __DIR__ . '/storage/local.sqlite';
    define('DB_DSN', 'sqlite:' . $sqlite_path);
    define('DB_USER', null);
    define('DB_PASS', null);

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script_name = $_SERVER['SCRIPT_NAME'] ?? '';

    $base_path = dirname($script_name);
    $base_path = ($base_path === '/' || $base_path === '\\') ? '' : $base_path;
    define('BASE_URL', $protocol . '://' . $host . $base_path);
}

// 2. Konstanta situs
define('WA_NUMBER', '6281234567890');

/**
 * Definisi seluruh key CMS agar semua menu/section bisa dikelola dari Admin.
 * Format: key => [default, type, description]
 */
function get_cms_setting_definitions(): array
{
    static $defs = null;
    if ($defs !== null) {
        return $defs;
    }

    $placeholder = 'assets/img/placeholder-plain.svg';

    $defs = [
        // General + Navigation
        'seo_title' => ['Front Photobooth - Premium Photobooth Experience', 'text', 'SEO title'],
        'seo_desc' => ['Photobooth modern untuk event Anda.', 'text', 'SEO description'],
        'nav_logo' => [$placeholder, 'image', 'Logo Navbar'],
        'nav_brand_text' => ['Front Photobooth', 'text', 'Brand text navbar'],
        'nav_home_text' => ['Beranda', 'text', 'Teks menu Beranda'],
        'nav_paket_text' => ['Paket', 'text', 'Teks menu Paket'],
        'nav_templates_text' => ['Templates', 'text', 'Teks menu Templates'],
        'nav_inspirasi_text' => ['Inspirasi', 'text', 'Teks menu Inspirasi'],
        'nav_gallery_text' => ['Galeri', 'text', 'Teks menu Galeri'],
        'nav_blog_text' => ['Blog', 'text', 'Teks menu Blog'],
        'nav_contact_text' => ['Kontak', 'text', 'Teks menu Kontak'],
        'nav_cta_text' => ['Booking', 'text', 'Teks tombol navbar'],
        'nav_cta_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link tombol navbar'],

        // Home - Hero
        'home_hero_badge' => ['We Capture Energy, Not Just Photos', 'text', 'Text badge hero'],
        'home_hero_title' => ["Bukan Sekadar Foto.\nIni Pengalaman Seru di Event Kamu.", 'text', 'Judul hero utama'],
        'home_hero_desc' => ['Photobooth modern hasil instan + props premium, bikin semua tamu betah bergaya. Waktunya buat acaramu lebih hidup!', 'text', 'Deskripsi hero'],
        'home_hero_cta_text' => ['Cek Ketersediaan Event', 'text', 'Teks tombol CTA hero'],
        'home_hero_cta_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link tombol CTA hero'],
        'home_hero_1' => [$placeholder, 'image', 'Hero image utama'],

        // Home - Problem
        'home_prob_title' => ['Event Ramai, Tapi Kurang Berkesan?', 'text', 'Judul section problem'],
        'home_prob_quote' => ['Datang. Makan. Pulang.', 'text', 'Quote section problem'],
        'home_prob_sub' => ['Front Photobooth siap merubah suasana!', 'text', 'Subtext section problem'],

        // Home - Core
        'home_core_title' => ['Kami Tidak Menjual Foto.<br><span class="text-orange">Kami Menciptakan Serunya Momen!</span>', 'html', 'Judul section core'],
        'home_core_1_title' => ['Interaction', 'text', 'Core 1 judul'],
        'home_core_1_desc' => ['Memecah rasa canggung, buat tamu lebih berani dan lepas buat tampil interaktif!', 'text', 'Core 1 deskripsi'],
        'home_core_2_title' => ['Experience', 'text', 'Core 2 judul'],
        'home_core_2_desc' => ['Hiburan utama yang bikin semua tamu dandan rapi merasa sangat dihargai keberadaannya.', 'text', 'Core 2 deskripsi'],
        'home_core_3_title' => ['Memory', 'text', 'Core 3 judul'],
        'home_core_3_desc' => ['Suvenir fisik premium yang bakal dipajang dan disimpan terus bertahun-tahun.', 'text', 'Core 3 deskripsi'],

        // Home - Signature + Service
        'home_props' => [$placeholder, 'image', 'Image signature/props'],
        'home_sig_badge' => ['The Front Way', 'text', 'Badge signature'],
        'home_sig_title' => ['Signature Experience', 'text', 'Judul signature'],
        'home_sig_list_1' => ['System driven: Alur teratur, antrian rapih', 'text', 'Signature list 1'],
        'home_sig_list_2' => ['Consistent quality: Studio Lighting mantap, foto anti kusam!', 'text', 'Signature list 2'],
        'home_sig_list_3' => ['Curated team: Kru asik & pro-aktif bantu arahin gaya', 'text', 'Signature list 3'],
        'home_sig_list_4' => ['Instant Print: Cetakan kilat & warna solid', 'text', 'Signature list 4'],
        'home_srv_title' => ['Service Breakdown', 'text', 'Judul service breakdown'],
        'home_srv_desc' => ['Sudah include semuanya, tinggal masuk *frame* dan siapkan posenya.', 'text', 'Subtext service breakdown'],
        'home_srv_badge_1' => ['Mesin Photobooth Pro', 'text', 'Service badge 1'],
        'home_srv_badge_2' => ['Kru Interaktif', 'text', 'Service badge 2'],
        'home_srv_badge_3' => ['Cetakan 4R/Strip', 'text', 'Service badge 3'],
        'home_srv_badge_4' => ['Frame Custom Theme', 'text', 'Service badge 4'],
        'home_srv_badge_5' => ['Softcopy G-Drive/QR', 'text', 'Service badge 5'],
        'home_srv_badge_6' => ['Free Fun Props', 'text', 'Service badge 6'],
        'home_srv_quote' => ["Kamu urus tamu yang datang.\nKami urus keceriaannya!", 'text', 'Quote service breakdown'],

        // Home - Package
        'home_pkg_title' => ['Pilihan Service Kita', 'text', 'Judul section paket'],
        'home_pkg_desc' => ['Bisa custom sesuka hati sesuai skala & konsep acara kamu.', 'text', 'Subtext section paket'],
        'home_pkg_1_title' => ['Basic Experience', 'text', 'Paket 1 judul'],
        'home_pkg_1_desc' => ['Sempurna buat chill intimate party yang cozy.', 'text', 'Paket 1 deskripsi'],
        'home_pkg_1_list_1' => ['Durasi santai 2–3 Jam', 'text', 'Paket 1 list 1'],
        'home_pkg_1_list_2' => ['Print sesuai kuota', 'text', 'Paket 1 list 2'],
        'home_pkg_1_list_3' => ['1 Operator Friendly', 'text', 'Paket 1 list 3'],
        'home_pkg_1_list_4' => ['Kacamata Props Standard', 'text', 'Paket 1 list 4'],
        'home_pkg_2_badge' => ['Most Popular', 'text', 'Paket 2 badge'],
        'home_pkg_2_title' => ['Full Experience', 'text', 'Paket 2 judul'],
        'home_pkg_2_desc' => ['Maksimal buat birthday megah atau wedding rame!', 'text', 'Paket 2 deskripsi'],
        'home_pkg_2_list_1' => ['Gas terus 3–4 Jam', 'text', 'Paket 2 list 1'],
        'home_pkg_2_list_2' => ['Unlimited Print, Bebas!', 'text', 'Paket 2 list 2'],
        'home_pkg_2_list_3' => ['2 Operator (Tukang foto + asisten)', 'text', 'Paket 2 list 3'],
        'home_pkg_2_list_4' => ['Desain Custom Overlay Bebas', 'text', 'Paket 2 list 4'],
        'home_pkg_2_list_5' => ['Props Lucu Super Lengkap', 'text', 'Paket 2 list 5'],
        'home_pkg_3_title' => ['Brand Experience', 'text', 'Paket 3 judul'],
        'home_pkg_3_desc' => ['Khusus corporate activation, brand launch.', 'text', 'Paket 3 deskripsi'],
        'home_pkg_3_list_1' => ['Waktu Custom via Run-down', 'text', 'Paket 3 list 1'],
        'home_pkg_3_list_2' => ['Mesin & Frame Full Branding', 'text', 'Paket 3 list 2'],
        'home_pkg_3_list_3' => ['Tim Sangat Dedicated', 'text', 'Paket 3 list 3'],
        'home_pkg_3_list_4' => ['Lead Data Capture & Scan QR', 'text', 'Paket 3 list 4'],
        'home_pkg_cta_text' => ['Diskusi Konsep via WhatsApp', 'text', 'CTA section paket'],
        'home_pkg_cta_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link CTA section paket'],

        // Home - Scrap
        'home_scrap_title' => ['Scrapbook Momen Seru', 'text', 'Judul section scrapbook'],
        'home_scrap_desc' => ['Nggak ada tamu yang kaku. Semua pasti keluar karakter aslinya!', 'text', 'Subtext scrapbook'],
        'home_scrap_1' => [$placeholder, 'image', 'Scrap image 1'],
        'home_scrap_1_text' => ['Engagement', 'text', 'Scrap text 1'],
        'home_scrap_2' => [$placeholder, 'image', 'Scrap image 2'],
        'home_scrap_2_text' => ['Gala Dinner', 'text', 'Scrap text 2'],
        'home_scrap_3' => [$placeholder, 'image', 'Scrap image 3'],
        'home_scrap_4' => [$placeholder, 'image', 'Scrap image 4'],
        'home_scrap_5' => [$placeholder, 'image', 'Scrap image 5'],
        'home_scrap_5_text' => ['Sweet 17th', 'text', 'Scrap text 5'],

        // Home - Clients
        'home_clients_title' => ['Telah Dipercaya Oleh', 'text', 'Judul section klien'],
        'home_clients_desc' => ['Kami bangga telah menjadi bagian dari momen spesial berbagai brand ternama dan acara personal yang tak terlupakan.', 'text', 'Deskripsi section klien'],

        // Home - Trust + Scarcity + Close
        'home_trust_title' => ['Kenapa Banyak Client <span class="text-orange">Repeat Order?</span>', 'html', 'Judul trust'],
        'home_trust_1_title' => ['Always On-Time', 'text', 'Trust 1 judul'],
        'home_trust_1_desc' => ['Dateng duluan selalu aman sebelum acara mulai.', 'text', 'Trust 1 deskripsi'],
        'home_trust_2_title' => ['Zero Ribet', 'text', 'Trust 2 judul'],
        'home_trust_2_desc' => ['Mandiri pasang alat & rapih 100% tanpa merepotkan WO.', 'text', 'Trust 2 deskripsi'],
        'home_trust_3_title' => ['Hasil Jernih', 'text', 'Trust 3 judul'],
        'home_trust_3_desc' => ['Flash kamera DSLR pro nggak bikin muka abu-abu pucat.', 'text', 'Trust 3 deskripsi'],
        'home_trust_4_title' => ['Harga Transparan', 'text', 'Trust 4 judul'],
        'home_trust_4_desc' => ['Sesuai invoice, bebas biaya siluman mendadak.', 'text', 'Trust 4 deskripsi'],
        'home_scarcity_badge' => ['PENTING BANGET', 'text', 'Badge scarcity'],
        'home_scarcity_title' => ["Kita Batasin Event\nDalam 1 Hari!", 'text', 'Judul scarcity'],
        'home_scarcity_desc' => ['Biar kualitas tetap maksimal dan kru tetap prima, <strong>kami nggak ngambil overbooking</strong>. Kalau slot tanggal event kamu udah keisi, mohon maaf banget kita tutup pintu 🙏', 'html', 'Deskripsi scarcity'],
        'home_scarcity_cta_text' => ['Amankan Tanggal Sekarang', 'text', 'CTA scarcity'],
        'home_scarcity_cta_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link CTA scarcity'],
        'home_close_title' => ['Ayo, Ramaikan Acaramu!', 'text', 'Judul final close'],
        'home_close_desc' => ['Jangan biarin tamu cuman duduk main HP di meja. Bikin mereka gabung, gaya gokil, dan bawa kenangan fisiknya pulang!', 'text', 'Deskripsi final close'],
        'home_close_cta1_text' => ['Booking Langsung!', 'text', 'CTA 1 final close'],
        'home_close_cta1_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link CTA 1 final close'],
        'home_close_cta2_text' => ['Ngobrol Santai via WA', 'text', 'CTA 2 final close'],
        'home_close_cta2_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link CTA 2 final close'],

        // Gallery page
        'gallery_hero_badge' => ['The Print Wall', 'text', 'Badge hero gallery'],
        'gallery_hero_title' => ['Gallery Momen Seru', 'text', 'Judul hero gallery'],
        'gallery_hero_desc' => ['Nggak ada tamu yang kaku di depan kamera Front Photobooth!', 'text', 'Deskripsi hero gallery'],
        'gallery_img_1' => [$placeholder, 'image', 'Gallery image 1'],
        'gallery_img_2' => [$placeholder, 'image', 'Gallery image 2'],
        'gallery_img_3' => [$placeholder, 'image', 'Gallery image 3'],
        'gallery_img_4' => [$placeholder, 'image', 'Gallery image 4'],
        'gallery_img_5' => [$placeholder, 'image', 'Gallery image 5'],
        'gallery_img_6' => [$placeholder, 'image', 'Gallery image 6'],
        'gallery_cta_title' => ['Pengen Acaramu Seseru Mereka?', 'text', 'Judul CTA gallery'],
        'gallery_cta_text' => ['Ngobrol Sama Mimim', 'text', 'Teks CTA gallery'],
        'gallery_cta_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link CTA gallery'],

        // Pricelist page
        'price_hero_badge' => ['Harga Transparan', 'text', 'Badge hero pricelist'],
        'price_hero_title' => ['Choose Your Experience', 'text', 'Judul hero pricelist'],
        'price_hero_desc' => ['Nggak ada biaya siluman. Nggak ada hidden fee. Semua all-in beres.', 'text', 'Deskripsi hero pricelist'],
        'price_pkg_1_title' => ['Basic Experience', 'text', 'Paket 1 judul'],
        'price_pkg_1_desc' => ['Sempurna buat chill intimate party yang cozy.', 'text', 'Paket 1 deskripsi'],
        'price_pkg_1_list_1' => ['Durasi santai 2–3 Jam', 'text', 'Paket 1 list 1'],
        'price_pkg_1_list_2' => ['Print sesuai kuota', 'text', 'Paket 1 list 2'],
        'price_pkg_1_list_3' => ['1 Operator Friendly', 'text', 'Paket 1 list 3'],
        'price_pkg_1_list_4' => ['Kacamata Props Standard', 'text', 'Paket 1 list 4'],
        'price_pkg_1_btn_text' => ['Pilih Paket Ini', 'text', 'Tombol paket 1'],
        'price_pkg_1_btn_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link tombol paket 1'],
        'price_pkg_2_badge' => ['Most Popular', 'text', 'Badge paket 2'],
        'price_pkg_2_title' => ['Full Experience', 'text', 'Paket 2 judul'],
        'price_pkg_2_desc' => ['Maksimal buat birthday megah atau wedding rame!', 'text', 'Paket 2 deskripsi'],
        'price_pkg_2_list_1' => ['Gas terus 3–4 Jam', 'text', 'Paket 2 list 1'],
        'price_pkg_2_list_2' => ['Unlimited Print, Bebas!', 'text', 'Paket 2 list 2'],
        'price_pkg_2_list_3' => ['2 Operator (Tukang foto + asisten)', 'text', 'Paket 2 list 3'],
        'price_pkg_2_list_4' => ['Desain Custom Overlay Bebas', 'text', 'Paket 2 list 4'],
        'price_pkg_2_list_5' => ['Props Lucu Super Lengkap', 'text', 'Paket 2 list 5'],
        'price_pkg_2_btn_text' => ['Booking Sekarang', 'text', 'Tombol paket 2'],
        'price_pkg_2_btn_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link tombol paket 2'],
        'price_pkg_3_title' => ['Brand Experience', 'text', 'Paket 3 judul'],
        'price_pkg_3_desc' => ['Khusus corporate activation, brand launch.', 'text', 'Paket 3 deskripsi'],
        'price_pkg_3_list_1' => ['Waktu Custom via Run-down', 'text', 'Paket 3 list 1'],
        'price_pkg_3_list_2' => ['Mesin & Frame Full Branding', 'text', 'Paket 3 list 2'],
        'price_pkg_3_list_3' => ['Tim Sangat Dedicated', 'text', 'Paket 3 list 3'],
        'price_pkg_3_list_4' => ['Lead Data Capture & Scan QR', 'text', 'Paket 3 list 4'],
        'price_pkg_3_btn_text' => ['Diskusi Konsep', 'text', 'Tombol paket 3'],
        'price_pkg_3_btn_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link tombol paket 3'],

        // Templates page
        'templates_hero_badge' => ['Pilih Style Foto Lo', 'text', 'Badge hero templates'],
        'templates_hero_title' => ['Template Layout Frame', 'text', 'Judul hero templates'],
        'templates_hero_desc' => ['Suka cetakan besar? Atau model strip lucu? Kita sedia semuanya!', 'text', 'Deskripsi hero templates'],
        'templates_tab_1' => ['Postcard 4R', 'text', 'Tab template 1'],
        'templates_tab_2' => ['Photostrip 2R', 'text', 'Tab template 2'],
        'templates_tab_3' => ['Polaroid Model', 'text', 'Tab template 3'],
        'templates_tab_4' => ['Trio Strip', 'text', 'Tab template 4'],
        'templates_card_1_title' => ['Single 4R (1 Frame)', 'text', 'Card template 1'],
        'templates_card_1_img' => [$placeholder, 'image', 'Gambar template 1'],
        'templates_card_2_title' => ['Grid 3 (4R)', 'text', 'Card template 2'],
        'templates_card_2_img' => [$placeholder, 'image', 'Gambar template 2'],
        'templates_card_3_title' => ['Grid 4 (4R)', 'text', 'Card template 3'],
        'templates_card_3_img' => [$placeholder, 'image', 'Gambar template 3'],
        'templates_note' => ['Semua template bisa di-kustomisasi menggunakan overlay brand/nama kamu!', 'text', 'Catatan section templates'],

        // Inspirasi page
        'insp_hero_badge' => ['Ide Seru', 'text', 'Badge hero inspirasi'],
        'insp_hero_title' => ['Inspirasi Desain Frame', 'text', 'Judul hero inspirasi'],
        'insp_hero_desc' => ['Liat-liat dulu hasil karya Front Photobooth untuk berbagai macam event.', 'text', 'Deskripsi hero inspirasi'],
        'insp_tab_1' => ['All Style', 'text', 'Tab inspirasi 1'],
        'insp_tab_2' => ['Wedding / Engagement', 'text', 'Tab inspirasi 2'],
        'insp_tab_3' => ['Birthday', 'text', 'Tab inspirasi 3'],
        'insp_tab_4' => ['Corporate', 'text', 'Tab inspirasi 4'],
        'insp_card_1_img' => [$placeholder, 'image', 'Inspirasi image 1'],
        'insp_card_1_title' => ['Floral Minimalist Wedding', 'text', 'Inspirasi title 1'],
        'insp_card_1_subtitle' => ['Photostrip 2R Template', 'text', 'Inspirasi subtitle 1'],
        'insp_card_2_img' => [$placeholder, 'image', 'Inspirasi image 2'],
        'insp_card_2_title' => ['Telkomsel Gala Dinner', 'text', 'Inspirasi title 2'],
        'insp_card_2_subtitle' => ['Dark Corporate Postcard', 'text', 'Inspirasi subtitle 2'],
        'insp_card_3_img' => [$placeholder, 'image', 'Inspirasi image 3'],
        'insp_card_3_title' => ['Aesthetics Sweet 17th', 'text', 'Inspirasi title 3'],
        'insp_card_3_subtitle' => ['Polaroid Funky Style', 'text', 'Inspirasi subtitle 3'],
        'insp_cta_title' => ['Udah Kebayang Konsep Eventnya?', 'text', 'Judul CTA inspirasi'],
        'insp_cta_text' => ['Diskusi Konsep Sekarang', 'text', 'Teks CTA inspirasi'],
        'insp_cta_link' => ['https://wa.me/' . WA_NUMBER, 'text', 'Link CTA inspirasi'],

        // Blog page
        'blog_hero_badge' => ['Front Photobooth Blog', 'text', 'Badge hero blog'],
        'blog_hero_title' => ['Insight & Cerita Event', 'text', 'Judul hero blog'],
        'blog_hero_desc' => ['Tips event, inspirasi konsep, dan update terbaru dari tim kami.', 'text', 'Deskripsi hero blog'],
        'blog_empty_title' => ['Belum ada artikel.', 'text', 'Judul empty blog'],
        'blog_empty_desc' => ['Artikel blog akan tampil di sini setelah dipublish dari admin.', 'text', 'Deskripsi empty blog'],
        'blog_readmore_text' => ['Baca Selengkapnya', 'text', 'Teks tombol baca artikel'],
        'blog_detail_badge' => ['Detail Artikel', 'text', 'Badge detail blog'],
        'blog_not_found_title' => ['Artikel Tidak Ditemukan', 'text', 'Judul artikel tidak ditemukan'],
        'blog_not_found_desc' => ['Artikel tidak ditemukan atau belum dipublish.', 'text', 'Pesan artikel tidak ditemukan'],
        'blog_back_text' => ['Kembali ke Blog', 'text', 'Teks tombol kembali ke blog'],

        // Footer
        'footer_copyright' => ['© ' . date('Y') . ' Front Photobooth. All Rights Reserved.', 'text', 'Teks hak cipta footer'],
    ];

    for ($i = 1; $i <= 8; $i++) {
        $defs['client_logo_' . $i] = [$placeholder, 'image', 'Logo Klien ' . $i];
        $defs['client_name_' . $i] = ['Nama Klien ' . $i, 'text', 'Nama Klien ' . $i];
    }

    return $defs;
}

/**
 * Seed default CMS settings if not exists.
 */
function seed_cms_settings(PDO $pdo): void
{
    $definitions = get_cms_setting_definitions();

    try {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $sql = $driver === 'sqlite'
            ? 'INSERT OR IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES (?, ?, ?, ?)'
            : 'INSERT IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES (?, ?, ?, ?)';

        $stmt = $pdo->prepare($sql);
        foreach ($definitions as $key => $meta) {
            $stmt->execute([$key, $meta[0], $meta[1], $meta[2]]);
        }
    } catch (Throwable $e) {
        // Silent fail if settings table doesn't exist yet.
    }
}

// 3. Koneksi database
$pdo = null;
try {
    if ($is_production) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    } else {
        $dsn = DB_DSN;
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // Auto-seed CMS keys agar semua menu/section muncul di Admin Content.
    seed_cms_settings($pdo);
} catch (PDOException $e) {
    if ($is_production) {
        error_log('Database Connection Error: ' . $e->getMessage());
        http_response_code(503);
        die('Situs sedang dalam perbaikan. Silakan coba lagi nanti.');
    }

    die('Koneksi database gagal: ' . $e->getMessage());
}

// 4. Cache settings
$APP_SETTINGS = [];
if ($pdo) {
    try {
        $stmt = $pdo->query('SELECT setting_key, setting_value FROM settings');
        $settings_from_db = $stmt->fetchAll();
        foreach ($settings_from_db as $setting) {
            $APP_SETTINGS[$setting['setting_key']] = $setting['setting_value'];
        }
    } catch (Throwable $e) {
        // Tabel mungkin belum ada saat setup awal.
    }
}

// 5. Helper
function get_setting($key, $default = '')
{
    global $APP_SETTINGS;
    return isset($APP_SETTINGS[$key]) && $APP_SETTINGS[$key] !== '' ? $APP_SETTINGS[$key] : $default;
}

function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function asset($path)
{
    if (strpos((string) $path, 'http') === 0) {
        return $path;
    }
    $path = ltrim((string) $path, '/');
    return BASE_URL . '/' . $path;
}

function log_event(string $message): void
{
    $logDir = __DIR__ . '/storage/logs/';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    error_log('[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, 3, $logDir . 'events.log');
}

/**
 * Ensure blog table exists for both SQLite (local) and MySQL (production).
 */
function ensure_blog_table_exists(?PDO $pdo): void
{
    if (!$pdo) {
        return;
    }

    static $checked = false;
    if ($checked) {
        return;
    }

    try {
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($driver === 'sqlite') {
            $pdo->exec('
                CREATE TABLE IF NOT EXISTS blog_posts (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT NOT NULL,
                    slug TEXT NOT NULL UNIQUE,
                    excerpt TEXT,
                    content TEXT NOT NULL,
                    cover_image TEXT,
                    is_published INTEGER NOT NULL DEFAULT 0,
                    published_at DATETIME,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ');
            $pdo->exec('CREATE INDEX IF NOT EXISTS idx_blog_posts_published ON blog_posts (is_published, published_at)');
        } else {
            $pdo->exec('
                CREATE TABLE IF NOT EXISTS blog_posts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    slug VARCHAR(255) NOT NULL UNIQUE,
                    excerpt TEXT,
                    content LONGTEXT NOT NULL,
                    cover_image TEXT,
                    is_published TINYINT(1) NOT NULL DEFAULT 0,
                    published_at DATETIME NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_blog_posts_published (is_published, published_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ');
        }
    } catch (Throwable $e) {
        // Silent fail to avoid crashing frontend in constrained environments.
    }

    $checked = true;
}
