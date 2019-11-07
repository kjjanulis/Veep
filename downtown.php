<?php
// Initialize the session
session_start();
 // Include config file
require_once "config.php";
include 'mainvar.php';
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
echo '<meta http-equiv="Refresh" content="0; url=https://www.veeple.online/login.php" />';
    exit;
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
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
    <title>Veeple.Online - Veeps</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="presetstyle.css">
<script src="jquery-1.11.2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="javajava.js?<?php echo $catch; ?>" type="text/javascript"></script>
</head>
<body>
<div id="animatedBackground">
</div>
<?php
$id = $_SESSION["id"];

include 'header.php';
echo '<div class="full">';
if($ohealth == 0){
echo '<meta http-equiv="Refresh" content="0; url=home.php" />';
die("It looks like you don't have a veep!");
}
echo '<div class="town">';
echo '<a href="/dolphingeneral.php"><img src="https://veeple.online/Images/veeptown/1.png" alt="Veep Town"></a>';
echo '<a href="/groommies.php"><img src="https://veeple.online/Images/veeptown/2.png" alt="Veep Town"></a>';
echo '<a href="/hotel.php"><img src="https://veeple.online/Images/veeptown/3.png" alt="Veep Town"></a>';
echo '<a href="/peggysplayhousetoyshop.php"><img src="https://veeple.online/Images/veeptown/4.png" alt="Veep Town"></a>';
echo '<a href="/fabiosfoodcourt.php"><img src="https://veeple.online/Images/veeptown/5.png" alt="Veep Town"></a>';
echo '</div>';

if($debug == 1){
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
}
echo '</div>';
include 'footer.php';
?>
</body>
</html>