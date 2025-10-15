<?php
session_start();
if (isset($_SESSION['alert_msg'])) {
	$msg = $_SESSION['alert_msg'];
	echo "<script>alert('$msg')</script>";
	unset($_SESSION['alert_msg']);
}
?>
<!doctype html>
<html lang="en">

<head>
	<title>Login | OIT</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="wrap">
						<div class="login-wrap p-4 p-md-5">
							<div class="d-flex">
								<div class="w-100">
									<h3 class="mb-4">Sign In</h3>
								</div>
								<div class="w-100">
									<p class="social-media d-flex justify-content-end">
										<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
										<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
									</p>
								</div>
							</div>
							<p class="text-center text-danger text-sm"><?php
																		if (isset($_SESSION['p_msg'])) {
																			echo $_SESSION['p_msg'];
																			unset($_SESSION['p_msg']);
																		}
																		?></p>
							<form action="backend/login.php" method="post" class="signin-form">
								<div class="form-group mt-3 pb-2">
									<input type="text" class="form-control" name="uname" required>
									<label class="form-control-placeholder" for="username">Username</label>
								</div>
								<div class="form-group">
									<input id="password-field" name="pass" type="password" class="form-control" required>
									<label class="form-control-placeholder" for="password">Password</label>
									<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
								</div>
								<div class="form-group">
									<button type="submit" name="log" class="form-control btn btn-primary rounded submit px-3">Sign In</button>
								</div>
								<div class="form-group d-md-flex">
									<div class="w-50 text-left">
										<label class="checkbox-wrap mb-0" style="font-size: 13px;">Remember Me
											<input type="checkbox" checked>
											<span class="checkmark"></span>
										</label>
									</div>
									<div class="w-50 text-md-right" style="font-size: 13px;">
										<a href="#">Forgot Password</a>
									</div>
								</div>
							</form>
							<p class="text-center" style="font-size: 13px;">Not a member? <a href="register.php">Sign Up</a></p>
							<p class="text-center" style="font-size: 13px;">Admin? <a href="../admin/login/index.php">Login Here</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>

</body>

</html>