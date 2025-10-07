<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all expenses for the user, ordered by date
$stmt = $pdo->prepare("SELECT * FROM expenses WHERE user_id=? ORDER BY expense_date DESC");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Expenses - ExpenseTracker</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f5f7fb; margin: 0; padding: 0; }
    .navbar { background: #4a90e2; padding: 15px; display: flex; justify-content: space-between; align-items: center; color: #fff; }
    .navbar .logo img { height: 40px; }
    .navbar .nav-links a { margin: 0 15px; text-decoration: none; color: #fff; font-weight: bold; }
    .table-container { max-width: 1000px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
    th { background-color: #4a90e2; color: #fff; }
    tr:hover { background-color: #f1f1f1; }
  </style>
</head>
<body>

<div class="navbar">
  <div class="logo"><img src="images/logo.png" alt="Logo"></div>
  <div class="nav-links">
    <a href="dashboard.php">Dashboard</a>
    <a href="add_expense.php">Add Expense</a>
    <a href="logout.php">Logout</a>
  </div>
</div>

<div class="table-container">
  <h2>📊 Expense History</h2>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Description</th>
        <th>Amount (₹)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($expenses as $e): ?>
        <tr>
          <td><?php echo htmlspecialchars($e['expense_date']); ?></td>
          <td><?php echo htmlspecialchars($e['description']); ?></td>
          <td>₹<?php echo number_format($e['amount'], 2); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h3 style="margin-top:2rem;">Expenses Breakdown</h3>
  <canvas id="expensesChart" height="100"></canvas>
</div>

<script>
const ctx = document.getElementById('expensesChart').getContext('2d');
const chart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: [<?php foreach($expenses as $e){echo "'".htmlspecialchars($e['description'])."',";} ?>],
    datasets: [{
      label: 'Expenses',
      data: [<?php foreach($expenses as $e){echo $e['amount'].",";} ?>],
      backgroundColor: [
        '#4F46E5','#10B981','#F59E0B','#EF4444','#6366F1','#3B82F6','#8B5CF6'
      ]
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>

</body>
</html>
