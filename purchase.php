<?php
session_start();
include 'koneksi.php'; // Ganti dengan file koneksi database Anda

// Periksa apakah user sudah login dan ambil id_user
if (!isset($_SESSION['id'])) {
    die("<p>Please log in to access this page.</p>");
}

$id = $_SESSION['id'];

// Ambil level user dari tabel users
$query_level = "SELECT level FROM users WHERE id = ?";
$stmt_level = $conn->prepare($query_level);
$stmt_level->bind_param('i', $id);
$stmt_level->execute();
$stmt_level->bind_result($level);
$stmt_level->fetch();
$stmt_level->close();

// Ambil paket membership dari database
$query = "SELECT * FROM membership ORDER BY membership_id ASC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("<p>Failed to fetch membership packages: " . mysqli_error($conn) . "</p>");
}

$memberships = [];
while ($row = mysqli_fetch_assoc($result)) {
    $memberships[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_membership = $_POST['membership_id'];
    $nomor_hp = $level === 'User' ? $_POST['nomor_hp'] : null;
    $foto_kartu_identitas = $level === 'User' ? $_FILES['foto_kartu_identitas'] : null;
    $bukti_pembayaran = $_FILES['bukti_pembayaran'];

    // Validasi data
    if ($level === 'User' && (!$nomor_hp || !$foto_kartu_identitas['tmp_name'])) {
        die("<p>Please fill out all required fields for User level.</p>");
    }
    if (!$bukti_pembayaran['tmp_name']) {
        die("<p>Proof of payment is required.</p>");
    }

    // Tentukan folder tujuan
    $uploadDirIdentitas = 'uploads/foto_kartu_id/';
    $uploadDirBukti = 'uploads/bukti_pembayaran/';

    // Buat folder jika belum ada
    if (!is_dir($uploadDirIdentitas)) {
        mkdir($uploadDirIdentitas, 0777, true);
    }
    if (!is_dir($uploadDirBukti)) {
        mkdir($uploadDirBukti, 0777, true);
    }

    // Fungsi untuk mengupload file
    function uploadFile($file, $uploadDir, $prefix) {
        $fileName = $prefix . '_' . time() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filePath;
        } else {
            die("<p>Failed to upload file: " . $file['name'] . "</p>");
        }
    }

    // Upload file ke folder masing-masing
    $foto_kartu_identitas_path = $foto_kartu_identitas ? uploadFile($foto_kartu_identitas, $uploadDirIdentitas, '') : null;
    $bukti_pembayaran_path = uploadFile($bukti_pembayaran, $uploadDirBukti, '');

    // Simpan data ke database (termasuk level)
    $stmt = $conn->prepare("INSERT INTO request_membership (id_user, id_membership, foto_kartu_identitas, bukti_pembayaran, nomor_hp, tanggal_request, status_pembayaran, level) VALUES (?, ?, ?, ?, ?, NOW(), 'pending', ?)");
    $stmt->bind_param('iissss', $id, $id_membership, $foto_kartu_identitas_path, $bukti_pembayaran_path, $nomor_hp, $level);

    if ($stmt->execute()) {
        echo "<script>
                alert('Request successfully submitted! Please wait for admin approval.');
                window.location.href = 'index.php';
              </script>";
        exit(); // Make sure to stop the script execution
    } else {
        echo "<p>Failed to submit your request: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Membership</title>
    <center><img src="images/logo.svg" alt="Logo" style="height: 45px;"></center>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #2c3e50;
        }

        form {
            background-color: #fff;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
            color: #2c3e50;
        }

        select, input[type="text"], input[type="file"], button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="file"] {
            padding: 5px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 6px;
            padding: 12px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input,
        .form-group select {
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: none;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group button {
            background-color: #27ae60;
            color: white;
        }

        .form-group button:hover {
            background-color: #2ecc71;
        }

        .alert {
            padding: 15px;
            background-color: #f2dede;
            color: #a94442;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Purchase Membership</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="membership_id">Select Membership Package:</label>
            <select name="membership_id" id="membership_id" required>
                <?php foreach ($memberships as $membership): ?>
                    <option value="<?= $membership['membership_id'] ?>">
                        <?= $membership['membership_name'] ?> - Rp<?= $membership['price'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if ($level === 'User'): ?>
            <div class="form-group">
                <label for="nomor_hp">Phone Number:</label>
                <input type="text" name="nomor_hp" id="nomor_hp" required>
            </div>

            <div class="form-group">
                <label for="foto_kartu_identitas">Upload ID Photo:</label>
                <input type="file" name="foto_kartu_identitas" id="foto_kartu_identitas" accept="image/*" required>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="bukti_pembayaran">Upload Proof of Payment:</label>
            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" required>
        </div>

        <div class="form-group">
            <button type="submit">Submit</button>
        </div>
    </form>
</body>
</html>
