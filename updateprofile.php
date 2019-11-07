<?php
// Initialize the session
session_start();
 // Include config file
require_once "config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
echo '<meta http-equiv="Refresh" content="0; url=https://www.veeple.online/login.php" />';
    exit;
}

// Set Main Var
include 'mainvar.php';
$wallet_err = $email_err = $bio_err = '';
if(isset($_GET['done']))
{
    $done = $_GET['done'];
} else {
    $done = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
    <title>Veeple.Online - Update Profile</title>
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
  <?php
include 'header.php';
// Post
if( $_POST['upinfo'] ) {
        $checkwalletsql = "SELECT * FROM users WHERE wallet = ? AND username != ?";
        
        if($stmt = mysqli_prepare($link, $checkwalletsql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_wallet, $username);
            
            // Set parameters
            $param_wallet = trim($_POST["wallet"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $wallet_err = "This wallet is already in use with another account.";
                } else{
                    $wallet = trim($_POST["wallet"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    
    // Validate bio
if (preg_match($filterwords, strtolower(trim($_POST["bio"]))))
{
    $bio_err = "Please remove explicit language from bio.";
}
    
    // Validate email
     if(empty(trim($_POST["email"]))){
        $email_err = "A valid email is required.";     
    } else{
if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email = trim($_POST["email"]);
}
 else {
     $email_err = "Please enter a valid email.";
}
    }
if($_POST['emailsub'] == 'yes'){
$param_emailsub = 1;
} else { $param_emailsub = 0;}
    
    // Check input errors before inserting in database
    if(empty($wallet_err) && empty($email_err) && empty($bio_err)){
        
        // Prepare an insert statement
        $updateusersql = "UPDATE users SET email=?, gender=?, country=?, wallet=?, bio=?, emailsub = ? WHERE username=?";
         
        if($stmt = mysqli_prepare($link, $updateusersql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssis", $param_email, $param_gender, $param_country, $param_wallet, $param_bio, $param_emailsub, $param_username);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_gender = $_POST["gender"];
            $param_country = $_POST["country"];
            $param_wallet = $wallet;
            $param_bio = $_POST["bio"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                ?>
<meta http-equiv="refresh" content="0;URL='updateprofile.php?done=1' />
<?php
            } else{
                echo "Something went wrong. Please try again later."; 
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>
<div class="full">
<?php
if($friendid != ''){
    die('You can not edit the info of a friend.');
}
if($done == 1){
    echo '<h3><b><font color="green">Profile Updated</font></b></h3>';
}
?>
<h2>Edit User Profile</h2></br>
<div class="editinfol">
        <form action="" name="upinfo" method="post">
          <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Username</label>
              <input name="username" type="text" disabled class="form-control" value="<?php echo $username; ?>">
              <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>        
            <div class="form-group <?php echo (!empty($birth_err)) ? 'has-error' : ''; ?>">
                <label>Birthday</label>
                <input name="birth" type="text" disabled="disabled" class="form-control" placeholder="mm/dd/yyyy" value="<?php echo $birthday; ?>">
                <span class="help-block"><?php echo $birth_err; ?></span>
            </div>   
                        <div class="form-group <?php echo (!empty($gender_err)) ? 'has-error' : ''; ?>">
                <label>Gender</label>
                <select name="gender" class="form-control">
                  <option value="" <?php if (!(strcmp("", $gender))) {echo "selected=\"selected\"";} ?>></option>
                  <option value="Male" <?php if (!(strcmp("Male", $gender))) {echo "selected=\"selected\"";} ?>>Male</option>
                  <option value="Female" <?php if (!(strcmp("Female", $gender))) {echo "selected=\"selected\"";} ?>>Female</option>
                  <option value="Other" <?php if (!(strcmp("Other", $gender))) {echo "selected=\"selected\"";} ?>>Other</option>
                </select>
                <span class="help-block"><?php echo $gender_err; ?></span>
</div>
</div>
<div class="editinfor">
                     <div class="form-group <?php echo (!empty($country_err)) ? 'has-error' : ''; ?>">
                <label>Country</label>
                <select name="country" class="form-control">
<?php

foreach($countrylist as $key => $value) {

?>
<option value="<?= $key ?>" title="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($value) ?></option>
<?php

}

?>
</select>
</div>
                        <div class="form-group <?php echo (!empty($wallet_err)) ? 'has-error' : ''; ?>">
                <label>Cryptocurrency Wallet (Public BITCOIN Key)</label>
                <input name="wallet" type="text" class="form-control" value="<?php echo $wallet; ?>" maxlength="50">
                <span class="help-block"><?php echo $wallet_err; ?></span>
</br><b>Veep Coin: </b><?php echo floor($otokens); ?></br><b>SATS Value: </b><?php echo btc($otokens); ?></br>1 SAT = 0.00000001 ฿
</div>
</div>
<div class="full">
          <div class="form-group <?php echo (!empty($bio_err)) ? 'has-error' : ''; ?>">
            <label>User Bio</label>
            <textarea name="bio" type="text" class="form-control" size="500" maxlength="500" rows="8" cols="10" wrap="soft"><?php echo $userbio; ?></textarea>
            <span class="help-block"><?php echo $bio_err; ?></span>
            </div> 
<div class="form-group">
<input type="checkbox" name="emailsub" value="yes" <?php if($emailsub == 1){ echo 'checked'; }?>/>
<label>  Subscribe to Veep Emails</label>
</div> 
            <div class="form-group">
                <input type="submit" name="upinfo" class="btn btn-primary" value="Update">
            </div>
        </form> 
        <div class="btbuttons"><p>
			<button class="btn btn-warning" onclick="window.location.href = 'reset-password.php';">Reset Your Password</button>
<button class="btn btn-danger" onclick="window.location.href = 'logout.php';">Sign Out of Veep</button>
    </p></div>
</div>
</div>
<?php
include 'footer.php';
?>
</div>  
</body>
</html>