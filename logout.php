<?php
require_once __DIR__ . '/config.php';
log_event('Admin logout from ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
$_SESSION = [];
session_destroy();
header('Location: ' . BASE_URL . '/login.php');
exit;
