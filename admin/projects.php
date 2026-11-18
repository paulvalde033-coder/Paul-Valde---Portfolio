<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $message = 'Invalid form request.';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'add' || $action === 'edit') {
            $id = (int) ($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $imagePath = trim($_POST['image_path'] ?? '');
            $projectUrl = trim($_POST['project_url'] ?? '');

            $valid = $title !== '' && strlen($title) <= 180
                && $description !== ''
                && strlen($category) <= 80
                && strlen($imagePath) <= 255
                && ($projectUrl === '' || isSafeExternalUrl($projectUrl));

            if ($action === 'edit') {
                $valid = $valid && $id > 0;
            }

            if (!$valid) {
                $message = 'Enter valid project details. Project links must be valid HTTP or HTTPS URLs.';
            } elseif ($action === 'add') {
                $statement = $db->prepare('INSERT INTO projects (title, description, category, image_path, project_url) VALUES (?, ?, ?, ?, ?)');
                $statement->execute([$title, $description, $category, $imagePath, $projectUrl]);
                $message = 'Project added.';
            } else {
                $statement = $db->prepare('UPDATE projects SET title = ?, description = ?, category = ?, image_path = ?, project_url = ? WHERE id = ?');
                $statement->execute([$title, $description, $category, $imagePath, $projectUrl, $id]);
                $message = 'Project updated.';
            }
        } elseif ($action === 'delete') {
            $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
            if (!$id || $id < 1) {
                $message = 'Invalid project.';
            } else {
                $statement = $db->prepare('DELETE FROM projects WHERE id = ?');
                $statement->execute([$id]);
                $message = $statement->rowCount() > 0 ? 'Project deleted.' : 'Project not found.';
            }
        }
    }
}

$projects = $db->query('SELECT * FROM projects ORDER BY sort_order, id')->fetchAll();
$token = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Projects</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="content-pages.css">
</head>
<body class="admin-page">
    <header class="admin-header">
        <a class="admin-brand" href="dashboard.php"><span class="brand-mark">P</span><span><strong>Paul.</strong><small>Admin Panel</small></span></a>
        <nav class="admin-nav" aria-label="Admin navigation">
            <a href="dashboard.php"><span>⌂</span> Overview</a>
            <a class="active" href="projects.php"><span>▦</span> Projects</a>
            <a href="skills.php"><span>✦</span> Skills</a>
            <a href="messages.php"><span>✉</span> Messages</a>
        </nav>
        <form class="logout-form" action="logout.php" method="post"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><button class="button button-quiet" type="submit">Log out <span aria-hidden="true">↗</span></button></form>
    </header>
    <main class="admin-main content-main">
        <header class="topbar"><div><p>PORTFOLIO CONTENT</p><h1>Projects</h1></div><a class="view-site" href="../index.php" target="_blank" rel="noopener">View portfolio <span>↗</span></a></header>
        <div class="content-wrap">
            <div class="page-heading"><div><p class="eyebrow">YOUR WORK</p><h2>Showcase your projects</h2><p>Add and maintain the work you’re proud to share.</p></div><a class="primary-button" href="#add-project">＋ Add project</a></div>
            <?php if ($message !== ''): ?><div class="notice" role="status"><?php echo escape($message); ?></div><?php endif; ?>
            <section class="panel" id="add-project"><p class="eyebrow">NEW ENTRY</p><h2>Add a project</h2>
                <form class="admin-form" method="post"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><input type="hidden" name="action" value="add">
                    <div class="form-grid"><label>Project title<input name="title" type="text" maxlength="180" placeholder="e.g. Personal portfolio" required></label><label>Category<input name="category" type="text" maxlength="80" value="web" placeholder="Web, design, photography"></label></div>
                    <label>Description<textarea name="description" rows="3" required></textarea></label>
                    <div class="form-grid"><label>Image path<input name="image_path" type="text" maxlength="255" placeholder="images/project.png"></label><label>Project URL<input name="project_url" type="url" maxlength="255" placeholder="https://example.com"></label></div>
                    <div class="form-actions"><button class="primary-button" type="submit">Add project <span>→</span></button></div>
                </form>
            </section>
            <section><div class="section-title"><div><p class="eyebrow">PORTFOLIO LIBRARY</p><h2>Existing projects <span class="count-pill"><?php echo count($projects); ?></span></h2></div></div>
                <?php if (!$projects): ?><div class="empty-state"><span aria-hidden="true">▦</span><h2>No projects yet</h2><p>Add your first project using the form above.</p></div><?php else: ?>
                    <div class="record-grid">
                    <?php foreach ($projects as $project): ?>
                        <article class="record-card"><div class="record-title"><div><span class="category-pill"><?php echo escape($project['category'] ?: 'Uncategorized'); ?></span><h3><?php echo escape($project['title']); ?></h3><p>Project #<?php echo (int) $project['id']; ?></p></div><span class="record-glyph" aria-hidden="true">▦</span></div>
                            <form class="admin-form edit-form" method="post"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><input type="hidden" name="action" value="edit"><input type="hidden" name="id" value="<?php echo (int) $project['id']; ?>">
                                <div class="form-grid"><label>Title<input name="title" type="text" maxlength="180" value="<?php echo escape($project['title']); ?>" required></label><label>Category<input name="category" type="text" maxlength="80" value="<?php echo escape($project['category']); ?>"></label></div>
                                <label>Description<textarea name="description" rows="3" required><?php echo escape($project['description']); ?></textarea></label>
                                <div class="form-grid"><label>Image path<input name="image_path" type="text" maxlength="255" value="<?php echo escape($project['image_path']); ?>"></label><label>Project URL<input name="project_url" type="url" maxlength="255" value="<?php echo escape($project['project_url']); ?>"></label></div>
                                <div class="form-actions"><button class="primary-button" type="submit">Save changes</button></div>
                            </form>
                            <form class="delete-form" method="post" onsubmit="return confirm('Delete this project? This cannot be undone.');"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo (int) $project['id']; ?>"><button class="danger-button" type="submit">Delete project</button></form>
                        </article>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</body>
</html>
