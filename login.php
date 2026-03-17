<?php
session_start();
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/user_manager.php';

seedDefaultAdmin();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter both email and password.';
    } else {
        $user = loginUser($email, $password);
        if ($user) {
            $_SESSION['user'] = [
                'username' => $user['username'],
                'role' => $user['role'],
            ];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eSerbisyo - Login</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="app">
    <div class="hero">
      <div class="hero-brand">
        <h1>eSerbisyo</h1>
      </div>
      <div  aria-hidden="true"></div>
    </div>

    <main class="card">
      <h2>WELCOME</h2>
      <p class="subtitle">Login to your account to continue</p>

      <?php if (!empty($errors)) : ?>
        <div class="alert">
          <?php foreach ($errors as $error) : ?>
            <div><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <label>
          Email
          <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email, ENT_QUOTES) : ''; ?>" placeholder="example@email.com" required />
        </label>

        <label>
          Password
          <div class="password-container">
            <input type="password" name="password" placeholder="********" required />
            <span class="toggle-password" onclick="togglePassword(this)">Show</span>
          </div>
        </label>

        <button type="submit" class="btn">Login</button>
      </form>

      <p class="footer-text">
        If you don't have an account, <a href="register.php">click here</a>.
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