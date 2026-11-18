<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid form request.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $statement = $db->prepare('SELECT id, password_hash FROM administrators WHERE email = ?');
        $statement->execute([$email]);
        $admin = $statement->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            loginAdmin($admin['id']);
            header('Location: dashboard.php');
            exit;
        }

        $error = 'The email or password is incorrect.';
    }
}

$token = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="login-page">
    <main class="login-shell">
        <a class="admin-brand login-brand" href="../index.php"><span class="brand-mark">P</span><span>Portfolio<span class="brand-muted"> Admin</span></span></a>
        <section class="login-card">
            <div class="login-art" aria-hidden="true"><span class="art-orbit orbit-one"></span><span class="art-orbit orbit-two"></span><span class="art-star">✦</span><span class="art-caption">YOUR CREATIVE SPACE</span></div>
            <div class="login-content"><p class="eyebrow">WELCOME BACK</p><h1>Sign in to your workspace</h1><p class="page-description">Manage your portfolio content and keep things fresh.</p>
                <?php if ($error !== ''): ?><div class="notice notice-error" role="alert"><?php echo escape($error); ?></div><?php endif; ?>
                <form class="admin-form login-form" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
                    <label for="email">Email address<input id="email" name="email" type="email" required autocomplete="username" placeholder="you@example.com" value="<?php echo escape($_POST['email'] ?? ''); ?>"></label>
                    <label for="password">Password<input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password"></label>
                    <button class="button button-primary login-submit" type="submit">Sign in <span aria-hidden="true">→</span></button>
                </form>
                <a class="back-link" href="../index.php">← Back to portfolio</a>
            </div>
        </section>
        <p class="login-footer">© <?php echo date('Y'); ?> Portfolio workspace</p>
    </main>
</body>
</html>
