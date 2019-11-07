<?php
// Initialize the session
session_start();
 // Include config file
require_once "../config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

// Set Main Var
include '../mainvar.php';

// SQLs
$sql = "SELECT * FROM `items` ORDER BY id DESC";
$result = mysqli_query($link, $sql);

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
    <title>Veeple.Online - Admin</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../presetstyle.css?<?php echo $catch; ?>">
</head>
<body>
<div id="animatedBackground">
</div>
<?php
include '../header.php';
echo '<div class="full">';
?>
</br>
<button class="btn btn-primary" onclick="window.location.href = '../admin.php';">Back to Admin</button>
</br>
<?php
if($loglevel < 1){
die('<h1>No Access</h1></br><h3>Please contact an admin to request admin privileges</h3>');
} else {
if ($result ->num_rows > 0) {
    // output data of each row
    while($row = $result ->fetch_assoc()) {
$veepidvalue = value($row["veepid"]);
  echo '<div class="allveeps" style="height: 250px;"><img src="'.$row["image_link"].'" alt="'.$row["item_name"].'" style="height: 100px;"></br><b>'.$row["item_name"].'</br>Cost: '.$row["cost"].'</b></br>Item # '.$row["id"].'</br>';
if($row["health_mod"] != 0){
echo $row["health_mod"].' Health</br>';
}
if($row["hunger_mod"] != 0){
echo $row["hunger_mod"].' Hunger</br>';
}
if($row["hygiene_mod"] != 0){
echo $row["hygiene_mod"].' Hygiene</br>';
}
if($row["happiness_mod"] != 0){
echo $row["happiness_mod"].' Happiness</br>';
}
if($row["energy_mod"] != 0){
echo $row["energy_mod"].' Energy</br>';
}
if($row["durability"] != 1){
echo $row["durability"].' Uses</br>';
}
echo '</div>';
}
}
$feedsql = "SELECT * FROM `log` WHERE type = 'item' ORDER BY `id` DESC LIMIT 0 , 100";
$feedresult = mysqli_query($link, $feedsql);

echo '<h1>Market Activity</h1></br><table id="feed">';
if ($feedresult ->num_rows > 0) {
    // output data of each row
    while($feedrow = $feedresult ->fetch_assoc()) {
  echo '<tr>
<td>'.$feedrow["post"].'</td><td>'.$feedrow["time"].'</tr>';
}
}
echo '</table></br>';
}
if($debug == 1){
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
}
?>
<div class="footer">
<?php
echo $version;
?>
</div>
</div>  
</body>
</html>