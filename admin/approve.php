<?php
session_start();
require '../koneksi.php'; // Ganti dengan file koneksi database Anda

// Periksa apakah admin sudah login
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'Admin') {
    die("<p>Access denied. Admins only.</p>");
}

// Ambil semua request membership dari database
$query = "
    SELECT r.*, m.membership_name, u.username, u.level
    FROM request_membership r
    JOIN membership m ON r.id_membership = m.membership_id
    JOIN users u ON r.id_user = u.id
    ORDER BY r.tanggal_request DESC
";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("<p>Failed to fetch requests: " . htmlspecialchars(mysqli_error($conn)) . "</p>");
}

$requests = [];
while ($row = mysqli_fetch_assoc($result)) {
    $requests[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_request = $_POST['id_request'];
    $status_pembayaran = $_POST['status_pembayaran'];

    // Debugging untuk memeriksa nilai status_pembayaran
    var_dump($status_pembayaran);  // Hapus setelah debugging selesai

    // Lanjutkan dengan pemrosesan request seperti sebelumnya
    $query = "SELECT * FROM request_membership WHERE id_request = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_request);
    $stmt->execute();
    $request = $stmt->get_result()->fetch_assoc();

    if (!$request) {
        die("<p>Request not found or already processed.</p>");
    }
    
    // Ambil data level dari request
    $level_user = $request['level'];
    
    // Jika status pembayaran diterima
    if ($status_pembayaran === 'diterima') {
        // Ambil data request berdasarkan id_request
        $query = "SELECT * FROM request_membership WHERE id_request = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_request);
        $stmt->execute();
        $request = $stmt->get_result()->fetch_assoc();

        if ($request) {
            $id_user = $request['id_user'];
            $id_membership = $request['id_membership'];
            $nomor_hp = $request['nomor_hp'];
            $foto_kartu_identitas = $request['foto_kartu_identitas'];
            $bukti_pembayaran = $request['bukti_pembayaran'];

            // Ambil durasi membership dari tabel membership
            $query = "SELECT duration_months FROM membership WHERE membership_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $id_membership);
            $stmt->execute();
            $stmt->bind_result($duration_months);
            $stmt->fetch();
            $stmt->close();

            $membership_date = date('Y-m-d'); //
            $expiration_date = date('Y-m-d', strtotime("+$duration_months months"));

            // Jika level adalah 'member', hanya masukkan data ke tabel 'member'
            if ($request['level'] === 'Member') {
                // Cek apakah user sudah memiliki membership yang expired
                $query = "SELECT id, membership_id, membership_date, expiration_date, status FROM member WHERE id_user = ? AND status = 'expired' ORDER BY id_user DESC LIMIT 1";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $id_user);
                $stmt->execute();
                $stmt->store_result();
            
                if ($stmt->num_rows > 0) {
                    // Bind result sesuai dengan kolom yang ada pada query
                    $stmt->bind_result($id_member, $existing_membership_id, $membership_date, $existing_expiration_date, $status);
                    $stmt->fetch();
            
                    // Ambil durasi membership baru
                    $query = "SELECT duration_months FROM membership WHERE membership_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('i', $id_membership);
                    $stmt->execute();
                    $stmt->bind_result($duration_months);
                    $stmt->fetch();
                    $stmt->close();
            
                    // Update tanggal membership dan expiration
                    $membership_date = date('Y-m-d');
                    $expiration_date = date('Y-m-d', strtotime("+$duration_months months"));
            
                    // Update data membership di tabel 'member'
                    $query = "UPDATE member SET membership_id = ?, membership_date = ?, expiration_date = ?, status = 'active' WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('issi', $id_membership, $membership_date, $expiration_date, $id_member);
                    $stmt->execute();
            
                    // Masukkan bukti pembayaran ke tabel 'bukti_pembayaran'
                    $tanggal_diterima = date('Y-m-d H:i:s');
                    $query = "INSERT INTO bukti_pembayaran (id_user, id_member, bukti_pembayaran, tanggal_diterima) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('iiss', $id_user, $id_member, $bukti_pembayaran, $tanggal_diterima);
                    $stmt->execute();
                } else {
                    echo "<p>No expired membership found for this user.</p>";
                }
            
                
            } elseif ($request['level'] === 'User') {
                 // Jika level adalah 'user', update level user menjadi 'member'
                if ($level_user === 'User') {
                $query = "UPDATE users SET level = 'member' WHERE id = ? AND level = 'user'";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $id_user);
                $stmt->execute();
            }
                // Untuk level 'user', masukkan data ke tabel 'member' dan 'member_detail'
                $query = "INSERT INTO member (id_user, membership_id, membership_date, expiration_date, status) VALUES (?, ?, ?, ?, 'active')";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('iiss', $id_user, $id_membership, $membership_date, $expiration_date);
                $stmt->execute();
                $id_member = $conn->insert_id;

                // Masukkan ke tabel 'member_detail'
                $query = "SELECT nama FROM users WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $id_user);
                $stmt->execute();
                $stmt->bind_result($nama);
                $stmt->fetch();
                $stmt->close();

                $query = "INSERT INTO member_detail (id_member, nomor_hp, foto_kartu_identitas) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('iss', $id_member,  $nomor_hp, $foto_kartu_identitas);
                $stmt->execute();

                // Masukkan ke tabel 'bukti_pembayaran'
                $tanggal_diterima = date('Y-m-d H:i:s');
                $query = "INSERT INTO bukti_pembayaran (id_user, id_member, bukti_pembayaran, tanggal_diterima) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('iiss', $id_user, $id_member, $bukti_pembayaran, $tanggal_diterima);
                $stmt->execute();
            }

            // Hapus data dari tabel 'request_membership'
            $query = "CALL DeleteRequestMembership(?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $id_request);
            $stmt->execute();
            $stmt->close();

            echo "<p>Request accepted and processed successfully!</p>";
        } else {
            echo "<p>Request not found.</p>";
        }
    } else {
        // Jika status pembayaran ditolak, hanya update status dan hapus request
        $stmt = $conn->prepare("UPDATE request_membership SET status_pembayaran = ? WHERE id_request = ?");
        $stmt->bind_param('si', $status_pembayaran, $id_request);
        $stmt->execute();

        // Hapus data dari tabel 'request_membership'
        $query = "CALL DeleteRequestMembership(?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_request);
        $stmt->execute();
        $stmt->close();

        echo "<p>Request updated successfully!</p>";
    }

    // Refresh halaman untuk memastikan data terbaru
    header("Location: approve.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Requests</title>
<style>
    /* Modal Container */
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.9);
}

