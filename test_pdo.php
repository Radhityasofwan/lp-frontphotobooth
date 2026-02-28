<?php
require_once __DIR__ . '/config.php';
echo "PDO is " . ($pdo ? "connected" : "NOT connected");
if (!$pdo) {
    echo "\nCheck storage/events.log for details.";
}
