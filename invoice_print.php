<?php
include 'db.php';
$order_id = $_GET['order_id'];
$results = $conn->query("SELECT * FROM transactions WHERE order_id = '$order_id'");
$grand_total = 0;
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="img/icon.jpg">
    <style>
    @media print {
        .no-print {
            display: none;
        }
    }

    .receipt {
        background: #fff;
        padding: 30px;
        max-width: 500px;
        margin: 30px auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body>
    <div class="receipt">
        <h4 class="text-center">🧾 Invoice - <?= $order_id ?></h4>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $results->fetch_assoc()):
        $product = $conn->query("SELECT name FROM products WHERE id = {$row['product_id']}")->fetch_assoc();
        $grand_total += $row['total_price'];
      ?>
                <tr>
                    <td><?= $product['name'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>Rp <?= number_format($row['total_price'] / $row['quantity'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['total_price'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <th colspan="3">Grand Total</th>
                    <th>Rp <?= number_format($grand_total, 0, ',', '.') ?></th>
                </tr>
            </tbody>
        </table>
        <p class="text-center">Terima kasih telah berbelanja!</p>
    </div>
    <div class="text-center no-print">
        <button onclick="window.print()" class="btn btn-dark">🖨 Print</button>
        <a href="order.php" class="btn btn-primary">🛒 New Order</a>
        <a href="transaction_history.php" class="btn btn-secondary">📜 History</a>
    </div>
</body>

</html>