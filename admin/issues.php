<?php
// Initialize the session
session_start();
 // Include config file
require_once "../config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

// Set Main Var
include '../mainvar.php';

// Set Main Var
$newssql = "SELECT `type` , `details` , `user` , `time` FROM `reports` WHERE fixed = 0 ORDER BY time DESC";
$newsresult = mysqli_query($link, $newssql);
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
    <link rel="stylesheet" href="../style.css?v=<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../presetstyle.css?<?php echo $catch; ?>">
</head>
<body>
<div id="animatedBackground">
</div>
<?php
include '../header.php';
echo '<div class="full">';
?>
</br>
<button class="btn btn-primary" onclick="window.location.href = '../admin.php';">Back to Admin</button>
<?php
if($loglevel < 1){
die('<h1>No Access</h1></br><h3>Please contact an admin to request admin privileges</h3>');
} else {
echo '<h1>Reported Issues</h1></br>';
if($newsresult->num_rows > 0) {
echo '<table id="newsfeed">';
    // output data of each row
    while($newsrow = $newsresult ->fetch_assoc()) {
  echo '<tr>
<td><b>'.$newsrow["type"].'</b></br>'.$newsrow["details"].'</br></br>Reported By: '.$newsrow["user"].'</br>'.$newsrow["time"].'</br>';
echo '</td></tr>';
}
echo '</table></br>';
} else {
echo '<h3>Yay, We have no issues reported!</h3>';
}
}
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
</body>
</html>