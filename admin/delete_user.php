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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    if (deleteUser($username)) {
        header('Location: manage_accounts.php?success=User marked as deleted successfully.');
        exit;
    } else {
        $error = 'Failed to mark user as deleted.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_retrieve'])) {
    if (retrieveUser($username)) {
        header('Location: manage_accounts.php?success=User retrieved successfully.');
        exit;
    } else {
        $error = 'Failed to retrieve user.';
    }
}

$deleteUser = getUserByUsername($username);

if (!$deleteUser) {
    header('Location: manage_accounts.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Delete User - eSerbisyo</title>
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
        <h1 style="color: #fff; text-align: center; margin-bottom: 0.5em;">Delete User</h1>
      </section>

      <?php if (isset($error)) : ?>
        <div class="alert alert-error">
          <?php echo htmlspecialchars($error, ENT_QUOTES); ?>
        </div>
      <?php endif; ?>

      <section class="delete-user">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete the user <strong><?php echo htmlspecialchars($deleteUser['username'], ENT_QUOTES); ?></strong>?</p>
        <p>This action cannot be undone.</p>
        <form method="post">
          <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete User</button>
          <a href="manage_accounts.php" class="btn btn-secondary">Cancel</a>
        </form>
      </section>
    </main>
  </div>
</body>
</html>