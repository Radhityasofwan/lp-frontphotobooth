<?php
require_once __DIR__ . '/../config.php';
session_start();

// Auto-create default admin if users table is empty
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        if ($stmt->fetchColumn() == 0) {
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            $pdo->exec("INSERT INTO users (username, password_hash) VALUES ('admin', '$hash')");
        }
    } catch (PDOException $e) {
        // Schema probably not created
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($pdo) {
        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_id'] = $user['id'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    } else {
        // Fallback for local testing without DB
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_id'] = 1;
            header('Location: index.php');
            exit;
        } else {
            $error = "DB tidak terkoneksi. Gunakan admin/admin123 untuk test lokal.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Admin | Front Photobooth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #111;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-box {
            background: #1a1a1a;
            padding: 2.5rem 2rem;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            border: 1px solid #333;
        }

        h1 {
            margin-top: 0;
            font-size: 1.5rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
        }

        h1 span {
            color: #F77B0F;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: .5rem;
            font-weight: bold;
            font-size: 0.85rem;
            color: #aaa;
            text-transform: uppercase;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: .85rem;
            background: #000;
            border: 1px solid #333;
            color: #fff;
            border-radius: 4px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: #F77B0F;
        }

        button {
            width: 100%;
            padding: .85rem;
            background: linear-gradient(135deg, #F77B0F 0%, #FFAF32 100%);
            color: #111;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            transition: background 0.2s;
            margin-top: 1rem;
        }

        button:hover {
            opacity: 0.9;
        }

        .error {
            color: #ffadaf;
            background: rgba(247, 123, 15, 0.2);
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            border: 1px solid rgba(247, 123, 15, 0.4);
        }

        .note {
            font-size: 0.8rem;
            color: #666;
            text-align: center;
            margin-top: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h1>Admin <span>Photobooth</span></h1>
        <?php if (!empty($error)): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="note">Akses khusus manajemen. First time login: admin / admin123</div>
    </div>
</body>

</html>