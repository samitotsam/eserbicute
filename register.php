<?php
session_start();
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/user_manager.php';

seedDefaultAdmin();

$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role = 'resident';

    $firstName = trim($_POST['first_name'] ?? '');
    $middleName = trim($_POST['middle_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $suffix = trim($_POST['suffix'] ?? '');

    $civilStatus = $_POST['civil_status'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $citizenship = trim($_POST['citizenship'] ?? '');
    $birthDate = trim($_POST['birth_date'] ?? '');

    $houseAddress = trim($_POST['house_address'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $barangay = trim($_POST['barangay'] ?? '');

    $contactNumber = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $acceptedTerms = isset($_POST['terms']);
    $acceptedPrivacy = isset($_POST['privacy']);

    if ($password === '' || $confirm === '' || $firstName === '' || $lastName === '' || $civilStatus === '' || $sex === '' || $citizenship === '' || $birthDate === '' || $houseAddress === '' || $province === '' || $city === '' || $barangay === '' || $contactNumber === '' || $email === '') {
        $errors[] = 'All fields are required.';
    } elseif (!$acceptedTerms) {
        $errors[] = 'You must agree to the Terms & Conditions.';
    } elseif (!$acceptedPrivacy) {
        $errors[] = 'You must accept the Data Privacy Policy.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    } elseif (!preg_match('/^\d{10,15}$/', $contactNumber)) {
        $errors[] = 'Please enter a valid contact number (10-15 digits).';
    } elseif (!in_array($civilStatus, ['single', 'married', 'divorced', 'widowed'], true)) {
        $errors[] = 'Please select a valid civil status.';
    } elseif (!in_array($sex, ['male', 'female', 'other'], true)) {
        $errors[] = 'Please select a valid sex.';
    } else {
        $userData = [
            'username' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'suffix' => $suffix,
            'civil_status' => $civilStatus,
            'sex' => $sex,
            'citizenship' => $citizenship,
            'birth_date' => $birthDate,
            'house_address' => $houseAddress,
            'province' => $province,
            'city' => $city,
            'barangay' => $barangay,
            'contact_number' => $contactNumber,
            'email' => $email,
            'accepted_terms' => $acceptedTerms,
            'accepted_privacy' => $acceptedPrivacy,
            'pending' => true, // Residents need admin approval
        ];

        if (registerUser($userData)) {
            $_SESSION['user'] = [
                'username' => $email,
                'role' => $role,
            ];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'An account with that email already exists.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eSerbisyo - Register</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>


    <main class="box">
      <h2>Create account</h2>

      <?php if (!empty($errors)) : ?>
        <div class="alert">
          <?php foreach ($errors as $error) : ?>
            <div><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="form-header">
          <h3>USER INFORMATION</h3>
        </div>

        <div class="form-grid">
          <label>
            First Name *
            <input type="text" name="first_name" value="<?php echo isset($firstName) ? htmlspecialchars($firstName, ENT_QUOTES) : ''; ?>" placeholder="Juan" required />
          </label>

          <label>
            Middle Name
            <input type="text" name="middle_name" value="<?php echo isset($middleName) ? htmlspecialchars($middleName, ENT_QUOTES) : ''; ?>" placeholder="Dela" />
          </label>

          <label>
            Last Name *
            <input type="text" name="last_name" value="<?php echo isset($lastName) ? htmlspecialchars($lastName, ENT_QUOTES) : ''; ?>" placeholder="Cruz" required />
          </label>

          <label>
            Suffix
            <input type="text" name="suffix" value="<?php echo isset($suffix) ? htmlspecialchars($suffix, ENT_QUOTES) : ''; ?>" placeholder="Jr., Sr., III" />
          </label>

          <label>
            Sex *
            <select name="sex" required>
              <option value="" <?php echo (!isset($sex) || $sex === '') ? 'selected' : ''; ?>>Select</option>
              <option value="male" <?php echo (isset($sex) && $sex === 'male') ? 'selected' : ''; ?>>Male</option>
              <option value="female" <?php echo (isset($sex) && $sex === 'female') ? 'selected' : ''; ?>>Female</option>
              <option value="other" <?php echo (isset($sex) && $sex === 'other') ? 'selected' : ''; ?>>Other</option>
            </select>
          </label>

          <label>
            Civil Status *
            <select name="civil_status" required>
              <option value="" <?php echo (!isset($civilStatus) || $civilStatus === '') ? 'selected' : ''; ?>>Select</option>
              <option value="single" <?php echo (isset($civilStatus) && $civilStatus === 'single') ? 'selected' : ''; ?>>Single</option>
              <option value="married" <?php echo (isset($civilStatus) && $civilStatus === 'married') ? 'selected' : ''; ?>>Married</option>
              <option value="divorced" <?php echo (isset($civilStatus) && $civilStatus === 'divorced') ? 'selected' : ''; ?>>Divorced</option>
              <option value="widowed" <?php echo (isset($civilStatus) && $civilStatus === 'widowed') ? 'selected' : ''; ?>>Widowed</option>
            </select>
          </label>

          <label>
            Citizenship *
            <input type="text" name="citizenship" value="<?php echo isset($citizenship) ? htmlspecialchars($citizenship, ENT_QUOTES) : ''; ?>" placeholder="Filipino" required />
          </label>

          <label>
            Birth Date *
            <input type="date" name="birth_date" value="<?php echo isset($birthDate) ? htmlspecialchars($birthDate, ENT_QUOTES) : ''; ?>" required />
          </label>
        </div>

        <div class="form-header">
          <h3>CONTACT INFORMATION</h3>
        </div>

        <div class="form-grid">
          <label class="full-width">
            House/Unit/Building/Village/Street *
            <input type="text" name="house_address" value="<?php echo isset($houseAddress) ? htmlspecialchars($houseAddress, ENT_QUOTES) : ''; ?>" placeholder="123 Main St" required />
          </label>

          <label>
            Province *
            <input type="text" name="province" value="<?php echo isset($province) ? htmlspecialchars($province, ENT_QUOTES) : ''; ?>" placeholder="Province" required />
          </label>

          <label>
            City *
            <input type="text" name="city" value="<?php echo isset($city) ? htmlspecialchars($city, ENT_QUOTES) : ''; ?>" placeholder="City" required />
          </label>

          <label>
            Barangay *
            <input type="text" name="barangay" value="<?php echo isset($barangay) ? htmlspecialchars($barangay, ENT_QUOTES) : ''; ?>" placeholder="Barangay" required />
          </label>

          <label>
            Contact Number *
            <input type="tel" name="contact_number" value="<?php echo isset($contactNumber) ? htmlspecialchars($contactNumber, ENT_QUOTES) : ''; ?>" placeholder="09123456789" required />
          </label>

          <label>
            Email *
            <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email, ENT_QUOTES) : ''; ?>" placeholder="john@example.com" required />
          </label>

          <label>
            Password *
            <div class="password-container">
              <input type="password" name="password" placeholder="********" required />
              <span class="toggle-password" onclick="togglePassword(this)">Show</span>
            </div>
          </label>

          <label>
            Confirm Password *
            <div class="password-container">
              <input type="password" name="confirm_password" placeholder="********" required />
              <span class="toggle-password" onclick="togglePassword(this)">Show</span>
            </div>
          </label>
        </div>

        <div class="checkbox-group">
          <label class="checkbox">
            <input type="checkbox" name="terms" value="1" <?php echo isset($acceptedTerms) && $acceptedTerms ? 'checked' : ''; ?> required />
            <span>I agree to the <a href="terms.php">Terms &amp; Conditions</a>.</span>
          </label>

          <label class="checkbox">
            <input type="checkbox" name="privacy" value="1" <?php echo isset($acceptedPrivacy) && $acceptedPrivacy ? 'checked' : ''; ?> required />
            <span>I accept the <a href="privacy.php">Data Privacy Policy</a>.</span>
          </label>
        </div>

        <button type="submit" class="btn">Proceed</button>
      </form>

      <p class="footer-text">
        Already have an account? <a href="index.php">Login here</a>.
      </p>

      <footer class="footer">eSerbisyo | © Copyright 2025 | Barangay Service and Records Management System </footer>
    </main>
  </div>

  <script>
    function togglePassword(element) {
      const input = element.previousElementSibling;
      if (input.type === 'password') {
        input.type = 'text';
        element.textContent = 'Hide';
      } else {
        input.type = 'password';
        element.textContent = 'Show';
      }
    }
  </script>
</body>
</html>