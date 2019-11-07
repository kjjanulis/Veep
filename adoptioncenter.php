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
    <title>Veeple.Online - Adoption Center</title>
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
$id = $_SESSION["id"];
$totveepsaval = 0;

include 'header.php';
echo '<div class="info">';
echo '<img src="https://veeple.online/Images/adoptioncenter.gif" alt="Veep Adoption Center" class="vologo"></br>';
if($veepid != 0){
die("<h3>You already have a veep,</h3></br>Currently veeple.online only supports one veep at a time.");
}
echo '<br>Which veep would you like to adopt?</b><br>';
echo '<div class="stats" style="background-color: #9c7538; margin-top: -155; padding-top: 120;">';
while($totveepsaval <=5){
$avalveepssql = "SELECT * FROM `aval_veeps`";
$avresult = mysqli_query($link, $avalveepssql);
if ($avresult ->num_rows > 0) {
    // output data of each row
    while($avrow = $avresult ->fetch_assoc()) {
$howrare = ceil($avrow["rare"]/15);
$show = rand(1,$howrare);
if($show ==1){
$totveepsaval++;
	$avalvid = $avrow["veepid"];
$vtcofveep = value($avalvid);
if($vtcofveep <= $otokens){
echo '<div class="adoptveeps" onclick="adopt('.$avrow["veepid"].','.$vtcofveep.')"><img src="https://veeple.online/Images/veeple/'.$avalvid.'/'.$avalvid.'.gif" alt="Veep"><span class="tooltiptext">Veep Coin Cost</br>'.$vtcofveep.'</span></div>';
} else {
echo '<div class="dadoptveeps"><img src="https://veeple.online/Images/veeple/'.$avalvid.'/'.$avalvid.'.gif" alt="Veep"><span class="tooltiptext">Not enough Veep Coin</br>'.$vtcofveep.'</span></div>';
}
}
}
}
}
if($debug == 1){
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
}
echo '</div></div>';
include 'footer.php';
?>
</body>
</html>