<?php
require_once __DIR__ . '/../config.php';
session_start();

$error = '';

// Auto-create default admin if users table is empty
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        if ($stmt->fetchColumn() == 0) {
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            $insertStmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $insertStmt->execute(['admin', $hash]);
        }
    } catch (PDOException $e) {
        $error = "Database table 'users' not found. Please run <code>setup_local_db.php</code> first.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($pdo && empty($error)) {
        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_id'] = $user['id'];
            header('Location: settings.php');
            exit;
        }

        $error = 'Username atau password salah.';
    } elseif (!$pdo) {
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_id'] = 1;
            header('Location: settings.php');
            exit;
        }

        $error = 'DB tidak terkoneksi. Gunakan admin/admin123 untuk test lokal.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Admin | Front Photobooth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= asset('admin/assets/admin.css') ?>">
</head>

<body class="admin-login-page">
    <main class="auth-shell">
        <section class="auth-card" aria-label="Form login admin">
            <h1 class="auth-title">Admin <strong>Photobooth</strong></h1>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <?= $error // Allow HTML for setup link ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" required autocomplete="username">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>

            <p class="auth-note">Akses manajemen. First login: admin / admin123</p>
        </section>
    </main>
</body>

</html>
