<?php
session_start();
require "../koneksi.php";

// Redirect ke halaman error jika session username tidak ada
if (empty($_SESSION['username'])) {
    header("Location: ../error.php");
    exit();
}

$username = $_SESSION['username'];

// Query level pengguna berdasarkan username
$query = "SELECT id, nama, level FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Jika tidak ditemukan hasil, redirect ke error
if ($result->num_rows === 0) {
    header("Location: ../error.php");
    exit();
}

$row = $result->fetch_assoc();
$userLevel = $row['level'];
$_SESSION['nama'] = $row['nama'];
$_SESSION['id'] = $row['id'];

// Hanya izinkan Admin
if ($userLevel !== 'Admin') {
    header("Location: ../error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    
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
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
    <style>
        .form-container {
            margin: 20px;
        }
        .form-container input, .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="form-container">
        
        <!-- Table to view users -->
        <center><img src="../images/logo.svg" href="index.php" alt="Logo" style="height: 45px;"></center>
       <center><h3>All Users</h3></center> 
        <center><a href="index.php">Return to dashboard</a></center>
        <table id="usersTable" class="display">
            <thead>
                <tr>
                    <th>ID User</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Nama</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM users";
                $hasil = mysqli_query($conn, $query);
                foreach ($hasil as $data) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $data['id']; ?>
                        </td>
                        <td>
                            <?php echo $data['username']; ?>
                        </td>
                        <td>
                            <?php echo $data['email']; ?>
                        </td>
                        <td>
                            <?php echo $data['nama']; ?>
                        </td>
                        <td>
                            <?php echo $data['level']; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery-migrate-3.0.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.easing.1.3.js"></script>
    <script src="../js/jquery.waypoints.min.js"></script>
    <script src="../js/jquery.stellar.min.js"></script>
    <script src="../js/jquery.animateNumber.min.js"></script>
    <script src="../js/bootstrap-datepicker.js"></script>
    <script src="../js/jquery.timepicker.min.js"></script>
    <script src="../js/owl.carousel.min.js"></script>
    <script src="../js/jquery.magnific-popup.min.js"></script>
    <script src="../js/scrollax.min.js"></script>
    <script src="../https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
    <script src="../js/google-map.js"></script>
    <script src="../js/main.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
                // Menambahkan konfigurasi pagination dan pencarian
                "paging": true,
                "searching": true,
                "lengthChange": false, // Menonaktifkan opsi jumlah per halaman
                "pageLength": 10, // Menentukan jumlah entri per halaman
                "ordering": true, // Menonaktifkan pengurutan
            });
        });
    </script>
</body>
</html>
