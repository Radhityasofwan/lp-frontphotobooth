<?php
/**
 * order.php – Form processor
 * Sanitizes, validates, saves to CSV + MySQL (if available), redirects to thank-you
 */
require_once __DIR__ . '/config.php';
session_start();

header('Content-Type: text/html; charset=utf-8');

// Method guard
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method Not Allowed');
}

// Rate limit per session
$now = time();
$last = (int) ($_SESSION['last_submit'] ?? 0);
if ($last && ($now - $last) < RATE_LIMIT_SECONDS) {
  http_response_code(429);
  exit('Terlalu cepat. Tunggu beberapa detik lalu coba lagi.');
}

// ─── Collect & sanitize ───────────────────────────────────────────────────────
$name = clean($_POST['name'] ?? '', 80);
$phone = clean($_POST['phone'] ?? '', 20);
$address = clean($_POST['address'] ?? '', 300);
$design = clean($_POST['design'] ?? '', 60);
$size = clean($_POST['size'] ?? '', 10);
$qty = max(1, min(20, (int) ($_POST['qty'] ?? 1)));
$note = clean($_POST['note'] ?? '', 300);
$agree = (int) ($_POST['agree_dp'] ?? 0);

// UTM / Click IDs
$fields = [
  'utm_source',
  'utm_medium',
  'utm_campaign',
  'utm_content',
  'utm_term',
  'fbclid',
  'gclid',
  'wbraid',
  'gbraid',
  'referrer'
];
$tracking = [];
foreach ($fields as $f) {
  $tracking[$f] = clean($_POST[$f] ?? '', 255);
}

// ─── Validate ──────────────────────────────────────────────────────────────────
$errors = [];
if ($name === '')
  $errors[] = 'Nama wajib diisi.';
if ($phone === '')
  $errors[] = 'Nomor WhatsApp wajib diisi.';
if ($address === '')
  $errors[] = 'Alamat wajib diisi.';
if ($design === '')
  $errors[] = 'Pilih desain.';
if ($size === '')
  $errors[] = 'Pilih ukuran.';
if ($agree !== 1)
  $errors[] = 'Persetujuan DP wajib dicentang.';

// Handle File Upload
$paymentProofFile = '';
if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] !== UPLOAD_ERR_NO_FILE) {
  $file = $_FILES['payment_proof'];

  // Check errors
  if ($file['error'] !== UPLOAD_ERR_OK) {
    $errors[] = 'Gagal mengunggah bukti pembayaran.';
  } elseif ($file['size'] > UPLOAD_MAX_SIZE) {
    $errors[] = 'Ukuran file maksimal 5MB.';
  } else {
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($mime, $allowedTypes, true)) {
      $errors[] = 'Format file harus berupa gambar (JPG, PNG, WEBP).';
    } else {
      // Move file securely
      $ext = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg';
      $filename = uniqid('proof_', true) . '.' . strtolower($ext);
      $destination = UPLOAD_DIR . $filename;

      if (move_uploaded_file($file['tmp_name'], $destination)) {
        $paymentProofFile = $filename;
      } else {
        $errors[] = 'Gagal menyimpan file bukti pembayaran.';
      }
    }
  }
} else {
  $errors[] = 'Bukti pembayaran wajib dilampirkan.';
}

// Honeypot check (add hidden field "hp_email" filled only by bots)
if (!empty($_POST['hp_email'])) {
  http_response_code(400);
  exit('Bad request');
}

if ($errors) {
  http_response_code(400);
  echo '<p>Error: ' . implode(' ', array_map('htmlspecialchars', $errors)) . '</p>';
  echo '<p><a href="javascript:history.back()">← Kembali</a></p>';
  exit;
}

$phone_norm = norm_phone($phone);
$_SESSION['last_submit'] = $now;

// ─── Calculate Price ──────────────────────────────────────────────────────────
$total_qty = $qty;
if ($design === 'Ichigo + Black (Paket Doble)') {
  $total_qty = $qty * 2;
}
$pairs = floor($total_qty / 2);
$singles = $total_qty % 2;
$base_price = ($pairs * PRICE_PROMO_2) + ($singles * PRICE_PROMO_1);

$surcharge = 0;
if (in_array(strtoupper($size), ['XXL', '3XL', '4XL', '5XL'])) {
  $surcharge = PRICE_SURCHARGE * $total_qty;
}
$total_price = $base_price + $surcharge;

// ─── Generate/Read Token ─────────────────────────────────────────────────────
$orderToken = $_COOKIE['order_session'] ?? '';
if (!$orderToken) {
  $orderToken = bin2hex(random_bytes(32));
  // Set cookie for 30 days
  setcookie('order_session', $orderToken, time() + 2592000, '/', '', isset($_SERVER['HTTPS']), true);
}

// ─── Save to CSV (fallback + primary log) ─────────────────────────────────────
$csvRow = array_map(function ($v) {
  // Prevent CSV injection: prefix dangerous chars
  if (in_array($v[0] ?? '', ['=', '@', '+', '-', '|', '%'], true))
    $v = "'" . $v;
  return $v;
}, [
  date('c'),
  $name,
  $phone_norm,
  $address,
  $design,
  $size,
  (string) $qty,
  (string) $total_price,
  $note,
  $paymentProofFile,
  $tracking['utm_source'],
  $tracking['utm_medium'],
  $tracking['utm_campaign'],
  $tracking['utm_content'],
  $tracking['utm_term'],
  $tracking['fbclid'],
  $tracking['gclid'],
  $tracking['wbraid'],
  $tracking['gbraid'],
  $tracking['referrer'],
  $orderToken,
]);

