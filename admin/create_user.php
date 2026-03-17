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

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = trim($_POST['new_username'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $newRole = $_POST['new_role'] ?? '';
    $newFirstName = trim($_POST['new_first_name'] ?? '');
    $newLastName = trim($_POST['new_last_name'] ?? '');
    $newMiddleName = trim($_POST['new_middle_name'] ?? '');
    $newSuffix = trim($_POST['new_suffix'] ?? '');
    $newCivilStatus = $_POST['new_civil_status'] ?? '';
    $newSex = $_POST['new_sex'] ?? '';
    $newCitizenship = trim($_POST['new_citizenship'] ?? '');
    $newBirthDate = $_POST['new_birth_date'] ?? '';
    $newHouseAddress = trim($_POST['new_house_address'] ?? '');
    $newProvince = trim($_POST['new_province'] ?? '');
    $newCity = trim($_POST['new_city'] ?? '');
    $newBarangay = trim($_POST['new_barangay'] ?? '');
    $newContactNumber = trim($_POST['new_contact_number'] ?? '');
    $newEmail = trim($_POST['new_email'] ?? '');

    if ($newUsername === '' || $newPassword === '' || $newRole === '' || $newFirstName === '' || $newLastName === '') {
        $errors[] = 'All required fields are required.';
    } elseif (!filter_var($newUsername, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address for username.';
    } elseif (!in_array($newRole, ['admin', 'staff', 'resident'])) {
        $errors[] = 'Invalid role.';
    } else {
        $newUserData = [
            'username' => $newUsername,
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'role' => $newRole,
            'first_name' => $newFirstName,
            'middle_name' => $newMiddleName,
            'last_name' => $newLastName,
            'suffix' => $newSuffix,
            'civil_status' => $newCivilStatus,
            'sex' => $newSex,
            'citizenship' => $newCitizenship,
            'birth_date' => $newBirthDate,
            'house_address' => $newHouseAddress,
            'province' => $newProvince,
            'city' => $newCity,
            'barangay' => $newBarangay,
            'contact_number' => $newContactNumber,
            'email' => $newEmail,
            'pending' => false, // Admin-created accounts are approved by default
        ];

        if (registerUser($newUserData)) {
            $success = 'User created successfully.';
        } else {
            $errors[] = 'Username already exists.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create User - eSerbisyo</title>
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
        <h1 style="color: #fff; text-align: center; margin-bottom: 0.5em;">Create New User</h1>
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

      <section class="add-user">
        <form method="post" novalidate>
          <div class="form-grid">
            <label>
              Email (Username) *
              <input type="email" name="new_username" required />
            </label>
            <label>
              Password *
              <input type="password" name="new_password" required />
            </label>
            <label>
              Role *
              <select name="new_role" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="resident">Resident</option>
              </select>
            </label>
            <label>
              First Name *
              <input type="text" name="new_first_name" required />
            </label>
            <label>
              Middle Name
              <input type="text" name="new_middle_name" />
            </label>
            <label>
              Last Name *
              <input type="text" name="new_last_name" required />
            </label>
            <label>
              Suffix
              <input type="text" name="new_suffix" />
            </label>
            <label>
              Civil Status
              <select name="new_civil_status">
                <option value="">Select</option>
                <option value="single">Single</option>
                <option value="married">Married</option>
                <option value="widowed">Widowed</option>
                <option value="divorced">Divorced</option>
              </select>
            </label>
            <label>
              Sex
              <select name="new_sex">
                <option value="">Select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </label>
            <label>
              Citizenship
              <input type="text" name="new_citizenship" />
            </label>
            <label>
              Birth Date
              <input type="date" name="new_birth_date" />
            </label>
            <label>
              House Address
              <input type="text" name="new_house_address" />
            </label>
            <label>
              Province
              <input type="text" name="new_province" />
            </label>
            <label>
              City
              <input type="text" name="new_city" />
            </label>
            <label>
              Barangay
              <input type="text" name="new_barangay" />
            </label>
            <label>
              Contact Number
              <input type="text" name="new_contact_number" />
            </label>
            <label>
              Email
              <input type="email" name="new_email" />
            </label>
          </div>
          <button type="submit" class="btn">Create User</button>
        </form>
      </section>
    </main>
  </div>
</body>
</html>