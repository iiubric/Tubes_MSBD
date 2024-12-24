<?php
$servername = "localhost"; // Server database
$username = "root";        // Username database
$password = "";            // Password database
$dbname = "finsbodyfactory"; // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
