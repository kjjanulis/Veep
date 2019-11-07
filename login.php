<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
// Check for referral and promos
if(isset($_GET['promo']))
{
$_SESSION["promo"] = $_GET['promo'];
}
if(isset($_GET['refer']))
{
$_SESSION["refer"] = $_GET['refer'];
}

// Include config file
require_once "config.php";
$acc_err = $_SESSION["acc_err"];
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
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
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
$_SESSION["acc_err"] = $_SESSION["acc_err"] + 1;
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
$_SESSION["acc_err"] = $_SESSION["acc_err"] + 1;
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
$veepr = rand(1, $veepcount);
$veepl = rand(1, $veepcount);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-2120847936638816",
          enable_page_level_ads: true
     });
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-55212904-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-55212904-2');
</script>
    <meta charset="UTF-8">
        <meta property="og:image" content="https://veeple.online/Images/share.png">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
	<meta name="keywords" content="Veeple.Online
Veeple
Veep
Virtual Pet
Games
Online Games
Friends
Social Media
Pets
Pet
Cute
Neopets
Tamagotchi
Crypto
ETH Pets">
<meta name="description" content="Veep is a new site where you choose a creature and care for it. Win veep coin by playing games but make sure to take care of your veep and keep it happy! Veeps are worth veep coin as well based on how rare they become. Hang out with your friends and help them take care of their veep as well.
 Veep and the Veeple await you! ">
    <title>Veeple.Online</title>
    <link rel="stylesheet" href="style.css?<?php echo mt_rand(); ?>">
    <link rel="stylesheet" href="presetstyle.css?<?php echo mt_rand(); ?>">
</head>
<div id="animatedBackground">
</div>
<body>
<div class="top"><img src="https://veeple.online/Images/veep-online.png" alt="Veep Online" class="vologo"></div>
<div class="bio">
<?php
if($limited=='yes'){
	echo $limitedmsg;
}
$status = $_GET["status"];
if($status == ''){
echo "Veeple.Online is a new site where you choose a creature and care for it. Win veep coin by playing games but make sure to take care of your veep and keep it happy! Veeps are worth veep coin as well based on how rare they become. Hang out with your friends and help them take care of their veep as well.</br><b> Veep and the Veeple await you! </b></br>";
} elseif($status == 1) {
echo "<h3>Congratulations on your new Veep Account! </br>Please sign in below with your new login to get started.</h3>";
} elseif($status == 2){
echo "<h3>Your password has been sent to your email to be reset. Please check your email and click the link to make a new password.</h3>";
} elseif($status == 3){
echo "<h3>Your password has been reset. Please login with your new password.</h3>";
}
?>
</div>
<img src="https://mediakjcom.fatcow.com/Veep/Images/veeple/<?php echo $veepr; ?>/<?php echo $veepr; ?>.gif" alt="Veep" class="flimg">
<img src="https://mediakjcom.fatcow.com/Veep/Images/veeple/<?php echo $veepl; ?>/<?php echo $veepl; ?>.gif" alt="Veep" class="frimg">
    <div class="wrapper" style="background-color: #ccff99;margin-bottom: 80px;">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<?php if($acc_err <= 4){ ?>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login"> <?php } else { echo '<font color="red"><b>Too Many Failed Attemps. </br></br>Please reset your password.</br></br></b></font>';} ?>
<a href="register.php" class="btn btn-primary">Sign up</a>
</br></br>
<a href="forgotpass.php" class="btn btn-danger">Forgot Password</a></br>
<?php
if($acc_err >= 1 && $acc_err <= 4){
echo '<font color="red"><b>Attempt number '.$acc_err.' of 5</b></font>';
}
?>
            </div>
        </form>
    </div>
<div class="footer">
<?php
echo $version;
?>
</div>
</body>
<?php
if($limited != 'yes'){
	echo'
 <script language="javascript">
	 function getWidth() {
  return Math.max(
    document.body.scrollWidth,
    document.documentElement.scrollWidth,
    document.body.offsetWidth,
    document.documentElement.offsetWidth,
    document.documentElement.clientWidth
  );
}
if(getWidth()>=1400) {document.write("<style>body{zoom:120%;}</style>");}
</script>';
}
?>
</html>