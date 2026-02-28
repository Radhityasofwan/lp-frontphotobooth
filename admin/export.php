<?php
require_once __DIR__ . '/../config.php';
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=leads_export_' . date('Y_m_d_His') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Date', 'Name', 'Phone', 'Address', 'Design', 'Size', 'Qty', 'Note', 'Status', 'UTM Source', 'UTM Medium', 'UTM Campaign', 'FBCLID', 'GCLID', 'Referrer']);

$stmt = $pdo->query("SELECT id, created_at, name, phone, address, design, size, quantity, note, status, utm_source, utm_medium, utm_campaign, fbclid, gclid, referrer FROM leads ORDER BY id ASC");

while ($row = $stmt->fetch()) {
    fputcsv($output, $row);
}
fclose($output);
exit;
