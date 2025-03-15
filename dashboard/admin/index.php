<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
	<title>Dashboard</title>
</head>

<body>

	<!-- Loader -->
	<div class="loader"></div>

	<!-- SIDEBAR -->
	<?php echo $sidebar->getSideBar(); ?> <!-- This will render the sidebar -->
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu'></i>
			<form action="#">
				<div class="form-input">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<div class="username">
				<span>Hello, <label for=""><?php echo $user_fname ?></label></span>
			</div>
			<a href="profile" class="profile" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Profile">
				<img src="../../src/img/<?php echo $user_profile ?>">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Dashboard</a>
						</li>
					</ul>
				</div>
			</div>

			</div>
			<ul class="dashboard_data">
				<div class="gauge_dashboard">
					<div class="status" >
						<div class="card arduino" id="soundSensor1Card">
							<h1>SOUND SENSOR 1 STATUS</h1>
							<div class="sensor-data">
								<span id="soundDevice1">Loading...</span>
								<p id="soundStatus1"></p>
							</div>
						</div>
					</div>
					<div class="status">
						<div class="card arduino" id="soundSensor2Card">
							<h1>SOUND SENSOR 2 STATUS</h1>
							<div class="sensor-data">
								<span id="soundDevice2">Loading...</span>
								<p id="soundStatus2"></p>
							</div>
						</div>
					</div>
					<div class="status">
						<div class="card arduino" id="soundSensor3Card">
							<h1>SOUND SENSOR 3 STATUS</h1>
							<div class="sensor-data">
								<span id="soundDevice3">Loading...</span>
								<p id="soundStatus3"></p>
							</div>
						</div>
					</div>
					<div class="gauge">
						<div class="card gauge_card">
							<p class="card-title">SOUND SENSOR 1 VALUE</p>
							<div id="SoundSensorDevice1"></div>
						</div>
						<div class="card gauge_card">
							<p class="card-title">SOUND SENSOR 2 VALUE</p>
							<div id="SoundSensorDevice2"></div>
						</div>
						<div class="card gauge_card">
							<p class="card-title">SOUND SENSOR 3 VALUE</p>
							<div id="SoundSensorDevice3"></div>
						</div>
					</div>
				</div>
			</ul>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
	<script src="../../src/js/gauge.js"></script>
</body>

</html>