<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
$resetkey = $_GET["key"];
if($resetkey == ''){
$resetkey = $_POST["key"];
}
if($resetkey == ''){
echo '<meta http-equiv="Refresh" content="0; url=https://www.veeple.online/login.php" />';
    exit;
} else {
$keysql = "SELECT username, forgot_pass_identity FROM users WHERE forgot_pass_identity = '$resetkey'";
$keyresult = mysqli_query($rlink, $keysql);
$keyrow = mysqli_fetch_assoc($keyresult);
$id = $keyrow["username"];
}
} else {
$id = $_SESSION["username"];
}
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $idsql = "UPDATE users SET password = ?, forgot_pass_identity = ? WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $idsql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_password, $wipekey, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $id;
$wipekey = '';
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php?status=3");
                exit();
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Veeple.Online - Password Reset</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="presetstyle.css?<?php echo $catch; ?>">
</head>
<body>
<div id="animatedBackground">
</div>
<?php
include 'header.php';
?>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please enter a new password for username<b> <?php echo $id; ?></b></p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
 <input type="hidden" id="key" name="key" value="<?php echo $resetkey; ?>">
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>