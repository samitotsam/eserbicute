<?php
session_start();

if (empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];

if ($role !== 'resident' && $role !== 'staff') {
    header('Location: dashboard.php');
    exit;
}

// Load requests from JSON
$requestsFile = __DIR__ . '/data/requests.json';
$requests = [];
if (file_exists($requestsFile)) {
    $requests = json_decode(file_get_contents($requestsFile), true) ?? [];
}

// Filter requests for current user
$userRequests = array_filter($requests, function($req) use ($user) {
    return $req['username'] === $user['username'];
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Requests - eSerbisyo</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header class="topbar">
    <div class="topbar-left">
      <div class="logo">eSerbisyo</div>
      <div class="tagline">Barangay Service and Records Management System </div>
    </div>
    <div class="topbar-right">
      <div class="user-info">
        <span class="user-role"><?php echo ucfirst($role); ?></span>
        <span class="user-name"><?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?></span>
      </div>
      <a class="btn btn-secondary" href="logout.php">Logout</a>
    </div>
  </header>

  <main class="dashboard">
    <section class="welcome">
      <h1>My Requests</h1>
      <p>Track the status of your document requests.</p>
      <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </section>

    <section class="requests-list">
      <?php if (empty($userRequests)) : ?>
        <p>You have no requests yet. <a href="request_document.php">Submit a new request</a>.</p>
      <?php else : ?>
        <table class="requests-table">
          <thead>
            <tr>
              <th>Document Type</th>
              <th>Status</th>
              <th>Submitted On</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($userRequests as $req) : ?>
              <tr>
                <td><?php echo htmlspecialchars($req['document_type'], ENT_QUOTES); ?></td>
                <td><?php echo htmlspecialchars($req['status'], ENT_QUOTES); ?></td>
                <td><?php echo htmlspecialchars($req['submitted_at'], ENT_QUOTES); ?></td>
                <td><button class="btn btn-sm">View Details</button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </main>

  <footer class="footer">eSerbisyo | © Copyright 2025 | Barangay Service and Records Management System </footer>
</body>
</html>