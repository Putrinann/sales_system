<!-- product.php -->
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
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>


<div class="container mt-4">
  <div class="row">
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
        <button class="btn btn-success" name="save">Save Product</button>
      </form>
      <a href="index.php" class="btn btn-secondary mt-3">⬅ Back</a>

      <?php
      if (isset($_POST['save'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $conn->query("INSERT INTO products(name, price, stock) VALUES('$name', '$price', '$stock')");
        echo "<div class='alert alert-success mt-2'>Product added!</div>";
        echo "<meta http-equiv='refresh' content='1'>";
      }
      ?>
    </div>

    <div class="col-md-6">
      <h4>All Products</h4>
      <table class="table table-bordered bg-white">
        <tr>
          <th>Name</th><th>Price</th><th>Stock</th><th>Action</th>
        </tr>
        <?php
        $data = $conn->query("SELECT * FROM products");
        while ($row = $data->fetch_assoc()) {
          echo "<tr>
            <td>{$row['name']}</td>
            <td>Rp ".number_format($row['price'],0,',','.')."</td>
            <td>{$row['stock']}</td>
            <td><a href='?delete={$row['id']}' class='btn btn-sm btn-danger'>Delete</a></td>
          </tr>";
        }
        ?>
      </table>

      <?php
      if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $conn->query("DELETE FROM products WHERE id='$id'");
        echo "<meta http-equiv='refresh' content='0;url=product.php'>";
      }
      ?>
    </div>
  </div>
</div>

</body>
</html>
