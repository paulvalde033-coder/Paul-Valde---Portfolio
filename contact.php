<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php#contact');
    exit;
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    header('Location: index.php?message=invalid#contact');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (
    strlen($name) < 2 || strlen($name) > 120 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190 ||
    strlen($subject) < 3 || strlen($subject) > 180 ||
    strlen($message) < 10
) {
    header('Location: index.php?message=invalid#contact');
    exit;
}

$statement = $db->prepare(
    'INSERT INTO contact_messages (sender_name, sender_email, subject, message) VALUES (?, ?, ?, ?)'
);
$statement->execute([$name, $email, $subject, $message]);

header('Location: index.php?message=sent#contact');
exit;
