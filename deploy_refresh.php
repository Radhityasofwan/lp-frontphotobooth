<?php
/**
 * Deploy Refresh Helper (Hostinger friendly)
 *
 * Usage (recommended):
 * 1) Login admin terlebih dahulu, lalu akses /deploy_refresh.php
 * 2) Atau set env DEPLOY_REFRESH_TOKEN di hosting, lalu akses:
 *    /deploy_refresh.php?token=YOUR_TOKEN
 *
 * Delete file ini setelah selesai deploy untuk keamanan.
 */

require_once __DIR__ . '/config.php';
session_start();

header('Content-Type: text/plain; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$expectedToken = trim((string) getenv('DEPLOY_REFRESH_TOKEN'));
$providedToken = trim((string) ($_GET['token'] ?? ''));
$isAdminSession = !empty($_SESSION['admin_id']);
$isTokenAuthorized = $expectedToken !== '' && hash_equals($expectedToken, $providedToken);

if (!$isAdminSession && !$isTokenAuthorized) {
    http_response_code(403);
    echo "403 Forbidden\n";
    echo "Login admin dulu atau set DEPLOY_REFRESH_TOKEN dan kirim via ?token=.\n";
    exit;
}

echo "=== Front Photobooth Deploy Refresh ===\n";
echo 'Time: ' . date('Y-m-d H:i:s') . "\n";
echo 'Host: ' . ($_SERVER['HTTP_HOST'] ?? '-') . "\n\n";

if (!$pdo) {
    echo "Database: NOT CONNECTED\n";
    exit;
}

try {
    seed_cms_settings($pdo);
    echo "[OK] CMS settings seeded\n";
} catch (Throwable $e) {
    echo "[ERR] Seed CMS settings: " . $e->getMessage() . "\n";
}

try {
    ensure_blog_table_exists($pdo);
    echo "[OK] Blog table verified\n";
} catch (Throwable $e) {
    echo "[ERR] Blog table verify: " . $e->getMessage() . "\n";
}

try {
    ensure_testimonials_table_exists($pdo);
    echo "[OK] Testimonials table verified\n";
} catch (Throwable $e) {
    echo "[ERR] Testimonials table verify: " . $e->getMessage() . "\n";
}

$deployVersionPath = __DIR__ . '/storage/deploy.version';
$version = date('YmdHis');
if (@file_put_contents($deployVersionPath, $version) !== false) {
    echo "[OK] deploy.version updated: {$version}\n";
} else {
    echo "[ERR] Failed to update deploy.version\n";
}

if (function_exists('opcache_reset')) {
    $reset = @opcache_reset();
    echo $reset ? "[OK] OPcache reset\n" : "[WARN] OPcache reset skipped/failed\n";
} else {
    echo "[INFO] OPcache extension not available\n";
}

echo "\nDone. Silakan hard refresh browser (Ctrl/Cmd + Shift + R).\n";
