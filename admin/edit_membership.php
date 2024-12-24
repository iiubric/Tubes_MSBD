<?php
session_start();
require "../koneksi.php";

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($_SESSION['level'] !== 'Admin') {
    header("Location: error.php");
    exit();
}

// Jika ada ID, ambil data membership untuk di-edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM membership WHERE membership_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $membership = $result->fetch_assoc();

    // Jika data tidak ditemukan
    if (!$membership) {
        echo "Membership not found!";
        exit();
    }
}

// Proses untuk update data membership
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['membership_name'];
    $duration = $_POST['duration_months'];
    $price = $_POST['price'];
    $benefits = $_POST['benefits'];

    // Query untuk update data membership
    $query_update = "UPDATE membership SET membership_name = ?, duration_months = ?, price = ?, benefits = ? WHERE membership_id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("siisi", $name, $duration, $price, $benefits, $id);
    $stmt_update->execute();

    // Redirect kembali ke daftar membership setelah berhasil update
    header("Location: membership.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Membership</title>
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="../css/animate.css">

<link rel="stylesheet" href="../css/owl.carousel.min.css">
<link rel="stylesheet" href="../css/owl.theme.default.min.css">
<link rel="stylesheet" href="../css/magnific-popup.css">

<link rel="stylesheet" href="../css/bootstrap-datepicker.css">
<link rel="stylesheet" href="../css/jquery.timepicker.css">

<link rel="stylesheet" href="../css/flaticon.css">
<link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h1>Edit Membership</h1>
    <form method="POST" action="">
        <label for="membership_name">Membership Name:</label>
        <input type="text" name="membership_name" id="membership_name" value="<?php echo $membership['membership_name']; ?>" required>

        <label for="duration_months">Duration (Months):</label>
        <input type="number" name="duration_months" id="duration_months" value="<?php echo $membership['duration_months']; ?>" required>

        <label for="price">Price:</label>
        <input type="text" name="price" id="price" value="<?php echo $membership['price']; ?>" required>

        <label for="benefits">Benefits:</label>
        <textarea name="benefits" id="benefits" required><?php echo $membership['benefits']; ?></textarea>

        <button type="submit">Update Membership</button>
    </form>
</body>
</html>
