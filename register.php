<?php
session_start();
include("captcha/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();

// Include config file
require_once "config.php";
$veepr = rand(1, $veepcount); 
$veepl = rand(1, $veepcount); 

// Define variables and initialize with empty values
$username = $email = $birth = $cap = $password = $promocode = $confirm_password = "";
$emailsub = 1;
$username_err = $email_err = $birth_err = $password_err = $promocode_err = $confirm_password_err = $cap_err = "";
$time = new DateTime('NOW');
$timelog = $time->format('YmdHi');
$filterwords = "(anal|anus|arse|ass|ass fuck|ass hole|assfucker|asshole|assshole|bastard|bitch|black cock|bloody hell|boong|cock|cockfucker|cocksuck|cocksucker|coon|coonnass|crap|cunt|cyberfuck|damn|dick|douche|erect|erection|erotic|fag|faggot|fuck|Fuck off|fuck you|fuckass|fuckhole|god damn|gook|homoerotic|hore|lesbian|lesbians|mother fucker|motherfuck|motherfucker|negro|nigger|orgasim|orgasm|penis|penisfucker|piss|piss off|porn|porno|pornography|pussy|retard|sadist|sex|sexy|shit|slut|son of a bitch|suck|tits|viagra|whore|thong)";

// Check for referral and promos
if(isset($_GET['promo']))
{
$_SESSION["promo"] = $_GET['promo'];
$promocode = $_GET['promo'];
}
if(isset($_GET['refer']))
{
$_SESSION["refer"] = $_GET['refer'];
$promocode = $_GET['refer'];
}
if(isset($_SESSION["promo"]))
{
$promocode = $_SESSION["promo"];
}
if(isset($_SESSION["refer"]))
{
$promocode = $_SESSION["refer"];
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } 
if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', trim($_POST["username"])))
{
    $username_err = "Please remove special characters from username.";
}
if (preg_match($filterwords, strtolower(trim($_POST["username"]))))
{
    $username_err = "Please remove explicit language from username.";
}
else{
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
                echo "Oops! Something went wrong with the username. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Validate email
     if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a valid email.";     
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
        $emailsql = "SELECT id FROM users WHERE email= ?";
        
        if($stmt = mysqli_prepare($link, $emailsql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) != 0){
                    $email_err = "This email is in use with another account. Please login or reset your password";
                } else{
        $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong with the email. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
}

// Check promo code if not blank  
    if(trim($_POST["promocode"]) !== ""){

        // Prepare a select statement
        $sql = "SELECT signupcoin FROM promos WHERE code = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_promocode);
            
            // Set parameters
            $param_promocode = strtolower(trim($_POST["promocode"]));
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 0){
                    $promocode_err = "This promo code dosn't exist, please try again.";
                } else {
mysqli_stmt_bind_result($stmt,$newtokens);
mysqli_stmt_fetch($stmt);
}
                }
            } else{
                echo "Oops! Something went wrong in the promo section. Please try again later.";
            }     
        // Close statement
        mysqli_stmt_close($stmt);
}  else {
$newtokens = 500;
}
    // Validate Captcha
     $code = $_POST['cap'];
    $cap = $_POST['code'];
    if(strcasecmp($code, $cap) != 0) {
      $cap_err = 'Captcha is wrong try again.';
  }

// Validate Date of Birth
if (empty(trim($_POST["birth"]))){
      // the user's date of birth cannot be a null string
      $birth_err = "Please enter your birthday.";
}
elseif (!ereg("^([0-9]{4})-([0-9]{2})-([0-9]{2})$", trim($_POST["birth"]), $parts)){
      // Check the format
      $birth_err = "Your birthday is not a valid date in the format YYYY-MM-DD";
}
elseif (!checkdate($parts[2],$parts[3],$parts[1])){
      $birth_err = "The date of birth is invalid. Please check that the month is between 1 and 12, and the day is valid for that month.";
}
elseif (intval($parts[1]) <= 1890){
      // Make sure that the user has a reasonable birth year
      $birth_err = "You must be alive to be able to take care of a Veeple.";
}
else {
$birth = trim($_POST["birth"]);
}

    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($promocode_err) && empty($cap_err) && empty($birth_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, firstlog, birth, tokens,emailsub) VALUES (?, ?, ?, 1, ?, ?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssii", $param_username, $param_email, $param_password, $param_birth, $newtokens, $param_emailsub);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_birth = $birth;
if($_POST['emailsub'] == 'yes'){
$param_emailsub = 1;
} else { $param_emailsub = 0;}
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
$addtoveep = "INSERT INTO veeps (username) VALUES ('$username')";
mysqli_query($link, $addtoveep);
              //send reset password email
                $to = $email;
                $subject = "Welcome to Veeple.Online!";
                $mailContent = '<b>'.$username.'</b> Welcome to Veeple.Online! <br/>Your new Veep awaits you. You may begin by logging into your account and visiting our adoption center to adopt your new Veep! Veep has many social and game features that we hope that you take advantage of. You may collect Veep Coins by playing games and you may use these on exclusive Veep gear for your pet! Remember to check in on your Veep periodically so that it does remain healthy and happy! Please note that Veep is new and we are always adding new features to the site daily so check in for new updates!<br/>
                <br/>
                Veeple Online';
                //set content-type header for sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                //additional headers
                $headers .= 'From: Veeple Online <veep@veeple.online>' . "\r\n";
                //send email
                mail($to,$subject,$mailContent,$headers);
                // Redirect to login page
                header("location: login.php?status=1");
            } else{
                echo "Something went wrong adding a veep. Please try again later."; 
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
<html lang="en">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-55212904-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-55212904-2');
</script>
    <meta charset="UTF-8">
    <title>Veeple.Online - Sign Up</title>
    <link rel="stylesheet" href="style.css?v=<?php echo mt_rand(); ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="presetstyle.css?<?php echo mt_rand(); ?>">
</head>
<body>
<div id="animatedBackground">
</div>
<div class="top"><img src="https://veeple.online/Images/veep-online.png" alt="Veep Online" class="vologo"></div>
<img src="https://mediakjcom.fatcow.com/Veep/Images/veeple/<?php echo $veepr; ?>/<?php echo $veepr; ?>.gif" alt="Veep" class="frimg">
<img src="https://mediakjcom.fatcow.com/Veep/Images/veeple/<?php echo $veepl; ?>/<?php echo $veepl; ?>.gif" alt="Veep" class="flimg">           
<div class="wrapper">
	<?php
	if($limited=='yes'){
	echo $limitedmsg;
}
	?>
<h2>Welcome to Veep!</h2>
        <p>Please fill this out to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" maxlength="25" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>        
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($birth_err)) ? 'has-error' : ''; ?>">
                <label>Birthday</label>
                <input name="birth" type="date" class="form-control" placeholder="yyyy-mm-dd" value="<?php echo $birth; ?>">
                <span class="help-block"><?php echo $birth_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($promocode_err)) ? 'has-error' : ''; ?>">
                <label>Promo Code</label>
                <input type="text" name="promocode" class="form-control" placeholder="If you do not have a promo code or a referal code leave this blank." value="<?php echo $promocode; ?>">
                <span class="help-block"><?php echo $promocode_err; ?></span>
            </div>
</br>
<div class="form-group">
<input type="checkbox" name="emailsub" value="yes" <?php if($emailsub == 1){ echo 'checked'; }?>/>
<label>  Subscribe to Veep Emails</label>
</div> 
</br>
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
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-primary" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>  
<?php
include 'footer.php';
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