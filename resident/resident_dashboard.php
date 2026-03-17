<?php
session_start();

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'resident') {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../user_manager.php';

$userSession = $_SESSION['user'];
$users = loadUsers();
$user = null;
foreach ($users as $u) {
    if (strcasecmp($u['username'], $userSession['username']) === 0) {
        $user = $u;
        break;
    }
}

if (!$user) {
    header('Location: index.php');
    exit;
}

$roleLabel = 'Resident';
$welcome = "Welcome, " . htmlspecialchars($user['first_name'], ENT_QUOTES) . "!";

$moduleCards = [
    ['title' => 'AI-Powered Chatbot', 'description' => 'Get answers to FAQs and assistance.', 'icon' => '🤖', 'link' => 'chatbot.php'],
    ['title' => 'Community Bulletin', 'description' => 'View announcements for ayuda and community updates.', 'icon' => '📢', 'link' => 'bulletin.php'],
    ['title' => 'Feedback & History', 'description' => 'Provide feedback and view your request history.', 'icon' => '📝', 'link' => 'feedback_history.php'],
    ['title' => 'Resident Census', 'description' => 'Access and update resident census information.', 'icon' => '👥', 'link' => 'census.php'],
    ['title' => 'OCR Document Digitization', 'description' => 'Digitize documents using OCR technology.', 'icon' => '📄', 'link' => 'ocr_digitization.php'],
    ['title' => 'Incident & Blotter Reporting', 'description' => 'Report incidents and access blotter records (controlled access).', 'icon' => '🚨', 'link' => 'incident_reporting.php'],
    ['title' => 'BDRRMC Records', 'description' => 'Access Barangay Disaster Risk Reduction Management Committee records.', 'icon' => '🌪️', 'link' => 'bdrmc_records.php'],
    ['title' => 'Document Logs & Audit Trails', 'description' => 'View document logs, audit trails, and generate automated reports (Excel/CSV).', 'icon' => '📊', 'link' => 'document_logs.php'],
    ['title' => 'Notifications & Reminders', 'description' => 'Receive SMS/Email notifications and clearance expiration reminders.', 'icon' => '📱', 'link' => 'notifications.php'],
    ['title' => 'Digital Seals & E-Signatures', 'description' => 'Use digital seals, e-signatures, and QR code verification.', 'icon' => '✍️', 'link' => 'digital_seals.php'],
    ['title' => 'Resource & Facility Reservations', 'description' => 'Reserve resources and facilities.', 'icon' => '🏢', 'link' => 'reservations.php'],
    ['title' => 'Virtual Queue Management', 'description' => 'Manage virtual queues for services.', 'icon' => '⏳', 'link' => 'queue_management.php'],
    ['title' => 'Barangay Digital ID', 'description' => 'Access your proposed Barangay Digital ID for identity verification.', 'icon' => '🆔', 'link' => 'digital_id.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eSerbisyo - Resident Dashboard</title>
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
          <li><a href="resident_dashboard.php">Dashboard</a></li>
          <li><a href="chatbot.php">AI-Powered Chatbot</a></li>
          <li><a href="bulletin.php">Community Bulletin</a></li>
          <li><a href="feedback_history.php">Feedback & History</a></li>
          <li><a href="census.php">Resident Census</a></li>
          <li><a href="ocr_digitization.php">OCR Digitization</a></li>
          <li><a href="incident_reporting.php">Incident Reporting</a></li>
          <li><a href="bdrmc_records.php">BDRRMC Records</a></li>
          <li><a href="document_logs.php">Document Logs</a></li>
          <li><a href="notifications.php">Notifications</a></li>
          <li><a href="digital_seals.php">Digital Seals</a></li>
          <li><a href="reservations.php">Reservations</a></li>
          <li><a href="queue_management.php">Virtual Queue</a></li>
          <li><a href="digital_id.php">Digital ID</a></li>
        </ul>
        <div class="sidebar-logout-wrap">
          <a href="../logout.php" class="sidebar-logout">Logout</a>
        </div>
        </ul>
        <div class="sidebar-user">
          <span class="user-role">Resident</span><br>
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