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
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $imagePath = trim($_POST['image_path'] ?? '');
            $projectUrl = trim($_POST['project_url'] ?? '');

            if ($title === '' || $description === '') {
                $message = 'Enter a project title and description.';
            } else {
                $statement = $db->prepare(
                    'INSERT INTO projects (title, description, category, image_path, project_url) VALUES (?, ?, ?, ?, ?)'
                );
                $statement->execute([$title, $description, $category, $imagePath, $projectUrl]);
                $message = 'Project added.';
            }
        }

        if ($action === 'edit') {
            $id = (int) ($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $imagePath = trim($_POST['image_path'] ?? '');
            $projectUrl = trim($_POST['project_url'] ?? '');

            if ($id < 1 || $title === '' || $description === '') {
                $message = 'Enter valid project details.';
            } else {
                $statement = $db->prepare(
                    'UPDATE projects SET title = ?, description = ?, category = ?, image_path = ?, project_url = ? WHERE id = ?'
                );
                $statement->execute([$title, $description, $category, $imagePath, $projectUrl, $id]);
                $message = 'Project updated.';
            }
        }

        if ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);

            $statement = $db->prepare('DELETE FROM projects WHERE id = ?');
            $statement->execute([$id]);
            $message = 'Project deleted.';
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

    <?php if ($message !== ''): ?>
        <p><?php echo escape($message); ?></p>
    <?php endif; ?>

    <h2>Add Project</h2>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
        <input type="hidden" name="action" value="add">

        <label>Title
            <input name="title" type="text" maxlength="180" required>
        </label>

        <label>Description
            <textarea name="description" required></textarea>
        </label>

        <label>Category
            <input name="category" type="text" maxlength="80" value="web">
        </label>

        <label>Image path
            <input name="image_path" type="text" maxlength="255">
        </label>

        <label>Project URL
            <input name="project_url" type="url" maxlength="255">
        </label>

        <button type="submit">Add</button>
    </form>

    <h2>Existing Projects</h2>
    <?php foreach ($projects as $project): ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?php echo escape($project['id']); ?>">

            <label>Title
                <input name="title" type="text" maxlength="180" value="<?php echo escape($project['title']); ?>" required>
            </label>

            <label>Description
                <textarea name="description" required><?php echo escape($project['description']); ?></textarea>
            </label>

            <label>Category
                <input name="category" type="text" maxlength="80" value="<?php echo escape($project['category']); ?>">
            </label>

            <label>Image path
                <input name="image_path" type="text" maxlength="255" value="<?php echo escape($project['image_path']); ?>">
            </label>

            <label>Project URL
                <input name="project_url" type="url" maxlength="255" value="<?php echo escape($project['project_url']); ?>">
            </label>

            <button type="submit">Update</button>
        </form>

        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo escape($project['id']); ?>">
            <button type="submit" onclick="return confirm('Delete this project?')">Delete</button>
        </form>
    <?php endforeach; ?>
</body>
</html>
