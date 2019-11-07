<?php
$veepcount = 28; // How many veeps are there to pick from
$veepcoincut = 1.25; // Veeps cut on sales in %
// Set Debug to 1 to display internal info
$debug = 0;
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'mediakjcom.fatcowmysql.com');
define('DB_USERNAME', 'veeponline');
define('DB_PASSWORD', 'Its#40658');
define('DB_NAME', 'flashpoint');
define('DB_SERVERR', 'mediakjcom.fatcowmysql.com');
define('DB_USERNAMER', 'read');
define('DB_PASSWORDR', 'Its#40658');
define('DB_NAMER', 'flashpoint');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$rlink = mysqli_connect(DB_SERVERR, DB_USERNAMER, DB_PASSWORDR, DB_NAMER); 

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
$vsql = "SELECT * FROM `version` ORDER BY `version`.`id` DESC LIMIT 0 , 1";
$vresult = mysqli_query($link, $vsql);
$vrow = mysqli_fetch_assoc($vresult);
$versionnum = $vrow["version"];
$version = 'Version '.$versionnum.'</br>Kyle Janulis | James Tarrow | Nubiaanna Adkins | Ron Freeman | Linda Kendricks | Andrew Goralczyk | Adam Gonzalez';
$catch = $versionnum;
$numveep = rand(1,$veepcount);

	$ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false)) {
	$limitedmsg = '<h3>It seems, that your are using Internet Explorer which is not supported with Veeple.Online at this time.</br>
Why not try out
<a href="https://www.google.com/chrome/">Google Chrome</a>?</h3>You can still use veep but some features may not work.</br></br></br>';
	$limited = 'yes';
}
?>