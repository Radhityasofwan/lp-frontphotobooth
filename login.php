<?php
require_once __DIR__ . '/config.php';
session_start();

// Default admin credentials (change immediately in production!)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS_HASH', '$2y$12$dummyhash.replaceWithRealHash'); // run: password_hash('yourpassword', PASSWORD_BCRYPT)

if (!empty($_SESSION['kriders_logged_in'])) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    // Check against DB first, fallback to hardcoded
    $ok = false;

    if ($pdo) {
        try {
            $st = $pdo->prepare('SELECT password_hash FROM users WHERE username = ? LIMIT 1');
            $st->execute([$user]);
            $row = $st->fetch();
            if ($row && password_verify($pass, $row['password_hash'])) {
                $ok = true;
            }
        } catch (PDOException $e) {
        }
    }

    // Fallback: hardcoded hash
    if (!$ok && $user === ADMIN_USER && password_verify($pass, ADMIN_PASS_HASH)) {
        $ok = true;
    }

    if ($ok) {
        $_SESSION['kriders_logged_in'] = true;
        log_event("Admin login: $user from " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        header('Location: admin.php');
        exit;
    }

    $error = 'Username atau password salah.';
    log_event("Failed login attempt: $user");
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin | Kamen Riders</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #0b0b0b;
            color: #f0f0f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .card {
            background: #161616;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            padding: 2.25rem;
            width: 100%;
            max-width: 380px;
        }

        h1 {
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #e62429;
            padding-left: .75rem;
        }

        h1 span {
            color: #e62429;
        }

        .field {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #8a8a8a;
            margin-bottom: .35rem;
        }

        input {
            width: 100%;
            background: #0d0d0d;
            border: 1px solid #333;
            color: #f0f0f0;
            padding: .75rem .85rem;
            border-radius: 5px;
            outline: none;
            font-size: 1rem;
        }

        input:focus {
            border-color: #39ff14;
            box-shadow: 0 0 0 2px rgba(57, 255, 20, .15);
        }

        button {
            width: 100%;
            padding: .85rem;
            background: #e62429;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            margin-top: .25rem;
            transition: background .15s;
        }

        button:hover {
            background: #b91b1f;
        }

        .error {
            background: rgba(230, 36, 41, .1);
            border: 1px solid rgba(230, 36, 41, .35);
            color: #ffadaf;
            padding: .65rem .85rem;
            border-radius: 5px;
            font-size: .875rem;
            margin-bottom: 1rem;
        }

        .hint {
            font-size: .75rem;
            color: #555;
            text-align: center;
            margin-top: 1.25rem;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Admin <span>CRM</span></h1>
        <?php if ($error): ?>
            <div class="error">
                <?= h($error) ?>
            </div>
        <?php endif; ?>
        <form method="post" autocomplete="on">
            <div class="field">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" required autocomplete="username">
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit">Masuk</button>
        </form>
        <p class="hint">Akses khusus admin. Jaga kerahasiaan password.</p>
    </div>
</body>

</html>