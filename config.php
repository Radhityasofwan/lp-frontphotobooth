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

// ─────────────────────────────────────────────
// TRACKING IDs  (fill before going live)
// ─────────────────────────────────────────────
define('GA4_ID', defined('LOCAL_GA4_ID') ? LOCAL_GA4_ID : '');
define('GADS_AW_ID', defined('LOCAL_GADS_AW_ID') ? LOCAL_GADS_AW_ID : '');
define('GADS_CONV_LABEL', defined('LOCAL_GADS_CONV_LABEL') ? LOCAL_GADS_CONV_LABEL : '');
define('META_PIXEL_ID', defined('LOCAL_META_PIXEL_ID') ? LOCAL_META_PIXEL_ID : '');

// Load local secrets if exists (DB + Tracking)
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
}

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