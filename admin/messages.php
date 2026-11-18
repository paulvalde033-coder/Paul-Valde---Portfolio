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
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="content-pages.css">
</head>
<body class="admin-page">
    <header class="admin-header">
        <a class="admin-brand" href="dashboard.php"><span class="brand-mark">P</span><span><strong>Paul.</strong><small>Admin Panel</small></span></a>
        <nav class="admin-nav" aria-label="Admin navigation"><a href="dashboard.php"><span>⌂</span> Overview</a><a href="projects.php"><span>▦</span> Projects</a><a href="skills.php"><span>✦</span> Skills</a><a class="active" href="messages.php"><span>✉</span> Messages</a></nav>
        <form class="logout-form" action="logout.php" method="post"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><button class="button button-quiet" type="submit">Log out <span aria-hidden="true">↗</span></button></form>
    </header>
    <main class="admin-main content-main">
        <header class="topbar"><div><p>GET IN TOUCH</p><h1>Messages</h1></div><a class="view-site" href="../index.php" target="_blank" rel="noopener">View portfolio <span>↗</span></a></header>
        <div class="content-wrap">
            <div class="page-heading"><div><p class="eyebrow">YOUR INBOX</p><h2>Conversations</h2><p>Good things start with a hello.</p></div><span class="inbox-total"><?php echo count($messages); ?> total</span></div>
            <?php if ($message !== ''): ?><div class="notice" role="status"><?php echo escape($message); ?></div><?php endif; ?>
            <?php if (!$messages): ?><div class="empty-state"><span aria-hidden="true">✉</span><h2>Your inbox is quiet</h2><p>Messages from your portfolio contact form will appear here.</p></div><?php else: ?>
                <section class="message-list" aria-label="Contact messages">
                    <?php foreach ($messages as $item): ?>
                        <article class="message-card <?php echo $item['status'] !== 'read' ? 'message-unread' : ''; ?>">
                            <div class="message-heading"><div class="sender-avatar" aria-hidden="true"><?php echo escape(strtoupper(substr($item['sender_name'], 0, 1))); ?></div><div class="sender-details"><h2><?php echo escape($item['sender_name']); ?></h2><a href="mailto:<?php echo escape($item['sender_email']); ?>"><?php echo escape($item['sender_email']); ?></a></div><span class="status-pill <?php echo $item['status'] === 'read' ? 'status-read' : 'status-unread'; ?>"><?php echo escape($item['status']); ?></span></div>
                            <div class="message-content"><h3><?php echo escape($item['subject']); ?></h3><p><?php echo nl2br(escape($item['message'])); ?></p></div>
                            <div class="message-footer"><span>Received <?php echo escape($item['created_at']); ?></span><?php if ($item['status'] !== 'read'): ?><form method="post"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>"><button class="secondary-button" type="submit">Mark as read</button></form><?php else: ?><span class="read-indicator">✓ Read</span><?php endif; ?></div>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
