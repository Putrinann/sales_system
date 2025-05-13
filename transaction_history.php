<?php
include 'db.php';
$orders = $conn->query("SELECT DISTINCT order_id, created_at FROM transactions ORDER BY created_at DESC");

// Get total revenue from all transactions
$revenue_query = $conn->query("SELECT SUM(total_price) as revenue FROM transactions");
$total_revenue = $revenue_query->fetch_assoc()['revenue'] ?? 0;
?>
<?php include 'navbar.php'; ?>


<!DOCTYPE html>
<html>
<head>
  <title>Transaction History</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body {
       background-color:rgb(162, 157, 142);
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    nav {
      background-color: #343a40;
    }
    nav a {
      color: #fff !important;
    }
    h5 {
        color: #fff !important;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h3>📜 Transaction History</h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Total</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($order = $orders->fetch_assoc()):
        $order_id = $order['order_id'];
        $order_total_query = $conn->query("SELECT SUM(total_price) as total FROM transactions WHERE order_id = '$order_id'");
        $order_total = $order_total_query->fetch_assoc()['total'] ?? 0;
      ?>
        <tr>
          <td><?= $order_id ?></td>
          <td><?= $order['created_at'] ?></td>
          <td>Rp <?= number_format($order_total, 0, ',', '.') ?></td>
          <td><a href="invoice_print.php?order_id=<?= $order_id ?>" class="btn btn-sm btn-info">View Invoice</a></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="mt-4">
    <h5>Total Revenue: <span class="text-success">Rp <?= number_format($total_revenue, 0, ',', '.') ?></span></h5>
  </div>

  <a href="index.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
</div>
</body>
</html>
