<?php
require_once __DIR__ . '/config.php';

session_start();
header('Content-Type: text/html; charset=utf-8');

// Rate limit per session
$now = time();
$last = isset($_SESSION['last_submit']) ? (int)$_SESSION['last_submit'] : 0;
if ($last && ($now - $last) < RATE_LIMIT_SECONDS) {
  http_response_code(429);
  echo "Terlalu cepat. Coba lagi beberapa detik.";
  exit;
}
$_SESSION['last_submit'] = $now;

// Helpers
function clean($s, $max=240) {
  $s = trim((string)$s);
  $s = preg_replace('/\s+/', ' ', $s);
  if (mb_strlen($s) > $max) $s = mb_substr($s, 0, $max);
  return $s;
}

function norm_phone($p) {
  $p = preg_replace('/[^0-9+]/', '', (string)$p);
  // normalize Indonesian numbers
  $p = ltrim($p);
  if (strpos($p, '+') === 0) $p = substr($p, 1);
  if (strpos($p, '0') === 0) $p = '62' . substr($p, 1);
  if (strpos($p, '62') !== 0) $p = '62' . $p;
  return $p;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo "Method not allowed";
  exit;
}

// Collect
$name = clean($_POST['name'] ?? '', 80);
$phone = clean($_POST['phone'] ?? '', 20);
$address = clean($_POST['address'] ?? '', 240);
$design = clean($_POST['design'] ?? '', 60);
$size = clean($_POST['size'] ?? '', 10);
$qty = (int)($_POST['qty'] ?? 1);
$note = clean($_POST['note'] ?? '', 240);
$agree = isset($_POST['agree_dp']) ? (int)$_POST['agree_dp'] : 0;

// UTM
$utm_source = clean($_POST['utm_source'] ?? '', 80);
$utm_medium = clean($_POST['utm_medium'] ?? '', 80);
$utm_campaign = clean($_POST['utm_campaign'] ?? '', 120);
$utm_content = clean($_POST['utm_content'] ?? '', 120);
$utm_term = clean($_POST['utm_term'] ?? '', 120);

// Validate
$errors = [];
if ($name === '') $errors[] = "Nama wajib diisi";
if ($phone === '') $errors[] = "Nomor WA wajib diisi";
if ($address === '') $errors[] = "Alamat wajib diisi";
if ($design === '') $errors[] = "Pilih desain";
if ($size === '') $errors[] = "Pilih ukuran";
if ($qty < 1 || $qty > 20) $errors[] = "Jumlah tidak valid";
if ($agree !== 1) $errors[] = "Persetujuan DP wajib dicentang";

if ($errors) {
  http_response_code(400);
  echo "Error: " . implode(", ", $errors);
  exit;
}

$phone_norm = norm_phone($phone);

// Save to CSV
if (!file_exists(LEADS_CSV)) {
  @file_put_contents(LEADS_CSV, "timestamp,name,phone,address,design,size,qty,note,utm_source,utm_medium,utm_campaign,utm_content,utm_term,user_agent,ip\n");
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ua = clean($_SERVER['HTTP_USER_AGENT'] ?? '', 200);

$row = [
  date('c'),
  $name,
  $phone_norm,
  $address,
  $design,
  $size,
  (string)$qty,
  $note,
  $utm_source,
  $utm_medium,
  $utm_campaign,
  $utm_content,
  $utm_term,
  $ua,
  $ip
];

$fp = @fopen(LEADS_CSV, 'a');
if ($fp) {
  fputcsv($fp, $row);
  fclose($fp);
}

// Build WA message
$msgLines = [
  "Halo admin, saya ingin order Jersey Kamen Rider (Edisi 1)",
  "",
  "Nama: {$name}",
  "No WA: {$phone_norm}",
  "Alamat: {$address}",
  "Desain: {$design}",
  "Size: {$size}",
  "Jumlah: {$qty}",
  "Catatan: " . ($note ?: "-"),
  "",
  "DP minimal: IDR 100.000 / jersey",
  "Periode PO: 27 Feb - 08 Mar 2026",
  "Produksi: 09 - 21 Mar 2026",
  "",
  "UTM Source: " . ($utm_source ?: "-"),
  "UTM Campaign: " . ($utm_campaign ?: "-")
];

$waText = implode("\n", $msgLines);
$waUrl = "https://wa.me/" . WA_NUMBER . "?text=" . rawurlencode($waText);

// Redirect to thank-you page (fires tracking + offers WA)
$_SESSION['last_order'] = [
  'name' => $name,
  'phone' => $phone_norm,
  'design' => $design,
  'size' => $size,
  'qty' => $qty,
  'wa' => $waUrl
];

header('Location: ' . BASE_PATH . '/thank-you.php', true, 302);
exit;