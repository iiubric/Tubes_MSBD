<?php
include 'koneksi.php';

$errorMessage = ""; // Variabel untuk pesan error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($username)) {
        $errorMessage = "Username harus diisi.";
    } elseif (empty($password)) {
        $errorMessage = "Password harus diisi.";
    } else {
        // Periksa username di database
        $sql = "SELECT password, level FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Ambil password dan level yang tersimpan
            $stmt->bind_result($hashedPassword, $level);
            $stmt->fetch();

            // Verifikasi password
            if (password_verify($password, $hashedPassword)) {
                // Login berhasil
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['level'] = $level;

                // Redirect berdasarkan level
                switch ($level) {
                    case "User":
                        header("Location: index.php");
                        break;
                    case "Member":
                        header("Location: index.php");
                        break;
                    case "Cashier":
                        header("Location: cashier/index.php");
                        break;
                    case "Admin":
                        header("Location: admin/index.php");
                        break;
                    default:
                        $errorMessage = "Level pengguna tidak valid.";
                        break;
                }
                exit(); // Hentikan eksekusi
            } else {
                $errorMessage = "Password salah.";
            }
        } else {
            $errorMessage = "Username tidak ditemukan.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
    </style>
</head>
<body>
    <div class="login-container">
        <center><img src="images/logo.svg" alt="" style="height: 45px;"></center>
        <h2 class="text-center mb-4">Login</h2>
        <!-- Tampilkan pesan error jika ada -->
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
        <div class="mt-3 text-center">
            <small>Belum punya akun? <a href="register.php">Daftar di sini</a></small>
        </div>
    </div>
</body>
</html>
