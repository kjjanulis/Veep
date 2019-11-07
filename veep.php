<?php
// Update stats from last login
mysqli_query($link, $logsql);
if($status !== 'dead' && $health > 0){
mysqli_query($link, $xpupdatesql);
mysqli_query($link, $tokensql);
$tokens = floor($tokens);
$_SESSION["tokens"] = $tokens;
}

// Set stats to whole numbers
$health = ceil($health);
$hunger = ceil($hunger);
$happiness= ceil($happiness);
$hygiene = ceil($hygiene);
$energy = ceil($energy);

echo '<div class="backgrounds" style="background-image: url(https://veeple.online/Images/backgrounds/'.$veepid.'.png);">';

if($firstlog == 1 && $friendid == ''){
        echo '<h1>Hi, ' . htmlspecialchars($_SESSION["username"]) . '. Welcome to Veep.</h1>';
mysqli_query($link, $firstlogsql);
mysqli_query($link, $addfirstfriend);
} elseif($friendid == '') {
if($daycare !== 'yes'){
        echo '<h1>Hi, ' . htmlspecialchars($_SESSION["username"]) . '. Welcome back,</br></h1>';
} else{
$dcout = new DateTime($daycareout);
        echo '<h1>Hi, ' . htmlspecialchars($_SESSION["username"]) . '. Welcome back,</br></h1><p style="font-size:20px">your veep is still at the hotel!</br></br>Relax, kickback, and enjoy yourself knowing your veep is being well cared for.</p></br></br></br></br><b>Your veep will return from the Veep Inn at</br>'.$dcout->format( 'm/d/Y h:i  A' ).'</b>';
}
$loginupdate = "UPDATE users SET login = now() WHERE id='$loguser'";
mysqli_query($link, $loginupdate);
}
if($firstlog == 1 && $friendid == ''){
echo "<b>Welcome to Veeple here are your Veep's Stats. Be sure to check in and keep your veeple fed and happy!</br></br></b>";
$stmt = $link->prepare($newuserpost);
$stmt->bind_param("sss", $newuser, $ptype, $logname);
$ptype = 'New User';
$newuser = '<b>'.$logname.' Joined Veeple.Online</b>';
$stmt->execute();
$stmt->close();
}
if($friendid !== ''){
$popularityupdate = "UPDATE users SET popularity = popularity + 1 WHERE id='$user'";
mysqli_query($link, $popularityupdate);
if($daycare !== 'yes'){
        echo "<h1>".$username."'s Veep</br></h1>";
} else {
        echo "<h1>".$username."'s Veep is in the Veep Hotel</br></h1>";
}
?>
<button class="btn btn-primary" onclick="window.location.href = 'userprofile.php?friendid=<?php echo $friendid;?>';">View User Profile</button>
<?php
$getfriendstatus = "SELECT * FROM friends WHERE (friend_one=$user OR friend_two=$user) AND (friend_one=$loguser OR friend_two=$loguser)";
$friendstatusresult = mysqli_query($link, $getfriendstatus);
$friendstatusrow =  mysqli_fetch_assoc($friendstatusresult);
$friendstatus = $friendstatusrow['status'];
if($friendstatus == 0){
echo '<button class="btn btn-green" onclick="friend(1)">Add as Friend</button></br></br>';
} elseif ($friendstatus == 2){
?>
<button class="btn btn-primary"  onclick="window.location.href = 'sendmail.php?friend=<?php echo $username;?>';">Send Message</button></br>
<?php
}
}
if($daycare !== 'yes'){
$xpbar = ($experience-floor($experience))*100;
echo '</br><b>Level '.$level.'</b></br><div class="w3-light-grey" style="border-radius: 6px;background-color: #d2d2d2!important;max-width: 300px;margin: auto;"><div id="healthbar" class="w3-green" style="border-radius: 6px;height:20px;width:'.$xpbar.'%"></div></div>';
}
?>
<div class="veep">
<?php
if($daycare !== 'yes'){
	if($hat !== ''){
		echo '<img src="https://veeple.online/Images/veeple/hats/'.$hat.'/'.$veepid.'.png" alt="'.$hat.'" class="overlayveep">';
	}
	if($hygiene <= 20){
		echo '<img src="https://veeple.online/Images/veeple/smelly.gif" alt="Smelly Veep" class="overlayveep">';
	}
if($energy <= 15){
			echo '<img src="https://veeple.online/Images/veeple/sleep.gif" alt="Sleepy Veep" class="overlayveep">';
echo '<img src="https://veeple.online/Images/veeple/'.$veepid.'/'.$veepid.'_sleep.gif" alt="Sleepy Veep" class="veep">';
} else {
?>
<img src="https://veeple.online/Images/veeple/<?php echo $veepid.'/'.$veepid;?>.gif" alt="Veep" class="<?php if($hygiene <= 20){echo 'smellyfilter ';} ?>veep">
<?php
}
?>
</div>
<?php
if($veepname !== ''){
    if($health <= 50){
echo "<h4><b>".$veepname."</b> dosn't look too well</h4>";
    }  elseif ($hunger <= 30){
        echo "<h4><b>".$veepname."</b> looks pretty hungry</h4>";
    }
        elseif ($energy <=40 && $happiness <= 40 && $hunger <= 40 && $hygiene <= 40){
        echo "<h4><b>".$veepname."</b> is strieght up not having a good time</h4>";
    }  
    elseif ($happiness <= 30){
        echo "<h4><b>".$veepname."</b> dosn't look too happy</h4>";
    } elseif ($hygiene <= 30) {
                echo "<h4><b>".$veepname."</b> doesn't smell too good</h4>";
    } elseif ($energy <=15) {
                echo "<h4><b>".$veepname."</b> looks exhausted</h4>";
    } elseif ($energy >=75 && $happiness >= 75 && $hunger >= 75 && $hygiene >= 75) {
                echo "<h4><b>".$veepname."</b> is living their best life!</h4>";
    } elseif ($happiness >= 75 && $hygiene >= 75) {
                echo "<h4><b>".$veepname."</b> is happy and well</h4>";
    } elseif ($hygiene >= 90) {
                echo "<h4><b>".$veepname."</b> is squeaky clean and groomed</h4>";
    } else {
        echo "<h4><b>".$veepname."</b></h4>";
    }
}
echo '</div>';
echo '<div class="stats"><div class="fullbars">
<b>Health</b><div class="w3-light-grey" style="border-radius: 6px;"><div id="healthbar" class="w3-green" style="border-radius: 6px;height:20px;width:'.$health.'%">'.$health.'%</div></div></div>
<div class="bars"><b>Hunger</b><div class="w3-light-grey" style="border-radius: 6px;"><div id="hungerbar" class="w3-amber" style="border-radius: 6px;height:20px;width:'.$hunger.'%">'.$hunger.'%</div></div></div>
<div class="bars"><b>Happiness</b><div class="w3-light-grey" style="border-radius: 6px;"><div id="happinessbar" class="w3-yellow" style="border-radius: 6px;height:20px;width:'.$happiness.'%">'.$happiness.'%</div></div></div>
<div class="bars"><b>Hygiene</b><div class="w3-light-grey" style="border-radius: 6px;"><div id="hygienebar" class="w3-cyan" style="border-radius: 6px;height:20px;width:'.$hygiene.'%">'.$hygiene.'%</div></div></div>
<div class="bars"><b>Energy</b><div class="w3-light-grey" style="border-radius: 6px;"><div id="energybar" class="w3-indigo" style="border-radius: 6px;height:20px;width:'.$energy.'%">'.$energy.'%</div></div></div>';
	echo '<div class="invdisplay"><h4><b>My Items</b></h4>';
include 'itemInterface.php';
$itemMapper = new ItemMapper();
$userInventory = $itemMapper->getInventory($loguser);
if(count($userInventory) == 0){
echo '<h5>Your inventory is empty. </br></br>Head over to one of veep shops to buy some things for your veep.</br></h5>';
}
foreach($userInventory as $key => $item) {
if($item->category !== 'Award'){
    echo '<div class="item" onclick="useitem('.$key.','.$veepnum.')"><img src="'.$item->imageLink.'" alt="'.$item->name.'"></br><b>'.$item->name.'</b>';
if(($item->consumable) == 1){
echo '</br>'.$item->curDurability.'/'.$item->durability;
}
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
echo '</span></div><input name="'.$key.'" type="hidden" id="'.$key.'" value="'.$item->category.'"><input name="itemname'.$key.'" type="hidden" id="itemname'.$key.'" value="'.$item->name.'">';
}
}
if($loglevel == 10 && $debug == 1){
echo '</br></br>';
print_r($userInventory);
}
echo '</div>';
}
	if($daycare == 'yes'){
		echo '</div>';
	}

echo '</div>';
?>