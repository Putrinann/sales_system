<?php
session_start();
unset($_SESSION['cart']);
header('Location: order.php');
exit;
?>