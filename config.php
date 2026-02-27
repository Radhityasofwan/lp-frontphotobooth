<?php
// =========================
// Global Config (AUTO BASE PATH)
// Works for: https://domain.com/  OR  https://domain.com/subfolder/
// =========================

// Detect scheme behind proxy / shared hosting
$proto = 'http';
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
  $proto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https' ? 'https' : 'http';
} elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
  $proto = 'https';
}

// Detect host
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Detect base path from current script location
// Example root: /index.php -> dirname = "/" -> basePath = ""
// Example subfolder: /kamenriders/index.php -> dirname = "/kamenriders"
$dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$basePath = ($dir === '' || $dir === '/') ? '' : $dir;

// Define BASE_PATH + BASE_URL
define('BASE_PATH', $basePath);
define('BASE_URL', $proto . '://' . $host . BASE_PATH);

// WhatsApp number (E.164 without +)
define('WA_NUMBER', '6281617260666');

// Brand info
define('BRAND_NAME', 'Ozverligsportwear');
define('COLLAB_NAME', 'Kemalikart');

// Tracking IDs (fill these)
define('GA4_ID', '');            // e.g. 'G-ABC123DEF4'
define('GADS_AW_ID', '');        // e.g. 'AW-123456789'
define('GADS_CONV_LABEL', '');   // e.g. 'AbCdEfGhIjkLmNoPq'
define('META_PIXEL_ID', '');     // e.g. '123456789012345'

// Lead storage
define('LEADS_CSV', __DIR__ . '/storage/leads.csv');
define('EVENTS_LOG', __DIR__ . '/storage/events.log');

// Basic rate limit seconds per session
define('RATE_LIMIT_SECONDS', 10);