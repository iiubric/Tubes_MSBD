<?php
include 'koneksi.php';

// Variabel untuk menampung pesan
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    $name = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name)) {
        $errorMessage = "Nama harus diisi.";
    } elseif (empty($username)) {
        $errorMessage = "Username harus diisi.";
    } elseif (empty($email)) {
        $errorMessage = "Email harus diisi.";
    } elseif (empty($password)) {
        $errorMessage = "Password harus diisi.";
    } elseif (strlen($password) < 8) {
        $errorMessage = "Password harus memiliki minimal 8 karakter.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Format email tidak valid.";
    } else {
        // Sanitasi input
        $name = mysqli_real_escape_string($conn, $name);
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Cek apakah email sudah terdaftar
        $checkEmailSql = "SELECT email FROM users WHERE email = ?";
        $stmt = $conn->prepare($checkEmailSql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Jika email sudah ada
            $errorMessage = "Email sudah terdaftar. Silakan gunakan email lain.";
            $stmt->close();
        } else {
            // Jika email belum ada, simpan data
            $stmt->close();

            // Buat statement baru untuk menyimpan data
            $insertSql = "INSERT INTO users (nama, username, email, password, level) VALUES (?, ?, ?, ?, ?);";
            $stmt = $conn->prepare($insertSql);
            $userLevel = "User";
            $stmt->bind_param("sssss", $name, $username, $email, $hashedPassword, $userLevel);

            if ($stmt->execute()) {
                // Set pesan sukses
                $successMessage = "Registrasi Berhasil!";
                // Arahkan ke halaman utama
                echo "<script>
                        alert('Registrasi berhasil!');
                        window.location.href = 'index.php';
                      </script>";
                exit();
            } else {
                $errorMessage = "Terjadi kesalahan: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
        .success-message {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <center><img src="images/logo.svg" alt="Logo" style="height: 45px;"></center>
        <h2 class="text-center mb-4">Sign Up</h2>
        <!-- Tampilkan pesan error jika ada -->
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php elseif (!empty($successMessage)): ?>
            <div class="success-message">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (minimal 8 karakter)</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Sign up</button>
            </div>
            <div class="mt-3 text-center">
            <small>Sudah punya akun? <a href="login.php">Login di sini</a></small>
            </div>
        </form>
    </div>
</body>
</html>
