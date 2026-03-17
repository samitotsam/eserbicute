<?php
session_start();

if (empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];

if ($role === 'admin') {
    header('Location: admin/dashboard.php');
    exit;
} elseif ($role === 'staff') {
    header('Location: staff/staff_dashboard.php');
    exit;
} elseif ($role === 'resident') {
    header('Location: resident/resident_dashboard.php');
    exit;
} else {
    // Fallback
    header('Location: index.php');
    exit;
}
