<?php
$host = 'localhost';
$port = 4306; // kalau MySQL kamu jalan di port 4306
$user = 'root';
$pass = ''; // kosong karena nggak pakai password
$db   = 'sales_system';

$conn = new mysqli($host, $user, $pass, $db, $port);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
