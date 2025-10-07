<?php 
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'] ?? "User";

// Get budget and income
$stmt = $pdo->prepare("SELECT budget, income FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
$budget = $user_data['budget'] ?? 0;
$income = $user_data['income'] ?? 0;

// Total spent this month
$stmt = $pdo->prepare("SELECT SUM(amount) FROM expenses WHERE user_id=? AND MONTH(expense_date)=MONTH(CURRENT_DATE())");
$stmt->execute([$user_id]);
$total_spent = $stmt->fetchColumn() ?? 0;

$remaining = $budget - $total_spent;
$progress = ($budget > 0) ? round(($total_spent / $budget) * 100, 2) : 0;
$savings = $income - $total_spent;

// Top categories
$stmt = $pdo->prepare("
    SELECT c.name AS category, SUM(e.amount) as total 
    FROM expenses e
    LEFT JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ? AND MONTH(e.expense_date) = MONTH(CURRENT_DATE())
    GROUP BY c.name
    ORDER BY total DESC 
    LIMIT 3
");
$stmt->execute([$user_id]);
$top_categories = $stmt->fetchAll();

// Recent expenses (with category)
$stmt = $pdo->prepare("
    SELECT c.name AS category, e.amount, e.expense_date AS date
    FROM expenses e
    LEFT JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ?
    ORDER BY e.expense_date DESC 
    LIMIT 5
");
$stmt->execute([$user_id]);
$recent_expenses = $stmt->fetchAll();

// Transactions count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM expenses WHERE user_id=? AND MONTH(expense_date)=MONTH(CURRENT_DATE())");
$stmt->execute([$user_id]);
$transactions_count = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f5f7fb; font-family: 'Segoe UI', sans-serif; }
    .hero { background: linear-gradient(135deg, #7F00FF, #00C9FF); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; }
    .card { border: none; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 20px; }
    .progress { height: 15px; border-radius: 10px; }
    .profile-menu { float: right; }
    .nav-links .btn { margin: 0 5px; text-decoration: none; color: #fff; background: #4a90e2; font-weight: bold; padding: 8px 15px; border-radius: 8px; transition: 0.3s; }
    .nav-links .btn:hover { background: #357ab7; }
    .nav-links .btn-danger { background: #e74c3c; }
    .nav-links .btn-danger:hover { background: #c0392b; }
  </style>
</head>
<body>
<div class="container mt-4">

  <!-- Hero Section -->
  <div class="hero d-flex justify-content-between align-items-center flex-wrap">
    <div>
      <h2>💰 Welcome, <?= htmlspecialchars($user_name) ?>!</h2>
      <p>Track your spending and manage your budget effectively</p>
      <h4>Monthly Goal: <span class="fw-bold">₹<?= number_format($budget, 2) ?></span></h4>
      <h4>Income: <span class="fw-bold">₹<?= number_format($income, 2) ?></span></h4>
      <p>Progress: <?= $progress ?>%</p>
    </div>
    <div class="profile-menu">
      <div class="navbar">
        <div class="nav-links">
          <a href="dashboard.php" class="btn">🏠 Dashboard</a>
          <a href="list_expenses.php" class="btn">📜 View Expenses</a>
          <a href="add_expense.php" class="btn">➕ Add Expense/Income</a>
          <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Section -->
  <div class="row text-center mb-4">
    <div class="col-md-2"><div class="card"><h6>Total Spent</h6><p class="text-danger fs-4">₹<?= number_format($total_spent, 2) ?></p></div></div>
    <div class="col-md-2"><div class="card"><h6>Remaining</h6><p class="text-success fs-4">₹<?= number_format($remaining, 2) ?></p></div></div>
    <div class="col-md-2"><div class="card"><h6>Monthly Budget</h6><p class="text-primary fs-4">₹<?= number_format($budget, 2) ?></p></div></div>
    <div class="col-md-2"><div class="card"><h6>Income</h6><p class="fs-4">₹<?= number_format($income, 2) ?></p></div></div>
    <div class="col-md-2"><div class="card"><h6>Savings</h6><p class="text-success fs-4">₹<?= number_format($savings, 2) ?></p></div></div>
    <div class="col-md-2"><div class="card"><h6>Transactions</h6><p class="fs-4"><?= $transactions_count ?></p></div></div>
  </div>

  <!-- Budget Usage -->
  <div class="card mb-4">
    <h5>📊 Budget Usage</h5>
    <p>You've used <?= $progress ?>% of your monthly budget</p>
    <div class="progress mb-2">
      <div class="progress-bar bg-info" style="width: <?= $progress ?>%"></div>
    </div>
    <small class="text-muted">₹<?= number_format($total_spent, 2) ?> spent / ₹<?= number_format($budget, 2) ?> budget</small>
  </div>

  <!-- Top Categories -->
  <div class="card mb-4">
    <h5>🏆 Top Categories</h5>
    <?php if (count($top_categories) > 0): ?>
      <ul>
        <?php foreach ($top_categories as $cat): ?>
          <li><?= htmlspecialchars($cat['category']) ?> - ₹<?= number_format($cat['total'], 2) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p class="text-muted">No expenses recorded this month</p>
    <?php endif; ?>
  </div>

  <!-- Recent Expenses -->
  <div class="card">
    <h5>🕒 Recent Expenses</h5>
    <?php if (count($recent_expenses) > 0): ?>
      <table class="table">
        <thead><tr><th>Category</th><th>Amount</th><th>Date</th></tr></thead>
        <tbody>
          <?php foreach ($recent_expenses as $exp): ?>
            <tr>
              <td><?= htmlspecialchars($exp['category']) ?></td>
              <td>₹<?= number_format($exp['amount'], 2) ?></td>
              <td><?= $exp['date'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-muted">No recent expenses</p>
    <?php endif; ?>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
