<?php
require_once __DIR__ . '/../config.php';
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$status_filter = $_GET['status'] ?? '';
$where = "1=1";
$params = [];
if ($status_filter) {
    if ($status_filter === 'today') {
        $where .= " AND DATE(created_at) = CURDATE()";
    } else {
        $where .= " AND status = ?";
        $params[] = $status_filter;
    }
}

$stmt = $pdo->prepare("SELECT * FROM leads WHERE $where ORDER BY id DESC");
$stmt->execute($params);
$leads = $stmt->fetchAll();

// UTM Stats
$utmStmt = $pdo->query("SELECT utm_source, utm_campaign, COUNT(*) as total FROM leads WHERE utm_source != '' GROUP BY utm_source, utm_campaign ORDER BY total DESC");
$utms = $utmStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard CRM | Kamen Riders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: -apple-system, system-ui, sans-serif; background: #f4f4f5; margin: 0; padding: 20px; color: #333;}
        .container { max-width: 1400px; margin: auto; }
        .layout-grid { display: grid; gap: 20px; }
        @media(min-width: 900px) { .layout-grid { grid-template-columns: 1fr 300px; } }
        
        .box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border:1px solid #e5e7eb;}
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;}
        h1 { margin: 0; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 0.5px;}
        h2 { margin-top: 0; font-size: 1.1rem; border-bottom: 1px solid #eee; padding-bottom: 10px;}
        
        .filters { margin-bottom: 15px; display: flex; gap: 8px; flex-wrap: wrap; }
        .btn { padding: 6px 12px; background: #f3f4f6; text-decoration: none; color: #374151; border-radius: 4px; border: 1px solid #d1d5db; font-size:0.85rem; transition: background 0.1s;}
        .btn.active { background: #111; color: #fff; border-color: #111;}
        .btn:hover:not(.active) { background: #e5e7eb; }
        .btn-primary { background: #111; color: white; border: none; }
        .btn-primary:hover { background: #333; color: white; }
        .btn-export { background: #10b981; color: white; border: none; margin-left: auto; font-weight:600;}
        .btn-export:hover { background: #059669; color: white; }
        
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #f3f4f6; text-align: left; vertical-align:top;}
        th { background: #f9fafb; font-weight: 600; color: #6b7280; text-transform: uppercase; font-size: 0.75rem;}
        tr:hover td { background: #f9fafb; }
        
        .status-select { padding: 4px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 0.85rem; outline: none; background:#fff;}
        .badge-status { padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-contacted { background: #dbeafe; color: #1e40af; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .text-sm { font-size: 0.8rem; color: #6b7280;}
        .mt-4 { margin-top: 1rem; }
        .stats-table th, .stats-table td { padding: 8px; border-bottom: 1px dashed #e5e7eb;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kamen Riders <span style="color:#e62429">CRM</span></h1>
            <a href="actions.php?action=logout" class="btn btn-primary">Logout</a>
        </div>
        
        <div class="layout-grid">
            <div class="box">
                <div class="filters">
                    <span style="align-self:center; font-size:0.85rem; font-weight:bold;">Tampilan:</span>
                    <a href="index.php" class="btn <?= $status_filter===''?'active':'' ?>">Semua</a>
                    <a href="index.php?status=today" class="btn <?= $status_filter==='today'?'active':'' ?>">Hari Ini</a>
                    <a href="index.php?status=pending" class="btn <?= $status_filter==='pending'?'active':'' ?>">Pending</a>
                    <a href="index.php?status=contacted" class="btn <?= $status_filter==='contacted'?'active':'' ?>">Contacted</a>
                    <a href="index.php?status=paid" class="btn <?= $status_filter==='paid'?'active':'' ?>">Paid</a>
                    <a href="index.php?status=cancelled" class="btn <?= $status_filter==='cancelled'?'active':'' ?>">Cancelled</a>
                    <a href="export.php" class="btn btn-export">↓ Export CSV</a>
                </div>

                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Kontak</th>
                                <th>Pesanan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($leads as $l): ?>
                            <tr>
                                <td class="text-sm">
                                    <?= date('d M Y', strtotime($l['created_at'])) ?><br>
                                    <span style="color:#aaa"><?= date('H:i', strtotime($l['created_at'])) ?></span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($l['name']) ?></strong><br>
                                    <div class="text-sm" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?= htmlspecialchars($l['address']) ?>">
                                        <?= htmlspecialchars($l['address']) ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="https://wa.me/<?= htmlspecialchars($l['phone']) ?>" target="_blank" style="color:#059669; text-decoration:none; font-weight:600;">
                                        <?= htmlspecialchars($l['phone']) ?>
                                    </a>
                                </td>
                                <td class="text-sm">
                                    <strong><?= htmlspecialchars($l['design']) ?></strong><br>
                                    Size: <?= htmlspecialchars($l['size']) ?> &bull; Qty: <?= $l['quantity'] ?>
                                </td>
                                <td>
                                    <form action="actions.php" method="post" style="margin:0;">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="id" value="<?= $l['id'] ?>">
                                        <select name="status" class="status-select" onchange="this.form.submit()">
                                            <option value="pending" <?= $l['status']=='pending'?'selected':'' ?>>Pending</option>
                                            <option value="contacted" <?= $l['status']=='contacted'?'selected':'' ?>>Contacted</option>
                                            <option value="paid" <?= $l['status']=='paid'?'selected':'' ?>>Paid</option>
                                            <option value="cancelled" <?= $l['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                                        </select>
                                    </form>
                                    <?php if($l['utm_source']): ?>
                                        <div style="margin-top:4px; font-size:0.7rem; color:#9ca3af;">src: <?= htmlspecialchars($l['utm_source']) ?></div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($leads)): ?>
                                <tr><td colspan="5" style="text-align:center; padding: 2rem; color:#9ca3af;">Tidak ada data lead ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="box">
                    <h2>UTM Performance</h2>
                    <?php if(empty($utms)): ?>
                        <p class="text-sm">Belum ada data UTM terlacak.</p>
                    <?php else: ?>
                        <table class="stats-table">
                            <tr><th style="background:none;">Source / Campaign</th><th style="background:none; text-align:right;">Leads</th></tr>
                            <?php foreach($utms as $u): ?>
                            <tr>
                                <td class="text-sm">
                                    <strong><?= htmlspecialchars($u['utm_source']) ?></strong><br>
                                    <span style="color:#9ca3af"><?= htmlspecialchars($u['utm_campaign']) ?></span>
                                </td>
                                <td align="right"><strong><?= $u['total'] ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
                
                <div class="box mt-4">
                    <h2>Panduan</h2>
                    <ul class="text-sm" style="padding-left:1rem; margin-bottom:0; color:#4b5563;">
                        <li style="margin-bottom:8px">Lead otomatis berstatus <strong>Pending</strong>.</li>
                        <li style="margin-bottom:8px">Jika sudah balas WA, ubah ke <strong>Contacted</strong>.</li>
                        <li style="margin-bottom:8px">Setelah bukti transfer/DP diterima, ubah ke <strong>Paid</strong>.</li>
                        <li>Gunakan <strong>Export CSV</strong> untuk rekap Excel.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
