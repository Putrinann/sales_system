<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($product_id && $quantity > 0) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'quantity' => $quantity
        ];
    }
    header('Location: order.php');
    exit;
}

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$products = $conn->query("SELECT * FROM products WHERE name LIKE '%$search%'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color:rgb(162, 157, 142);
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #2c3e50;
    }
    .navbar .nav-link {
      color: #fff !important;
    }
    .btn-custom {
      border-radius: 12px;
      background-color: #f39c12;
      color: white;
    }
    .btn-custom:hover {
      background-color: #e67e22;
    }
    .card, .table, select, input, button {
      border-radius: 12px;
    }
    .product-item {
      background-color: #ffffff;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 15px;
      border-radius: 12px;
    }
    .product-item .product-name {
      font-weight: bold;
      font-size: 1.1rem;
    }
    .product-item .product-details {
      color: #7f8c8d;
    }
    .search-results h4 {
      margin-top: 20px;
      font-size: 1.3rem;
      color: #2c3e50;
    }
    .table-striped thead {
      background-color: #f39c12;
      color: #fff;
    }
    .table-striped tbody tr:hover {
      background-color: #f1c40f;
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

<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>🛒 New Order</h3>
    <a href="index.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>

  <!-- Search bar -->
  <form class="row mb-4" method="GET">
    <div class="col-md-8">
      <input type="text" name="search" class="form-control" placeholder="Search product name..." value="<?= htmlspecialchars($search) ?>" style="border-radius: 12px;">
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-custom w-100">Search</button>
    </div>
  </form>

  <!-- Search results display -->
  <?php if ($search): ?>
  <div class="search-results">
    <h4>Search Results for "<?= htmlspecialchars($search) ?>"</h4>
    <?php if ($products->num_rows > 0): ?>
      <div class="row">
        <?php while ($row = $products->fetch_assoc()): ?>
          <div class="col-md-4 mb-3">
            <div class="product-item">
              <p class="product-name"><?= $row['name'] ?></p>
              <p class="product-details">Stock: <?= $row['stock'] ?> items</p>
              <form method="POST">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <input type="number" name="quantity" class="form-control" min="1" max="<?= $row['stock'] ?>" required placeholder="Qty" style="border-radius: 12px;">
                <button type="submit" class="btn btn-custom mt-2 w-100">Add to Cart</button>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>No products found for your search.</p>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- Cart preview -->
  <h4 class="mt-5">🧾 Current Cart</h4>
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Product</th>
        <th>Qty</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($_SESSION['cart'] as $item) {
        $product = $conn->query("SELECT name FROM products WHERE id = {$item['product_id']}")->fetch_assoc();
        echo "<tr><td>{$product['name']}</td><td>{$item['quantity']}</td></tr>";
      }
      ?>
    </tbody>
  </table>


<div class="d-flex justify-content-between">
    <a href="invoice_preview.php" class="btn btn-warning btn-lg text-white w-48" style="border-radius: 15px; padding: 12px 20px; background-color: #f39c12; border: none; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">Preview Invoice</a>
    <a href="clear_cart.php" class="btn btn-danger btn-lg text-white w-48" style="border-radius: 15px; padding: 12px 20px; background-color: #e74c3c; border: none; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">Cancel Order</a>
</div>

  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; 2025 Your Company - All rights reserved</p>
</div>

</body>
</html>
