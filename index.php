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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-2120847936638816",
          enable_page_level_ads: true
     });
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-55212904-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-55212904-2');
</script>
<link rel="manifest" href="/manifest.json" />
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  var OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "9d293838-99c3-4125-8044-4dd64ad5db72",
    });
  });
</script>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> --!>
    <meta property="og:image" content="https://veeple.online/Images/share.png">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
	<meta name="keywords" content="Veeple.Online
Veeple
Veep
Virtual Pet
Games
Online Games
Friends
Social Media
Pets
Pet
Cute
Neopets
Tamagotchi
Crypto
ETH Pets">
<meta name="description" content="Veep is a new site where you choose a creature and care for it. Win veep coin by playing games but make sure to take care of your veep and keep it happy! Veeps are worth veep coin as well based on how rare they become. Hang out with your friends and help them take care of their veep as well.
 Veep and the Veeple await you! ">
    <title>Veeple.Online</title>
    <link rel="stylesheet" href="style.css?v<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="presetstyle.css">
</head>
<body>
<div id="animatedBackground">
</div>
<?php
// Include Main Varibles
include 'mainvar.php';

if($firstlog != 1){
// Stat Mods
$moder = 4000; // modifys the stats, the higher the number the slower it takes
$substat = $stataffect / $moder;

if($friendid == ''){
$tokens = $otokens + ($substat/2);
$uexperience = $oexperience + ($substat / 10000);
} else {
$tokens = $otokens + ($substat/4);
$uexperience = $oexperience + ($substat / 10000);
}
if($ohealth >= 100){

$health = 100;
} else {
$health = $ohealth;
}
if(($ohunger - ($substat*($hungermod))) <= 0){
$hunger = 0;
$health = $health + (($ohunger - ($substat*$hungermod))/5);
} else {
$hunger = ($ohunger - ($substat*($hungermod)));
}
if(($ohappiness - ($substat*($happinessmod))) <= 0){
$happiness = 0;
$health = $health + (($ohappiness - ($substat*($happinessmod)))/20);
} else {
$happiness = ($ohappiness - ($substat*($happinessmod)));
}
if(($ohygiene - ($substat*($hygienemod))) <= 0){
$hygiene = 0;
$health = $health + (($ohygiene - ($substat*($hygienemod)))/20);
} else {
$hygiene = ($ohygiene - ($substat*($hygienemod)));
}
if(($oenergy + (($substat * 10)*($energymod))) >= 100){
$energy = 100;
} else {
$energy = ($oenergy + (($substat * 10)*($energymod)));
}
} else {
$health = $ohealth;
$hunger = $ohunger;
$hygiene = $ohygiene;
$happiness = $ohappiness;
$energy = $oenergy;
}
//Check to see if still alive
if($health <= 0){
$status = 'dead';
} else {
$status = 'active';
}

// stat double check
if($happiness>= 100){
$happiness = 100;
}
if($happiness<= 0){
$happiness = 0;
}
if($hunger >= 100){
$hunger = 100;
}
if($hunger <= 0){
$hunger = 0;
}
if($hygiene >= 100){
$hygiene = 100;
}
if($hygiene <= 0){
$hygiene = 0;
}
if($energy >= 100){
$energy = 100;
}
if($energy <= 0){
$energy = 0;
}

// Set Veep
if($veepid == 0 && $status !== 'dead' && $friendid == ''){
echo '<meta http-equiv="Refresh" content="0; url=https://www.veeple.online/adoptioncenter.php" />';
}

// SQL's
$logsql = "UPDATE `users` JOIN `veeps` SET lastlog = NOW(), health = $health, hunger = $hunger, happiness = $happiness, hygiene = $hygiene, energy = $energy, veepid = $veepid WHERE users.username = veeps.username AND id='$user'";
$firstlogsql = "UPDATE users SET firstlog = 0, login = now() WHERE id='$loguser'";
$refirstlogsql = "UPDATE users SET firstlog = 1, login = now() WHERE id='$loguser'";
$tokensql = "UPDATE users SET tokens = $tokens WHERE id='$loguser'";
$xpupdatesql = "UPDATE `users` JOIN `veeps` SET experience = $uexperience WHERE users.username = veeps.username AND id='$loguser'";
$newuserpost = "INSERT INTO log (post, type, logger) VALUES (?,?,?)";
$addfirstfriend= "INSERT INTO friends (friend_one, friend_two, status) VALUES ($loguser,12,2)";
$adoptsql = "UPDATE `veeps` SET health=?, hunger=?, happiness=?, hygiene=?, energy=?, veepid=0, veepname=?, hungermod=?, happinessmod=?, hygienemod=?, experience = 0, energymod=?, veepbirth = NOW() WHERE username=?";
$newadoptsql = "INSERT INTO `veeps` (username, health, hunger, happiness, hygiene, energy, hungermod, happinessmod, hygienemod, energymod) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$veepsellsql = "UPDATE `aval_veeps` SET rare = rare - 1, owned = owned - 1 WHERE veepid = ?";
$diedsql = "UPDATE `users` JOIN `veeps` SET health = 0, experience = 0, veepid = 0, hunger = 0, hygiene = 0, happiness = 0, energy = 0, veepname = '' WHERE users.username = veeps.username AND id='$user'";

// Post Actions
if( $_POST['adoptveep'] ) {
	if($veepid != 21 && $veepid != 0){
$stmt = $link->prepare($veepsellsql);
$stmt->bind_param("i", $veepid);
$stmt->execute();
$stmt->close();
}
echo '<meta http-equiv="Refresh" content="0; url=https://www.veeple.online/adoptioncenter.php" />';
}

include 'header.php';
echo '<div class="mainpg">';
echo '<div class="info">';
if($status == 'dead' OR $health == 0){
include 'died.php';
} else {
include 'veep.php';
}
//include 'error.php';
echo '</div>';
echo '<div class="sidefeed">';
include 'sidefeed.php';
echo '</div>';
	echo '</div>';
include 'footer.php';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="javajava.js?v<?php echo $catch; ?>" type="text/javascript"></script>
</body>
</html>