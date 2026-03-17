<?php
session_start();

if (empty($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];

if ($role !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../user_manager.php';

$username = $_GET['username'] ?? '';

if (declineUser($username)) {
    header('Location: manage_accounts.php?success=User declined and removed.');
    exit;
} else {
    header('Location: manage_accounts.php?error=Failed to decline user.');
    exit;
}
?>