<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";
 
$veepr = rand(1, $veepcount);
$veepl = rand(1, $veepcount);
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
    <link rel="stylesheet" href="style.css?<?php echo mt_rand(); ?>">
    <link rel="stylesheet" href="presetstyle.css?<?php echo mt_rand(); ?>">
</head>
<div id="animatedBackground">
</div>
<body>
<div class="top"><img src="https://veeple.online/Images/veep-online.png" alt="Veep Online" class="vologo"></div>
<?php
if($limited=='yes'){
	echo $limitedmsg;
}
?>
<img src="https://mediakjcom.fatcow.com/Veep/Images/veeple/<?php echo $veepr; ?>/<?php echo $veepr; ?>.gif" alt="Veep" class="flimg">
<img src="https://mediakjcom.fatcow.com/Veep/Images/veeple/<?php echo $veepl; ?>/<?php echo $veepl; ?>.gif" alt="Veep" class="frimg">
    <div class="info" style="width: 50%;">
		<h1>Veeple.Online</br>Error</h1></br></br>
	<p>If you continue to experience this error please report the issue to us here. <a href="reportissue.php" target="_blank">Report Issue</a></br></br>Keep in mind that Veeple.Online is constantly undergoing changes and updates so please check back soon.</p>
    </div>
<div class="footer">
<?php
echo $version;
?>
</div>
</body>
<?php
if($limited != 'yes'){
	echo'
 <script language="javascript">
	 function getWidth() {
  return Math.max(
    document.body.scrollWidth,
    document.documentElement.scrollWidth,
    document.body.offsetWidth,
    document.documentElement.offsetWidth,
    document.documentElement.clientWidth
  );
}
if(getWidth()>=1400) {document.write("<style>body{zoom:120%;}</style>");}
</script>';
}
?>
</html>