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
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
    <title>Veeple.Online - Admin</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="presetstyle.css?<?php echo $catch; ?>">
</head>
<body>
<div id="animatedBackground">
</div>
<?php
include 'header.php';
echo '<div class="full">';
?>
	<h1>Report Issue</h1>
<?php
// Post
if( $_POST['newspost'] ) {   
        // Prepare an insert statement
        $postnewssql = "INSERT INTO reports (type,details,user) VALUES (?,?,?)";
         
        if($stmt = mysqli_prepare($link, $postnewssql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_title, $param_post,$logname);
            
            // Set parameters
            $param_title = trim($_POST["title"]);
            $param_post = trim($_POST["post"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
             //send reset password email
                $to = 'kylejanulis@gmail.com';
                $subject = "Veeple.Online Issue";
                $mailContent = '<b>'.$username.'</b> Reported an issue with Veeple.Online! <br/>
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
                ?>
<meta http-equiv="refresh" content="0;URL='https://www.veeple.online' />
<?php
            } else{
                echo "Something went wrong. Please try again later."; 
            }
		}

        // Close statement
        mysqli_stmt_close($stmt);
        
    // Close connection
    mysqli_close($link);
}
?>
<form id="newspost" name="newspost" action="" method="post">
<div class="form-group">
  <p>
    <label for="title">Type of issue:</label>
  </p>
  <p>
    <select name="title" required="required" id="title">
      <option value="Account" selected="selected">User Account</option>
      <option value="Veep">Veep</option>
      <option value="Game">Game</option>
      <option value="Idea">Idea</option>
      <option value="Payment">Payment/Billing</option>
      <option value="Other">Other</option>
    </select>
  </p>
  </div>
  <p>
<div class="form-group">
  <p>
          <label for="post">Issue Details:
    </label>
    </br>
  </p>
  <p>
    <textarea name="post" cols="96" rows="15" required="required" wrap="soft" class="form-control" id="post"></textarea>
  </div>
  </p>
  <p>
<div class="form-group">
    <input type="submit" name="newspost" id="newspost" value="Post" class="btn btn-primary">
</div>
  </p>
</form>

	<?php
if($debug == 1){
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
}
echo '</div>';
?>
<div class="footer">
<?php
echo $version;
?>
</div>
</div>  
</body>
</html>