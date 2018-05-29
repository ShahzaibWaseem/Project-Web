<?php
require 'config.php';
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
    header("location: login.php");
    exit;
}
$get_all = mysqli_query($link, "SELECT * FROM users");

$username = $_SESSION['username'];
$username = trim($username);

$message = mysqli_real_escape_string($link, $_POST['message']);

$sentTo = "shafay";

$sender = mysqli_query($link, "SELECT * FROM users WHERE username = '".$username."';");
$sender = mysqli_fetch_array($sender);

$image = $sender['image'];

$reciever = mysqli_query($link, "SELECT * FROM users WHERE username = '".$sentTo."';");
$reciever = mysqli_fetch_array($reciever);

if (isset($_POST['usr']) && isset($_POST['submit'])) {
    $sql = "INSERT INTO messages (image, message, sender, sentTo) VALUES ('$image', '$message', '$username', '$sentTo')";
    mysqli_query($link, $sql);
}
$result = mysqli_query($link, "SELECT * FROM messages WHERE sender = ".$username." & sentTo =".$sentTo.";");

?>
<!DOCTYPE html>
<html>
<head>
    <title>index</title>
    <!-- ***** RWD : STEP 1 ***** -->
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../css/fonts.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <script src="../js/jquery.js"></script>

    <!-- Custom Code files -->
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="stylesheet" type="text/css" href="../css/animations.css">
    <link rel="stylesheet" href="../css/messaging.css">
    <script src="../js/scripts.js"></script>
    <script src="../js/animations.js"></script>
    <script src="../js/messaging.js"></script>
</head>
<body>
    <div class="m-scene" id="main">
        <div class="m-header scene_element scene_element--fadein">
            <div id="navbar" class="navbar">
                <div class="left" id="logo">RED Wao</div>
                <a class="left" href="index.html" style="background-color:gray;">Home</a>
                <a class="left" href="about.html">About</a>
                <a class="left" href="contact.html">Contact</a>
                <a class="right" id="user" href="logout.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>

                <a class="right" href="profile.php" style="padding: 5px 5px"><i class="fa fa-cog" style="font-size: 21px;"></i></a>

                <a class="right" href="notifications.php" style="padding: 5px 5px"><i class="fa fa-bell right" style="font-size: 21px;"></i></a>

                <a href="javascript:void(0);" style="font-size:15px;" class="icon right" onclick="myFunction()">&#9776;</a>
            </div>
        </div>

        <div class="m-page scene_element scene_element--fadeinup">
            <div class="row eventsSeminars">
                <div class="parallax">
                    <div class="textBlock">
                        <h1>Seminars</h1>
                        <p>Seminars are a great place to learn some really cool skills click here to get locations of some of the seminars which are taking place near you</p>
                        <a class="invertedButton" href="seminars.html">Seminars</a>
                    </div>
                </div>
            </div>
            <div class="row startups">
                <div class="parallax">
                    <div class="textBlock">
                        <h1>Startups</h1>
                        <p>Get some insight of some of the newly formed Start-Ups</p>
                        <a class="button" href="startups.html">Start-Ups</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="jobs col-6 col-m-12">
                    <div class="parallax">
                        <div class="textBlock">
                            <h1>Jobs</h1>
                            <p>Find Job Offerings at some of the Major Places in Pakistan &amp; abroad</p>
                            <a class="button" href="jobs.html">Find</a>
                        </div>
                    </div>
                </div>
                <div class="internships col-6 col-m-12">
                    <div class="parallax">
                        <div class="textBlock">
                            <h1>Internships</h1>
                            <p>Find Internships near you</p>
                            <a class="button" href="internships.html">Find</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row trendingCompanies">
                <div class="parallax">
                    <div class="textBlock">
                        <h1>Trending Companies</h1>
                        <p>Get the Ranking of latest Trending Companies by Clicking Here</p>
                        <a class="invertedButton" href="trendingCompanies.html">Trending</a>
                    </div>
                </div>
            </div>
            <div class="row techNews">
                <div class="parallax">
                    <div class="textBlock">
                        <h1>Tech News</h1>
                        <p>Keep posted with what's hot in the Tech World or Substrbe to our News-Letter to get emails weekly</p>
                        <a class="button" href="techNews.html">News</a>
                        <a class="button" href="#newsLetter">Subscribe</a>
                    </div>
                </div>
            </div>
            <div class="row universities">
                <div class="parallax">
                    <div class="textBlock">
                        <h1>Universities</h1>
                        <p>Keep posted about some of the Deadlines of some of the major universities in Pakistan &amp; abroad Applications and their Ranking</p>
                        <a class="button" href="universities.html">Universities</a>
                    </div>
                </div>
            </div>
            <div class="row alumni">
                <div class="parallax">
                    <div class="textBlock">
                        <h1>Alumni</h1>
                        <p>Get in touch with Alumni to get some insight about the future that you should expect out of your field</p>
                        <a class="invertedButton">Message Now</a>
                    </div>
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
        <div id="live-chat">

            <header class="clearfix">
                <a href="#" class="chat-close">x</a>
                <h4><?php echo "$sentTo";?></h4>
                <form name="selectuser">
                    <select name="usr">
                        <option></option>
                        <?php
                        while ($userx = mysqli_fetch_array($get_all)) {
                            echo "<option>".$userx['username']."</option>";
                            $sentTo = $userx['username'];
                        }

                        ?>
                    </select>
                </form>
            </header>

            <div class="chat">

                <div class="chat-history">

                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<div class='chat-message clearfix'>";
                        echo "<img src='../img/".$row['image']."' width='32' height='32'>";
                        echo "<div class='chat-message-content clearfix'><span class='chat-time'>".$row['time']."</span><h5>".$row['sender']."</h5><p>".$row['message']."</p></div>";
                        echo "</div><hr>";
                    }
                    ?>

                </div> <!-- end chat-history -->


                <form action="welcome.php" method="post">

                    <fieldset>

                        <input type="text" placeholder="Type your message…" name="message" autofocus>
                        <input type="submit" name="submit" style="display: none;">

                    </fieldset>

                </form>

            </div> <!-- end chat -->

        </div> <!-- end live-chat -->

    </div>
    <script>
        $username = $("#navbar a#user").html();
        $("#navbar a#user").hover(
            function(){
                $(this).html("Logout?");
            },
            function(){
                $(this).html($username);
            }
            );
        </script>
        <script>
            (function() {

                $('#live-chat header').on('click', function() {

                    $('.chat').slideToggle(300, 'swing');
                    $('.chat-message-counter').fadeToggle(300, 'swing');

                });

                $('.chat-close').on('click', function(e) {

                    e.preventDefault();
                    $('#live-chat').fadeOut(300);

                });

                $('.alumni a').on('click', function(e) {
                    e.preventDefault();
                    $('#live-chat').fadeIn(300);
                });

            }) ();
        </script>
    </body>
    </html>