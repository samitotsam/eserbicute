<?php
session_start();

if (empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];

if ($role !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../user_manager.php';

$errors = [];
$success = '';

if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
if (isset($_GET['error'])) {
    $errors[] = $_GET['error'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = trim($_POST['new_username'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $newRole = $_POST['new_role'] ?? '';
    $newFirstName = trim($_POST['new_first_name'] ?? '');
    $newLastName = trim($_POST['new_last_name'] ?? '');

    if ($newUsername === '' || $newPassword === '' || $newRole === '' || $newFirstName === '' || $newLastName === '') {
        $errors[] = 'All fields are required.';
    } elseif (!filter_var($newUsername, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif (!in_array($newRole, ['admin', 'staff', 'resident'])) {
        $errors[] = 'Invalid role.';
    } else {
        $newUserData = [
            'username' => $newUsername,
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'role' => $newRole,
            'first_name' => $newFirstName,
            'middle_name' => '',
            'last_name' => $newLastName,
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
            'email' => $newUsername,
        ];

        if (registerUser($newUserData)) {
            $success = 'User added successfully.';
        } else {
            $errors[] = 'Username already exists.';
        }
    }
}

// Load users
$users = loadUsers();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Users - eSerbisyo</title>
  <link rel="stylesheet" href="../styles.css" />
</head>
<body>
  <div class="dashboard-layout">
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="logo">eSerbisyo</div>
        <div class="tagline">Barangay Service and Records Management System</div>
      </div>
      <nav class="sidebar-nav">
        <ul>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="manage_accounts.php">Manage Accounts</a></li>
          <li><a href="create_user.php">Create New Account</a></li>
          <li><a href="post_announcements.php">Post Announcements</a></li>
          <li><a href="process_requests.php">Process Requests</a></li>
        </ul>
        <div class="sidebar-logout-wrap">
          <a href="../logout.php" class="sidebar-logout">Logout</a>
        </div>
      </nav>
    </aside>
    <main class="dashboard-main">
    <section class="welcome">
      <h1 style="color: #000000; text-align: center; margin-bottom: 0.5em;">Manage Users</h1>

    <?php if (!empty($errors)) : ?>
      <div class="alert alert-error">
        <?php foreach ($errors as $error) : ?>
          <div><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($success) : ?>
      <div class="alert alert-success">
        <?php echo htmlspecialchars($success, ENT_QUOTES); ?>
      </div>
    <?php endif; ?>

    <section class="users-list">
      <h2>All Users</h2>
      <table class="users-table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <?php if (!isset($user['deleted']) || !$user['deleted']): ?> <!-- Exclude deleted accounts -->
            <tr>
              <td><?= htmlspecialchars($user['username']) ?></td>
              <td><?= htmlspecialchars($user['role']) ?></td>
              <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td>
                <a href="view_user.php?username=<?= urlencode($user['username']) ?>" class="btn btn-view">View</a>
              </td>
            </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <section class="pending-users-list" style="margin-top:2.5em;">
      <h2>Pending Accounts</h2>
      <table class="users-table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u) : ?>
            <?php if (isset($u['pending']) && $u['pending']) : ?>
            <tr>
              <td><?= htmlspecialchars($u['username']) ?></td>
              <td><?= htmlspecialchars($u['role']) ?></td>
              <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td>
                <a href="accept_user.php?username=<?= urlencode($u['username']) ?>" class="btn btn-sm" style="margin-right: 0.5em;">Accept</a>
                <a href="decline_user.php?username=<?= urlencode($u['username']) ?>" class="btn btn-sm btn-danger">Decline</a>
              </td>
            </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Move View Deleted Accounts button to the bottom -->
    <div class="deleted-accounts-button">
      <a href="deleted_accounts.php" class="btn btn-retrieve">View Deleted Accounts</a>
    </div>
  </main>
  </div>
</body>
</html>