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
$search = $_GET['search'];
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
    <link rel="stylesheet" href="presetstyle.css?<?php echo $catch; ?>">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="javajava.js" type="text/javascript"></script>
</head>
<body>
<div id="animatedBackground">
</div>
<?php
$id = $_SESSION["id"];
//setup multi page data
$results_per_page = 35;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
if($search != ''){
$pagesql = "SELECT COUNT(id) AS total FROM `users` JOIN `veeps` WHERE users.username = veeps.username AND id != '$id' AND health > 1 AND firstlog = 0 AND veepid > 0 AND (users.username LIKE '%".$search."%') OR (`veepname` LIKE '%".$search."%')"; 
} else {
$pagesql = "SELECT COUNT(id) AS total FROM `users` JOIN `veeps` WHERE users.username = veeps.username AND id != '$id' AND health > 1 AND firstlog = 0 AND veepid > 0"; 
}
$pageresult = $rlink->query($pagesql);
$pagerow = $pageresult->fetch_assoc();
$total_pages = ceil($pagerow["total"] / $results_per_page);

// Set Main Var
if($search != ''){
$allveepssql = "SELECT * FROM `users` JOIN `veeps` WHERE users.username = veeps.username AND id != '$id' AND health > 1 AND firstlog = 0 AND veepid > 0 AND (users.username LIKE '%".$search."%') OR (`veepname` LIKE '%".$search."%') ORDER BY `veeps` . `veepbirth`  DESC LIMIT $start_from, ".$results_per_page;
} else {
$allveepssql = "SELECT * FROM `users` JOIN `veeps` WHERE users.username = veeps.username AND id != '$id' AND health > 1 AND firstlog = 0 AND veepid > 0 ORDER BY `veeps` . `veepbirth`  DESC LIMIT $start_from, ".$results_per_page;
}
$allveepsresult = mysqli_query($rlink, $allveepssql);

include 'header.php';
echo '<div class="full">';
if($ohealth == 0){
echo '<meta http-equiv="Refresh" content="0; url=home.php" />';
die("It looks like you don't have a veep!");
}
echo '<img src="https://veeple.online/Images/population.gif" alt="Veep Population" class="headerlogo"></br>';
    echo '<form action="" method="GET">
        <input type="text" name="search" id="search"/>
        <input type="submit" value="Search" class="btn btn-primary" id=""/>
    </form>';
echo '<div class="stats">';
if ($allveepsresult->num_rows > 0) {
    // output data of each row
    while($row = $allveepsresult->fetch_assoc()) {
  echo '<a href="index.php?friendid='.$row["id"].'"><div class="allveeps">';
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
    echo "<a href='users.php?page=".$i."&search=".$search."'><div class='pagesnav'>".$i."</div></a>"; 
};
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