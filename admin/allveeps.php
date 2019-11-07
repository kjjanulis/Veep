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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<style>
.veepimg {
text-align: center;
float: left;
  padding: 20px;
 height: 200px;
  width: 200px;
position: relative;
overflow: hidden;
}
</style>
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
</br>
<?php
if($loglevel < 1){
die('<h1>No Access</h1></br><h3>Please contact an admin to request admin privileges</h3>');
} else {
echo '<h2>All veep types available</h2></br>';
$allveepsaval = "SELECT * FROM aval_veeps";
$allveepsavalresult = mysqli_query($link, $allveepsaval);
if ($allveepsavalresult ->num_rows > 0) {
    // output data of each row
    while($veepsrow = $allveepsavalresult->fetch_assoc()) {
echo '<img src="https://veeple.online/Images/veeple/'.$veepsrow["veepid"].'/'.$veepsrow["veepid"].'.gif" alt="Veep" class="veep" style="margin:10px 10px">';
}
}
}
echo '</div>';

if($debug == 1){
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
}
?>
<div class="footer">
<?php
echo $version;
?>
</div>
</div>  
</body>
</html>