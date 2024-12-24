<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
}

// Hapus session
session_destroy();

header("Location: login.php");
exit();
?>
