<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Product Management</title>
    <link rel="icon" type="image/png" href="img/icon.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
    body {
        background-color: rgb(250, 251, 251);
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
                        <input type="number" class="form-control" name="price" min="500" required>
                    </div>
                    <div class="mb-2">
                        <label>Size:</label>
                        <select class="form-control" name="size" required>
                            <option value="">-- Select Size --</option>
                            <?php for ($i = 36; $i <= 41; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Stock:</label>
                        <input type="number" class="form-control" name="stock" min="1" required>
                    </div>

                    <button class="btn btn-success" name="save">Save Product</button>
                </form>
                <a href="index.php" class="btn btn-secondary mt-3">⬅ Back</a>

                <?php
                if (isset($_POST['save'])) {
                    $name = $_POST['name'];
                    $price = $_POST['price'];
                    $stock = max(1, (int)$_POST['stock']);
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
                        <th>Sizes</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
                    // SORTING BY NAME A-Z
                    $data = $conn->query("SELECT * FROM products ORDER BY name ASC");
                    while ($row = $data->fetch_assoc()):
                    ?>
                    <?php if ($edit_id === (int)$row['id']): ?>
                    <!-- Edit Mode -->
                    <tr>
                        <form method="POST">
                            <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                            <td><input type="text" name="edit_name" value="<?= htmlspecialchars($row['name']) ?>"
                                    class="form-control" required></td>
                            <td><input type="number" name="edit_price" value="<?= $row['price'] ?>" class="form-control"
                                    required></td>
                            <td><input type="text" name="edit_size" value="<?= htmlspecialchars($row['size']) ?>"
                                    class="form-control"></td>
                            <td><input type="number" name="edit_stock" value="<?= $row['stock'] ?>" class="form-control"
                                    required></td>

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
                        <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['size']) ?></td>
                        <td><?= $row['stock'] ?></td>
                        <td>
                            <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <button class="btn btn-sm btn-danger"
                                onclick="showDeleteModal(<?= $row['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endwhile; ?>
                </table>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this product?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Handle delete
                if (isset($_GET['delete'])) {
                    $id = (int)$_GET['delete'];
                    $check = $conn->query("SELECT COUNT(*) as total FROM transactions WHERE product_id = $id")->fetch_assoc();
                    if ($check['total'] > 0) {
                        echo "<div class='alert alert-danger'>Cannot delete: Product is used in transactions.</div>";
                    } else {
                        $conn->query("DELETE FROM products WHERE id = $id");
                        echo "<meta http-equiv='refresh' content='0;url=product.php'>";
                    }
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

    <!-- Bootstrap JS and Modal script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function showDeleteModal(productId) {
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.href = '?delete=' + productId;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    </script>

</body>

</html>
