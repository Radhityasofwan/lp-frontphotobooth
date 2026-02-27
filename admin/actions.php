<?php
require_once __DIR__ . '/../config.php';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit('Forbidden');
}

$action = $_POST['action'] ?? '';

if ($action === 'update_status') {
    $id = (int) $_POST['id'];
    $status = $_POST['status'];

    $valid_statuses = ['pending', 'contacted', 'paid', 'cancelled'];
    if (in_array($status, $valid_statuses)) {
        $stmt = $pdo->prepare("UPDATE leads SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        // Log event
        $logMessage = "[" . date("Y-m-d H:i:s") . "] Admin " . $_SESSION['admin_id'] . " updated lead ($id) status to $status" . PHP_EOL;
        error_log($logMessage, 3, __DIR__ . '/../storage/logs/events.log');
    }

    $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: $ref");
    exit;
}
