<?php
session_start();
require "koneksi.php";

$username = $_SESSION['username'] ?? null;
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);


if ($username) {
    // Query level pengguna berdasarkan username
    $query = "SELECT id, nama, level FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika tidak ditemukan hasil, redirect ke error
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userLevel = $row['level'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['id'] = $row['id'];
    } else {
        header("Location: error.php");
        exit;
    }
}

$user_id = $_SESSION['id'];
// Ambil status membership dari tabel member berdasarkan user_id
$query_member = "SELECT status FROM member WHERE id_user = ?";
$stmt_member = $conn->prepare($query_member);
$stmt_member->bind_param("i", $user_id);
$stmt_member->execute();
$result_member = $stmt_member->get_result();
$member = $result_member->fetch_assoc();
$member_status = $member['status'];
?>
<!-- Aint nobody prayin for me -->


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fins Body Factory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="images/logo.svg" alt="Logo" style="height: 45px;"> Fin's Body Factory
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fa fa-bars"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
                          <ul class="navbar-nav ml-auto">
                          <?php if (isset($_SESSION['username'])): ?>
                  <li class="nav-item" >
                      <a class="nav-link" style="color: rgba(175,223,51,255);" href="">
                          Welcome, <?= htmlspecialchars($_SESSION['nama']) ?>
                      </a>
                  </li>
                  <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                  <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                  <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>

                  <!-- Logout button positioned beside Contact -->
                  <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                  
                  <!-- If the user is an admin, show the dashboard link -->
                  <?php if ($_SESSION['level'] === 'Admin'): ?>
                      <li class="nav-item"><a class="nav-link" href="admin/index.php">Admin Dashboard</a></li>
                  <?php endif; ?>
              <?php else: ?>
                  <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
              <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
    <!-- END nav -->
<style>
    .slider-item.js-fullheight {
  background-image: url('images/baground.jpg');
  background-size: cover;
  background-position: center;
  background-color: rgba(0, 0, 0, 0.5); /* Dims the background */
  height: 100vh; /* Adjust height as needed */
}

</style>
    <div class="hero-wrap js-fullheight">
	    <div class="home-slider owl-carousel js-fullheight">
	      <div class="slider-item js-fullheight" style="background-image:url(images/background.jpg);">
	      	<div class="overlay"></div>
	        <div class="container">
	          <div class="row no-gutters slider-text align-items-center">
		          <div class="col-md-7 ftco-animate">
		          	<div class="text w-100">
		          		<h2 style="font-size: 3rem; font-weight: bold;">Welcome to Fin's Body Factory</h2>
                        <p>.</p>
                        <h1 style="font-weight: bold;">Build Your Body and Fitness with Professional Touch</h1>
			            <?php if (!($member_status === 'active' || $_SESSION['level'] === 'Admin')): ?>
    <p><a href="#pricing" class="btn btn-primary">Join Us</a></p>
<?php endif; ?>
   
                  <?php if (!isset($_SESSION['username'])): ?>
                 <a href="register.php" class="btn btn-white">Sign up</a>
                    <?php endif; ?>
                  </div>
		          </div>
		        </div>
	        </div>
	      </div>

	  </div>

        <section id="pricing" class="ftco-section bg-light">    
            <div class="container">    
                <div class="row justify-content-center pb-5 mb-3">  
                <?php if (!($_SESSION['level'] === 'Admin' || $member_status === 'active')): ?>
    <div class="col-md-7 heading-section text-center ftco-animate">    
        <span class="subheading mb-3">Membership Pricelist</span>    
    </div>    
    <div class="row">   
        <div class="col-md-6 col-lg-3 ftco-animate">    
            <div class="block-7">    
                <div class="text-center">    
                    <h4 class="heading-2">Monthly Pass</h4>    
                    <span class="excerpt d-block">Monthly Pass</span>    
                    <span class="price"><sup>Rp</sup> <span class="number">300k</span></span>    
                    <a href="purchase.php" class="btn btn-primary px-4 py-3">Get Started</a>    
                </div>    
            </div>    
        </div>    

        <div class="col-md-6 col-lg-3 ftco-animate">    
            <div class="block-7">    
                <div class="text-center">    
                    <h4 class="heading-2">Standard</h4>    
                    <span class="excerpt d-block">3 Months</span>    
                    <span class="price"><sup>Rp</sup> <span class="number">800k</span></span>    
                    <a href="purchase.php" class="btn btn-primary px-4 py-3">Get Started</a>    
                </div>    
            </div>    
        </div>    

        <div class="col-md-6 col-lg-3 ftco-animate">    
            <div class="block-7">    
                <div class="text-center">    
                    <h4 class="heading-2">Premium</h4>    
                    <span class="excerpt d-block">6 Months + 1 Month Free</span>    
                    <span class="price"><sup>Rp</sup> <span class="number">1.35jt</span></span>    
                    <a href="purchase.php" class="btn btn-primary px-4 py-3">Get Started</a>    
                </div>    
            </div>    
        </div>    

        <div class="col-md-6 col-lg-3 ftco-animate">    
            <div class="block-7">    
                <div class="text-center">    
                    <h4 class="heading-2">Platinum</h4>    
                    <span class="excerpt d-block">12 Months + 2 Months Free + T-shirt</span>    
                    <span class="price"><sup>Rp</sup> <span class="number">2.6jt</span></span>    
                    <a href="purchase.php" class="btn btn-primary px-4 py-3">Get Started</a>    
                </div>    
            </div>    
        </div>    
    </div>
