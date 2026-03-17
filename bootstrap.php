<?php

function getUsersFilePath(): string
{
    return __DIR__ . '/data/users.json';
}

function ensureDataDirectoryExists(): void
{
    $dataDir = dirname(getUsersFilePath());
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
}

function loadUsers(): array
{
    ensureDataDirectoryExists();

    $usersFile = getUsersFilePath();
    if (!file_exists($usersFile)) {
        return [];
    }

    $content = trim((string)file_get_contents($usersFile));
    if ($content === '') {
        return [];
    }

    $users = json_decode($content, true);
    if (!is_array($users)) {
        return [];
    }

    return $users;
}

function saveUsers(array $users): void
{
    ensureDataDirectoryExists();
    file_put_contents(getUsersFilePath(), json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function seedDefaultAdmin(): void
{
    $users = loadUsers();
    if (!empty($users)) {
        return;
    }

    $users[] = [
        'username' => 'admin@local',
        'password' => password_hash('Password123!', PASSWORD_DEFAULT),
        'role' => 'admin',
        'first_name' => 'Admin',
        'middle_name' => '',
        'last_name' => 'User',
        'suffix' => '',
        'civil_status' => '',
        'sex' => '',
        'citizenship' => '',
        'birth_date' => '',
        'house_address' => '',
        'province' => '',
        'city' => '',
        'barangay' => '',
        'contact_number' => '',
        'email' => 'admin@local',
    ];

    saveUsers($users);
}

// Ensure a default admin exists on first launch.
seedDefaultAdmin();