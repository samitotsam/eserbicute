<?php

require_once __DIR__ . '/bootstrap.php';

function loginUser(string $username, string $password): ?array
{
    $users = loadUsers();

    foreach ($users as $user) {
        if (strcasecmp($user['username'], $username) === 0 && password_verify($password, $user['password'])) {
            return $user;
        }
    }

    return null;
}

function registerUser(array $userData): bool
{
    $users = loadUsers();

    // Check if username already exists
    foreach ($users as $user) {
        if (strcasecmp($user['username'], $userData['username']) === 0) {
            return false; // Username taken
        }
    }

    // Add new user
    $users[] = $userData;
    saveUsers($users);

    return true;
}

function logoutUser(): void
{
    session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}