/* Modal Content (Image) */
.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
}

/* Caption Text */
#caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    font-size: 16px;
}

/* Close Button */
.close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    user-select: none;
}
</style>
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
<c>
<center><img src="../images/logo.svg" href="index.php" alt="Logo" style="height: 45px;">
<h1>Membership Requests</h1></a>
<a href="index.php">Return to dashboard</a></center>
    <table id="tabel" class="display table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Membership</th>
                <th>Phone Number</th>
                <th>Kartu Identitas</th>
                <th>Bukti Pembayaran</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= $request['id_request'] ?></td>
                    <td><?= htmlspecialchars($request['username']) ?></td>
                    <td><?= htmlspecialchars($request['membership_name']) ?></td>
                    <td><?= htmlspecialchars($request['nomor_hp'] ?? '-') ?></td>
                    <td>
                        <?php if (!empty($request['foto_kartu_identitas'])): ?>
                            <img src="../<?= htmlspecialchars($request['foto_kartu_identitas']) ?>" alt="Foto Kartu Identitas" style="max-width: 100px; max-height: 100px;">
                        <?php else: ?>
                            <span>No Kartu Identitas</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if (!empty($request['bukti_pembayaran'])): ?>
                            <img src="../<?= htmlspecialchars($request['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" style="max-width: 100px; max-height: 100px;">
                        <?php else: ?>
                            <span>No Bukti Pembayaran</span>
                        <?php endif; ?>
                    </td>
                                        <td><?= $request['tanggal_request'] ?></td>
                    <td><?= htmlspecialchars($request['status_pembayaran']) ?></td>
                    <td>
                        <form action="" method="POST" style="display: inline-block;">
                            <input type="hidden" name="id_request" value="<?= $request['id_request'] ?>">
                            <select name="status_pembayaran">
                                <option value="pending" <?= $request['status_pembayaran'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="diterima" <?= $request['status_pembayaran'] === 'diterima' ? 'selected' : '' ?>>Accepted</option>
                                <option value="ditolak" <?= $request['status_pembayaran'] === 'ditolak' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
    <div id="caption"></div>
</div>

</body>
</html>

<script>
    // Get modal elements
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const captionText = document.getElementById('caption');
    const closeBtn = document.querySelector('.close');

    // Add click event to all images inside the table
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('click', () => {
            modal.style.display = 'block'; // Show modal
            modalImg.src = img.src; // Set modal image to clicked image
            captionText.innerHTML = img.alt; // Set modal caption to alt text
        });
    });

    // Close modal when clicking the close button
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Close modal when clicking outside the image
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>


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


<script>
        $(document).ready(function () {
            $('#tabel').DataTable();
        });
    </script>