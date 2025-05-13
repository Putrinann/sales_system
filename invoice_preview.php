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
<html>
<head>
  <title>Invoice Preview</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h3>🧾 Invoice Preview</h3>
  <table class="table">
    <thead><tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr></thead>
    <tbody>
      <?php foreach ($cart as $item):
        $product = $conn->query("SELECT * FROM products WHERE id = {$item['product_id']}")->fetch_assoc();
        $total = $product['price'] * $item['quantity'];
        $grand_total += $total;
      ?>
        <tr>
          <td><?= $product['name'] ?></td>
          <td><?= $item['quantity'] ?></td>
          <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
          <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
        </tr>
      <?php endforeach; ?>
      <tr><th colspan="3">Grand Total</th><th>Rp <?= number_format($grand_total, 0, ',', '.') ?></th></tr>
    </tbody>
  </table>

  <form method="POST" action="save_order.php">
    <button type="submit" class="btn btn-success">Save Order</button>
    <a href="order.php" class="btn btn-secondary">Back</a>
  </form>
</div>
</body>
</html>
