<?php
/**
 * admin.php – Mini CRM Dashboard
 * Reads from CSV, filter by status, search by name/phone, export CSV, update status
 */
require_once __DIR__ . '/config.php';

if (empty($_SESSION['admin_ok'])) {
    header('Location: ' . BASE_URL . '/login.php');
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
            fputcsv($out, $r);
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
    foreach ($lines as $line) {
        $row = array_combine($header, str_getcsv($line));
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
            background: #f5f5f5;
            color: #1a1a1a;
        }

        .topbar {
            background: #111;
            color: #fff;
            padding: .75rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .topbar-links a {
            color: #aaa;
            font-size: .85rem;
            text-decoration: none;
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
            padding: 1.25rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: .75rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 1rem;
        }

        .stat-card__n {
            font-size: .72rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .stat-card__v {
            font-size: 2rem;
            font-weight: 700;
            margin-top: .15rem;
        }

        .layout {
            display: grid;
            gap: 1.25rem;
        }

        @media(min-width: 1024px) {
            .layout {
                grid-template-columns: 1fr 280px;
            }
        }

        .box {
            background: #fff;
            border: 1px solid #e5e7eb;
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
            border: 1px solid #d1d5db;
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
            border: 1px solid #d1d5db;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            color: #374151;
            background: #fff;
        }

        .pill:hover {
            background: #f3f4f6;
        }

        .pill.active {
            background: #111;
            color: #fff;
            border-color: #111;
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
            background: #f9fafb;
            padding: .65rem .75rem;
            text-align: left;
            font-size: .73rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: .65rem .75rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }

        tr:hover td {
            background: #fafafa;
        }

        .text-muted {
            color: #6b7280;
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
            border: 1px solid #d1d5db;
            font-size: .8rem;
            outline: none;
            background: #f9fafb;
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
            border-bottom: 1px dashed #e5e7eb;
            font-size: .85rem;
        }

        .utm-row:last-child {
            border: none;
        }

        .guide li {
            margin-bottom: .5rem;
            font-size: .85rem;
            color: #4b5563;
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
            <a href="<?= h(BASE_URL) ?>/" target="_blank">← Landing Page</a>
            <a href="?export=1" class="btn" style="background:#059669">↓ Export CSV</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>

    <div class="main">

        <!-- Stats -->
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
    </div><!-- /main -->
</body>

</html>