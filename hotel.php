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
if($_POST['days']) {
$amount = $_POST["days"];
$payamount = 50 * $amount;
if($otokens > $payamount){
$daycareset= "UPDATE `users` JOIN `veeps` SET hunger = 100, happiness = 100, hygiene = 100, energy = 100, daycare = TIMESTAMPADD(DAY,$amount,NOW()) WHERE users.username = veeps.username AND id='$loguser'";
mysqli_query($link, $daycareset);
$paysql = "UPDATE users SET tokens = tokens - $payamount WHERE id='$loguser'";
mysqli_query($link, $paysql);
echo '<meta http-equiv="Refresh" content="0; url=https://www.veeple.online/home.php" />';
} else {
echo '<meta http-equiv="Refresh" content="0; url=https://www.veeple.online/daycare.php?status=ne" />';
}
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
    <title>Veeple.Online - Daycare</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="presetstyle.css?<?php echo $catch; ?>">
<script src="jquery-1.11.2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="javajava.js?v=<?php echo $catch; ?>" type="text/javascript"></script>
</head>
<body>
<div id="animatedBackground">
</div>
<?php
if($ohealth == 0){
echo '<meta http-equiv="Refresh" content="0; url=home.php" />';
die("It looks like you don't have a veep!");
}
?>
<?php
include 'header.php';
echo '<div class="info" style="background-image: url(https://veeple.online/Images/hotel.gif); padding-top: 250px;"><div class="whitetext">';
if($_GET["status"] == 'ne'){
echo '<h3><font color="red">Not enough tokens</font></h3>';
}
//echo '<img src="https://veeple.online/Images/hotel.gif" alt="Veep Hotel" class="vologo"></br>';
if($daycare == 'yes'){
        echo '<h1>Hi, <b>' . htmlspecialchars($_SESSION["username"]) . '</b>. Welcome back,</br></h1><p style="font-size:20px">your veep is still in daycare!</br></br>Relax, kickback, and enjoy yourself knowing your veep is being well cared for.</p></br></br></br></br><b>Your veep will return from daycare at</br>'.$daycareout.'</b>';
die();
}
	?>
	<form id="daycare" name="daycare" method="post">
	  <p>
	    <label for="days">How many days would you like your veep to stay?<br><b>50 Veep Coin a Day
        </label></br>
	    <select name="days" id="days" form="daycare">
	      <option value="1" selected="selected">1</option>
	      <option value="2">2</option>
	      <option value="3">3</option>
	      <option value="4">4</option>
	      <option value="5">5</option>
	      <option value="6">6</option>
	      <option value="7">7</option>
	      <option value="8">8</option>
	      <option value="9">9</option>
	      <option value="10">10</option>
	      <option value="11">11</option>
	      <option value="12">12</option>
	      <option value="13">13</option>
	      <option value="14">14</option>
        </select>
      </p>
</b>
	  <p>
	    <input name="submit" type="submit" id="submit" form="daycare" value="Check Veep In" class="btn btn-primary">
	  </p>
</form>
	</div>
<?php
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