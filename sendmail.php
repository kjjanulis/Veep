<?php
// Initialize the session
session_start();
 // Include config file
require_once "config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Set Main Var
include 'mainvar.php';
$direct = $_GET["friend"];
$title = $_GET["title"];

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
	<h1><?php if($title !== ''){echo "Reply to";}else{echo "New Message to";}?> <?php echo $direct; ?></h1>
<?php
// Post
if( $_POST['newspost'] ) {   
	        // Prepare a select statement
        $emailsql = "SELECT emailsub, email FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $emailsql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $direct);
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 0){
					$emailsub = 0;
                } else {
mysqli_stmt_bind_result($stmt,$emailsub, $sendtoemail);
mysqli_stmt_fetch($stmt);
}
                }
            }
	if($emailsub == 1){
if($title == ''){
            $param_title = trim($_POST["title"]);
} else {
$param_title = trim($title);
}
		            //send a notification email
                $to = $sendtoemail;
                $subject = "New Veeple.Online Mail!";
                $mailContent = '<b>Hi '.$direct.', You have new mail from '.$logname.' on Veeple.Online!</b><br/><br/><b>'.trim($_POST["title"]).'</b><br/>'.trim($_POST["post"]).'<br/><br/>To respond please login on Veeple.Online<br/><br/>To unsubscribe from emails please login and edit your profile.<br/>
                <br/>
                Veeple Online';
                //set content-type header for sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                //additional headers
                $headers .= 'From: Veeple Online <veep@veeple.online>' . "\r\n";
                //send email
                mail($to,$subject,$mailContent,$headers);
	}
        // Close statement
        mysqli_stmt_close($stmt);
        // Prepare an insert statement
        $postnewssql = "INSERT INTO mail (subject,post,logger,direction) VALUES (?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $postnewssql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_title, $param_post,$logname,$direct);
            
            // Set parameters
if($title == ''){
            $param_title = trim($_POST["title"]);
} else {
$param_title = trim($title);
}
            $param_post = trim($_POST["post"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                ?>
<meta http-equiv="refresh" content="0;URL='https://www.veeple.online/mail.php?status=sent' />
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
	<div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
  <p>
    <label for="title">Subject:</label>
  </p>
  <p>
    <input name="title" type="text" id="title" size="100" maxlength="255" required="required" value="<?php echo $title; ?>" class="form-control" <?php if($title !== ''){echo "disabled";} ?>>
  </p>
		<span class="help-block"><?php echo $title_err; ?></span>
	</div>
  <p>
	  <div class="form-group <?php echo (!empty($post_err)) ? 'has-error' : ''; ?>">
    <label for="post">Message:</br>
    </label>
    <textarea name="post" cols="96" rows="15" required="required" wrap="soft" class="form-control" id="post"></textarea>
		  <span class="help-block"><?php echo $post_err; ?></span>
	</div>
  </p>
  <p>
	  <div class="form-group">
    <input type="submit" name="newspost" id="newspost" value="Send" class="btn btn-primary">
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
<script src="jquery-1.11.2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="javajava.js" type="text/javascript"></script>
</html>