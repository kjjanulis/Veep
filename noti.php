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
echo '<div class="full" style="text-align:center;">';
if($friendrequestnum > 0){
echo '<div class="editinfol"  style="text-align:center;">';
}
echo '<h3><b>'.$onoticount.' New Notifications</b></h3></br><table id="noti">';
if ($allnotiresult ->num_rows > 0) {
    // output data of each row
    while($notirow = $allnotiresult ->fetch_assoc()) {
if($notirow["unread"] == 'true'){
  echo '<tr>
<td><b>'.$notirow["post"].'</b></td></tr>';
} else {
  echo '<tr>
<td>'.$notirow["post"].'</td></tr>';
}
}
echo '</table></br>';
}
$notiupdatesql = "UPDATE `log` SET unread='false' WHERE direction='$logname' AND unread='true'";
mysqli_query($link, $notiupdatesql);
echo '</div>';
if($friendrequestnum > 0){
echo '<div class="editinfor"  style="text-align:center;">';
echo '<h3><b>'.$friendrequestnum.' Friend Request</b></h3></br>';
$getfriends = "SELECT id, username, friend_one, friend_two, status FROM `friends` JOIN `users` WHERE friends.friend_two = users.id AND friend_one=$loguser AND status = 1";
$friendsresult = mysqli_query($link, $getfriends);
if ($friendsresult->num_rows > 0) {
    // output data of each row
    while($friendsrow = $friendsresult->fetch_assoc()) {
echo '<b>'.$friendsrow["username"].' wants to be your friend </b><button class="addfriend" onclick="friendconfirm('.$friendsrow["friend_two"].')">Confirm</button><button class="cancelfriend" onclick="frienddeny('.$friendsrow["friend_two"].')">Deny</button></br>';
}
}
echo '</div>';
}
echo '</div>';
include 'footer.php';
?>
</div>  
</body>
</html>