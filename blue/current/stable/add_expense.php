<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php"); exit;
}

$error = '';
$success = '';

// Handle Add Expense
// Handle Add Expense
if (isset($_POST['add_expense'])) {
    $desc = $_POST['description'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['date']; // use variable expense_date

    // Corrected query
    $stmt = $pdo->prepare("INSERT INTO expenses (user_id, description, amount, expense_date) VALUES (?,?,?,?)");
    $stmt->execute([$_SESSION['user_id'], $desc, $amount, $expense_date]);

    $total = $pdo->query("SELECT SUM(amount) FROM expenses WHERE user_id=".$_SESSION['user_id'])->fetchColumn();
    $budget = $pdo->query("SELECT budget FROM users WHERE id=".$_SESSION['user_id'])->fetchColumn();

    if ($budget > 0 && $total > $budget) {
        $success = "Expense added ✅ but ⚠️ You have exceeded your budget!";
    } else {
        $success = "Expense added successfully ✅";
    }
}

// Handle Set Budget
if (isset($_POST['set_budget'])) {
    $new_budget = $_POST['budget'];
    $stmt = $pdo->prepare("UPDATE users SET budget=? WHERE id=?");
    $stmt->execute([$new_budget, $_SESSION['user_id']]);
    $success = "Your monthly budget has been updated to ₹" . $new_budget;
}

// Handle Set Income
if (isset($_POST['set_income'])) {
    $new_income = $_POST['income'];
    $stmt = $pdo->prepare("UPDATE users SET income=? WHERE id=?");
    $stmt->execute([$new_income, $_SESSION['user_id']]);
    $success = "Your monthly income has been updated to ₹" . $new_income;
}

// Fetch current budget and income
$current_budget = $pdo->query("SELECT budget FROM users WHERE id=".$_SESSION['user_id'])->fetchColumn();
$current_income = $pdo->query("SELECT income FROM users WHERE id=".$_SESSION['user_id'])->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Expense - ExpenseTracker</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
      margin: 0;
      padding: 0;
    }
    .navbar {
      background: #4a90e2;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #fff;
    }
    .navbar .logo img {
      height: 40px;
    }
    .navbar .nav-links a {
      margin: 0 15px;
      text-decoration: none;
      color: #fff;
      font-weight: bold;
    }
    .form-wrapper {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 30px;
      margin: 40px auto;
      max-width: 1200px;
      flex-wrap: wrap;
    }
    .form-card {
      background: #fff;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
      width: 300px;
      flex: 1;
    }
    .form-card h2 {
      text-align: center;
      color: #4a90e2;
    }
    input, button {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    button {
      background: #4a90e2;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #357ab7;
    }
    .alert {
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-weight: bold;
    }
    .alert.success { background: #d4edda; color: #155724; }
    .alert.danger { background: #f8d7da; color: #721c24; }
    .budget-display {
      background: #e9f5ff;
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 15px;
      color: #333;
    }
  </style>
</head>
<body>
<div class="navbar">
  <div class="logo"><img src="images/logo.png" alt="Logo"></div>
  <div class="nav-links">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="list_expenses.php">📑 View Expenses</a>
    <a href="logout.php">🚪 Logout</a>
  </div>
</div>

<div class="form-wrapper">
  <!-- Add Expense Form -->
  <div class="form-card">
    <h2>💸 Add New Expense</h2>
    <?php if($error): ?><div class="alert danger"><?php echo $error; ?></div><?php endif; ?>
    <?php if($success): ?><div class="alert success"><?php echo $success; ?></div><?php endif; ?>
    
    <form method="post">
      <input type="text" name="description" placeholder="📝 Expense Description" required>
      <input type="number" step="0.01" name="amount" placeholder="💰 Amount in ₹" required>
      <input type="date" name="date" required>
      <button type="submit" name="add_expense">+ Add Expense</button>
    </form>
  </div>

  <!-- Set Budget Form -->
  <div class="form-card">
    <h2>🎯 Set Monthly Budget</h2>
    <div class="budget-display">
      Current Budget: <strong>₹<?php echo $current_budget ?: 0; ?></strong>
    </div>
    <form method="post">
      <input type="number" step="0.01" name="budget" placeholder="Enter New Budget in ₹" required>
      <button type="submit" name="set_budget">💾 Save Budget</button>
    </form>
  </div>

  <!-- Set Income Form -->
  <div class="form-card">
    <h2>💵 Add Monthly Income</h2>
    <div class="budget-display">
      Current Income: <strong>₹<?php echo $current_income ?: 0; ?></strong>
    </div>
    <form method="post">
      <input type="number" step="0.01" name="income" placeholder="Enter Income in ₹" required>
      <button type="submit" name="set_income">💾 Save Income</button>
    </form>
  </div>
</div>
</body>
</html>
