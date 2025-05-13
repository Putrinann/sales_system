<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Product Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color:rgb(162, 157, 142);
      font-family: 'Segoe UI', sans-serif;
    }
    nav {
      background-color: #343a40;
    }
    nav a {
      color: #fff !important;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-4">
  <div class="row">
    <!-- Add Product Form -->
    <div class="col-md-6">
      <h4>Add New Product</h4>
      <form method="POST">
        <div class="mb-2">
          <label>Name:</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-2">
          <label>Price:</label>
          <input type="number" class="form-control" name="price" required>
        </div>
        <div class="mb-2">
          <label>Stock:</label>
          <input type="number" class="form-control" name="stock" required>
        </div>
        <div class="mb-2">
          <label>Sizes (comma-separated):</label>
          <input type="text" class="form-control" name="size" placeholder="e.g., 36,37,38">
        </div>
        <button class="btn btn-success" name="save">Save Product</button>
      </form>
      <a href="index.php" class="btn btn-secondary mt-3">⬅ Back</a>

      <?php
      if (isset($_POST['save'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $size = $_POST['size'];
        $conn->query("INSERT INTO products(name, price, stock, size) VALUES('$name', '$price', '$stock', '$size')");
        echo "<div class='alert alert-success mt-2'>Product added!</div>";
        echo "<meta http-equiv='refresh' content='1'>";
      }
      ?>
    </div>

    <!-- All Products Table -->
    <div class="col-md-6">
      <h4>All Products</h4>
      <table class="table table-bordered bg-white">
        <tr>
          <th>Name</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Sizes</th>
          <th>Action</th>
        </tr>
        <?php
        $edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
        $data = $conn->query("SELECT * FROM products");
        while ($row = $data->fetch_assoc()):
        ?>
          <?php if ($edit_id === (int)$row['id']): ?>
          <!-- Edit Mode -->
          <tr>
            <form method="POST">
              <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
              <td><input type="text" name="edit_name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control" required></td>
              <td><input type="number" name="edit_price" value="<?= $row['price'] ?>" class="form-control" required></td>
              <td><input type="number" name="edit_stock" value="<?= $row['stock'] ?>" class="form-control" required></td>
              <td><input type="text" name="edit_size" value="<?= htmlspecialchars($row['size']) ?>" class="form-control"></td>
              <td>
                <button class="btn btn-sm btn-success" name="update">Save</button>
                <a href="product.php" class="btn btn-sm btn-secondary">Cancel</a>
              </td>
            </form>
          </tr>
          <?php else: ?>
          <!-- Normal Row -->
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>Rp <?= number_format($row['price'],0,',','.') ?></td>
            <td><?= $row['stock'] ?></td>
            <td><?= htmlspecialchars($row['size']) ?></td>
            <td>
              <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
              <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
            </td>
          </tr>
          <?php endif; ?>
        <?php endwhile; ?>
      </table>

      <?php
      // Handle delete
      if (isset($_GET['delete'])) {
        $id = (int)$_GET['delete'];
        $conn->query("DELETE FROM products WHERE id = $id");
        echo "<meta http-equiv='refresh' content='0;url=product.php'>";
      }

      // Handle update
      if (isset($_POST['update'])) {
        $id = (int)$_POST['edit_id'];
        $name = $_POST['edit_name'];
        $price = $_POST['edit_price'];
        $stock = $_POST['edit_stock'];
        $size = $_POST['edit_size'];
        $conn->query("UPDATE products SET name='$name', price='$price', stock='$stock', size='$size' WHERE id=$id");
        echo "<meta http-equiv='refresh' content='0;url=product.php'>";
      }
      ?>
    </div>
  </div>
</div>

</body>
</html>
