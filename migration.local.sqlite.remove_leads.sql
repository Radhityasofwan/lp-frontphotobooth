-- Migration patch for existing local SQLite DB
-- Date: 2026-03-10

PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS settings (
    setting_key TEXT PRIMARY KEY,
    setting_value TEXT,
    setting_type TEXT DEFAULT 'text' CHECK(setting_type IN ('text', 'image', 'html')),
    description TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS analytics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    session_id TEXT NOT NULL,
    ip_address TEXT,
    event_type TEXT NOT NULL,
    event_value INTEGER DEFAULT 0,
    page_url TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_analytics_event_type ON analytics (event_type);
CREATE INDEX IF NOT EXISTS idx_analytics_session_id ON analytics (session_id);
CREATE INDEX IF NOT EXISTS idx_analytics_created_at ON analytics (created_at);

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
);

CREATE INDEX IF NOT EXISTS idx_blog_posts_published ON blog_posts (is_published, published_at);
CREATE INDEX IF NOT EXISTS idx_blog_posts_slug ON blog_posts (slug);

DROP TABLE IF EXISTS leads;

INSERT OR IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES
('seo_title', 'Front Photobooth - Premium Photobooth Experience', 'text', 'SEO title'),
('seo_desc', 'Photobooth modern untuk event Anda.', 'text', 'SEO description'),
('nav_brand_text', 'Front Photobooth', 'text', 'Brand text navbar'),
('nav_cta_text', 'Booking', 'text', 'Teks tombol navbar'),
('nav_cta_link', 'https://wa.me/6281234567890', 'text', 'Link tombol navbar');

-- Remaining CMS keys auto-seed by application (config.php)
