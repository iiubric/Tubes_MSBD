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
<!-- Aint nobody prayin for me -->


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fins Body Factory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
		<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
      <a class="navbar-brand" href="#">
                <img src="../images/logo.svg" alt="" style="height: 45px;"> Fin's Body Factory
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item" style="color: rgba(175,223,51,255);">
                            <a class="nav-link" style="color: rgba(175,223,51,255);" href="admin/profileadmin.php?id=<?= $_SESSION['id'] ?>">
                               Welcome, <?= htmlspecialchars( $_SESSION['nama']) ?>
                            </a>
                        </li>
                       
                    <li class="nav-item"><a href="../index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="approve.php" class="nav-link">Approve membership</a></li>
                    <li class="nav-item"><a href="datauser.php" class="nav-link">User Data</a></li>
                    <li class="nav-item"><a href="viewmember.php" class="nav-link">View Members</a></li>
                    <li class="nav-item"><a href="membership.php" class="nav-link">Membership</a></li>

                        <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="../login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>

	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->

    <div class="hero-wrap js-fullheight">
	    <div class="home-slider owl-carousel js-fullheight">
	      <div class="slider-item js-fullheight" style="background-image:url(../images/baground.jpg);">
	      	<div class="overlay"></div>
	        <div class="container">
	          <div class="row no-gutters slider-text align-items-center">
		          <div class="col-md-7 ftco-animate">
		          	<div class="text w-100">
		          		<h2>Welcome to Fin's Body Factory</h2>
			            <h1 class="mb-4" >Build Your Body and Fitness with Professional Touch</h1>
		            </div>
		          </div>
		        </div>
	        </div>
	      </div>
	  </div>

   

   

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


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



  </body>
</html>
