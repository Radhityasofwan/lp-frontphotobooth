CREATE DATABASE IF NOT EXISTS frontphotobooth;
USE frontphotobooth;

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
    note TEXT,
    status ENUM('pending', 'contacted', 'cancelled') DEFAULT 'pending',
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
('seo_title', 'Front Photobooth - Premium Photobooth Experience', 'text', 'Judul Artikel SEO'),
('home_hero_1', 'https://picsum.photos/seed/fp_home1/300/200', 'image', 'Home: Gambar Header Strip 1'),
('home_hero_2', 'https://picsum.photos/seed/fp_home2/300/200', 'image', 'Home: Gambar Header Strip 2'),
('home_hero_3', 'https://picsum.photos/seed/fp_home3/300/200', 'image', 'Home: Gambar Header Strip 3'),
('home_props', 'https://picsum.photos/seed/fp_home4/400/400', 'image', 'Home: Gambar Props Experience'),
('home_scrap_1', 'https://picsum.photos/seed/fp_home5/300/300', 'image', 'Home: Scrapbook 1'),
('home_scrap_2', 'https://picsum.photos/seed/fp_home6/300/400', 'image', 'Home: Scrapbook 2'),
('home_scrap_3', 'https://picsum.photos/seed/fp_home7/200/200', 'image', 'Home: Scrapbook 3'),
('home_scrap_4', 'https://picsum.photos/seed/fp_home8/200/200', 'image', 'Home: Scrapbook 4'),
('home_scrap_5', 'https://picsum.photos/seed/fp_home9/300/300', 'image', 'Home: Scrapbook 5'),
('gallery_1', 'https://picsum.photos/seed/fp_bday/400/400', 'image', 'Gallery: Polaroid Kiri'),
('gallery_2', 'https://picsum.photos/seed/fp_smile1/300/300', 'image', 'Gallery: Strip Kiri Bawah 1'),
('gallery_3', 'https://picsum.photos/seed/fp_smile2/300/300', 'image', 'Gallery: Strip Kiri Bawah 2'),
('gallery_4', 'https://picsum.photos/seed/fp_gala/400/600', 'image', 'Gallery: Polaroid Tengah Atas'),
('gallery_5', 'https://picsum.photos/seed/fp_outing/400/300', 'image', 'Gallery: Polaroid Tengah Bawah'),
('gallery_6', 'https://picsum.photos/seed/fp_wed1/300/300', 'image', 'Gallery: Strip Kanan 1'),
('gallery_7', 'https://picsum.photos/seed/fp_wed2/300/300', 'image', 'Gallery: Strip Kanan 2'),
('gallery_8', 'https://picsum.photos/seed/fp_wed3/300/300', 'image', 'Gallery: Strip Kanan 3'),
('gallery_9', 'https://picsum.photos/seed/fp_reunion/400/400', 'image', 'Gallery: Polaroid Kanan'),
('template_1', 'https://picsum.photos/seed/fp_tpl1/400/400', 'image', 'Template: Single Frame'),
('template_2a', 'https://picsum.photos/seed/fp_tpl2a/400/200', 'image', 'Template: Trio Top'),
('template_2b', 'https://picsum.photos/seed/fp_tpl2b/200/200', 'image', 'Template: Trio Bottom Left'),
('template_2c', 'https://picsum.photos/seed/fp_tpl2c/200/200', 'image', 'Template: Trio Bottom Right'),
('template_3a', 'https://picsum.photos/seed/fp_tpl3a/200/200', 'image', 'Template: Quad Top Left'),
('template_3b', 'https://picsum.photos/seed/fp_tpl3b/200/200', 'image', 'Template: Quad Top Right'),
('template_3c', 'https://picsum.photos/seed/fp_tpl3c/200/200', 'image', 'Template: Quad Bottom Left'),
('template_3d', 'https://picsum.photos/seed/fp_tpl3d/200/200', 'image', 'Template: Quad Bottom Right'),
('insp_1', 'https://picsum.photos/seed/fp_insp1/400/400', 'image', 'Inspirasi: Kiri'),
('insp_2', 'https://picsum.photos/seed/fp_insp2/400/400', 'image', 'Inspirasi: Tengah'),
('insp_3', 'https://picsum.photos/seed/fp_insp3/400/400', 'image', 'Inspirasi: Kanan');
