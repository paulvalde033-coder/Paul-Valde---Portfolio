<?php

function escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function isSafeExternalUrl($url)
{
    if (!is_string($url) || $url === '' || strlen($url) > 255) {
        return false;
    }

    $parts = parse_url($url);
    if ($parts === false || empty($parts['host']) || !isset($parts['scheme'])) {
        return false;
    }

    $scheme = strtolower($parts['scheme']);
    return in_array($scheme, ['http', 'https'], true)
        && !isset($parts['user'])
        && !isset($parts['pass'])
        && filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function startSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function getCsrfToken()
{
    startSession();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token)
{
    startSession();

    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}
