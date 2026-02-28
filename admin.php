<?php
/**
 * admin.php – Mini CRM Dashboard
 * Reads from CSV, filter by status, search by name/phone, export CSV, update status
 */
require_once __DIR__ . '/config.php';
session_start();

if (empty($_SESSION['kriders_logged_in'])) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

// ─── Settings POST Handler ───────────────────────────────────────────
if (($_SERVER['REQUEST_METHOD'] === 'POST') && ($_POST['action'] ?? '') === 'save_settings') {
    if (!$pdo)
        die('Database required for settings.');

    // Save standard text/html settings
    if (!empty($_POST['setting']) && is_array($_POST['setting'])) {
        $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
        foreach ($_POST['setting'] as $k => $v) {
            $stmt->execute([$v, $k]);
        }
    }

    // Save image uploads
    if (!empty($_FILES['setting_img'])) {
        $uploadDir = __DIR__ . '/storage/uploads/';
        if (!is_dir($uploadDir))
            mkdir($uploadDir, 0755, true);

        $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
        foreach ($_FILES['setting_img']['tmp_name'] as $k => $tmpPath) {
            if (is_uploaded_file($tmpPath)) {
                $ext = pathinfo($_FILES['setting_img']['name'][$k], PATHINFO_EXTENSION);
                $newName = 'img_' . preg_replace('/[^a-zA-Z0-9_]/', '', $k) . '_' . time() . '.' . $ext;
                if (move_uploaded_file($tmpPath, $uploadDir . $newName)) {
                    $dbPath = 'storage/uploads/' . $newName;
                    $stmt->execute([$dbPath, $k]);
                }
            }
        }
    }

    header('Location: admin.php?tab=settings&saved=1');
    exit;
}

// ─── Update status (POST) ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $targetTs = clean($_POST['timestamp'] ?? '', 50);
    $newStatus = in_array($_POST['new_status'] ?? '', ['pending', 'contacted', 'paid', 'cancelled'])
        ? $_POST['new_status'] : 'pending';

    if ($pdo) {
        try {
            // Use MySQL if available
            $id = (int) $_POST['lead_id'];
            $pdo->prepare('UPDATE leads SET status=? WHERE id=?')->execute([$newStatus, $id]);
            log_event("Status updated: lead#$id → $newStatus");
        } catch (PDOException $e) {
            log_event('Status update failed: ' . $e->getMessage());
        }
    }

    // Also patch CSV
    if (file_exists(LEADS_CSV)) {
        $lines = file(LEADS_CSV, FILE_IGNORE_NEW_LINES);
        $out = [];
        foreach ($lines as $i => $line) {
            if ($i === 0) {
                $out[] = $line;
                continue;
            } // header
            $row = str_getcsv($line);
            // timestamp is col 0; we add status as col 9 if patching CSV
            // We keep CSV minimal; status update is primarily via MySQL
            $out[] = $line;
        }
        file_put_contents(LEADS_CSV, implode("\n", $out));
    }

    header('Location: admin.php' . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''));
    exit;
}

// ─── Export CSV ───────────────────────────────────────────────────────────────
if (isset($_GET['export'])) {
    if ($pdo) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=leads_' . date('Y_m_d_His') . '.csv');
        $out = fopen('php://output', 'w');
        fputcsv($out, [
            'ID',
            'Date',
            'Name',
            'Phone',
            'Address',
            'Design',
            'Size',
            'Qty',
            'Total Price',
            'Note',
            'Payment Proof',
            'Status',
            'UTM Source',
            'UTM Medium',
            'UTM Campaign',
            'UTM Content',
            'UTM Term',
            'FBCLID',
            'GCLID',
            'WBRAID',
            'GBRAID',
            'Referrer'
        ]);
        $rows = $pdo->query('SELECT * FROM leads ORDER BY id ASC')->fetchAll();
        foreach ($rows as $r) {
            fputcsv($out, [
                $r['id'] ?? '',
                $r['created_at'] ?? '',
                $r['name'] ?? '',
                $r['phone'] ?? '',
                $r['address'] ?? '',
                $r['design'] ?? '',
                $r['size'] ?? '',
                $r['quantity'] ?? '',
                $r['total_price'] ?? '',
                $r['note'] ?? '',
                $r['payment_proof'] ?? '',
                $r['status'] ?? '',
                $r['utm_source'] ?? '',
                $r['utm_medium'] ?? '',
                $r['utm_campaign'] ?? '',
                $r['utm_content'] ?? '',
                $r['utm_term'] ?? '',
                $r['fbclid'] ?? '',
                $r['gclid'] ?? '',
                $r['wbraid'] ?? '',
                $r['gbraid'] ?? '',
                $r['referrer'] ?? ''
            ]);
        }
        fclose($out);
        exit;
    }
    // Fallback: serve raw CSV
    if (file_exists(LEADS_CSV)) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=leads_export.csv');
        readfile(LEADS_CSV);
        exit;
    }
    die('No data to export.');
}

