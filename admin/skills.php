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

        if ($action === 'add') {
            $name = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $proficiency = (int) ($_POST['proficiency'] ?? 0);

            if ($name === '' || $proficiency < 0 || $proficiency > 100) {
                $message = 'Enter a skill name and a proficiency between 0 and 100.';
            } else {
                $statement = $db->prepare('INSERT INTO skills (name, category, proficiency) VALUES (?, ?, ?)');
                $statement->execute([$name, $category, $proficiency]);
                $message = 'Skill added.';
            }
        } elseif ($action === 'edit') {
            $id = (int) ($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $proficiency = (int) ($_POST['proficiency'] ?? 0);

            if ($id < 1 || $name === '' || $proficiency < 0 || $proficiency > 100) {
                $message = 'Enter valid skill details.';
            } else {
                $statement = $db->prepare('UPDATE skills SET name = ?, category = ?, proficiency = ? WHERE id = ?');
                $statement->execute([$name, $category, $proficiency, $id]);
                $message = 'Skill updated.';
            }
        } elseif ($action === 'delete') {
            $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
            if (!$id || $id < 1) {
                $message = 'Invalid skill.';
            } else {
                $statement = $db->prepare('DELETE FROM skills WHERE id = ?');
                $statement->execute([$id]);
                $message = $statement->rowCount() > 0 ? 'Skill deleted.' : 'Skill not found.';
            }
        }
    }
}

$skills = $db->query('SELECT * FROM skills ORDER BY sort_order, id')->fetchAll();
$token = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Skills</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-page">
    <header class="admin-header">
        <a class="admin-brand" href="dashboard.php"><span class="brand-mark">P</span><span>Portfolio<span class="brand-muted"> Admin</span></span></a>
        <nav class="admin-nav" aria-label="Admin navigation"><a href="dashboard.php">Overview</a><a href="projects.php">Projects</a><a class="active" href="skills.php">Skills</a><a href="messages.php">Messages</a></nav>
        <form class="logout-form" action="logout.php" method="post"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><button class="button button-quiet" type="submit">Log out <span aria-hidden="true">↗</span></button></form>
    </header>
    <main class="admin-main">
        <div class="page-heading"><div><p class="eyebrow">CONTENT</p><h1>Skills</h1><p class="page-description">Keep your strengths and expertise current.</p></div><a class="button button-primary" href="#add-skill">＋ Add skill</a></div>
        <?php if ($message !== ''): ?><div class="notice" role="status"><?php echo escape($message); ?></div><?php endif; ?>
        <section class="panel form-panel" id="add-skill"><div class="section-heading"><div><p class="eyebrow">NEW ENTRY</p><h2>Add a skill</h2></div></div>
            <form class="admin-form" method="post"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><input type="hidden" name="action" value="add">
                <div class="form-grid form-grid-three"><label>Skill name<input name="name" type="text" maxlength="100" placeholder="e.g. Web design" required></label><label>Category<input name="category" type="text" maxlength="100" placeholder="Development, design…"></label><label>Proficiency (%)<input name="proficiency" type="number" min="0" max="100" placeholder="0–100" required></label></div>
                <div class="form-actions"><button class="button button-primary" type="submit">Add skill <span aria-hidden="true">→</span></button></div>
            </form>
        </section>
        <section class="content-section"><div class="section-heading"><div><p class="eyebrow">YOUR EXPERTISE</p><h2>Existing skills <span class="count-pill"><?php echo count($skills); ?></span></h2></div></div>
            <?php if (!$skills): ?><div class="empty-state"><span aria-hidden="true">✳</span><h3>No skills added yet</h3><p>Add your skills to show visitors what you do best.</p></div><?php else: ?>
                <div class="skill-list">
                    <?php foreach ($skills as $skill): ?>
                        <article class="record-card skill-card">
                            <div class="skill-summary"><div><span class="category-pill"><?php echo escape($skill['category'] ?: 'General'); ?></span><h3><?php echo escape($skill['name']); ?></h3></div><div class="proficiency-display"><strong><?php echo (int) $skill['proficiency']; ?>%</strong><span>proficiency</span></div></div>
                            <div class="progress-track" aria-label="<?php echo (int) $skill['proficiency']; ?> percent proficiency"><span style="width: <?php echo (int) $skill['proficiency']; ?>%"></span></div>
                            <form class="admin-form edit-form" method="post"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><input type="hidden" name="action" value="edit"><input type="hidden" name="id" value="<?php echo (int) $skill['id']; ?>">
                                <div class="form-grid form-grid-three"><label>Skill name<input name="name" type="text" maxlength="100" value="<?php echo escape($skill['name']); ?>" required></label><label>Category<input name="category" type="text" maxlength="100" value="<?php echo escape($skill['category']); ?>"></label><label>Proficiency (%)<input name="proficiency" type="number" min="0" max="100" value="<?php echo (int) $skill['proficiency']; ?>" required></label></div>
                                <div class="form-actions"><button class="button button-primary" type="submit">Save changes</button></div>
                            </form>
                            <form class="delete-form" method="post" onsubmit="return confirm('Delete this skill? This cannot be undone.');"><input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo (int) $skill['id']; ?>"><button class="button button-danger" type="submit">Delete skill</button></form>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
