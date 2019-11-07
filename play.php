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
$gameid = $_GET['gameid'];
$gametitle = '';
$scoring = 0;
$gameurl = '';
$getgamesql = "SELECT `title`, `loc`, `scoring` FROM `games`  WHERE id=?";
if($stmt = mysqli_prepare($rlink, $getgamesql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $gameid);
            
            // Set parameters
            $gameid = $_GET['gameid'];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 0){
                    $promocode_err = "This game dosn't exist, please try again.";
                } else {
mysqli_stmt_bind_result($stmt,$gametitle,$gameurl,$scoring);
mysqli_stmt_fetch($stmt);
}
                }
            } else{
                echo "Oops! Something went wrong in the promo section. Please try again later.";
            }     
        // Close statement
        mysqli_stmt_close($stmt);

// SQL's
if($scoring == 1){
$scoressql = "SELECT * FROM `highscores`  WHERE game='$gametitle' ORDER BY `score` DESC LIMIT 0 , 3";
$scoresresult = mysqli_query($rlink, $scoressql);
}
$popupdate = "UPDATE `games`  SET pop= pop + 1 WHERE id='$gameid'";
mysqli_query($link, $popupdate);
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
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
    <title>Veeple.Online - Games</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="presetstyle.css?<?php echo $catch; ?>">
    <link rel="stylesheet" href="style.css?v=<?php echo $catch; ?>">
<script src="jquery-1.11.2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="javajava.js" type="text/javascript"></script>
</head>
<body>
<div id="animatedBackground">
</div>
<?php
if($notloggedin == 0){
include 'header.php';
} else {
include 'headernotlogged.php';
}
?>
<div class="full">
<iframe src="<?php echo $gameurl; ?>/index.html" width="75%" height="75%" style="border:none;"></iframe>
</br>
<?php
if($scoring == 1){
echo '<h2><b>High Scores</b></h2>';
if ($scoresresult ->num_rows > 0) {
    // output data of each row
    while($scoresrow = $scoresresult ->fetch_assoc()) {
echo '<b>'.$scoresrow["user"].':</b>  '.$scoresrow ["score"].'</br>';
}
}
}
?>
</br></br>
</div>
<?php
include 'footer.php';
?>
</div>  
</body>
</html>