// ─── Load Leads ───────────────────────────────────────────────────────────────
$filterStatus = $_GET['status'] ?? '';
$search = trim($_GET['q'] ?? '');
$validStatuses = ['pending', 'contacted', 'paid', 'cancelled'];

$leads = [];
$utmStats = [];

if ($pdo) {
    try {
        $where = ['1=1'];
        $params = [];

        if ($filterStatus && in_array($filterStatus, $validStatuses)) {
            $where[] = 'status = ?';
            $params[] = $filterStatus;
        }
        if ($search) {
            $where[] = '(name LIKE ? OR phone LIKE ?)';
            $s = '%' . $search . '%';
            $params[] = $s;
            $params[] = $s;
        }
        $sql = 'SELECT * FROM leads WHERE ' . implode(' AND ', $where) . ' ORDER BY id DESC LIMIT 500';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $leads = $stmt->fetchAll();

        // UTM aggregation
        $utmRows = $pdo->query("SELECT utm_source, utm_campaign, COUNT(*) as total FROM leads WHERE utm_source != '' GROUP BY utm_source, utm_campaign ORDER BY total DESC LIMIT 20")->fetchAll();
        foreach ($utmRows as $r) {
            $utmStats[] = $r;
        }
    } catch (PDOException $e) {
    }
} elseif (file_exists(LEADS_CSV)) {
    // CSV fallback
    $lines = file(LEADS_CSV, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $header = str_getcsv(array_shift($lines));
    $is_old_csv = !in_array('payment_proof', $header);
    $new_header = ['timestamp', 'name', 'phone', 'address', 'design', 'size', 'qty', 'total_price', 'note', 'payment_proof', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'wbraid', 'gbraid', 'referrer', 'order_token'];

    foreach ($lines as $line) {
        $csv_row = str_getcsv($line);
        $current_header = ($is_old_csv && count($csv_row) >= 20) ? $new_header : $header;

        $hc = count($current_header);
        $rc = count($csv_row);
        if ($rc < $hc) {
            $csv_row = array_pad($csv_row, $hc, '');
        } else if ($rc > $hc) {
            $csv_row = array_slice($csv_row, 0, $hc);
        }
        $row = array_combine($current_header, $csv_row);

        if (!$row)
            continue;
        if ($filterStatus && ($row['status'] ?? '') !== $filterStatus)
            continue;
        if ($search) {
            $haystack = strtolower(($row['name'] ?? '') . ' ' . ($row['phone'] ?? ''));
            if (strpos($haystack, strtolower($search)) === false)
                continue;
        }
        $leads[] = $row;
    }
    $leads = array_reverse($leads); // newest first
}

// Totals
$total = count($leads);
$totalPending = 0;
$totalPaid = 0;
foreach ($leads as $l) {
    if (($l['status'] ?? '') === 'pending')
        $totalPending++;
    if (($l['status'] ?? '') === 'paid')
        $totalPaid++;
}

function statusClass(string $s): string
{
    return match ($s) {
        'paid' => 'st-paid',
        'contacted' => 'st-contacted',
        'cancelled' => 'st-cancelled',
        default => 'st-pending',
    };
}

// ─── Analytics Aggregation ────────────────────────────────────────────────────
$stat_views = 0;
$stat_reach = 0;
$stat_clicks = 0;
$stat_duration = 0;

if ($pdo) {
    try {
        $rowViews = $pdo->query("SELECT COUNT(*) AS c FROM analytics WHERE event_type LIKE 'view_%'")->fetch();
        $stat_views = $rowViews['c'] ?? 0;

        $rowReach = $pdo->query("SELECT COUNT(DISTINCT ip_address) AS c FROM analytics WHERE event_type LIKE 'view_%'")->fetch();
        $stat_reach = $rowReach['c'] ?? 0;

        $rowClicks = $pdo->query("SELECT COUNT(*) AS c FROM analytics WHERE event_type LIKE 'click_%'")->fetch();
        $stat_clicks = $rowClicks['c'] ?? 0;

        $rowDur = $pdo->query("SELECT AVG(event_value) AS a FROM analytics WHERE event_type = 'time_spent' AND event_value > 0")->fetch();
        $stat_duration = $rowDur['a'] ? round($rowDur['a']) : 0;
    } catch (PDOException $e) {
        // Table might not exist yet if no visits occurred
    }
}

// Formatting Helper for Duration
function formatDuration($seconds)
{
    if ($seconds < 60)
        return $seconds . 's';
    $m = floor($seconds / 60);
    $s = $seconds % 60;
    return $m . 'm ' . $s . 's';
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin CRM | Kamen Riders</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #050505;
            color: #f8f9fa;
        }

        .topbar {
            background: rgba(17, 17, 17, 0.95);
            backdrop-filter: blur(10px);
            color: #fff;
            padding: .75rem 1rem;
            display: flex;
            flex-wrap: wrap;
            row-gap: .5rem;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .topbar h1 {
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .topbar h1 span {
            color: #e62429;
        }

        .topbar-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .topbar-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .topbar-nav {
            display: flex;
            background: #222;
            border-radius: 6px;
            overflow: hidden;
            margin-right: 1rem;
        }

        .topbar-nav a {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            text-decoration: none;
            color: #aaa;
            font-weight: 700;
        }

        .topbar-nav a:hover {
            background: #333;
            color: #fff;
        }

        .topbar-nav a.active {
            background: #e62429;
            color: #fff;
        }

        .topbar-links a.link-out {
            color: #ccc;
            font-size: .85rem;
            text-decoration: none;
            font-weight: 600;
        }

        .topbar-links a.btn {
            background: #e62429;
            color: #fff;
            padding: .4rem .85rem;
            border-radius: 4px;
            font-weight: 700;
            font-size: .8rem;
        }

        .topbar-links a.btn:hover {
            background: #b91b1f;
        }

        .main {
            max-width: 1400px;
            margin: auto;
            padding: .75rem;
        }

        @media(min-width: 768px) {
            .main {
                padding: 1.25rem;
            }

            .topbar {
                padding: .75rem 1.25rem;
                flex-wrap: nowrap;
            }
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: .5rem;
            margin-bottom: 1rem;
        }

        @media(min-width: 768px) {
            .stats {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: .75rem;
                margin-bottom: 1.25rem;
            }
        }

        .stat-card {
            background: #111;
            border: 1px solid rgba(255, 30, 39, 0.2);
            border-radius: 6px;
            padding: .85rem;
        }

        .stat-card__n {
            font-size: .68rem;
            color: #8b8f97;
            text-transform: uppercase;
            letter-spacing: .5px;
            line-height: 1.2;
        }

        .stat-card__v {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: .25rem;
            line-height: 1;
        }

        @media(min-width: 768px) {
            .stat-card {
                padding: 1rem;
            }

            .stat-card__n {
                font-size: .72rem;
            }

            .stat-card__v {
                font-size: 2rem;
            }
        }

        .layout {
            display: grid;
            gap: 1rem;
        }

        @media(min-width: 1024px) {
            .layout {
                grid-template-columns: 1fr 280px;
                gap: 1.25rem;
            }
        }

        .box {
            background: #111;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: .75rem;
        }

        @media(min-width: 768px) {
            .box {
                padding: 1.1rem;
            }
        }

        @media(min-width: 1024px) {
            .layout {
                grid-template-columns: 1fr 280px;
            }
        }

        .box {
            background: #111;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 1.1rem;
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .toolbar form {
            display: flex;
            gap: .5rem;
            flex: 1;
            min-width: 200px;
        }

        .toolbar input[type=text] {
            flex: 1;
            padding: .5rem .75rem;
            border: 1px solid #333;
            background: #222;
            color: #fff;
            border-radius: 4px;
            font-size: .9rem;
            outline: none;
        }

        .toolbar input:focus {
            border-color: #111;
        }

        .pill-row {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }

        .pill {
            padding: .35rem .75rem;
            border-radius: 20px;
            border: 1px solid #333;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            color: #ccc;
            background: #222;
        }

        .pill:hover {
            background: #333;
        }

        .pill.active {
            background: #ff1e27;
            color: #fff;
            border-color: #ff1e27;
        }

        .pill-green {
            background: #ecfdf5;
            border-color: #6ee7b7;
            color: #065f46;
        }

        a.btn-export {
            background: #059669;
            color: #fff;
            padding: .45rem 1rem;
            border-radius: 4px;
            font-weight: 700;
            font-size: .82rem;
            text-decoration: none;
            white-space: nowrap;
        }

        a.btn-export:hover {
            background: #047857;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .875rem;
        }

        th {
            background: #1a1a1a;
            padding: .65rem .75rem;
            text-align: left;
            font-size: .73rem;
            color: #8b8f97;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 2px solid #333;
        }

        td {
            padding: .65rem .75rem;
            border-bottom: 1px solid #222;
            vertical-align: top;
        }

        tr:hover td {
            background: #151515;
        }

        .text-muted {
            color: #8b8f97;
            font-size: .8rem;
        }

        .wa-link {
            color: #059669;
            font-weight: 700;
            font-size: .85rem;
        }

        .st-bad {
            text-align: center;
            padding: 2rem;
            color: #9ca3af;
        }

        /* Status badges */
        .st-pending {
            background: #fef3c7;
            color: #92400e;
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }

        .st-contacted {
            background: #dbeafe;
            color: #1e40af;
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }

        .st-paid {
            background: #d1fae5;
            color: #065f46;
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }

        .st-cancelled {
            background: #fee2e2;
            color: #991b1b;
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }

        select.st-sel {
            margin-top: .35rem;
            padding: .3rem .5rem;
            border-radius: 4px;
            border: 1px solid #444;
            font-size: .8rem;
            outline: none;
            background: #222;
            color: #fff;
            cursor: pointer;
        }

        h3 {
            font-size: 1rem;
            margin-bottom: .75rem;
        }

        .utm-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .5rem 0;
            border-bottom: 1px dashed #333;
            font-size: .85rem;
        }

        .utm-row:last-child {
            border: none;
        }

        .guide li {
            margin-bottom: .5rem;
            font-size: .85rem;
            color: #aaa;
        }

        .guide ul {
            padding-left: 1.1rem;
            margin-top: .5rem;
        }

        .ov {
            overflow-x: auto;
        }
    </style>
</head>

<body>

    <div class="topbar">
        <h1>Kamen Riders <span>CRM</span></h1>
        <div class="topbar-links">
            <?php $activeTab = $_GET['tab'] ?? 'leads'; ?>
            <div class="topbar-nav d-none d-md-flex">
                <a href="?tab=leads" class="<?= $activeTab !== 'settings' ? 'active' : '' ?>">📦 Data Leads</a>
                <a href="?tab=settings" class="<?= $activeTab === 'settings' ? 'active' : '' ?>">⚙️ Pengaturan
                    Konten</a>
            </div>
            <a href="<?= h(BASE_URL) ?>/" target="_blank" class="link-out">← Landing Page</a>
            <?php if ($activeTab !== 'settings'): ?>
                <a href="?export=1" class="btn" style="background:#059669">↓ Export CSV</a>
            <?php endif; ?>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>

    <!-- Mobile Tab Nav -->
    <div class="topbar-nav d-md-none" style="margin: 10px; border-radius: 4px; border: 1px solid #333;">
        <a href="?tab=leads" style="flex:1; text-align:center"
            class="<?= $activeTab !== 'settings' ? 'active' : '' ?>">Data Leads</a>
        <a href="?tab=settings" style="flex:1; text-align:center"
            class="<?= $activeTab === 'settings' ? 'active' : '' ?>">Pengaturan</a>
    </div>

    <div class="main">

        <?php if ($activeTab === 'settings'): ?>

            <!-- =================== PENGATURAN KONTEN (SETTINGS) ================== -->
            <?php if (!empty($_GET['saved'])): ?>
                <div
                    style="background:#059669; color:#fff; padding:1rem; border-radius:6px; margin-bottom:1.5rem; font-weight:700;">
                    ✅ Pengaturan berhasil disimpan! Periksa <a href="<?= h(BASE_URL) ?>/" target="_blank"
                        style="color:#fff; text-decoration:underline;">halaman depan situs</a> untuk melihat perubahannya.
                </div>
            <?php endif; ?>

            <div style="background:#111; padding: 1.5rem; border-radius:8px; border: 1px solid rgba(255,255,255,0.1);">
                <h2
                    style="font-family:'Rajdhani',sans-serif; color:var(--red); text-transform:uppercase; font-size:1.5rem; margin-bottom:1rem;">
                    Kelola Konten Website</h2>
                <p style="color:#aaa; font-size:0.9rem; margin-bottom:2rem;">Sesuaikan teks penawaran promosi, gambar
                    background Hero, serta link spesifik Instagram untuk varian produk langsung tanpa menyentuh kode.</p>

                <form action="admin.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="save_settings">

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <?php
                        if ($pdo) {
                            $settingsRow = get_all_settings();
                            foreach ($settingsRow as $s) {
                                $k = h($s['setting_key']);
                                $v = h($s['setting_value']);
                                $desc = h($s['description']);
                                $type = $s['setting_type'];

                                echo '<div style="background:#1a1a1a; padding:1.2rem; border-radius:6px; border:1px solid #333;">';
                                echo '<label style="display:block; font-weight:700; font-size:0.9rem; color:#fff; margin-bottom:0.3rem;">' . h(ucwords(str_replace('_', ' ', $k))) . '</label>';
                                echo '<div style="font-size:0.75rem; color:#888; margin-bottom:0.8rem;">' . $desc . '</div>';

                                if ($type === 'text') {
                                    echo '<input type="text" name="setting[' . $k . ']" value="' . $v . '" style="width:100%; padding:0.6rem; background:#222; border:1px solid #444; color:#fff; border-radius:4px; font-size:0.85rem;" required>';
                                } elseif ($type === 'html') {
                                    echo '<textarea name="setting[' . $k . ']" rows="4" style="width:100%; padding:0.6rem; background:#222; border:1px solid #444; color:#fff; border-radius:4px; font-size:0.85rem;" required>' . $v . '</textarea>';
                                } elseif ($type === 'image') {
                                    echo '<div style="display:flex; align-items:center; gap:1rem;">';
                                    echo '<img src="' . h(asset($v)) . '" alt="Current" style="height:60px; width:auto; border-radius:4px; border:1px solid #444;">';
                                    echo '<input type="file" name="setting_img[' . $k . ']" accept="image/png, image/jpeg, image/webp" style="color:#aaa; font-size:0.8rem;">';
                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                        } else {
                            echo "<p style='color:red'>Database terputus. Mode offline tidak mendukung edit referensi konten.</p>";
                        }
                        ?>
                    </div>

                    <div style="margin-top: 2rem; border-top: 1px solid #333; padding-top: 1.5rem; text-align:right;">
                        <button class="btn btn-red" type="submit"
                            style="background:#e62429; color:#fff; padding:0.8rem 2rem; border:none; border-radius:4px; font-weight:700; font-size:1rem; cursor:pointer;">Simpan
                            Perubahan Konten</button>
                    </div>
                </form>
            </div>

        <?php else: ?>

            <!-- =================== DATA LEADS CRM ================== -->

            <!-- Tracking Insights (Batch 20) -->
            <h3 class="mt-2 mb-3"
                style="color:var(--red); font-family:'Rajdhani',sans-serif; text-transform:uppercase; letter-spacing:1px; border-bottom: 1px solid rgba(255, 30, 39, 0.3); padding-bottom:0.5rem;">
                Traffic & Engagement</h3>
            <div class="stats">
                <div class="stat-card" style="border-top-color:#00ff66;">
                    <div class="stat-card__n">Impressions</div>
                    <div class="stat-card__v" style="color:#00ff66">
                        <?= number_format($stat_views) ?>
                    </div>
                </div>
                <div class="stat-card" style="border-top-color:#38bdf8;">
                    <div class="stat-card__n">Unique Reach</div>
                    <div class="stat-card__v" style="color:#38bdf8">
                        <?= number_format($stat_reach) ?>
                    </div>
                </div>
                <div class="stat-card" style="border-top-color:#fbbf24;">
                    <div class="stat-card__n">CTA Clicks</div>
                    <div class="stat-card__v" style="color:#fbbf24">
                        <?= number_format($stat_clicks) ?>
                    </div>
                </div>
                <div class="stat-card" style="border-top-color:#a855f7;">
                    <div class="stat-card__n">Avg. Duration</div>
                    <div class="stat-card__v" style="color:#a855f7">
                        <?= formatDuration($stat_duration) ?>
                    </div>
                </div>
            </div>

            <h3 class="mt-4 mb-3"
                style="color:#fff; font-family:'Rajdhani',sans-serif; text-transform:uppercase; letter-spacing:1px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom:0.5rem;">
                Sales Conversions</h3>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-card__n">Total Leads</div>
                    <div class="stat-card__v">
                        <?= $total ?>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__n">Pending</div>
                    <div class="stat-card__v" style="color:#d97706">
                        <?= $totalPending ?>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__n">Paid</div>
                    <div class="stat-card__v" style="color:#059669">
                        <?= $totalPaid ?>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__n">Conversion</div>
                    <div class="stat-card__v">
                        <?= $total > 0 ? round($totalPaid / $total * 100) : 0 ?>%
                    </div>
                </div>
            </div>

            <div class="layout">
                <!-- Main leads table -->
                <div class="box">
                    <!-- Toolbar -->
                    <div class="toolbar">
                        <div class="pill-row">
                            <a href="admin.php" class="pill <?= $filterStatus === '' ? 'active' : '' ?>">Semua</a>
                            <a href="admin.php?status=pending"
                                class="pill <?= $filterStatus === 'pending' ? 'active' : '' ?>">Pending</a>
                            <a href="admin.php?status=contacted"
                                class="pill <?= $filterStatus === 'contacted' ? 'active' : '' ?>">Contacted</a>
                            <a href="admin.php?status=paid"
                                class="pill pill-green <?= $filterStatus === 'paid' ? 'active' : '' ?>">Paid</a>
                            <a href="admin.php?status=cancelled"
                                class="pill <?= $filterStatus === 'cancelled' ? 'active' : '' ?>">Cancelled</a>
                        </div>
                        <form method="get">
                            <?php if ($filterStatus): ?>
                                <input type="hidden" name="status" value="<?= h($filterStatus) ?>">
                            <?php endif; ?>
                            <input type="text" name="q" value="<?= h($search) ?>" placeholder="Cari nama / WA…">
                            <button type="submit"
                                style="padding:.5rem .85rem;background:#111;color:#fff;border:none;border-radius:4px;font-weight:700;cursor:pointer">Cari</button>
                        </form>
                    </div>

                    <div class="ov">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>WA / Alamat</th>
                                    <th>Pesanan</th>
                                    <th>Bukti TF</th>
                                    <th>Status</th>
                                    <th>UTM</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($leads)): ?>
                                    <tr>
                                        <td class="st-bad" colspan="7">Tidak ada lead ditemukan.</td>
                                    </tr>
                                <?php else:
                                    foreach ($leads as $l):
                                        $ts = $l['created_at'] ?? ($l['timestamp'] ?? '');
                                        $id = $l['id'] ?? 0;
                                        $name = $l['name'] ?? '-';
                                        $ph = $l['phone'] ?? '-';
                                        $addr = $l['address'] ?? '-';
                                        $des = $l['design'] ?? '-';
                                        $sz = $l['size'] ?? '-';
                                        $qty = $l['quantity'] ?? $l['qty'] ?? '-';
                                        $tot = $l['total_price'] ?? 0;
                                        $proof = $l['payment_proof'] ?? '-';
                                        $st = $l['status'] ?? 'pending';
                                        $src = $l['utm_source'] ?? '-';
                                        $camp = $l['utm_campaign'] ?? '-';
                                        $fbc = $l['fbclid'] ?? '';
                                        ?>
                                        <tr>
                                            <td class="text-muted">
                                                <?= (int) $id ?>
                                            </td>
                                            <td class="text-muted" style="white-space:nowrap">
                                                <?= $ts ? date('d M Y', strtotime($ts)) : '-' ?><br>
                                                <?= $ts ? date('H:i', strtotime($ts)) : '' ?>
                                            </td>
                                            <td><strong>
                                                    <?= h($name) ?>
                                                </strong></td>
                                            <td>
                                                <a class="wa-link" href="https://wa.me/<?= h($ph) ?>" target="_blank"
                                                    rel="noopener">
                                                    <?= h($ph) ?>
                                                </a><br>
                                                <span class="text-muted"
                                                    style="max-width:160px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                                                    title="<?= h($addr) ?>">
                                                    <?= h($addr) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong>
                                                    <?= h($des) ?>
                                                </strong><br>
                                                <span class="text-muted">
                                                    <?= h($sz) ?> ×
                                                    <?= h((string) $qty) ?>
                                                </span><br>
                                                <span class="text-muted" style="color:var(--green)">
                                                    <?= idr((int) $tot) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($proof && $proof !== '-'): ?>
                                                    <a href="<?= h(BASE_URL) ?>/storage/uploads/<?= h($proof) ?>" target="_blank"
                                                        style="color:var(--red); text-decoration:underline;">Lihat Bukti</a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="<?= statusClass($st) ?>">
                                                    <?= h($st) ?>
                                                </span>
                                                <?php if ($pdo): ?>
                                                    <form method="post" style="margin-top:.35rem">
                                                        <input type="hidden" name="update_status" value="1">
                                                        <input type="hidden" name="lead_id" value="<?= (int) $id ?>">
                                                        <input type="hidden" name="timestamp" value="<?= h($ts) ?>">
                                                        <select name="new_status" class="st-sel" onchange="this.form.submit()">
                                                            <option value="pending" <?= $st === 'pending' ? 'selected' : '' ?>>Pending
                                                            </option>
                                                            <option value="contacted" <?= $st === 'contacted' ? 'selected' : '' ?>>
                                                                Contacted
                                                            </option>
                                                            <option value="paid" <?= $st === 'paid' ? 'selected' : '' ?>>Paid</option>
                                                            <option value="cancelled" <?= $st === 'cancelled' ? 'selected' : '' ?>>
                                                                Cancelled
                                                            </option>
                                                        </select>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted">
                                                <?= h($src) ?><br>
                                                <span style="font-size:.72rem">
                                                    <?= h(mb_substr($camp, 0, 20)) ?>
                                                </span><br>
                                                <?php if ($fbc): ?>
                                                    <span title="<?= h($fbc) ?>" style="font-size:.7rem;color:#059669">fbclid ✓</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sidebar -->
                <div>
                    <div class="box" style="margin-bottom:1rem">
                        <h3>UTM Performance</h3>
                        <?php if (empty($utmStats)): ?>
                            <p class="text-muted">Belum ada data UTM.</p>
                        <?php else: ?>
                            <?php foreach ($utmStats as $u): ?>
                                <div class="utm-row">
                                    <div>
                                        <strong>
                                            <?= h($u['utm_source'] ?? '') ?>
                                        </strong><br>
                                        <span class="text-muted">
                                            <?= h($u['utm_campaign'] ?? '') ?>
                                        </span>
                                    </div>
                                    <strong>
                                        <?= (int) $u['total'] ?>
                                    </strong>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="box guide">
                        <h3>Panduan Status</h3>
                        <ul>
                            <li><strong>Pending</strong> — Lead baru masuk, belum dibalas.</li>
                            <li><strong>Contacted</strong> — Sudah balas WA / follow up.</li>
                            <li><strong>Paid</strong> — DP / lunas sudah diterima.</li>
                            <li><strong>Cancelled</strong> — Batal / tidak respons.</li>
                        </ul>
                    </div>
                </div>

            </div><!-- /layout -->
        <?php endif; // End Tab Toggle ?>
    </div><!-- /main -->

    <script>
        // Any required admin JS
    </script>
</body>

</html>