<?php
session_start();
include 'db.php';

if (empty($_SESSION['cart'])) {
    header("Location: order.php");
    exit;
}

$cart = $_SESSION['cart'];
$grand_total = 0;
?>
<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice Preview</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: rgb(245, 243, 235);
      font-family: 'Segoe UI', sans-serif;
    }
    .btn-custom {
      border-radius: 12px;
      font-weight: 500;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .footer {
      text-align: center;
      margin-top: 50px;
      font-size: 0.9rem;
      color: #7f8c8d;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4">🧾 Invoice Preview</h3>
  <table class="table table-bordered table-striped">
    <thead class="table-warning">
      <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cart as $item): ?>
        <?php
          $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
          $stmt->bind_param("i", $item['product_id']);
          $stmt->execute();
          $result = $stmt->get_result();
          if ($product = $result->fetch_assoc()):
              $total = $product['price'] * $item['quantity'];
              $grand_total += $total;
        ?>
          <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
          </tr>
        <?php endif; ?>
      <?php endforeach; ?>
      <tr class="fw-bold">
        <td colspan="3" class="text-end">Grand Total</td>
        <td>Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
      </tr>
    </tbody>
  </table>

  <form method="POST" action="save_order.php" class="d-flex gap-3">
    <button type="submit" class="btn btn-success btn-lg btn-custom">✅ Save Order</button>
    <a href="order.php" class="btn btn-secondary btn-lg btn-custom">⬅ Back</a>
  </form>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; 2025 Your Company - All rights reserved</p>
</div>
</body>
</html>
