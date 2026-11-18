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
                $statement = $db->prepare(
                    'INSERT INTO projects (title, description, category, image_path, project_url) VALUES (?, ?, ?, ?, ?)'
                );
                $statement->execute([$title, $description, $category, $imagePath, $projectUrl]);
                $message = 'Project added.';
            } else {
                $statement = $db->prepare(
                    'UPDATE projects SET title = ?, description = ?, category = ?, image_path = ?, project_url = ? WHERE id = ?'
                );
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
</head>
<body>
    <h1>Manage Projects</h1>
    <nav aria-label="Admin navigation">
        <a href="dashboard.php">Dashboard</a>
        <a href="projects.php">Projects</a>
        <a href="skills.php">Skills</a>
        <a href="messages.php">Messages</a>
    </nav>

    <?php if ($message !== ''): ?>
        <p role="status"><?php echo escape($message); ?></p>
    <?php endif; ?>

    <h2>Add Project</h2>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
        <input type="hidden" name="action" value="add">
        <label>Title <input name="title" type="text" maxlength="180" required></label>
        <label>Description <textarea name="description" required></textarea></label>
        <label>Category <input name="category" type="text" maxlength="80" value="web"></label>
        <label>Image path <input name="image_path" type="text" maxlength="255"></label>
        <label>Project URL <input name="project_url" type="url" maxlength="255" placeholder="https://example.com"></label>
        <button type="submit">Add</button>
    </form>

    <h2>Existing Projects</h2>
    <?php foreach ($projects as $project): ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?php echo (int) $project['id']; ?>">
            <label>Title <input name="title" type="text" maxlength="180" value="<?php echo escape($project['title']); ?>" required></label>
            <label>Description <textarea name="description" required><?php echo escape($project['description']); ?></textarea></label>
            <label>Category <input name="category" type="text" maxlength="80" value="<?php echo escape($project['category']); ?>"></label>
            <label>Image path <input name="image_path" type="text" maxlength="255" value="<?php echo escape($project['image_path']); ?>"></label>
            <label>Project URL <input name="project_url" type="url" maxlength="255" value="<?php echo escape($project['project_url']); ?>"></label>
            <button type="submit">Update</button>
        </form>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo (int) $project['id']; ?>">
            <button type="submit" onclick="return confirm('Delete this project?')">Delete</button>
        </form>
    <?php endforeach; ?>
</body>
</html>
