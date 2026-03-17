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
$editUser = getUserByUsername($username);

if (!$editUser) {
    header('Location: manage_accounts.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedData = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'middle_name' => trim($_POST['middle_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'suffix' => trim($_POST['suffix'] ?? ''),
        'civil_status' => $_POST['civil_status'] ?? '',
        'sex' => $_POST['sex'] ?? '',
        'citizenship' => trim($_POST['citizenship'] ?? ''),
        'birth_date' => $_POST['birth_date'] ?? '',
        'house_address' => trim($_POST['house_address'] ?? ''),
        'province' => trim($_POST['province'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'barangay' => trim($_POST['barangay'] ?? ''),
        'contact_number' => trim($_POST['contact_number'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
    ];

    if (updateUser($username, $updatedData)) {
        $success = 'User updated successfully.';
        $editUser = getUserByUsername($username); // Refresh data
    } else {
        $errors[] = 'Failed to update user.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit User - eSerbisyo</title>
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
        <h1 style="color: #fff; text-align: center; margin-bottom: 0.5em;">Edit User</h1>
      </section>

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

      <section class="edit-user">
        <form method="post" novalidate>
          <div class="form-grid">
            <label>
              First Name
              <input type="text" name="first_name" value="<?php echo htmlspecialchars($editUser['first_name'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              Middle Name
              <input type="text" name="middle_name" value="<?php echo htmlspecialchars($editUser['middle_name'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              Last Name
              <input type="text" name="last_name" value="<?php echo htmlspecialchars($editUser['last_name'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              Suffix
              <input type="text" name="suffix" value="<?php echo htmlspecialchars($editUser['suffix'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              Civil Status
              <select name="civil_status">
                <option value="">Select</option>
                <option value="single" <?php echo ($editUser['civil_status'] ?? '') === 'single' ? 'selected' : ''; ?>>Single</option>
                <option value="married" <?php echo ($editUser['civil_status'] ?? '') === 'married' ? 'selected' : ''; ?>>Married</option>
                <option value="widowed" <?php echo ($editUser['civil_status'] ?? '') === 'widowed' ? 'selected' : ''; ?>>Widowed</option>
                <option value="divorced" <?php echo ($editUser['civil_status'] ?? '') === 'divorced' ? 'selected' : ''; ?>>Divorced</option>
              </select>
            </label>
            <label>
              Sex
              <select name="sex">
                <option value="">Select</option>
                <option value="male" <?php echo ($editUser['sex'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($editUser['sex'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
              </select>
            </label>
            <label>
              Citizenship
              <input type="text" name="citizenship" value="<?php echo htmlspecialchars($editUser['citizenship'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              Birth Date
              <input type="date" name="birth_date" value="<?php echo htmlspecialchars($editUser['birth_date'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              House Address
              <input type="text" name="house_address" value="<?php echo htmlspecialchars($editUser['house_address'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              Province
              <input type="text" name="province" value="<?php echo htmlspecialchars($editUser['province'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              City
              <input type="text" name="city" value="<?php echo htmlspecialchars($editUser['city'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              Barangay
              <input type="text" name="barangay" value="<?php echo htmlspecialchars($editUser['barangay'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              Contact Number
              <input type="text" name="contact_number" value="<?php echo htmlspecialchars($editUser['contact_number'] ?? '', ENT_QUOTES); ?>" />
            </label>
            <label>
              Email
              <input type="email" name="email" value="<?php echo htmlspecialchars($editUser['email'] ?? '', ENT_QUOTES); ?>" />
            </label>
          </div>
          <button type="submit" class="btn">Update User</button>
        </form>
      </section>
    </main>
  </div>
</body>
</html>