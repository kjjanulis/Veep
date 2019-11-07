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
    <title>Veep</title>
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
echo '<div class="full">';
if($loglevel < 1){
die('<h1>No Access</h1></br><h3>Please contact an admin to request admin privileges</h3>');
} else {
echo '<h1>Welcome Admin '.$logname.'</h1></br>';
}
?>
<button class="btn btn-primary" onclick="window.location.href = 'admin/allveeps.php';">View All Veep Types</button>
<button class="btn btn-primary" onclick="window.location.href = 'admin/veepecon.php';">Veep Market</button>
<button class="btn btn-primary" onclick="window.location.href = 'admin/newspost.php';">Post News</button>
<button class="btn btn-primary" onclick="window.location.href = 'admin/issues.php';">Reported Issues</button>
</br>
<button class="btn btn-primary" onclick="window.location.href = 'admin/users.php';">User Database</button>
<button class="btn btn-primary" onclick="window.location.href = 'admin/log.php';">The Log</button>
<button class="btn btn-primary" onclick="window.location.href = 'admin/items.php';">Item Market</button>
</br></br>
<?php
  $url = 'https://api.coinmarketcap.com/v1/ticker/bitcoin/?convert=USD';
  $data = file_get_contents($url);
  $priceInfo = json_decode($data);
$bitcoinprice = $priceInfo[0]->price_usd;
function getBalance($address) {
    return file_get_contents('https://blockchain.info/de/q/addressbalance/'. $address);
}
$bitcoinbalance = (getBalance('17oJ6QhBgTVsg8apAzFHNX5TvMpyiVzieK'))/100000000;
$btcconvert = ($bitcoinprice*$bitcoinbalance);
$veepcointobtc = number_format((float)($bitcoinbalance/1000000), 10, '.', '');
$veepcoinvalue = ($veepcointobtc*$bitcoinprice);
$veepwalletvalue = number_format((float)$btcconvert, 2, '.', '');
  echo "</br></br><h2>Bitcoin Market</h2><h3>Bitcoin Price: <b>$".number_format((float)$bitcoinprice, 2, '.', ',')."</b></br>Veep SATS: <b>".number_format($bitcoinbalance*100000000)."</b></br>Veep Wallet Value: <b>$".$veepwalletvalue."</b></br>VC to SATS: <b>".number_format((float)($veepcointobtc*100000000), 2, '.', '')."</b></br>Veep Coin Value: <b>$". number_format((float)$veepcoinvalue, 7, '.', '')."</b></h3>";
?>
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