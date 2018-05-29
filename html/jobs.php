<?php
require 'config.php';
// Initialize the session
session_start();

// If session variable is set
if(isset($_SESSION['username']) || !empty($_SESSION['username'])){
	$username = $_SESSION['username'];
	$username = trim($username);


	if (isset($_POST['submit'])) {
		$job_id = $_POST['job_id'];
		$sql = "INSERT INTO applied_jobs (job_id, username) VALUES ($job_id,'$username');";
		mysqli_query($link, $sql);
	}
	$applied = mysqli_query($link, "SELECT link, title, description, employer, date, salary, website  FROM jobs, applied_jobs WHERE jobs.id = applied_jobs.job_id AND applied_jobs.username = '".$username."';");
}
$result = mysqli_query($link, "SELECT * FROM jobs");


?>
<!DOCTYPE html>
<html>
<head>
	<title>Jobs</title>
	<!-- ***** RWD : STEP 1 ***** -->
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="../css/fonts.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
	<script src="../js/jquery.js"></script>

	<!-- Custom Code files -->
	<link rel="stylesheet" type="text/css" href="../css/styles.css">
	<link rel="stylesheet" type="text/css" href="../css/animations.css">
	<script src="../js/scripts.js"></script>
	<script src="../js/animations.js"></script>
