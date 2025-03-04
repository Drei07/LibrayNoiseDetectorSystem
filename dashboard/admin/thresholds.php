<?php
include_once 'header.php';
require_once 'fetch.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
	<title>Threshold</title>
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
					<h1>Thresholds</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Thresholds</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-cog'></i> Configuration of Thresholds</h3>
					</div>
					<!-- BODY -->
					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/sensor-controller.php" method="POST" class="row gx-5 needs-validation" name="form" novalidate style="overflow: hidden;" >
								<div class="row gx-5 needs-validation">
									<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;">
										<i class='bx bxs-cog'></i> Device Configuration
									</label>
									<div class="col-md-6">
										<label for="mode" class="form-label">Max Sound <span>(Db) *</span></label>
										<select class="form-select form-control" name="maxSound" maxlength="6" autocomplete="off" id="maxSound" required>
											<option selected value="<?php echo $maxSound ?>"><?php echo $maxSound ?>db</option>
											<option value="60">60db</option>
											<option value="65">65db</option>
											<option value="70">70db</option>
											<option value="75">75db</option>
											<option value="80">80db</option>
											<option value="85">85db</option>
											<option value="90">90db</option>
											<option value="95">95db</option>
											<option value="100">100db</option>
											<option value="105">105db</option>
											<option value="110">110db</option>
											<option value="115">115db</option>
											<option value="120">120db</option>
										</select>
										<div class="invalid-feedback">
											Please select Max Sound.
										</div>
									</div>

									<div class="col-md-6">
										<label for="mode" class="form-label">Max Count <span>*</span></label>
										<select class="form-select form-control" name="maxCount" maxlength="6" autocomplete="off" id="maxCount" required>
											<option selected value="<?php echo $maxCount ?>"><?php echo $maxCount ?> Count</option>
											<option value="5">5 Count</option>
											<option value="10">10 Count</option>
											<option value="15">15 Count</option>
											<option value="20">20 Count</option>
											<option value="25">25 Count</option>
											<option value="30">30 Count</option>
											<option value="35">35 Count</option>
											<option value="40">40 Count</option>
											<option value="45">45 Count</option>
											<option value="55">55 Count</option>
											<option value="60">60 Count</option>
											<option value="65">65 Count</option>
											<option value="70">70 Count</option>
											<option value="75">75 Count</option>
											<option value="80">80 Count</option>
											<option value="85">85 Count</option>
											<option value="90">90 Count</option>
											<option value="95">95 Count</option>
											<option value="100">100 Count</option>
											<option value="105">105 Count</option>
											<option value="110">110 Count</option>
											<option value="115">115 Count</option>
											<option value="120">120 Count</option>
											<option value="125">125 Count</option>
											<option value="130">130 Count</option>
											<option value="135">135 Count</option>
											<option value="140">140 Count</option>
											<option value="145">145 Count</option>
											<option value="150">150 Count</option>
										</select>
										<div class="invalid-feedback">
											Please select Max Sound.
										</div>
									</div>

									<div class="col-md-6">
										<label for="mode" class="form-label">Reset Interval <span>(Seconds) *</span></label>
										<select class="form-select form-control" name="resetInterval" maxlength="6" autocomplete="off" id="resetInterval" required>
											<option selected value="<?php echo $resetInterval ?>"><?php echo ($resetInterval / 1000) . " seconds"; ?></option>
											<option value="5000">5 seconds</option>
											<option value="10000">10 seconds</option>
											<option value="15000">15 seconds</option>
											<option value="20000">20 seconds</option>
											<option value="25000">25 seconds</option>
											<option value="30000">30 seconds</option>
											<option value="35000">35 seconds</option>
											<option value="40000">40 seconds</option>
											<option value="45000">45 seconds</option>
											<option value="50000">50 seconds</option>
											<option value="55000">55 seconds</option>
											<option value="60000">60 seconds</option>
										</select>
										<div class="invalid-feedback">
											Please select Max Sound.
										</div>
									</div>

									<div class="col-md-6">
										<label for="mode" class="form-label">CoolDown Period <span>(Seconds) *</span></label>
										<select class="form-select form-control" name="cooldownPeriod" maxlength="6" autocomplete="off" id="cooldownPeriod" required>
											<option selected value="<?php echo $cooldownPeriod ?>"><?php echo ($cooldownPeriod  / 1000) . " seconds";  ?></option>
											<option value="5000">5 seconds</option>
											<option value="10000">10 seconds</option>
											<option value="15000">15 seconds</option>
											<option value="20000">20 seconds</option>
											<option value="25000">25 seconds</option>
											<option value="30000">30 seconds</option>
											<option value="35000">35 seconds</option>
											<option value="40000">40 seconds</option>
											<option value="45000">45 seconds</option>
											<option value="50000">50 seconds</option>
											<option value="55000">55 seconds</option>
											<option value="60000">60 seconds</option>
										</select>
										<div class="invalid-feedback">
											Please select Max Sound.
										</div>
									</div>
								</div>

								<div class="addBtn">
									<button type="submit" class="btn-success" name="btn-update-thresholds" id="btn-update">Set</button>
								</div>
							</form>
						</div>
					</section>
				</div>
			</div>
			</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>

</body>

</html>