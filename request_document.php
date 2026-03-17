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

$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentType = trim($_POST['document_type'] ?? '');
    $purpose = trim($_POST['purpose'] ?? '');

    if ($documentType === '' || $purpose === '') {
        $errors[] = 'All fields are required.';
    } else {
        // Save request
        $requestsFile = __DIR__ . '/data/requests.json';
        $requests = [];
        if (file_exists($requestsFile)) {
            $requests = json_decode(file_get_contents($requestsFile), true) ?? [];
        }

        $requests[] = [
            'id' => uniqid(),
            'username' => $user['username'],
            'document_type' => $documentType,
            'purpose' => $purpose,
            'status' => 'Pending',
            'submitted_at' => date('Y-m-d H:i:s'),
        ];

        if (!is_dir(dirname($requestsFile))) {
            mkdir(dirname($requestsFile), 0755, true);
        }
        file_put_contents($requestsFile, json_encode($requests, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $success = 'Request submitted successfully!';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Request Document - eSerbisyo</title>
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
      <h1>Request Document</h1>
      <p>Submit a new document request.</p>
      <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </section>

    <section class="request-form">
      <?php if (!empty($errors)) : ?>
        <div class="alert">
          <?php foreach ($errors as $error) : ?>
            <div><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($success) : ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES); ?></div>
      <?php endif; ?>

      <form method="post" novalidate>
        <label>
          Document Type *
          <select name="document_type" required>
            <option value="">Select Document</option>
            <option value="Barangay Clearance">Barangay Clearance</option>
            <option value="Certificate of Indigency">Certificate of Indigency</option>
            <option value="Business Permit">Business Permit</option>
            <option value="Cedula">Cedula</option>
          </select>
        </label>

        <label>
          Purpose *
          <textarea name="purpose" placeholder="State the purpose of your request..." required></textarea>
        </label>

        <button type="submit" class="btn">Submit Request</button>
      </form>
    </section>
  </main>

  <footer class="footer">eSerbisyo | © Copyright 2025 | Barangay Service and Records Management System </footer>
</body>
</html>