<?php endif; ?>

                </div>    
            </div>    
        </section>

    

    <section id="contact" class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="wrapper">
                        <div class="row no-gutters">
                            <div class="col-lg-8 col-md-7 order-md-last d-flex align-items-stretch">
                                <div class="contact-wrap w-100 p-md-5 p-4">
                                    <h3 class="mb-4">Our Location</h3>
                                    <div class="map-container">
                                        <!-- Embed Google Map -->
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15928.540088815716!2d98.66672426462173!3d3.556351115764614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3031315e57ef929d%3A0xe954f51374c7b4de!2sFin&#39;s%20Body%20Factory!5e0!3m2!1sid!2sid!4v1732954758937!5m2!1sid!2sid"
                                            width="600"
                                            height="450"
                                            style="border:0;"
                                            allowfullscreen=""
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade">
                                        </iframe>
                                    </div>
                                    <p class="mt-3">Find us at our location above or contact us for more information!</p>
                                </div>
                            </div>

                                    <div class="col-lg-4 col-md-5 d-flex align-items-stretch">
                                        <div class="info-wrap bg-primary w-100 p-md-5 p-4">
                                            <h3>Let's get in touch</h3>
                                            <p class="mb-4">We're open for any suggestion or just to have a chat</p>
                                    <div class="dbox w-100 d-flex align-items-start">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-map-marker"></span>
                                        </div>
                                        <div class="text pl-3">
                                        <p><span>Address:</span> Jl. Komp. CBD Polonia, Suka Damai, Kec. Medan Polonia, Kota Medan, Sumatera Utara</p>
                                      </div>
                                  </div>
                                    <div class="dbox w-100 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-phone"></span>
                                        </div>
                                        <div class="text pl-3">
                                        <p><span>Phone:</span> <a href="tel://1234567920">081234556529</a></p>
                                      </div>
                                  </div>
                                    <div class="dbox w-100 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-paper-plane"></span>
                                        </div>
                                        <div class="text pl-3">
                                            <p><span>finsbodyfactory@gmail.com</span></p>
                                        </div>
                                  </div>
                                  <div class="dbox w-100 d-flex align-items-center">
                                        <div class="icon d-flex align-items-center justify-content-center">
                                            <span class="fa fa-instagram"></span>
                                        </div>
                                        <div class="tect pl-3">
                                          <p><span><a href="https://www.instagram.com/finsbodyfactory/" target="_blank" rel="noopener noreferrer">@finsbodyfactory</a></span></p>
                                        </div>
                                    </div>
                              </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </section>

        <!-- Logout Confirmation Modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to logout?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="logout.php" class="btn btn-primary">Logout</a>
                    </div>
                </div>
            </div>
        </div>


    <footer class="footer">
				<div class="row">
					<div class="col-md-4 col-lg-5">
						<div class="row">
							<div class="col-md-12 col-lg-8 mb-md-0 mb-4">
								<h2 class="footer-heading"><a href="#" class="logo">Health<span>care</span></a></h2>
								<p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
								<a href="#">read more <span class="ion-ios-arrow-round-forward"></span></a>
							</div>
						</div>
					</div>
					<div class="col-md-8 col-lg-7">
						<div class="row">
							<div class="col-md-3 mb-md-0 mb-4 border-left">
								<h2 class="footer-heading">Services</h2>
								<ul class="list-unstyled">
		              <li><a href="#" class="py-1 d-block">Balance Body</a></li>
		              <li><a href="#" class="py-1 d-block">Physical Activity</a></li>
		              <li><a href="#" class="py-1 d-block">Fitness Program</a></li>
		              <li><a href="#" class="py-1 d-block">Healthy Food</a></li>
		            </ul>
							</div>
							<div class="col-md-3 mb-md-0 mb-4 border-left">
								<h2 class="footer-heading">About</h2>
								<ul class="list-unstyled">
		              <li><a href="#" class="py-1 d-block">Staff</a></li>
		              <li><a href="#" class="py-1 d-block">Team</a></li>
		              <li><a href="#" class="py-1 d-block">Careers</a></li>
		              <li><a href="#" class="py-1 d-block">Blog</a></li>
		            </ul>
							</div>
							<div class="col-md-3 mb-md-0 mb-4 border-left">
								<h2 class="footer-heading">Resources</h2>
								<ul class="list-unstyled">
		              <li><a href="#" class="py-1 d-block">Security</a></li>
		              <li><a href="#" class="py-1 d-block">Global</a></li>
		              <li><a href="#" class="py-1 d-block">Charts</a></li>
		              <li><a href="#" class="py-1 d-block">Privacy</a></li>
		            </ul>
							</div>
							<div class="col-md-3 mb-md-0 mb-4 border-left">
								<h2 class="footer-heading">Social</h2>
								<ul class="list-unstyled">
		              <li><a href="#" class="py-1 d-block">Facebook</a></li>
		              <li><a href="#" class="py-1 d-block">Twitter</a></li>
		              <li><a href="#" class="py-1 d-block">Instagram</a></li>
		              <li><a href="#" class="py-1 d-block">Google</a></li>
		            </ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-5">
          <div class="col-md-6 col-lg-8">

            <p class="copyright"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
          </div>
          <div class="col-md-6 col-lg-4 text-md-right">
          	<p class="mb-0 list-unstyled">
          		<a class="mr-md-3" href="#">Terms</a>
          		<a class="mr-md-3" href="#">Privacy</a>
          		<a class="mr-md-3" href="#">Compliances</a>
          	</p>
          </div>
        </div>
			</div>
		</footer>



  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

</body>
</html>
