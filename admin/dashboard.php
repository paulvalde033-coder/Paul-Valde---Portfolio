<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$projects = (int) $db->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$skills = (int) $db->query('SELECT COUNT(*) FROM skills')->fetchColumn();
$messages = (int) $db->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
$experiences = (int) $db->query('SELECT COUNT(*) FROM experience')->fetchColumn();
$token = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#17253d">
    <title>Portfolio Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="dashboard-theme.css">
</head>
<body class="admin-page">
    <aside class="sidebar">
        <a class="admin-brand" href="dashboard.php"><span class="brand-mark">P</span><span><strong>Paul.</strong><small>ADMIN WORKSPACE</small></span></a>
        <p class="sidebar-label">WORKSPACE</p>
        <nav class="admin-nav" aria-label="Admin navigation">
            <a class="active" href="dashboard.php"><span>⌂</span> Overview</a>
            <a href="projects.php"><span>▦</span> Projects</a>
            <a href="skills.php"><span>✦</span> Skills</a>
            <a href="messages.php"><span>✉</span> Messages</a>
        </nav>
        <div class="sidebar-note"><span class="note-icon">✳</span><p><strong>Keep it fresh.</strong><br>Small updates make a great impression.</p></div>
        <div class="sidebar-footer"><div class="mini-avatar">P</div><div class="admin-identity"><strong>Administrator</strong><small>Portfolio owner</small></div><form action="logout.php" method="post"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><button type="submit" title="Log out" aria-label="Log out">↗</button></form></div>
    </aside>
    <main class="admin-main">
        <header class="topbar"><div><p>PORTFOLIO WORKSPACE <span>/</span> OVERVIEW</p><h1>Dashboard</h1></div><a class="view-site" href="../index.php" target="_blank" rel="noopener">View portfolio <span>↗</span></a></header>
        <section class="dashboard-content">
            <div class="welcome-row"><div><p class="eyebrow">YOUR WORKSPACE</p><h2>Welcome back, Paul<span>.</span></h2><p>Here’s what’s happening with your portfolio today.</p></div><div class="date-card"><span class="date-icon">◷</span><div><small>TODAY</small><strong><?php echo date('F j, Y'); ?></strong></div></div></div>
            <section class="stats-grid" aria-label="Website overview">
                <a class="stat-card projects-card" href="projects.php"><div class="stat-top"><span class="stat-icon">▦</span><span class="trend">PORTFOLIO</span></div><p>Projects</p><strong><?php echo number_format($projects); ?></strong><span class="stat-foot">Manage your work <b>→</b></span></a>
                <a class="stat-card skills-card" href="skills.php"><div class="stat-top"><span class="stat-icon">✦</span><span class="trend">EXPERTISE</span></div><p>Skills</p><strong><?php echo number_format($skills); ?></strong><span class="stat-foot">Update your skills <b>→</b></span></a>
                <a class="stat-card messages-card" href="messages.php"><div class="stat-top"><span class="stat-icon">✉</span><span class="trend">INBOX</span></div><p>Messages</p><strong><?php echo number_format($messages); ?></strong><span class="stat-foot">Read conversations <b>→</b></span></a>
                <article class="stat-card experience-card"><div class="stat-top"><span class="stat-icon">⌁</span><span class="trend">PROFILE</span></div><p>Experience</p><strong><?php echo number_format($experiences); ?></strong><span class="stat-foot">Career highlights</span></article>
            </section>
            <section class="dashboard-grid">
                <article class="feature-card"><div class="feature-decoration" aria-hidden="true">✳</div><p class="eyebrow">PORTFOLIO HEALTH</p><h2>Keep creating.<br><em>Keep growing.</em></h2><p>Refresh your projects and skills to help visitors understand your work and what you bring to the table.</p><a href="projects.php">Explore your projects <span>→</span></a></article>
                <article class="activity-card"><div class="card-heading"><div><p class="eyebrow">GET THINGS DONE</p><h2>Quick actions</h2></div><span class="quick-mark">✦</span></div><a href="projects.php#add-project"><span class="action-icon">＋</span><span><strong>Add a project</strong><small>Share something you’ve built</small></span><b>→</b></a><a href="skills.php#add-skill"><span class="action-icon">✦</span><span><strong>Update your skills</strong><small>Keep your expertise current</small></span><b>→</b></a><a href="messages.php"><span class="action-icon">✉</span><span><strong>Open your inbox</strong><small>See who got in touch</small></span><b>→</b></a></article>
            </section>
            <footer class="dashboard-footer"><span>PAUL VALDE <b>•</b> ADMIN WORKSPACE</span><a href="../index.php" target="_blank" rel="noopener">Open public portfolio ↗</a></footer>
        </section>
    </main>
</body>
</html>
