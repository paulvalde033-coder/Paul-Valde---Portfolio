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
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-page">
    <header class="admin-header">
        <a class="admin-brand" href="dashboard.php"><span class="brand-mark">P</span><span>Portfolio<span class="brand-muted"> Admin</span></span></a>
        <nav class="admin-nav" aria-label="Admin navigation">
            <a class="active" href="dashboard.php">Overview</a>
            <a href="projects.php">Projects</a>
            <a href="skills.php">Skills</a>
            <a href="messages.php">Messages</a>
        </nav>
        <form class="logout-form" action="logout.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo escape(getCsrfToken()); ?>">
            <button class="button button-quiet" type="submit">Log out <span aria-hidden="true">↗</span></button>
        </form>
    </header>

    <main class="admin-main">
        <div class="page-heading">
            <div><p class="eyebrow">WORKSPACE</p><h1>Good to see you.</h1><p class="page-description">Here’s a quick overview of your portfolio content.</p></div>
            <a class="button button-primary" href="projects.php">Manage projects <span aria-hidden="true">→</span></a>
        </div>
        <section class="stats-grid" aria-label="Website overview">
            <article class="stat-card"><span class="stat-icon violet">▧</span><p class="stat-label">Projects</p><p class="stat-value"><?php echo escape($projects); ?></p><a href="projects.php">View projects <span aria-hidden="true">→</span></a></article>
            <article class="stat-card"><span class="stat-icon blue">✳</span><p class="stat-label">Skills</p><p class="stat-value"><?php echo escape($skills); ?></p><a href="skills.php">Manage skills <span aria-hidden="true">→</span></a></article>
            <article class="stat-card"><span class="stat-icon amber">✉</span><p class="stat-label">Messages</p><p class="stat-value"><?php echo escape($messages); ?></p><a href="messages.php">Read messages <span aria-hidden="true">→</span></a></article>
            <article class="stat-card"><span class="stat-icon green">⌁</span><p class="stat-label">Experience</p><p class="stat-value"><?php echo escape($experiences); ?></p><span class="stat-note">Portfolio entries</span></article>
        </section>
        <section class="panel welcome-panel">
            <div class="welcome-symbol" aria-hidden="true">✦</div>
            <div><p class="eyebrow">YOUR PORTFOLIO, YOUR STORY</p><h2>Make your next update count.</h2><p>Keep your projects and skills up to date so visitors can see what you’re working on.</p></div>
            <div class="welcome-actions"><a class="button button-primary" href="projects.php">Update projects</a><a class="text-link" href="skills.php">Edit your skills <span aria-hidden="true">→</span></a></div>
        </section>
    </main>
</body>
</html>
