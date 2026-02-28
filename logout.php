<?php
require_once __DIR__ . '/config.php';
session_start();
log_event('Admin logout from ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
$_SESSION = [];
session_destroy();
header('Location: ' . rtrim(BASE_URL, '/') . '/login.php');
exit;
