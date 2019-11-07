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
    <title>Veeple.Online - Veeps</title>
    <link rel="stylesheet" href="style.css?v=<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="presetstyle.css">
<script src="jquery-1.11.2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="javajava.js?<?php echo $catch; ?>" type="text/javascript"></script>
</head>
<body>
<div id="animatedBackground">
</div>
<?php
$id = $_SESSION["id"];
//setup multi page data
$results_per_page = 24;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$pagesql = "SELECT COUNT(id) AS total FROM `items`"; 
$pageresult = $rlink->query($pagesql);
$pagerow = $pageresult->fetch_assoc();
$total_pages = ceil($pagerow["total"] / $results_per_page);

// Set Main Var
$allitems = "SELECT * FROM `items` WHERE category != 'Award' ORDER BY rand(" . date("Ymdh") . ") LIMIT $start_from, ".$results_per_page;
$allitemsresult = mysqli_query($rlink, $allitems);

include 'header.php';
echo '<div class="full">';
if($ohealth == 0){
echo '<meta http-equiv="Refresh" content="0; url=home.php" />';
die("It looks like you don't have a veep!");
}
echo '<img src="https://veeple.online/Images/dolphingeneral.png" alt="Dolphin General" class="headerlogo"></br>';
echo '<div class="stats">';
if ($allitemsresult->num_rows > 0) {
    // output data of each row
    while($row = $allitemsresult->fetch_assoc()) {
$saleprice = floor($row["cost"]-($row["cost"]/10));
  echo '<div class="item" onclick="item('.$user.','.$row["id"].','.$saleprice.','.$row["durability"].','.$row["consumable"].')"><img src="'.$row["image_link"].'" alt="'.$row["item_name"].'"></br><b>'.$row["item_name"].'</b></br>';
   echo $saleprice.' Veep Coin';
echo '<span class="tooltiptext">';
if($row["description"] !== ''){
echo $row["description"].'</br>';
}
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
if($row["durability"] > 1){
echo $row["durability"].' Uses</br>';
}
echo '</span></div>';
}
echo '</br>';
/* for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='shop.php?page=".$i."'><div class='pagesnav'>".$i."</div></a>"; 
};*/
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