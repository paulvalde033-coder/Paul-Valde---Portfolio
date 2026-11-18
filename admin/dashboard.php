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
    <aside class="sidebar">
        <a class="admin-brand" href="dashboard.php"><span class="brand-mark">P</span><span><strong>Paul.</strong><small>Admin Panel</small></span></a>
        <nav class="admin-nav" aria-label="Admin navigation">
            <a class="active" href="dashboard.php"><span>⌂</span> Overview</a>
            <a href="projects.php"><span>▦</span> Projects</a>
            <a href="skills.php"><span>✦</span> Skills</a>
            <a href="messages.php"><span>✉</span> Messages</a>
        </nav>
        <div class="sidebar-footer">
            <div class="mini-avatar">P</div><div><strong>Administrator</strong><small>Portfolio owner</small></div>
            <form action="logout.php" method="post"><input type="hidden" name="csrf_token" value="<?php echo escape(getCsrfToken()); ?>"><button type="submit" title="Log out" aria-label="Log out">↗</button></form>
        </div>
    </aside>

    <main class="admin-main">
        <header class="topbar"><div><p>OVERVIEW</p><h1>Dashboard</h1></div><a class="view-site" href="../index.php" target="_blank">View portfolio <span>↗</span></a></header>
        <section class="dashboard-content">
            <div class="welcome-row"><div><p class="eyebrow">YOUR WORKSPACE</p><h2>Welcome back, Paul.</h2><p>Here’s what’s happening with your portfolio today.</p></div><div class="date-card"><span>Today</span><strong><?php echo date('M j, Y'); ?></strong></div></div>

            <section class="stats-grid" aria-label="Website overview">
                <article class="stat-card"><div class="stat-top"><span class="stat-icon projects-icon">▦</span><span class="trend">Portfolio</span></div><p>Projects</p><strong><?php echo escape($projects); ?></strong><a href="projects.php">Manage projects <span>→</span></a></article>
                <article class="stat-card"><div class="stat-top"><span class="stat-icon skills-icon">✦</span><span class="trend">Expertise</span></div><p>Skills</p><strong><?php echo escape($skills); ?></strong><a href="skills.php">Manage skills <span>→</span></a></article>
                <article class="stat-card"><div class="stat-top"><span class="stat-icon messages-icon">✉</span><span class="trend">Inbox</span></div><p>Messages</p><strong><?php echo escape($messages); ?></strong><a href="messages.php">Open inbox <span>→</span></a></article>
                <article class="stat-card"><div class="stat-top"><span class="stat-icon experience-icon">⌁</span><span class="trend">Profile</span></div><p>Experience</p><strong><?php echo escape($experiences); ?></strong><span class="stat-muted">Saved entries</span></article>
            </section>

            <section class="dashboard-grid">
                <article class="feature-card"><div><p class="eyebrow">PORTFOLIO HEALTH</p><h2>Keep creating.<br>Keep growing.</h2><p>Refresh your projects and skills to help visitors understand your latest work.</p><a class="button" href="projects.php">Update portfolio <span>→</span></a></div><div class="feature-art" aria-hidden="true"><span></span><i></i><b>P</b></div></article>
                <article class="activity-card"><div class="card-heading"><div><p class="eyebrow">SHORTCUTS</p><h2>Quick actions</h2></div></div><a href="projects.php"><span class="action-icon">＋</span><span><strong>Add project</strong><small>Share your latest work</small></span><b>›</b></a><a href="skills.php"><span class="action-icon">＋</span><span><strong>Update skill</strong><small>Improve your profile</small></span><b>›</b></a><a href="messages.php"><span class="action-icon">✉</span><span><strong>Read messages</strong><small>Check your inbox</small></span><b>›</b></a></article>
            </section>
        </section>
    </main>
</body>
</html>