$csvExists = file_exists(LEADS_CSV);
$fp = @fopen(LEADS_CSV, 'a');
if ($fp) {
  if (!$csvExists) {
    fputcsv($fp, [
      'timestamp',
      'name',
      'phone',
      'address',
      'design',
      'size',
      'qty',
      'total_price',
      'note',
      'payment_proof',
      'utm_source',
      'utm_medium',
      'utm_campaign',
      'utm_content',
      'utm_term',
      'fbclid',
      'gclid',
      'wbraid',
      'gbraid',
      'referrer',
      'order_token'
    ]);
  }
  fputcsv($fp, $csvRow);
  fclose($fp);
}

// ─── Save/Update MySQL (UPSERT logic via token) ──────────────────────────────
if ($pdo) {
  try {
    $existingOrder = false;
    if ($orderToken) {
      $stmtCheck = $pdo->prepare("SELECT id FROM leads WHERE order_token = ? LIMIT 1");
      $stmtCheck->execute([$orderToken]);
      $existingOrder = $stmtCheck->fetchColumn();
    }

    if ($existingOrder) {
      // OVERWRITE existing order
      $stmt = $pdo->prepare("
          UPDATE leads SET
            name=?, phone=?, address=?, design=?, size=?, quantity=?, total_price=?, note=?, payment_proof=?,
            utm_source=?, utm_medium=?, utm_campaign=?, utm_content=?, utm_term=?,
            fbclid=?, gclid=?, wbraid=?, gbraid=?, referrer=?, updated_at=NOW()
          WHERE id=?
      ");
      $stmt->execute([
        $name,
        $phone_norm,
        $address,
        $design,
        $size,
        $qty,
        $total_price,
        $note,
        $paymentProofFile,
        $tracking['utm_source'],
        $tracking['utm_medium'],
        $tracking['utm_campaign'],
        $tracking['utm_content'],
        $tracking['utm_term'],
        $tracking['fbclid'],
        $tracking['gclid'],
        $tracking['wbraid'],
        $tracking['gbraid'],
        $tracking['referrer'],
        $existingOrder
      ]);
      log_event("Updated lead id {$existingOrder}: $name | $design x$qty");
    } else {
      // NEW order
      $stmt = $pdo->prepare("
              INSERT INTO leads
                (name, phone, address, design, size, quantity, total_price, note, payment_proof, order_token, status,
                 utm_source, utm_medium, utm_campaign, utm_content, utm_term,
                 fbclid, gclid, wbraid, gbraid, referrer)
              VALUES (?,?,?,?,?,?,?,?,?,?,'pending',?,?,?,?,?,?,?,?,?,?)
          ");
      $stmt->execute([
        $name,
        $phone_norm,
        $address,
        $design,
        $size,
        $qty,
        $total_price,
        $note,
        $paymentProofFile,
        $orderToken,
        $tracking['utm_source'],
        $tracking['utm_medium'],
        $tracking['utm_campaign'],
        $tracking['utm_content'],
        $tracking['utm_term'],
        $tracking['fbclid'],
        $tracking['gclid'],
        $tracking['wbraid'],
        $tracking['gbraid'],
        $tracking['referrer'],
      ]);
      log_event("New lead: $name | $phone_norm | $design | $size x$qty");
    }
  } catch (PDOException $e) {
    log_event("MySQL transaction failed: " . $e->getMessage());
  }
} else {
  // If DB offline, log basic to file
  log_event("New lead recorded offline: $name | $phone_norm | $design | $size x$qty");
}

// ─── Build WhatsApp message ───────────────────────────────────────────────────
$waMsg = implode("\n", [
  'Halo Admin Ozverligsportwear, saya ingin konfirmasi pesanan:',
  '',
  "Nama    : $name",
  "WA      : $phone_norm",
  "Alamat  : $address",
  "Desain  : $design",
  "Ukuran  : $size",
  "Jumlah  : $qty",
  "Total Rp: " . number_format($total_price, 0, ',', '.'),
  'Catatan : ' . ($note ?: '-'),
  'Bukti TF: (Telah dilampirkan via web)',
  '',
  'Pre-Order  : 27 Feb – 08 Mar 2026',
  'Produksi   : 09 – 21 Mar 2026',
]);
$waUrl = 'https://wa.me/' . WA_NUMBER . '?text=' . rawurlencode($waMsg);

// ─── Session for thank-you page ───────────────────────────────────────────────
$_SESSION['last_order'] = [
  'name' => $name,
  'phone' => $phone_norm,
  'design' => $design,
  'size' => $size,
  'qty' => $qty,
  'total_price' => $total_price,
  'wa' => $waUrl,
];

// ─── Redirect ────────────────────────────────────────────────────────────────
header('Location: ' . BASE_URL . '/thank-you.php', true, 302);
exit;