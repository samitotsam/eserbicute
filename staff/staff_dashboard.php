<?php
session_start();

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'staff') {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
$roleLabel = 'Staff';
$welcome = "Welcome, Staff " . htmlspecialchars($user['username'], ENT_QUOTES) . "!";

$moduleCards = [
    ['title' => 'Post Announcements', 'description' => 'Create and publish new community announcements.', 'icon' => '📢', 'link' => 'post_announcements.php'],
    ['title' => 'Process Requests', 'description' => 'Accept or decline document and scheduling requests (court, service vehicle, etc.).', 'icon' => '✅', 'link' => 'process_requests.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eSerbisyo - Staff Dashboard</title>
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
          <li><a href="staff_dashboard.php">Dashboard</a></li>
          <li><a href="post_announcements.php">Post Announcements</a></li>
          <li><a href="process_requests.php">Process Requests</a></li>
        </ul>
        <div class="sidebar-logout-wrap">
          <a href="../logout.php" class="sidebar-logout">Logout</a>
        </div>
        </ul>
        <div class="sidebar-user">
          <span class="user-role">Staff</span><br>
          <span class="user-name"><?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?></span>
        </div>
      </nav>
    </aside>
    <main class="dashboard-main">
      <section class="welcome">
        <h1><?php echo $welcome; ?></h1>
        <p>You are logged in as <strong><?php echo $roleLabel; ?></strong>. Use the cards below to navigate.</p>
      </section>
      <section class="modules">
        <?php foreach ($moduleCards as $card) : ?>
          <article class="module-card">
            <div class="module-icon"><?php echo $card['icon']; ?></div>
            <h3><?php echo $card['title']; ?></h3>
            <p><?php echo $card['description']; ?></p>
            <a href="<?php echo $card['link']; ?>" class="btn btn-sm">Go</a>
          </article>
        <?php endforeach; ?>
      </section>
    </main>
  </div>

  <footer class="footer">eSerbisyo | © Copyright 2025 | Barangay Service and Records Management System </footer>
</body>
</html>