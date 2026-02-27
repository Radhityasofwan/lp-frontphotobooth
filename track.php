<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Ensure DB is available
if (!$pdo) {
    http_response_code(503);
    echo json_encode(['error' => 'Database Unavailable']);
    exit;
}

// Read raw JSON body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$session_id = $data['session_id'] ?? '';
$event_type = $data['event_type'] ?? '';
$event_value = (int) ($data['event_value'] ?? 0);
$page_url = $data['page_url'] ?? $_SERVER['HTTP_REFERER'] ?? '';
$ip_address = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

if (empty($session_id) || empty($event_type)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

try {
    // If it's a heartbeat (time_spent), we UPDATE the existing record for this session/page instead of spamming inserts
    if ($event_type === 'time_spent') {
        $stmtCheck = $pdo->prepare("SELECT id, event_value FROM analytics WHERE session_id = ? AND event_type = 'time_spent' AND page_url = ? LIMIT 1");
        $stmtCheck->execute([$session_id, $page_url]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            // Only update if the new value is higher
            if ($event_value > (int) $existing['event_value']) {
                $stmtUpdate = $pdo->prepare("UPDATE analytics SET event_value = ? WHERE id = ?");
                $stmtUpdate->execute([$event_value, $existing['id']]);
            }
            echo json_encode(['success' => true, 'action' => 'updated']);
            exit;
        }
    }

    // Default INSERT for views, clicks, and first-time heartbeats
    $stmt = $pdo->prepare("INSERT INTO analytics (session_id, ip_address, event_type, event_value, page_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        substr($session_id, 0, 64),
        substr($ip_address, 0, 45),
        substr($event_type, 0, 50),
        $event_value,
        substr($page_url, 0, 255)
    ]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    error_log("Analytics Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal Error']);
}
