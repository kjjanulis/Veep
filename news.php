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

// Set Main Var
$newssql = "SELECT `id` , `title`, `post`, DATE_FORMAT(time, '%r %M %d %Y') FROM `news` ORDER BY `id`  DESC LIMIT 0 , 5";
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
    <title>Veeple.Online - News</title>
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
	echo '<img src="https://veeple.online/Images/news.gif" alt="Veep Daily" class="headerlogo" style="margin-top: 15px;"></br>';
echo '</br><table id="newsfeed">';
if ($newsresult ->num_rows > 0) {
    // output data of each row
    while($newsrow = $newsresult ->fetch_assoc()) {
  echo '<tr>
<td><b><h2>'.$newsrow["title"].'</b></h2></br>'.$newsrow["post"].'</br></br>'.$newsrow["DATE_FORMAT(time, '%r %M %d %Y')"];
if($loglevel > 4){
?>
</br></br><img src="Images/editpost.png" alt="Edit Post" onclick="window.location.href = 'admin/newsedit.php?postid=<?php echo $newsrow["id"]; ?>'"/></br>
<?php
}
echo '</td></tr>';
}
echo '</table></br>';
}
echo '</div>';
include 'footer.php';
?> 
</body>
</html>