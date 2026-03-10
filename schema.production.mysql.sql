-- Front Photobooth - Production Schema (MySQL)
-- Target: phpMyAdmin import (MySQL 8+)
-- Date: 2026-03-10

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'image', 'html') DEFAULT 'text',
    description VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(64) NOT NULL,
    ip_address VARCHAR(45),
    event_type VARCHAR(50) NOT NULL,
    event_value INT DEFAULT 0,
    page_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_analytics_event_type (event_type),
    INDEX idx_analytics_session_id (session_id),
    INDEX idx_analytics_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
    INDEX idx_blog_posts_published (is_published, published_at),
    INDEX idx_blog_posts_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instagram_url VARCHAR(500) NOT NULL,
    caption VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_testimonials_active (is_active, sort_order, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cleanup arsitektur lama
DROP TABLE IF EXISTS leads;

-- Minimal defaults (full CMS keys akan auto-seed oleh aplikasi)
INSERT IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES
('seo_title', 'Front Photobooth - Premium Photobooth Experience', 'text', 'SEO title'),
('seo_desc', 'Photobooth modern untuk event Anda.', 'text', 'SEO description'),
('nav_brand_text', 'Front Photobooth', 'text', 'Brand text navbar'),
('nav_cta_text', 'Booking', 'text', 'Teks tombol navbar'),
('nav_cta_link', 'https://wa.me/6281234567890', 'text', 'Link tombol navbar'),
('footer_copyright', '© 2026 Front Photobooth. All Rights Reserved.', 'text', 'Footer copyright');

-- Catatan:
-- 1) Semua key CMS menu/section otomatis di-seed oleh config.php saat aplikasi dijalankan.
-- 2) Admin default user (admin / admin123) otomatis dibuat oleh admin/login.php jika tabel users kosong.
