<?php
require 'db.php';
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $budget = $_POST['budget'] ?? 0;
    $income = $_POST['income'] ?? 0;

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password,budget,income) VALUES (?,?,?,?,?)");
        $stmt->execute([$name,$email,$password,$budget,$income]);
        header("Location: login.php?signup=success");
        exit;
    } catch (Exception $e) {
        $error = "Email already exists or error occurred.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up - ExpenseTracker</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-body">
  <div class="auth-left">
    <div class="auth-left-content-left">
      <img src="images/extraimg.jpg" alt="Logo" class="logo-left-corner">
      <h1>ExpenseTracker</h1>
      <p>Create your account and start managing expenses smartly.</p>
    </div>
  </div>

  <div class="auth-right">
    <div class="auth-card">
      <h2>Sign Up</h2>
      <?php if($error): ?><div class="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
      <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="number" step="0.01" name="budget" placeholder="Monthly Budget (optional)">
        <input type="number" step="0.01" name="income" placeholder="Monthly Income (optional)">
        <button type="submit">Create Account</button>
      </form>
      <p class="link">Already have an account? <a href="login.php">Login</a></p>
    </div>
  </div>
</body>
</html>
