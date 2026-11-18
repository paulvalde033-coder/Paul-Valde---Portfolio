<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $message = 'Invalid form request.';
    } else {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            $message = 'Invalid message.';
        } else {
            $statement = $db->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?");
            $statement->execute([$id]);
            $message = $statement->rowCount() > 0 ? 'Message marked as read.' : 'Message is already read or no longer exists.';
        }
    }
}

$messages = $db->query('SELECT id, sender_name, sender_email, subject, message, status, created_at FROM contact_messages ORDER BY created_at DESC, id DESC')->fetchAll();
$token = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Messages</title>
</head>
<body>
    <header>
        <h1>Contact Messages</h1>
        <nav aria-label="Admin navigation">
            <a href="dashboard.php">Dashboard</a>
            <a href="projects.php">Projects</a>
            <a href="skills.php">Skills</a>
            <a href="messages.php">Messages</a>
            <form action="logout.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
                <button type="submit">Logout</button>
            </form>
        </nav>
    </header>

    <main>
        <?php if ($message !== ''): ?>
            <p role="status"><?php echo escape($message); ?></p>
        <?php endif; ?>

        <?php if (!$messages): ?>
            <p>No contact messages yet.</p>
        <?php else: ?>
            <?php foreach ($messages as $item): ?>
                <article>
                    <h2><?php echo escape($item['subject']); ?></h2>
                    <p><strong>From:</strong> <?php echo escape($item['sender_name']); ?>
                        &lt;<a href="mailto:<?php echo escape($item['sender_email']); ?>"><?php echo escape($item['sender_email']); ?></a>&gt;
                    </p>
                    <p><strong>Received:</strong> <?php echo escape($item['created_at']); ?></p>
                    <p><strong>Status:</strong> <?php echo escape($item['status']); ?></p>
                    <p><?php echo nl2br(escape($item['message'])); ?></p>
                    <?php if ($item['status'] !== 'read'): ?>
                        <form method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
                            <input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
                            <button type="submit">Mark as read</button>
                        </form>
                    <?php endif; ?>
                </article>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