</head>
<body>
	<div class="m-scene" id="main">
		<div class="m-aside scene_element scene_element--fadein">
			<div id="navbar" class="navbar">
				<div class="left" id="logo">RED Wao</div>
				<a class="left" href="index.html">Home</a>
				<a class="left" href="about.html">About</a>
				<a class="left" href="contact.html">Contact</a>

				<?php
				if(isset($_SESSION['username']) || !empty($_SESSION['username'])){
					echo "<a class='right' id='user' href='logout.php'>";
					echo htmlspecialchars($_SESSION['username']);
					echo "</a><a class='right' href='profile.php' style='padding: 5px 5px'><i class='fa fa-cog' style='font-size: 21px;'></i></a><a class='right' href='notifications.php' style='padding: 5px 5px'><i class='fa fa-bell right' style='font-size: 21px;''></i></a>";
				}
				else{
					echo "<a class='right' href='login.php'>Login</a><i class='fa fa-bell right' style='font-size: 24px;'></i><i class='fa fa-search right' style='font-size: 24px;'></i>";
				}
				?>

				<a href="javascript:void(0);" style="font-size:15px;" class="icon right" onclick="myFunction()">&#9776;</a>
			</div>
		</div>

		<div class="m-right-panel m-page scene_element scene_element--fadeinright">
			<div class="row jobContent">
				<h1>Jobs</h1>
				<div class="filter col-2 col-m-12">
					<h4>Advanced Search</h4>
						<input type="text" name="searchinternship" placeholder="Seach for Internships">
						<select class="employer">
							<option>EasyInsurance</option>
							<option>Alfaz Magazine Pakistan</option>
							<option>Technology Incubation Center</option>
							<option>Career Pakistan</option>
							<option>Interns Pakistan</option>
							<option>PrepareHOW</option>
							<option>Interact solutions</option>
							<option>Agentcy</option>
							<option>i-Docz Healthcare (SM-Pvt) Ltd</option>
							<option>IPS Academy Lahore</option>
							<option>Times IT Solutions</option>
							<option>Launchbox</option>
							<option>Viftech Solutions (Pvt.) Ltd.</option>
							<option>Darzi Online</option>
							<option>Ubbasoft</option>
							<option>Grappetite</option>
							<option>TOOLS HUB</option>
							<option>Wavetec</option>
							<option>Folio3</option>
							<option>Mochi Cordwainers</option>
							<option>NewsroomMedia</option>
							<option>BBS &amp; CO</option>
							<option>EasyInsurance</option>
							<option>Hashtag Services</option>
							<option>Synage Global</option>
							<option>Spur Solutions</option>
							<option>EvokeI Inc.</option>
							<option>Tag Services</option>
							<option>Technify</option>
							<option>Uniliver Pakistan</option>
							<option>Softiana Tech Limited</option>
							<option>Zong</option>
							<option>Aftab Associates PVT.Ltd</option>
							<option>Innovanza Solutions</option>
						</select>
						<br>
						<select class="city">
							<option>Islamabad</option>
							<option>Online</option>
							<option>Lahore</option>
							<option>Islamabad</option>
							<option>Karachi</option>
							<option>Pakistan</option>
						</select>
						<br>
					</form>
				</div>
				<div class="internshipAds col-10 col-m-12">

					<?php
					if (isset($_SESSION['username']) || !empty($_SESSION['username'])) {
						echo "<h2>Applied Jobs</h2>";
						while ($row = mysqli_fetch_array($applied)) {
							echo "<div class='jobAd'>
							<div class='jobHeader'>
							<div class='row'>
							<a href=" .$row["link"]. " style='text-decoration:none; color:white;'>
							<h4 class='col-9'>".$row["title"]."</h4>
							</a>
							</div>
							<div>
							<p>".$row["description"]."</p>
							</div>
							</div>

							<hr>

							<div class='jobFooter row'>
							<div class='col-3'><i class='fa fa-building'></i>".$row["employer"]."</div>
							<div class='col-3'><i class='fa fa-clock-o'></i>".$row["date"]."</div>
							<div class='col-3'><i class='fa fa-credit-card'></i>".$row["salary"]."</div>
							<div class='col-3'><i class='fa fa-file'></i>".$row["website"]."</div>
							</div>
							</div>";
						}
						echo "<h2>Jobs</h2>";
					}
					?>

					<?php
					$i = 1;
					while ($row = mysqli_fetch_array($result)) {
						echo "<div class='jobAd'>
						<form action='jobs.php' method='POST'>
						<input type='number' name='job_id' value=".$i." style='display:none;'/>
						<div class='jobHeader'>
						<div class='row'>
						<a href=" .$row["link"]. " style='text-decoration:none; color:white;'>
						<h4 class='col-9'>".$row["title"]."</h4>
						</a>
						<div>&nbsp;</div>
						<input class='col-2' type='submit' name='submit' value='Apply'/>
						</div>
						<div>
						<p>".$row["description"]."</p>
						</div>
						</div>

						<hr>

						<div class='jobFooter row'>
						<div class='col-3'><i class='fa fa-building'></i>".$row["employer"]."</div>
						<div class='col-3'><i class='fa fa-clock-o'></i>".$row["date"]."</div>
						<div class='col-3'><i class='fa fa-credit-card'></i>".$row["salary"]."</div>
						<div class='col-3'><i class='fa fa-file'></i>".$row["website"]."</div>
						</div>
						</div></form>";
						$i++;

					}
					?>
				</div>
			</div>
			<div class="footer">
				<div id="newsLetter">
					<h3>Subscribe to our News-Letter</h3>
					<p>We will send you emails Weekly to keep you posted on your Weekly Tech News</p>
					<input type="email" placeholder="someone@alias.com">
					<a class="button" href="#newsLetter">Subscribe</a>
				</div>
				<div class="footer-links row">
					<h2 align="center">Useful links to our Website</h2>
					<div style="float: left; width: 33%;">
						<ul>
							<li><a href="contact">Contact</a></li>
							<li><a href="about">About</a></li>
							<li><a href="archives">Archives</a></li>
						</ul>
					</div>
					<div style="float: left; width: 33%;">
						<ul>
							<li><a href="advertising">Advertise</a></li>
							<li><a href="workWithUs">Work with Us</a></li>
							<li><a href="license">License</a></li>
						</ul>
					</div>

					<div style="float: right; width: 33%;">
						<ul>
							<li><a href="random">Random</a></li>
							<li><a href="guest-posting">Guest Posting</a></li>
						</ul>
					</div>
				</div>
				<div class="copyrights">
					<p>
						&copy; Copyright 2018<br>All Rights Reserved
					</p>
				</div>
			</div>
		</div>
	</div>
	<script>
    $username = $("#navbar a#user").html();
    $("#navbar a#user").hover(
        function(){
            $(this).html("Logout?");
        },
        function(){
            $(this).html($username);
        });
    </script>
</body>
</html>