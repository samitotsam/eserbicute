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
$viewUser = getUserByUsername($username);

if (!$viewUser) {
    header('Location: manage_accounts.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>View User - eSerbisyo</title>
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
        <h1 style="color: #fff; text-align: center; margin-bottom: 0.5em;">View User</h1>
      </section>

      <section class="user-details">
        <h2>User Details</h2>
        <div class="form-grid">
          <div><strong>Username:</strong> <?php echo htmlspecialchars($viewUser['username'], ENT_QUOTES); ?></div>
          <div><strong>Role:</strong> <?php echo htmlspecialchars($viewUser['role'], ENT_QUOTES); ?></div>
          <div><strong>First Name:</strong> <?php echo htmlspecialchars($viewUser['first_name'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Middle Name:</strong> <?php echo htmlspecialchars($viewUser['middle_name'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Last Name:</strong> <?php echo htmlspecialchars($viewUser['last_name'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Suffix:</strong> <?php echo htmlspecialchars($viewUser['suffix'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Civil Status:</strong> <?php echo htmlspecialchars($viewUser['civil_status'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Sex:</strong> <?php echo htmlspecialchars($viewUser['sex'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Citizenship:</strong> <?php echo htmlspecialchars($viewUser['citizenship'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Birth Date:</strong> <?php echo htmlspecialchars($viewUser['birth_date'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>House Address:</strong> <?php echo htmlspecialchars($viewUser['house_address'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Province:</strong> <?php echo htmlspecialchars($viewUser['province'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>City:</strong> <?php echo htmlspecialchars($viewUser['city'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Barangay:</strong> <?php echo htmlspecialchars($viewUser['barangay'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Contact Number:</strong> <?php echo htmlspecialchars($viewUser['contact_number'] ?? '', ENT_QUOTES); ?></div>
          <div><strong>Email:</strong> <?php echo htmlspecialchars($viewUser['email'] ?? '', ENT_QUOTES); ?></div>
        </div>
        <div class="user-actions">
          <a href="edit_user.php?username=<?= urlencode($viewUser['username']) ?>" class="btn btn-edit">Edit</a>
          <form action="delete_user.php?username=<?= urlencode($viewUser['username']) ?>" method="post" style="display:inline;">
            <button type="submit" name="confirm_delete" class="btn btn-delete">Delete</button>
          </form>
          <?php if (isset($viewUser['deleted']) && $viewUser['deleted']): ?>
            <form action="delete_user.php?username=<?= urlencode($viewUser['username']) ?>" method="post" style="display:inline;">
              <button type="submit" name="confirm_retrieve" class="btn btn-retrieve">Retrieve</button>
            </form>
          <?php endif; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>