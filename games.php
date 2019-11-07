<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
$notloggedin = 1;
} else {
$notloggedin = 0;
}

 // Include config file
require_once "config.php";
if($notloggedin == 0){
include 'mainvar.php';
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
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
    <title>Veeple.Online - Games</title>
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
//setup multi page data
if (isset($_GET["order"])) { $order  = $_GET["order"]; } else {$order = 'id';}
$results_per_page = 21;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$pagesql = "SELECT COUNT(id) AS total FROM games"; 
$pageresult = $rlink->query($pagesql);
$pagerow = $pageresult->fetch_assoc();
$total_pages = ceil($pagerow["total"] / $results_per_page);

// Set Main Var
	if($order == 'title'){
		$desc = 'ASC';
	} else
	{
		$desc = 'DESC';
	}
if($userage >= 13){
$gamessql = "SELECT * FROM games ORDER BY $order $desc LIMIT $start_from, ".$results_per_page;
} else {
$gamessql = "SELECT * FROM games WHERE rating != 'M' ORDER BY $order $desc LIMIT $start_from, ".$results_per_page;
}
$gameresult = mysqli_query($rlink, $gamessql);

if($notloggedin == 0){
include 'header.php';
} else {
include 'headernotlogged.php';
}

echo '<div class="mainpg">';
echo '<div class="info">';
	if($limited=='yes'){
	echo $limitedmsg;
}
if($notloggedin == 1){
echo "<a href='index.php'><h3>Create a Veeple.Online account to earn veep coin and be posted to score boards!</h3></a>";
}
echo '<img src="https://veeple.online/Images/games.gif" alt="Veep Games" class="headerlogo"></br>';
echo "
<b>Order By</b></br><a href='games.php?order=id'>Newest</a> | <a href='games.php?order=pop'>Popularity</a> | <a href='games.php?order=title'>Name</a>
</br>";
if ($gameresult ->num_rows > 0) {
    // output data of each row
    while($grow = $gameresult ->fetch_assoc()) {
  echo '<a href="play.php?gameid='.$grow["id"].'"><div class="games"><img src="'.$grow["loc"].'thumb.gif" alt="'.$grow["title"].'"></br><b>'.$grow["title"].'</b><span class="tooltiptext">'.$grow["description"].'</span></div></a>';
}
}
echo '</br>';
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='games.php?page=".$i."&order=".$order."'><div class='pagesnav'>".$i."</div></a>"; 
}
echo '</div><div class="sidefeed">';
include 'gamefeed.php';
echo '</div></div>';
include 'footer.php';
?>
</body>
</html>