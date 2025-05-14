<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // kosong karena nggak pakai password
$db   = 'sales_system';

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>