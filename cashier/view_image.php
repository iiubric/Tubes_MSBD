<?php
require '../koneksi.php';

$id_request = $_GET['id_request'];
$image = $_GET['image'];

// Validasi input
if (!in_array($image, ['foto_kartu_identitas', 'bukti_pembayaran'])) {
    die('Invalid image parameter.');
}

$query = "SELECT $image FROM request_membership WHERE id_request = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_request);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $filePath = $row[$image];
    if (file_exists($filePath)) {
        header("Content-Type: image/jpeg"); // Sesuaikan dengan jenis file
        readfile($filePath);
        exit;
    }
}
die('Image not found.');
?>
