<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$projects = (int) $db->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$skills = (int) $db->query('SELECT COUNT(*) FROM skills')->fetchColumn();
$messages = (int) $db->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
$experiences = (int) $db->query('SELECT COUNT(*) FROM experience')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio Dashboard</title>
</head>
<body>
    <header>
        <h1>Portfolio Dashboard</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="projects.php">Projects</a>
            <a href="skills.php">Skills</a>
            <a href="messages.php">Messages</a>
            <form action="logout.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo escape(getCsrfToken()); ?>">
                <button type="submit">Logout</button>
            </form>
        </nav>
    </header>

    <main>
        <h2>Website overview</h2>
        <ul>
            <li>Projects: <?php echo escape($projects); ?></li>
            <li>Skills: <?php echo escape($skills); ?></li>
            <li>Messages: <?php echo escape($messages); ?></li>
            <li>Experiences: <?php echo escape($experiences); ?></li>
        </ul>
    </main>
</body>
</html>
