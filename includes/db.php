<?php

$host = getenv('DB_HOST');
$name = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

if (!$host || !$name || !$user) {
    throw new RuntimeException('Database settings are missing.');
}

try {
    $db = new PDO(
        "mysql:host=$host;dbname=$name;charset=utf8mb4",
        $user,
        $password ?: '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $error) {
    error_log($error->getMessage());
    throw new RuntimeException('The database connection is unavailable.');
}
