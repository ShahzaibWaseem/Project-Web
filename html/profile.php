<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
require '/usr/share/php/libphp-phpmailer/class.phpmailer.php';
require '/usr/share/php/libphp-phpmailer/class.smtp.php';

require 'config.php';
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
	header("location: login.php");
	exit;
}

$username = $_SESSION['username'];
$username = trim($username);

$res = mysqli_query($link, "SELECT * FROM users WHERE username = '".$username."';");
$res = mysqli_fetch_array($res);

// If upload button is clicked ...
if (isset($_POST['submit'])) {

	$msg = "";
	// Get image name
	$image = $_FILES['image']['name'];

	// image file directory
	$target = "../user_images/".basename($image);

	$firstName = mysqli_real_escape_string($link, $_POST["firstName"]);
	$lastName = mysqli_real_escape_string($link, $_POST["lastName"]);
	$age = $_POST["age"];
	$location = mysqli_real_escape_string($link, $_POST["location"]);
	$education = mysqli_real_escape_string($link, $_POST["education"]);
	$academic_transcript = mysqli_real_escape_string($link, $_POST["academic_transcript"]);
	$englishTest = mysqli_real_escape_string($link, $_POST["englishTest"]);
	$certificate_of_grad = mysqli_real_escape_string($link, $_POST["certificate_of_grad"]);
	$personal_statement = mysqli_real_escape_string($link, $_POST["personal_statement"]);
	$passport = mysqli_real_escape_string($link, $_POST["passport"]);
	$linkedin = mysqli_real_escape_string($link, $_POST["linkedin"]);
	$CV = mysqli_real_escape_string($link, $_POST["CV"]);
	$cover_letter = mysqli_real_escape_string($link, $_POST["cover_letter"]);
	$ref_letters = mysqli_real_escape_string($link, $_POST["ref_letters"]);
	$courses = mysqli_real_escape_string($link, $_POST["courses"]);

	$sql = "UPDATE users
	SET image = '".$image."',
	firstName = '".$firstName."',
	lastName = '".$lastName."',
	age = ".$age.",
	location = '".$location."',
	education = '".$education."',
	academic_transcript = '".$academic_transcript."',
	englishTest = '".$englishTest."',
	certificate_of_grad = '".$certificate_of_grad."',
	personal_statement = '".$personal_statement."',
	passport = '".$passport."',
	linkedin = '".$linkedin."',
	CV = '".$CV."',
	cover_letter = '".$cover_letter."',
	ref_letters = '".$ref_letters."',
	courses = '".$courses."'
	WHERE username = '".$username."';";

	// execute query
	$result = mysqli_query($link, $sql);

	if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
		$msg = "Image uploaded successfully";
	}else{
		$msg = "Failed to upload image";
	}

	//email
	$mail = new PHPMailer;
	$mail->setFrom('admin@example.com');
	$mail->addAddress($res["email"]);
	$mail->IsHTML(true);
	$mail->Subject = 'Message sent by PHPMailer';
	$mail->Body = 'Your Profile on <strong>Red Wao</strong> has been updated.<br><br><strong>Name : '.$firstName." ".$lastName.'</strong><br><strong>Age : '.$age.'</strong><br><strong>Prefered Location : '.$location.'</strong><br><strong>Education uptil now : '.$education.'</strong><br><strong>Marks in IELTS/TOEFL : '.$englishTest.'</strong><br><strong>Personal Statement: '.$personal_statement.'</strong><br><strong>Passport Number : '.$passport.'</strong><br><strong><a href="'.$linkedin.'">LinkedIn Account</a></strong><br><strong>Courses You have Taken : '.$courses.'</strong><br><br><br>login <a href="http://localhost/Web%20Project/html/login.php">here</a>';
	$mail->IsSMTP();
	$mail->SMTPSecure = 'tls';
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Port = 587;
	$mail->SMTPDebug = 0;

	//Set your existing gmail address as user name
	$mail->Username = 'sokulredwao@gmail.com';

    //Set the password of your gmail address here
	$mail->Password = 'waosokul';

	$mail->From = "sokulredwao@gmail.com";
	$mail->FromName = "Red Wao";


	if(!$mail->send()) {
		echo 'Email is not sent.';
		echo 'Email error: ' . $mail->ErrorInfo;
	} else {
		echo 'Email has been sent.';
	}
}

