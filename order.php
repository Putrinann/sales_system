<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Handle form submission (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'], $_POST['size'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $size = trim($_POST['size']);

    if ($product_id && $quantity > 0 && $size !== '') {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'size' => $size
        ];
    }
    header('Location: order.php');
    exit;
}

// Search handling
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_query = $conn->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%')");
$search_query->bind_param('s', $search);
$search_query->execute();
$search_results = $search_query->get_result();

// For dropdown
$all_products = $conn->query("SELECT * FROM products ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: rgb(250, 251, 251); font-family: 'Segoe UI', sans-serif; }
    .btn-custom { background-color:rgb(71, 106, 145); color: white; border-radius: 12px; }
    .btn-custom:hover { background-color:rgb(249, 243, 237); }
    .card, .table, select, input, button { border-radius: 12px; }
    .product-item { background: #fff; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1); padding: 20px; border-radius: 12px; }
    .footer { text-align: center; margin-top: 50px; font-size: 0.9rem; color: #7f8c8d; }
    .w-48 { width: 48%; }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>🛒 New Order</h3>
    <a href="index.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>

  <!-- Search Form -->
  <form class="row mb-4" method="GET">
    <div class="col-md-8">
      <input type="text" name="search" class="form-control" placeholder="Search product name..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-custom w-100">Search</button>
    </div>
  </form>

  <!-- Dropdown Select Form -->
  <form method="POST" class="row mb-4">
    <div class="col-md-4">
      <select name="product_id" id="product_id" class="form-select" onchange="this.form.submit()" required>
        <option value="">-- Select product --</option>
        <?php
        $selected_id = $_POST['product_id'] ?? '';
        mysqli_data_seek($all_products, 0); // reset result pointer
        while ($product = $all_products->fetch_assoc()):
        ?>
          <option value="<?= $product['id'] ?>" <?= $selected_id == $product['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($product['name']) ?> (Stock: <?= $product['stock'] ?>)
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <?php
    $selected_product = null;
    if ($selected_id) {
      $selected_product = $conn->query("SELECT * FROM products WHERE id = $selected_id")->fetch_assoc();
    }
    ?>

    <?php if ($selected_product): ?>
    <div class="col-md-2">
      <select name="size" class="form-select" required>
        <option value="">-- Size --</option>
        <?php foreach (explode(',', $selected_product['size']) as $sz): ?>
          <option value="<?= trim($sz) ?>"><?= trim($sz) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <input type="number" name="quantity" class="form-control" min="1" placeholder="Qty" required>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-custom w-100">Add</button>
    </div>
    <?php endif; ?>
  </form>

  <!-- Search Results -->
  <?php if ($search): ?>
    <h5>🔍 Results for "<?= htmlspecialchars($search) ?>"</h5>
    <?php if ($search_results->num_rows > 0): ?>
      <div class="row mb-4">
        <?php while ($row = $search_results->fetch_assoc()): ?>
          <div class="col-md-4">
            <div class="product-item mb-3">
              <p class="fw-bold"><?= htmlspecialchars($row['name']) ?></p>
              <p class="text-muted">Stock: <?= $row['stock'] ?> items</p>
              <form method="POST">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <select name="size" class="form-select mb-2" required>
                  <option value="">-- Size --</option>
                  <?php foreach (explode(',', $row['size']) as $sz): ?>
                    <option value="<?= trim($sz) ?>"><?= trim($sz) ?></option>
                  <?php endforeach; ?>
                </select>
                <input type="number" name="quantity" class="form-control mb-2" min="1" max="<?= $row['stock'] ?>" placeholder="Qty" required>
                <button type="submit" class="btn btn-custom w-100">Add to Cart</button>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>No products found.</p>
    <?php endif; ?>
  <?php endif; ?>

  <!-- Cart Preview -->
  <h4 class="mt-5">🧾 Current Cart</h4>
  <?php if (!empty($_SESSION['cart'])): ?>
    <table class="table table-striped table-bordered">
      <thead class="table-warning">
        <tr>
          <th>Product</th>
          <th>Size</th>
          <th>Quantity</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($_SESSION['cart'] as $item): ?>
          <?php
            $product = $conn->query("SELECT name FROM products WHERE id = {$item['product_id']}")->fetch_assoc();
            if ($product):
          ?>
            <tr>
              <td><?= htmlspecialchars($product['name']) ?></td>
              <td><?= htmlspecialchars($item['size']) ?></td>
              <td><?= $item['quantity'] ?></td>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="d-flex justify-content-between">
      <a href="invoice_preview.php" class="btn btn-warning btn-lg text-white w-48">Preview Invoice</a>
      <a href="clear_cart.php" class="btn btn-danger btn-lg text-white w-48">Cancel Order</a>
    </div>
  <?php else: ?>
    <p>No items in cart yet.</p>
  <?php endif; ?>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; 2025 Your Company - All rights reserved</p>
</div>

</body>
</html>
