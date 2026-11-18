<?php

require_once __DIR__ . '/includes/db.php';

$personal = $db->query('SELECT * FROM personal_information WHERE id = 1')->fetch();
$skills = $db->query('SELECT name, proficiency FROM skills ORDER BY sort_order, id')->fetchAll();
$projects = $db->query('SELECT * FROM projects ORDER BY sort_order, id')->fetchAll();
$education = $db->query('SELECT * FROM education ORDER BY sort_order, id')->fetchAll();
$experience = $db->query('SELECT * FROM experience ORDER BY sort_order, id')->fetchAll();

$fullName = $personal['full_name'] ?? 'Paul Valde';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($fullName); ?> | Portfolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="wrap bar">
        <a class="logo" href="#home">Paul.</a>
        <nav id="nav" aria-label="Main navigation">
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#resume">Resume</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section id="home">
        <div class="wrap hero">
            <div>
                <h1>Hi, I'm <?php echo htmlspecialchars($fullName); ?></h1>
                <p class="lead">
                    <?php echo htmlspecialchars($personal['introduction'] ?? 'Information Technology student and beginner web developer.'); ?>
                </p>
            </div>
            <?php if (!empty($personal['profile_image'])): ?>
                <img class="avatar" src="<?php echo htmlspecialchars($personal['profile_image']); ?>" alt="Portrait of <?php echo htmlspecialchars($fullName); ?>">
            <?php endif; ?>
        </div>
    </section>

    <section id="about">
        <div class="wrap">
            <h2>About me</h2>
            <p class="lead"><?php echo htmlspecialchars($personal['biography'] ?? ''); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($personal['location'] ?? ''); ?></p>
        </div>
    </section>

    <section id="skills">
        <div class="wrap">
            <h2>Skills</h2>
            <div class="two">
                <?php foreach ($skills as $skill): ?>
                    <div class="skill">
                        <div class="top">
                            <span><?php echo htmlspecialchars($skill['name']); ?></span>
                            <span><?php echo (int) $skill['proficiency']; ?>%</span>
                        </div>
                        <div class="track">
                            <div class="fill" data-level="<?php echo (int) $skill['proficiency']; ?>"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="projects">
        <div class="wrap">
            <h2>Projects</h2>
            <div class="grid">
                <?php foreach ($projects as $project): ?>
                    <article class="card project" data-type="<?php echo htmlspecialchars($project['category']); ?>">
                        <?php if (!empty($project['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($project['image_path']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        <?php endif; ?>
                        <div class="body">
                            <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p><?php echo htmlspecialchars($project['description']); ?></p>
                            <?php if (!empty($project['project_url'])): ?>
                                <a class="btn" href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank" rel="noopener">View project</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="resume">
        <div class="wrap">
            <h2>Resume</h2>
            <?php foreach ($education as $item): ?>
                <article class="card">
                    <h3><?php echo htmlspecialchars($item['qualification']); ?></h3>
                    <p><?php echo htmlspecialchars($item['institution']); ?></p>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                </article>
            <?php endforeach; ?>

            <?php foreach ($experience as $item): ?>
                <article class="card">
                    <h3><?php echo htmlspecialchars($item['position']); ?></h3>
                    <p><?php echo htmlspecialchars($item['organization']); ?></p>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="contact">
        <div class="wrap">
            <h2>Contact me</h2>
            <p><?php echo htmlspecialchars($personal['email'] ?? ''); ?></p>
        </div>
    </section>
</main>

<footer>
    <div class="wrap">© 2026 <?php echo htmlspecialchars($fullName); ?>. PERSONAL PORTFOLIO.</div>
</footer>

<script src="script.js"></script>
</body>
</html>
