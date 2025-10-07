<?php
require 'db.php';
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid login credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - ExpenseTracker</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-body">
  <div class="auth-left">
    <div class="auth-left-content-left">
      <img src="images/dashboard-bg.jpg" alt="Logo" class="logo-left-corner">
      <h1>ExpenseTracker</h1>
      <p>Manage expenses simply and effectively</p>
    </div>
  </div>

  <div class="auth-right">
    <div class="auth-card">
      <h2>Login</h2>
      <?php if($error): ?><div class="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
      <form method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Sign In</button>
      </form>
      <p class="link">Don’t have an account? <a href="signup.php">Sign Up</a></p> 
    </div>
  </div>
</body>
</html>
