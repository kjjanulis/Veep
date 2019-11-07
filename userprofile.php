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
require_once('mainvar.php');
require_once('trophies.php');

// SQL's
$veepnameset = "UPDATE `users` JOIN `veeps` SET veepname = ? WHERE users.username = veeps.username AND id='$user'";

// Posts
if( $_POST['veepname'] ) {
$vname_err = '';
if(empty(trim($_POST["vname"]))){
        $vname_err = "Please enter a name for your veep.";     
    }
if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', trim($_POST["vname"])))
{
    $vname_err = "Please remove special characters from name.";
}
if (preg_match($filterwords, strtolower(trim($_POST["vname"]))))
{
    $vname_err = "Your veep does not like that name.";
}
if(empty($vname_err)){
$stmt = $link->prepare($veepnameset);
$stmt->bind_param("s", $veepns);
$veepns = trim($_POST["vname"]);
$stmt->execute();
$stmt->close();
 echo "<meta http-equiv='refresh' content='0'>";
}
}
if( $_POST['veeprename'] ) {
$stmt = $link->prepare($veepnameset);
$stmt->bind_param("s", $veepns);
$veepns = '';
$stmt->execute();
$stmt->close();
 echo "<meta http-equiv='refresh' content='0'>";
}
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
?>
<div class="full">
<h2><?php echo $username."'s Veeple Profile"; ?></h2>
<?php
if($friendid != ''){
$getfriendstatus = "SELECT * FROM friends WHERE (friend_one=$user OR friend_two=$user) AND (friend_one=$loguser OR friend_two=$loguser)";
$friendstatusresult = mysqli_query($link, $getfriendstatus);
$friendstatusrow =  mysqli_fetch_assoc($friendstatusresult);
$friendstatus = $friendstatusrow['status'];
if($friendstatus == 2)
{
?>
<button class="btn btn-primary"  onclick="window.location.href = 'sendmail.php?friend=<?php echo $username;?>';">Send Message</button>
<?php
echo '<button class="btn btn-danger"" onclick="friend(2)">Remove Friend</button></br></br>';
} elseif ($friendstatus == 1){
echo '<button class="btn btn-warning" onclick="friend(2)">Cancel Friend Request</button></br></br>';
}
 else {
echo '<button class="btn btn-green" onclick="friend(1)">Add as Friend</button></br></br>';
}
}
?>
<div class="full">
<div class="editinfol" style="text-align:center;background-color: #d2e8fd;border-radius: 20px;padding: 10px;">
<?php
if($veepname == ''  && $friendid == ''){
echo '<form id="veepname" action="" method="post">
<input type="text" name="vname" id="vninput"/></br>
        <input type="submit" name="veepname" maxlength="20" value="Name my veep" class="btn btn-primary"  />
<span class="help-block">'.$vname_err.'</span>
    </form>';
}
if($friendid == '' && $veepname !== '') {
echo '</br><form id="veeprename" action="" method="post">
        <input type="submit" name="veeprename" value="Rename '.$veepname.'" class="btn btn-primary" id="veeprename" />
    </form>';
} elseif ($friendid !== '') {
echo '<h4><b>'.$veepname.'</h4></b>';
}

$xpbar = ($experience-floor($experience))*100;
echo '</br><b>Level '.$level.'</b></br><div class="w3-light-grey" style="border-radius: 6px;background-color: #d2d2d2!important;max-width: 300px;margin: auto;"><div id="healthbar" class="w3-green" style="border-radius: 6px;height:20px;width:'.$xpbar.'%"></div></div>';

// Find Value of Veep
if($level >= 2){
$levelmod = $level - 1;
} else {
$levelmod = 0;
}
$oveepcoinvalue = floor(value($veepid)/$veepcoincut);
$veepcoinvalue = $oveepcoinvalue + ($levelmod * 100);
	if($hat !== ''){
		echo '<img src="https://veeple.online/Images/veeple/hats/'.$hat.'/'.$veepid.'.png" alt="Veep Hat" class="overlayveep">';
	}
echo '<img src="https://veeple.online/Images/veeple/'.$veepid.'/'.$veepid.'.gif" alt="Veep" class="veep"></br>';
if($friendid == ''){
echo '<b>Veep Value</b></br>'.$veepcoinvalue.'</br>';
 echo '<button class="btn btn-danger" onclick="veeptocoin('.$veepcoinvalue.')">Sell Veep</button>';
}
?>
</div>
<div class="editinfor" style="background-color: #d2e8fd;border-radius: 20px;padding: 10px;">
                        <div class="form-group">
                <label>Country</label>
<?php echo '</br>'.$country; ?>
</div>
                        <div class="form-group">
                <label>Gender</label>
<?php echo '</br>'.$gender; ?>
</div>
          <div class="form-group">
            <label>User Bio</label>
            <?php echo '</br>'.$userbio; ?>
            </div>  
</div>
</div>
<?php
if($friendid != ''){
$allveepssql = "SELECT* FROM `friends` JOIN `veeps` JOIN `users` WHERE users.username = veeps.username AND (friends.friend_one = users.id OR friends.friend_two = users.id) AND status = 2 AND (friend_one = $friendid OR friend_two = $friendid) AND health > 0 AND id != $friendid ORDER BY RAND() LIMIT 0 , 6";
$allveepsresult = mysqli_query($link, $allveepssql);
echo '<div class="full" style="background-color: #d2e8fd;border-radius: 20px;margin-bottom: 20px;">';
echo "<h3>".$username."'s Top Friends</h3>";
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
}
echo '</div>';
}
echo '<div class="full" style="background-color: #d2e8fd;border-radius: 20px;margin-bottom: 20px;">';
echo '<h3>Awards</h3>';
include 'itemInterface.php';
$itemMapper = new ItemMapper();
$userInventory = $itemMapper->getInventory($user);
foreach($userInventory as $key => $item) {
if($item->category == 'Award'){
    echo '<div class="item" style="height: 150px;"><img src="'.$item->imageLink.'" alt="'.$item->name.'"></br><b>'.$item->name.'</b>';
echo '<span class="tooltiptext">';
if($item->description !== ''){
echo $item->description.'</br>';
}
if($item->health != 0){
echo $item->health.' Health</br>';
}
if($item->hunger != 0){
echo $item->hunger.' Hunger</br>';
}
if($item->hygiene != 0){
echo $item->hygiene.' Hygiene</br>';
}
if($item->happiness != 0){
echo $item->happiness.' Happiness</br>';
}
if($item->energy != 0){
echo $item->energy.' Energy</br>';
}
echo '</span></div>';
} 
}
echo '</div></br></br>';
if($friendid !== ''){
?>
<button class="btn btn-primary" onclick="window.location.href = 'home.php?friendid=<?php echo $friendid;?>';">Back to Veep</button>
<?php
} else {
?>
<button class="btn btn-primary" onclick="window.location.href = 'updateprofile.php'">Edit Profile</button>
<button class="btn btn-primary" onclick="window.location.href = 'home.php'">Back to Veep</button></br></br>
	<button class="btn btn-danger" onclick="window.location.href = 'logout.php';">Sign Out of Veep</button>
<?php
}
?>

<?php
include 'footer.php';
?>
</div>  
</body>
</html>