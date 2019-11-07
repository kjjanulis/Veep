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
$search = $_GET['search'];
//setup multi page data
$results_per_page = 50;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
if($search != ''){
$pagesql = "SELECT COUNT(id) AS total FROM `users` WHERE (`username` LIKE '%".$search."%')"; 
} else {
$pagesql = "SELECT COUNT(id) AS total FROM `users`"; 
}
$pageresult = $rlink->query($pagesql);
$pagerow = $pageresult->fetch_assoc();
$total_pages = ceil($pagerow["total"] / $results_per_page);

if($search != ''){
$newssql = "SELECT `username` , `tokens` , `login` FROM `users` WHERE (`username` LIKE '%".$search."%') ORDER BY id DESC LIMIT $start_from, ".$results_per_page;
$newsresult = mysqli_query($rlink, $newssql);
} else {
$newssql = "SELECT `username` , `tokens` , `login` FROM `users` ORDER BY id DESC LIMIT $start_from, ".$results_per_page;
$newsresult = mysqli_query($rlink, $newssql);
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
<?php
if($loglevel < 0){
die('<h1>No Access</h1></br><h3>Please contact an admin to request admin privileges</h3>');
} else {
echo '<h1>User Database</h1></br>';
    echo '<form action="" method="GET">
        <input type="text" name="search" id="search"/>
        <input type="submit" value="Search" class="btn btn-primary" id=""/>
    </form>';
echo $pagerow["total"]." Users";
if($newsresult->num_rows > 0) {
echo '<table id="newsfeed">';
echo '<tr><th>Username</th><th>Veep Coin</th><th>Login</th></tr>';
    // output data of each row
    while($newsrow = $newsresult ->fetch_assoc()) {
$lastlogin = new DateTime($newsrow["login"]);
  echo '<tr>
<td><b>'.$newsrow["username"].'</b></td><td>'.floor($newsrow["tokens"]).'</td><td>'.$lastlogin->format( 'h:i  A m/d/Y' ).'</td></tr>';
}
echo '</table>';
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
echo '</div>';
?>
<div class="footer">
<?php
echo $version;
?>
</div>  
</body>
</html>