CREATE DATABASE IF NOT EXISTS kamenriders;
USE kamenriders;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    design VARCHAR(50) NOT NULL, -- e.g., 'Ichigo', 'Black'
    size VARCHAR(10) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    total_price INT NOT NULL DEFAULT 0,
    note TEXT,
    payment_proof VARCHAR(255),
    order_token VARCHAR(64),
    status ENUM('pending', 'contacted', 'paid', 'cancelled') DEFAULT 'pending',
    utm_source VARCHAR(50),
    utm_medium VARCHAR(50),
    utm_campaign VARCHAR(50),
    utm_content VARCHAR(100),
    utm_term VARCHAR(100),
    fbclid VARCHAR(255),
    gclid VARCHAR(255),
    wbraid VARCHAR(255),
    gbraid VARCHAR(255),
    referrer TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL,
    ip_address VARCHAR(45),
    event_type VARCHAR(50) NOT NULL, -- e.g., 'view', 'click', 'time_spent'
    event_value INT DEFAULT 0, -- e.g. amount of seconds spent
    page_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (event_type),
    INDEX (session_id)
);

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'image', 'html') DEFAULT 'text',
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Seed initial static values to preserve existing frontend
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
('seo_title', 'Jersey Kamen Rider Custom untuk Komunitas Rider Indonesia', 'text', 'Judul Artikel SEO'),
('seo_content', '<p class=\"mb-3\">Desain <strong>jersey kamen rider custom</strong> yang sedang booming kini telah hadir untuk pencinta tokusatsu tanah air! Bernostalgia bersama <strong>jersey satria baja hitam</strong> dan pahlawan abad ke-90an kini terasa lebih autentik dan eksklusif dengan rilisan limited edition ini.</p><p class=\"mb-3\">Diproduksi secara matang oleh <em>Ozverligsportwear</em> berkolaborasi dengan komunitas seni <em>Kemalikart</em>, setiap balutan <strong>jersey fantasy kamen rider</strong> kami dirancang untuk menemani gaya hidup aktif Anda. Dari <strong>jersey anime custom indonesia</strong> hingga kebutuhan apparel harian saat riding akhir pekan, kualitas material premium (Andromax Sublimasi) kami dijamin tahan terhadap cuaca.</p><p class=\"mb-0\">Bagi para die-hard fans, sebuah <strong>jersey komunitas rider</strong> tak lengkap tanpa detil sempurna layaknya pahlawan itu sendiri. Jadikan <strong>jersey tokusatsu indonesia</strong> ini pelengkap koleksi utama Anda. Tunggu apa lagi? Lengkapi hari-harimu dengan gaya nostalgia <strong>jersey kamen rider indonesia</strong> yang membalut karakter gagah jagoan idola.</p>', 'html', 'Konten Artikel SEO di Bawah');
