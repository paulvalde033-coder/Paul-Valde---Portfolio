<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$personal = $db->query('SELECT * FROM personal_information WHERE id = 1')->fetch();
$skills = $db->query('SELECT name, proficiency FROM skills ORDER BY sort_order, id')->fetchAll();
$projects = $db->query('SELECT * FROM projects ORDER BY sort_order, id')->fetchAll();
$education = $db->query('SELECT * FROM education ORDER BY sort_order, id')->fetchAll();
$experience = $db->query('SELECT * FROM experience ORDER BY sort_order, id')->fetchAll();

$fullName = $personal['full_name'] ?? 'Paul Valde';
$token = getCsrfToken();
$messageStatus = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo escape($fullName); ?> | Portfolio</title>
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
                <h1>Hi, I'm <?php echo escape($fullName); ?></h1>
                <p class="lead">
                    <?php echo escape($personal['introduction'] ?? 'Information Technology student and beginner web developer.'); ?>
                </p>
            </div>
            <?php if (!empty($personal['profile_image'])): ?>
                <img class="avatar" src="<?php echo escape($personal['profile_image']); ?>" alt="Portrait of <?php echo escape($fullName); ?>">
            <?php endif; ?>
        </div>
    </section>

    <section id="about">
        <div class="wrap">
            <h2>About me</h2>
            <p class="lead"><?php echo escape($personal['biography'] ?? ''); ?></p>
            <p><strong>Location:</strong> <?php echo escape($personal['location'] ?? ''); ?></p>
        </div>
    </section>

    <section id="skills">
        <div class="wrap">
            <h2>Skills</h2>
            <div class="two">
                <?php foreach ($skills as $skill): ?>
                    <div class="skill">
                        <div class="top">
                            <span><?php echo escape($skill['name']); ?></span>
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
                    <article class="card project" data-type="<?php echo escape($project['category']); ?>">
                        <?php if (!empty($project['image_path'])): ?>
                            <img src="<?php echo escape($project['image_path']); ?>" alt="<?php echo escape($project['title']); ?>">
                        <?php endif; ?>
                        <div class="body">
                            <h3><?php echo escape($project['title']); ?></h3>
                            <p><?php echo escape($project['description']); ?></p>
                            <?php if (isSafeExternalUrl($project['project_url'])): ?>
                                <a class="btn" href="<?php echo escape($project['project_url']); ?>" target="_blank" rel="noopener noreferrer">View project</a>
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
                    <h3><?php echo escape($item['qualification']); ?></h3>
                    <p><?php echo escape($item['institution']); ?></p>
                    <p><?php echo escape($item['description']); ?></p>
                </article>
            <?php endforeach; ?>

            <?php foreach ($experience as $item): ?>
                <article class="card">
                    <h3><?php echo escape($item['position']); ?></h3>
                    <p><?php echo escape($item['organization']); ?></p>
                    <p><?php echo escape($item['description']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="contact">
        <div class="wrap">
            <h2>Contact me</h2>
            <p><?php echo escape($personal['email'] ?? ''); ?></p>

            <?php if ($messageStatus === 'sent'): ?>
                <p role="status">Thanks! Your message has been sent.</p>
            <?php elseif ($messageStatus === 'invalid'): ?>
                <p role="alert">Please check your details and try again.</p>
            <?php endif; ?>

            <form id="contact-form" class="card" action="contact.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo escape($token); ?>">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" minlength="2" maxlength="120" autocomplete="name" required>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" maxlength="190" autocomplete="email" required>
                <label for="subject">Subject</label>
                <input id="subject" name="subject" type="text" minlength="3" maxlength="180" required>
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" minlength="10" maxlength="5000" required></textarea>
                <button class="btn" type="submit">Send message</button>
            </form>
        </div>
    </section>
</main>

<footer>
    <div class="wrap">© 2026 <?php echo escape($fullName); ?>. PERSONAL PORTFOLIO.</div>
</footer>

<script src="script.js"></script>
</body>
</html>
