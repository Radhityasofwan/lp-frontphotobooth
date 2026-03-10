<?php
require_once __DIR__ . '/../config.php';
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=analytics_export_' . date('Y_m_d_His') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Created At', 'Session ID', 'IP Address', 'Event Type', 'Event Value', 'Page URL']);

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT id, created_at, session_id, ip_address, event_type, event_value, page_url FROM analytics ORDER BY id ASC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, $row);
        }
    } catch (Throwable $e) {
    }
}

fclose($output);
exit;
