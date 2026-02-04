<?php
include "../config/db.php";

$budgetRow = $conn->query("SELECT total_budget FROM budgets ORDER BY id DESC LIMIT 1")->fetch_assoc();
$expensesRow = $conn->query("SELECT COALESCE(SUM(amount), 0) AS total FROM expenses")->fetch_assoc();

$totalBudget = $budgetRow ? (float) $budgetRow['total_budget'] : 0;
$totalExpenses = $expensesRow ? (float) $expensesRow['total'] : 0;
$remaining = $totalBudget - $totalExpenses;

$result = $conn->query("SELECT * FROM deals");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Deals</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="container">
    <header class="page-header">
      <h1>Deals Within Budget</h1>
      <nav>
        <a href="../dashboard/index.php">Dashboard</a> |
        <a href="qr-saver.php">QR Saver</a> |
        <a href="../auth/login.php">Logout</a>
      </nav>
    </header>

    <div class="card">
      <p class="summary">Remaining: <strong>₱<?= number_format($remaining, 2) ?></strong></p>
    </div>

    <div class="card">
      <h3>Deals</h3>
      <?php while ($deal = $result->fetch_assoc()): ?>
        <div class="deal-item">
          <?= htmlspecialchars($deal['title']) ?> — ₱<?= number_format((float) $deal['price'], 2) ?>
          <?php if ((float) $deal['price'] <= $remaining): ?>
            ✅ Affordable
          <?php else: ?>
            ❌ Over Budget
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
