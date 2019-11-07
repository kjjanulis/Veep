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

//setup multi page data
$results_per_page = 20;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;
$pagesql = "SELECT COUNT(id) AS total FROM `mail` WHERE direction='$logname'"; 
$pageresult = $rlink->query($pagesql);
$pagerow = $pageresult->fetch_assoc();
$total_pages = ceil($pagerow["total"] / $results_per_page);

// Mail
$allmailsql = "SELECT `id`, `subject`, `post`, `logger`, `unread`, `direction`, DATE_FORMAT(time, '%r') FROM `mail` WHERE direction='$logname' ORDER BY `id` DESC LIMIT $start_from, ".$results_per_page;
$allmailresult = mysqli_query($rlink, $allmailsql);

echo '<div class="full" style="text-align:center;">';
if($_GET["status"] == sent){
echo '<h3><b><font color="green">Message Sent</font></b></h3>';
}
echo '<h3>Veeple Inbox</h3>';
echo '<a href="sentmail.php">Go to Sent</a>';
if ($allmailresult ->num_rows > 0) {
    // output data of each row
    while($mailrow = $allmailresult ->fetch_assoc()) {
 if($mailrow["unread"] == 'true'){
  echo '<button class="collapsiblemail"><b>'.$mailrow["subject"].'</b></button>
<div class="contentmail">
<b>From: '.$mailrow["logger"].'</b></br>
  <p>'.$mailrow["post"].'</p>
</br>'?>
<img src="https://www.veeple.online/Images/reply.png" alt="Reply" class="nicons" onclick="window.location.href = 'sendmail.php?<?php echo "friend=".$mailrow["logger"]."&title=".$mailrow["subject"];?>';">
<?php
echo '</div>';
} else {
  echo '<button class="collapsiblemail">'.$mailrow["subject"].'</button>
<div class="contentmail">
<b>From: '.$mailrow["logger"].'</b></br>
  <p>'.$mailrow["post"].'</p>
</br>'?>
<img src="https://www.veeple.online/Images/reply.png" alt="Reply" class="nicons" onclick="window.location.href = 'sendmail.php?<?php echo "friend=".$mailrow["logger"]."&title=".$mailrow["subject"];?>';">
<?php
echo '</div>';
}
}
echo '</br>';
}
$mailupdatesql = "UPDATE `mail` SET unread='false' WHERE direction='$logname'";
mysqli_query($link, $mailupdatesql);
for ($i=1; $i<=$total_pages; $i++) { 
    echo "<a href='mail.php?page=".$i."'><div class='pagesnav'>".$i."</div></a>"; 
};
echo '</div>';
include 'footer.php';
?>
</div>  
</body>
<script>
var coll = document.getElementsByClassName("collapsiblemail");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>
</html>