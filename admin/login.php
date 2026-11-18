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
    <meta name="theme-color" content="#292b46">
    <title>Administrator Login | Paul’s Portfolio</title>
    <link rel="stylesheet" href="login.css">
</head>
<body class="login-page">
    <main class="login-layout">
        <section class="login-showcase" aria-label="Portfolio administration">
            <a class="login-brand" href="../index.php" aria-label="Paul portfolio home"><span class="brand-mark">P</span><span>Paul<span class="brand-dot">.</span></span></a>
            <div class="showcase-copy">
                <span class="showcase-kicker"><span class="status-dot"></span> PORTFOLIO WORKSPACE</span>
                <h1>Your work.<br><em>Your story.</em></h1>
                <p>A quiet space to shape what the world sees. Keep your projects and skills beautifully up to date.</p>
            </div>
            <div class="showcase-art" aria-hidden="true"><div class="art-circle circle-back"></div><div class="art-circle circle-mid"></div><div class="art-circle circle-front"><span>P</span></div><i class="art-spark spark-one">✳</i><i class="art-spark spark-two">✦</i><span class="art-label">DESIGN · BUILD · CREATE</span></div>
            <p class="showcase-footer">© <?php echo date('Y'); ?> Paul Valde</p>
        </section>

        <section class="login-panel">
            <div class="login-content">
                <a class="mobile-brand" href="../index.php"><span class="brand-mark">P</span><span>Paul<span class="brand-dot">.</span></span></a>
                <div class="login-heading"><p class="eyebrow">WELCOME BACK</p><h2>Sign in</h2><p>Enter your administrator details to continue.</p></div>

                <?php if ($error !== ''): ?>
                    <div class="login-error" role="alert"><span aria-hidden="true">!</span><p><?php echo escape($error); ?></p></div>
                <?php endif; ?>

                <form class="login-form" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
                    <label for="email">Email address</label>
                    <div class="input-wrap"><span aria-hidden="true">✉</span><input id="email" name="email" type="email" required autocomplete="username" placeholder="you@example.com" value="<?php echo escape($_POST['email'] ?? ''); ?>"></div>
                    <label for="password">Password</label>
                    <div class="input-wrap"><span aria-hidden="true">⌑</span><input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password"></div>
                    <button class="sign-in-button" type="submit">Sign in <span aria-hidden="true">→</span></button>
                </form>
                <a class="back-link" href="../index.php"><span aria-hidden="true">←</span> Back to portfolio</a>
            </div>
            <p class="panel-footer">Secure administrator access</p>
        </section>
    </main>
</body>
</html>
