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
</head>
<body>
    <h1>Administrator Login</h1>

    <?php if ($error !== ''): ?>
        <p><?php echo escape($error); ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required autocomplete="username" value="<?php echo escape($_POST['email'] ?? ''); ?>">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">

        <button type="submit">Login</button>
    </form>
</body>
</html>
