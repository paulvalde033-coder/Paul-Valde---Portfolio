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
                $statement = $db->prepare(
                    'INSERT INTO skills (name, category, proficiency) VALUES (?, ?, ?)'
                );
                $statement->execute([$name, $category, $proficiency]);
                $message = 'Skill added.';
            }
        }

        if ($action === 'edit') {
            $id = (int) ($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $proficiency = (int) ($_POST['proficiency'] ?? 0);

            if ($id < 1 || $name === '' || $proficiency < 0 || $proficiency > 100) {
                $message = 'Enter valid skill details.';
            } else {
                $statement = $db->prepare(
                    'UPDATE skills SET name = ?, category = ?, proficiency = ? WHERE id = ?'
                );
                $statement->execute([$name, $category, $proficiency, $id]);
                $message = 'Skill updated.';
            }
        }

        if ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);

            $statement = $db->prepare('DELETE FROM skills WHERE id = ?');
            $statement->execute([$id]);
            $message = 'Skill deleted.';
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
</head>
<body>
    <h1>Manage Skills</h1>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="projects.php">Projects</a>
        <a href="skills.php">Skills</a>
        <a href="messages.php">Messages</a>
    </nav>

    <?php if ($message !== ''): ?>
        <p><?php echo escape($message); ?></p>
    <?php endif; ?>

    <h2>Add Skill</h2>
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
        <input type="hidden" name="action" value="add">

        <label for="name">Skill name</label>
        <input id="name" name="name" type="text" maxlength="100" required>

        <label for="category">Category</label>
        <input id="category" name="category" type="text" maxlength="100">

        <label for="proficiency">Proficiency</label>
        <input id="proficiency" name="proficiency" type="number" min="0" max="100" required>

        <button type="submit">Add</button>
    </form>

    <h2>Existing Skills</h2>
    <?php foreach ($skills as $skill): ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?php echo escape($skill['id']); ?>">

            <label>Name
                <input name="name" type="text" maxlength="100" value="<?php echo escape($skill['name']); ?>" required>
            </label>

            <label>Category
                <input name="category" type="text" maxlength="100" value="<?php echo escape($skill['category']); ?>">
            </label>

            <label>Proficiency
                <input name="proficiency" type="number" min="0" max="100" value="<?php echo escape($skill['proficiency']); ?>" required>
            </label>

            <button type="submit">Update</button>
        </form>

        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo escape($skill['id']); ?>">
            <button type="submit" onclick="return confirm('Delete this skill?')">Delete</button>
        </form>
    <?php endforeach; ?>
</body>
</html>
