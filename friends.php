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
    <title>Veeple.Online - Friends</title>
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
//setup multi page data
$results_per_page = 21;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$pagesql = "SELECT COUNT(id) AS total FROM `friends` JOIN `veeps` JOIN `users` WHERE users.username = veeps.username AND (friends.friend_one = users.id OR friends.friend_two = users.id) AND status = 2 AND (friend_one = $loguser OR friend_two = $loguser) AND id != $loguser AND health > 0"; 
$pageresult = $rlink->query($pagesql);
$pagerow = $pageresult->fetch_assoc();
$total_pages = ceil($pagerow["total"] / $results_per_page);

// Set Main Var
$allveepssql = "SELECT * FROM `friends` JOIN `veeps` JOIN `users` WHERE users.username = veeps.username AND (friends.friend_one = users.id OR friends.friend_two = users.id) AND status = 2 AND (friend_one = $loguser OR friend_two = $loguser) AND id != $loguser AND health > 0 ORDER BY `users` . `username`  ASC LIMIT $start_from, ".$results_per_page;
$allveepsresult = mysqli_query($rlink, $allveepssql);

include 'header.php';
echo '<div class="info">';
if($ohealth == 0){
echo '<meta http-equiv="Refresh" content="0; url=home.php" />';
die("It looks like you don't have a veep!");
}
echo '<h1>You have '.$pagerow["total"].' Friends</h1></br>';
echo '<div class="stats">';
if ($allveepsresult->num_rows > 0) {
    // output data of each row
    while($row = $allveepsresult->fetch_assoc()) {
  echo '<a href="home.php?friendid='.$row["id"].'"><div class="allveeps">';
	if($row["hat"] !== ''){
		echo '<img src="https://veeple.online/Images/veeple/hats/'.$row["hat"].'/'.$row["veepid"].'.png" alt="'.$row["hat"].'" style="position: absolute;">';
	}
echo '<img src="https://veeple.online/Images/veeple/'.$row["veepid"].'/'.$row["veepid"].'.gif" alt="Veep"></br><b>'.$row["username"].'</b></br>';
if($row["veepname"] !== ''){
   echo $row["veepname"].'</a></div>';
} else {
echo 'Unnamed Veep</a></div>';
}
}
echo '</br>';
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='friends.php?page=".$i."'><div class='pagesnav'>".$i."</div></a>"; 
};
echo '</div>';
if($friendrequestnum > 0){
echo '</br></br>';
echo '<h3><b>'.$friendrequestnum.' Friend Request</b></h3></br>';
$getfriends = "SELECT id, username, friend_one, friend_two, status FROM `friends` JOIN `users` WHERE friends.friend_two = users.id AND friend_one=$loguser AND status = 1";
$friendsresult = mysqli_query($link, $getfriends);
if ($friendsresult->num_rows > 0) {
    // output data of each row
    while($friendsrow = $friendsresult->fetch_assoc()) {
echo '<b>'.$friendsrow["username"].' wants to be your friend </b><button class="addfriend" onclick="friendconfirm('.$friendsrow["friend_two"].')">Confirm</button><button class="cancelfriend" onclick="frienddeny('.$friendsrow["friend_two"].')">Deny</button></br>';
}
}
}
}
if($debug == 1){
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
}
echo '</div><div class="sidefeed">';
$feedsql = "SELECT post, max(`log` . `id` )as max_id FROM `friends` JOIN `log` JOIN `users` WHERE users . username = log . logger AND (friends . friend_one =users . id OR friends . friend_two =users . id ) AND status =2 AND (friend_one = $loguser OR friend_two = $loguser) AND `users` . `id` != $loguser AND type = 'action' OR type = 'game' OR type = 'post' GROUP BY `log` . `post` ORDER BY `max_id`  DESC LIMIT 0 , 10";
$feedresult = mysqli_query($link, $feedsql);

echo '<h2>Friend Feed</h2></br><table id="feed">';
if ($feedresult ->num_rows > 0) {
    // output data of each row
    while($feedrow = $feedresult ->fetch_assoc()) {
  echo '<tr>
<td>'.$feedrow["post"].'</td></tr>';
}
}
echo '</table></br>';
echo '</div>';
include 'footer.php';
?>
</body>
</html>