$result = mysqli_query($link, "SELECT * FROM users WHERE username = '".$username."';");
$result = mysqli_fetch_array($result);

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
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
		<div class="m-header scene_element scene_element--fadein">
            <div id="navbar" class="navbar">
                <div class="left" id="logo">RED Wao</div>
                <a class="left" href="welcome.php">Home</a>
                <a class="left" href="about.html">About</a>
                <a class="left" href="contact.html">Contact</a>
                <a class="right" id="user" href="logout.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>

                <a class="right" href="profile.php" style="padding: 5px 5px"><i class="fa fa-cog" style="font-size: 21px;"></i></a>

                <a class="right" href="notifications.php" style="padding: 5px 5px"><i class="fa fa-bell right" style="font-size: 21px;"></i></a>

                <a href="javascript:void(0);" style="font-size:15px;" class="icon right" onclick="myFunction()">&#9776;</a>
            </div>
        </div>

		<div class="m-right-panel m-page scene_element scene_element--fadeinright">
			<div class="profile">
				<div class="tab">
					<button class="tablinks" onclick="openCity(event, 'general')" id="defaultOpen">General</button>
					<button class="tablinks" onclick="openCity(event, 'universities')">Universities</button>
					<button class="tablinks" onclick="openCity(event, 'jobs')">Jobs</button>
				</div>
				<form method="POST" action="profile.php" enctype="multipart/form-data" class="profileForm">
					<div id="general" class="tabcontent">
						<h1 style="text-align: center;">General Settings</h1>
						<div class="row">
							<div class="image-upload col-2 col-m-12">

								<input type="hidden" name="size" value="1000000">
								<label for="image">
									<?php
									if (!isset($result['image']) || empty($result['image']) ) {
										echo "<img src='../img/PlaceholderImage.png' width='200px' style='margin-left: auto; margin-right: auto;display: block;'/>";
									}
									else {
										echo "<img src='../img/".$result['image']."' width='200px' style='margin-left: auto; margin-right: auto;display: block;border-radius: 50%;'/>";
									}
									?>

								</label><br>

								<input id="image" name="image" type="file" style="display:none"/>
							</div>
							<div class="col-1 col-m-2"></div>
							<div class="col-9 col-m-12">
								<label>First Name</label><br><input class = "firstName" id = "firstName" type = "text" name="firstName" value = "<?php echo $result["firstName"]; ?>" placeholder = "Insert First Name"/><br>
								<label>Last Name</label><br><input class = "lastName" id = "lastName" type = "text" name="lastName" value = "<?php echo $result["lastName"]; ?>" placeholder = "Insert Last Name"/><br>
								<label>How old are you?</label><br><input class = "age" id = "age" type = "number" name="age" value = "<?php echo $result["age"]; ?>" placeholder = "Insert Your Age"/><br>
								<label>Where do you live?</label><br><input class = "location" id = "location" type = "text" name="location" value = "<?php echo $result["location"]; ?>" placeholder = "Insert Your Location"/><br>
								<label>What is your Education?</label><br><input class = "education" id = "education" type = "text" name="education" value = "<?php echo $result["education"]; ?>" placeholder = "Insert Education"/><br>
							</div>
						</div>

					</div>

					<div id="universities" class="tabcontent row">
						<h1 style="text-align: center;">Universities</h1>
						<label>Academic Transcript</label><br><input class = "academic_transcript" id = "academic_transcript" type = "text" name="academic_transcript" value = "<?php echo $result["academic_transcript"]; ?>" placeholder = "Insert academic Transcript"/><br>
						<label>English Test</label><br><input class = "englishTest" id = "englishTest" type = "text" name="englishTest" value = "<?php echo $result["englishTest"]; ?>" placeholder = "Insert English Test"/><br>
						<label>Certificate of Graduation</label><br><input class = "certificate_of_grad" id = "certificate_of_grad" type = "text" name="certificate_of_grad" value = "<?php echo $result["certificate_of_grad"]; ?>" placeholder = "Insert certificate_of_grad"/><br>
						<label>Personal Statement</label><br><input class = "personal_statement" id = "personal_statement" type = "text" name="personal_statement" value = "<?php echo $result["personal_statement"]; ?>" placeholder = "Personal Statement"/><br>
						<label>Passport Number</label><br><input class = "passport" id = "passport" type = "text" name="passport" value = "<?php echo $result["passport"]; ?>" placeholder = "Insert Your Passport Number" maxlength="11"/><br>
					</div>

					<div id="jobs" class="tabcontent row">
						<h1 style="text-align: center;">Jobs / Internships</h1>
						<label>LinkedIn Page URL</label><br><input class = "linkedin" id = "linkedin" type = "url" name="linkedin" value = "<?php echo $result["linkedin"]; ?>" placeholder = "Insert Your LinkedIn Page URL"/><br>
						<label>Upload your CV here</label><br><input class = "CV" id = "CV" type = "text" name="CV" value = "<?php echo $result["CV"]; ?>" placeholder = "Insert CV"/><br>
						<label>Upload your Cover Letter here</label><br><input class = "cover_letter" id = "cover_letter" type = "text" name="cover_letter" value = "<?php echo $result["cover_letter"]; ?>" placeholder = "Insert cover_letter"/><br>
						<label>Upload your Reference Letters here</label><br><input class = "ref_letters" id = "ref_letters" type = "text" name="ref_letters" value = "<?php echo $result["ref_letters"]; ?>" placeholder = "Insert ref_letters"/><br>
						<label>What courses have you taken</label><br><input class = "courses" id = "courses" type = "text" name="courses" value = "<?php echo $result["courses"]; ?>" placeholder = "Insert courses"/><br>
					</div>
					<div style="text-align: center;">
						<input type="submit" name="submit" value="Save Changes">
					</div>

				</form>
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
		function openCity(evt, cityName) {
			var i, tabcontent, tablinks;
			tabcontent = document.getElementsByClassName("tabcontent");
			for (i = 0; i < tabcontent.length; i++) {
				tabcontent[i].style.display = "none";
			}
			tablinks = document.getElementsByClassName("tablinks");
			for (i = 0; i < tablinks.length; i++) {
				tablinks[i].className = tablinks[i].className.replace(" active", "");
			}
			document.getElementById(cityName).style.display = "block";
			evt.currentTarget.className += " active";
		}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
</body>
</html>