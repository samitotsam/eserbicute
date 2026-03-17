<?php

require_once __DIR__ . '/bootstrap.php';

function loginUser(string $username, string $password): ?array
{
    $users = loadUsers();

    foreach ($users as $user) {
        if (strcasecmp($user['username'], $username) === 0 && password_verify($password, $user['password'])) {
            if (isset($user['pending']) && $user['pending']) {
                return null; // Pending users cannot log in
            }
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

function updateUser(string $username, array $updatedData): bool
{
    $users = loadUsers();
    foreach ($users as &$user) {
        if (strcasecmp($user['username'], $username) === 0) {
            $user = array_merge($user, $updatedData);
            saveUsers($users);
            return true;
        }
    }
    return false;
}

function deleteUser(string $username): bool
{
    $users = loadUsers();
    foreach ($users as $key => $user) {
        if (strcasecmp($user['username'], $username) === 0) {
            $users[$key]['deleted'] = true; // Mark as deleted
            saveUsers($users);
            return true;
        }
    }
    return false;
}

function acceptUser(string $username): bool
{
    return updateUser($username, ['pending' => false]);
}

function declineUser(string $username): bool
{
    return deleteUser($username); // Or mark as declined, but for simplicity, delete
}

function getUserByUsername(string $username): ?array
{
    $users = loadUsers();
    foreach ($users as $user) {
        if (strcasecmp($user['username'], $username) === 0) {
            return $user;
        }
    }
    return null;
}

function retrieveUser(string $username): bool
{
    $users = loadUsers();
    foreach ($users as $key => $user) {
        if (strcasecmp($user['username'], $username) === 0 && isset($user['deleted']) && $user['deleted']) {
            $users[$key]['deleted'] = false; // Mark as active
            saveUsers($users);
            return true;
        }
    }
    return false;
}