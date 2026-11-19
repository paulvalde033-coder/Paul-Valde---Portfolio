<?php

require_once __DIR__ . '/functions.php';

function isLoggedIn()
{
    startSession();

    if (empty($_SESSION['admin_id'])) {
        return false;
    }

    if (!empty($_SESSION['last_activity'])) {
        $timeout = 30 * 60;

        if (time() - $_SESSION['last_activity'] > $timeout) {
            logoutAdmin();
            return false;
        }
    }

    $_SESSION['last_activity'] = time();
    return true;
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function loginAdmin($adminId)
{
    startSession();
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $adminId;
    $_SESSION['last_activity'] = time();
}

function logoutAdmin()
{
    startSession();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $parameters = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $parameters['path'],
            $parameters['domain'],
            $parameters['secure'],
            $parameters['httponly']
        );
    }

    session_destroy();
}
