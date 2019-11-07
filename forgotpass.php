<?php
// Initialize the session
session_start();
include("captcha/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
 // Include config file
require_once "config.php";
$email_err = '';
function getName($n) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    }  
    return $randomString; 
} 
if($_SERVER["REQUEST_METHOD"] == "POST"){
if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";     
    } else{
if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email = trim($_POST["email"]);
}
 else {
     $email_err = "Please enter a valid email.";
}
    }
if($email_err == ''){
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email= ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 0){
                    $email_err = "There are no users with that email.";
                } else{
        $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
}
    // Validate Captcha
     $code = $_POST['cap'];
    $cap = $_POST['code'];
    if(strcasecmp($code, $cap) != 0) {
      $cap_err = 'Captcha is wrong try again.';
  }
if($email_err == '' && $cap_err == ''){
		        // Prepare a select statement
        $userlookupsql = "SELECT username FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $userlookupsql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $email);
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 0){
					$usernamefound = 'User Not Found';
                } else {
mysqli_stmt_bind_result($stmt,$usernamefound);
mysqli_stmt_fetch($stmt);
}
                }
            }
        // Close statement
        mysqli_stmt_close($stmt);
$resetsql = "UPDATE users SET forgot_pass_identity=? WHERE email = ?";
$stmt = $link->prepare($resetsql);
$stmt->bind_param("ss", $temppass, $email);
$temppass = getName(32);
$stmt->execute();
              //send reset password email
                $to = $email;
                $subject = "Veeple.Online Password Update Request";
                $mailContent = 'Hello '.$usernamefound.', <br/><br/>It seems that you are having trouble logging into your Veep account. No worries, we are here to help! If you have forgotten your password, a link has been provided below to reset it. If you have received this message in error, please feel free to ignore this message.<br/> <br/>Your Username is '.$usernamefound.' <br/><a href="https://www.veeple.online/reset-password.php?key='.$temppass.'">To reset your password, Click here.</a><br/><br/><br/>If you have issues with the reset link please visit https://www.veeple.online/reset-password.php?key='.$temppass.'<br/><br/>
Your Veep awaits you!
                <br/></br>
                Veeple Online';
                //set content-type header for sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                //additional headers
                $headers .= 'From: Veeple Online <veep@veeple.online>' . "\r\n";
                //send email
                mail($to,$subject,$mailContent,$headers);
$stmt->close();
                header("location: login.php?status=2");
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
    <title>Veeple.Online - Password Reset</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="presetstyle.css?<?php echo $catch; ?>">
<script src="jquery-1.11.2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="javajava.js" type="text/javascript"></script>
</head>
<body>
<div id="animatedBackground">
</div>
<div class="top"><img src="https://veeple.online/Images/veep-online.png" alt="Veep Online" class="vologo"></div>
<div class="info">
        <h2>Password Reset</h2>
	<h4>Please enter the email on your account.
	</h4>
	<h5><b>Don't worry if you forgot your username we will send you that too.</b></h5>
	</br></br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
            <input name="code" type="hidden" id="code" value="<?php print ($_SESSION['captcha']['code']);?>">
            <?php
        echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';

        ?>
        </div>
        <div class="form-group">
        <input name="cap" type="text" required id="cap" size="20" maxlength="10" value="<?php echo $cap; ?>">
        <span class="help-block"><?php echo $cap_err; ?></span>
            </div>     
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Email Password Reset">
<a href="login.php" class="btn btn-danger">Cancel</a>
            </div>
        </form>
</div>
<?php
include 'footer.php';
?>
</div>  
</body>
</html>