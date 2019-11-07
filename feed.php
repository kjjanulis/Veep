<?php
// Initialize the session
session_start();
 // Include config file
require_once "config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){

    exit;
}

// Set Main Var
$newssql = "SELECT `id` , `title`, `post`, DATE_FORMAT(time, '%r %M %d %Y') FROM `news` ORDER BY `id`  DESC LIMIT 0 , 10";
$newsresult = mysqli_query($rlink, $newssql);

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
echo '<div class="info">';
echo '<h1>Veep News</h1></br><table id="newsfeed">';
if ($newsresult ->num_rows > 0) {
    // output data of each row
    while($newsrow = $newsresult ->fetch_assoc()) {
  echo '<tr>
<td><b><h2>'.$newsrow["title"].'</b></h2></br>'.$newsrow["post"].'</br></br>'.$newsrow["DATE_FORMAT(time, '%r %M %d %Y')"].'</td></tr>';
}
echo '</table></br>';
}
echo '</div><div class="btbuttons">';

if ($friendid == ''){
echo '<p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Veep</a>
    </p></div>';
}
include 'footer.php';
?> 
</body>
</html>