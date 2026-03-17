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

$deletedUsers = array_filter(loadUsers(), function ($user) {
    return isset($user['deleted']) && $user['deleted'];
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Deleted Accounts - eSerbisyo</title>
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
      </nav>
      <div class="sidebar-logout-wrap">
        <a href="../logout.php" class="btn btn-logout">Logout</a>
      </div>
    </aside>
    <main class="main-content">
      <h1>Deleted Accounts</h1>
      <table class="user-table">
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
          <?php foreach ($deletedUsers as $user): ?>
          <tr>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
              <form action="delete_user.php?username=<?= urlencode($user['username']) ?>" method="post" style="display:inline;">
                <button type="submit" name="confirm_retrieve" class="btn btn-retrieve">Retrieve</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>