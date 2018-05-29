<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
require '/usr/share/php/libphp-phpmailer/class.phpmailer.php';
require '/usr/share/php/libphp-phpmailer/class.smtp.php';
// Include config file
require 'config.php';

// Define variables and initialize with empty values
$email = $username = $password = $confirm_password = "";
$email_err = $username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"])) ){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate email
    if(empty(trim($_POST["email"])) ){
        $email_err = "Please enter an Email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                $email = trim($_POST["email"]);
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (email, username, password, privileges) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_username, $param_password, $param_privileges);
            $user = "Normal User";

            // Set parameters
            $param_email = $email;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_privileges = $user;



            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: welcome.php");

                //email
                $mail = new PHPMailer;
                $mail->setFrom('admin@example.com');
                $mail->addAddress($email);
                $mail->IsHTML(true);
                $mail->Subject = 'Message sent by PHPMailer';
                $mail->Body = 'You are Registered to <strong>Red Wao</strong><br><br><strong>Password = '.$password.'</strong><br><strong>As a '.$user.'</strong><br><br><br>login <a href="http://localhost/Web%20Project/html/login.php">here</a>';
                $mail->IsSMTP();
                $mail->SMTPSecure = 'tls';
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Port = 587;
                $mail->SMTPDebug = 4;

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
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}
?>

<?php
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;
                            $_SESSION['logged'] = true;
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $_SESSION['logged'] = false;
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <link rel="stylesheet" type="text/css" href="../css/animations.css">
    <script src="../js/jquery.js"></script>
    <script src="../js/scripts.js"></script>
    <script src="../js/animations.js"></script>
</head>
<body>
    <div class="container">
        <div class="m-scene" id="main">
            <div class="m-header scene_element scene_element--fadein">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="signUp">
                    <h3>Create Your Account</h3>
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">

                        <span class="help-block"><?php echo $email_err; ?></span>

                        <input type="email" name="email" class="w100 form-control" value="<?php echo $email; ?>" placeholder="Insert Email">
                    </div>
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">

                        <span class="help-block"><?php echo $username_err; ?></span>

                        <input type="text" name="username"class="w100 form-control" value="<?php echo $username; ?>" placeholder="Insert Username">
                    </div>
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <span class="help-block"><?php echo $password_err; ?></span>

                        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Insert Password">
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>

                        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Verify Password">
                    </div>
                    <div class="form-group">
                        <button class="form-btn sx log-in" type="button">Log In</button>

                        <button class="form-btn dx" type="submit">Sign Up</button>
                    </div>

                </form>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="signIn">
                    <h3>Welcome</br>Back !</h3>
                    &nbsp;
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <span class="help-block"><?php echo $username_err; ?></span>
                        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" placeholder="Insert username">
                    </div>
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                     <span class="help-block"><?php echo $password_err; ?></span>
                     <input type="password" name="password" class="form-control" placeholder="Insert Password">
                 </div>
                 <div class="form-group">
                    <button class="form-btn sx back" type="button">Back</button>
                    <button class="form-btn dx" type="submit" value="Login">Log In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(".log-in").click(function(){
        $(".signIn").addClass("active-dx");
        $(".signUp").addClass("inactive-sx");
        $(".signUp").removeClass("active-sx");
        $(".signIn").removeClass("inactive-dx");
    });

    $(".back").click(function(){
        $(".signUp").addClass("active-sx");
        $(".signIn").addClass("inactive-dx");
        $(".signIn").removeClass("active-dx");
        $(".signUp").removeClass("inactive-sx");
    });
</script>
</body>
</html>