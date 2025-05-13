<?php
session_start();
include 'db.php';

$cart = $_SESSION['cart'];
$order_id = uniqid();

foreach ($cart as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $product = $conn->query("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();
    $total = $product['price'] * $quantity;

    $conn->query("INSERT INTO transactions (order_id, product_id, quantity, total_price, created_at)
        VALUES ('$order_id', $product_id, $quantity, $total, NOW())");

    $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");
}

unset($_SESSION['cart']);
header("Location: invoice_print.php?order_id=$order_id");
exit;
?>
<?php include 'navbar.php'; ?>
