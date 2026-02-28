<?php
require_once __DIR__ . '/../config.php';
session_start();

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
    if (in_array($status, $valid_statuses) && $pdo) {
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

if ($action === 'save_settings') {
    // 1. Save all text based fields
    if (!empty($_POST['settings_text']) && $pdo) {
        $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
        foreach ($_POST['settings_text'] as $key => $val) {
            $stmt->execute([$val, $key]);
        }
    }

    // 2. Process image uploads if exists
    $uploadDir = __DIR__ . '/../storage/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach ($_FILES as $inputName => $file) {
        if (strpos($inputName, 'upload_') === 0 && $file['error'] === UPLOAD_ERR_OK) {
            $settingKey = str_replace('upload_', '', $inputName);

            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (strpos($mime, 'image/') === 0) {
                // Generate a unique filename while preserving extension
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg';
                $newFilename = 'cms_' . $settingKey . '_' . time() . '.' . $ext;
                $targetFile = $uploadDir . $newFilename;

                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    // Update the DB to point to the new image URL relative to root
                    $publicPath = 'storage/uploads/' . $newFilename;
                    if ($pdo) {
                        $stmt = $pdo->prepare("UPDATE settings SET setting_value = ?, setting_type = 'image' WHERE setting_key = ?");
                        $stmt->execute([$publicPath, $settingKey]);
                    }
                }
            }
        }
    }

    $_SESSION['msg'] = 'Settings saved successfully!';
    header('Location: settings.php');
    exit;
}
