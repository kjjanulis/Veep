<?php
// Initialize the session
session_start();
 // Include config file
require_once "config.php";
include 'mainvar.php';
include 'itemInterface.php';

$user = $_POST['user'];
$loguser = $_SESSION["id"];
$username = $_POST['username'];
$logname = $_SESSION["username"];
$veepname = $_POST['veepname'];
$adoptnumber = $_POST['veep'];
$vtc = $_POST['sell'];
$veepval = $_POST['vtc'];


//SQLs
$hatsql = "UPDATE `veeps` SET happiness = happiness + 5, hat = ? WHERE veepnum=?";
$postsql = "INSERT INTO log (post, logger, direction, type) VALUES (?, ?, ?, ?)";
$wonfindsql = "UPDATE `users` JOIN `veeps` SET tokens = tokens + 25, happiness = 100, experience = experience + (0.01) WHERE users.username = veeps.username AND id='$loguser'";
$coinwinsql = "UPDATE `users` JOIN `veeps` SET tokens = ?, happiness = 100 WHERE users.username = veeps.username AND id=?";
$tokenscoresql = "UPDATE `users` JOIN `veeps` SET tokens = tokens + ?, happiness = 100 WHERE users.username = veeps.username AND id=?";
$adoptionsql = "UPDATE `users` JOIN `veeps` SET veepid='$adoptnumber', tokens = tokens - '$veepval' WHERE users.username = veeps.username AND id='$loguser'";
$postscoresql = "INSERT INTO highscores (game, score, user) VALUES (?, ?, ?)";
$coindropupdatesql = "UPDATE highscores SET score = score + 1 WHERE game=?";
$coindropresetsql = "UPDATE highscores SET score = 30 WHERE game=?";
$addadoptsql =  "UPDATE `aval_veeps` SET rare= rare + 1, owned = owned + 1 WHERE veepid='$adoptnumber'";
$soldsql = "UPDATE `users` JOIN `veeps` SET health = 0, experience = 0, veepid = 0, hunger = 0, hygiene = 0, happiness = 0, energy = 0, veepname = '', hat = '', daycare='0000-00-00 00:00:00', tokens = tokens + '$vtc' WHERE users.username = veeps.username AND id='$loguser'";
$addfriend = "INSERT INTO friends (friend_one,friend_two,status) VALUES (?,?,1)";
$delfriend = "DELETE FROM friends WHERE (friend_one=$user OR friend_two=$user) AND (friend_one=$loguser OR friend_two=$loguser)";
$confirmfriend = "UPDATE friends SET status = 2 WHERE (friend_one=$user OR friend_two=$user) AND (friend_one=$loguser OR friend_two=$loguser)";
$veepsellsql = "UPDATE `aval_veeps` SET rare = rare - 1, owned = owned - 1 WHERE veepid = ?";
$adoptresetsql = "UPDATE `veeps` SET health=?, hunger=?, happiness=?, hygiene=?, energy=?, veepid=0, veepname=?, hungermod=?, happinessmod=?, hygienemod=?, experience = 0, energymod=?, veepbirth = NOW() WHERE username=?";

// Using Items
   if(isset($_POST['itemkey'])) {
	   $itemMapper = new ItemMapper();
$userInventory = $itemMapper->getInventory($loguser);
$itemkey = $_POST['itemkey'];
$veepnum = $_POST['veepnum'];
$userInventory[$itemkey]->useItem($loguser, $veepnum);
$itemtype = $_POST['itemtype'];
$itemname = $_POST['itemname'];
	   
if($_POST['itemtype'] == 'Accessory'){
if($loguser == $user){
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'Stylize';
if($veepname == ''){$veepname = 'No Name';}
$post = $logname.' dressed up '.$veepname;
$logger = $logname;
$direction = 'self';
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($hatsql);
$stmt->bind_param("si", $itemname, $veepnum);
$veepnum = $_POST['veepnum'];
if($_POST['itemname'] == $hat){
$itemname = '';
} else {
$itemname = $_POST['itemname'];
}
$stmt->execute();
$stmt->close();
}
} elseif($itemtype == 'Toy'){
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'action';
if($loguser == $user){
if($veepname == ''){$veepname = 'No Name';}
$post = $logname.' played with '.$veepname;
$logger = $logname;
$direction = 'self';
} else {
$post = $logname." played with ".$username."'s veep ".$veepname;
$logger = $logname;
$direction = $username;
}
$stmt->execute();
$stmt->close();
} elseif($itemtype == 'Food'){
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'action';
if($loguser == $user){
if($veepname == ''){$veepname = 'No Name';}
$post = $logname.' fed '.$veepname.' a '.$itemname;
$logger = $logname;
$direction = 'self';
} else {
$post = $logname." fed ".$username."'s veep ".$veepname.' a '.$itemname;
$logger = $logname;
$direction = $username;
}
$stmt->execute();
$stmt->close();
} elseif($itemtype == 'Health'){
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'action';
if($loguser == $user){
if($veepname == ''){$veepname = 'No Name';}
$post = $logname.' healed '.$veepname;
$logger = $logname;
$direction = 'self';
} else {
$post = $logname." healed ".$username."'s veep ".$veepname;
$logger = $logname;
$direction = $username;
}
$stmt->execute();
$stmt->close();
}  elseif($itemtype == 'Grooming'){
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'action';
if($loguser == $user){
if($veepname == ''){$veepname = 'No Name';}
$post = $logname.' groomed '.$veepname;
$logger = $logname;
$direction = 'self';
} else {
$post = $logname." groomed ".$username."'s veep ".$veepname;
$logger = $logname;
$direction = $username;
}
$stmt->execute();
$stmt->close();
}
}

// Buying an item
       if(isset($_POST['itemid'])) {
$itemMapper = new ItemMapper();
$itemID = $_POST['itemid'];
$itemCost = $_POST['cost'];
$durability = $_POST['durability'];
$consumable = $_POST['consumable'];
$returnCode = $itemMapper->buyItem($loguser, $itemID, $itemCost, $durability, $consumable);
switch(returnCode)
{
    case 0:
        //success
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'item';
$post = $logname.' bought item '.$_POST['itemid'].' for '.$_POST['cost'].' Veep Coin';
$logger = $logname;
$direction = 'self';
$stmt->execute();
$stmt->close();
alert("Thank you come again soon!");
        break;

    case 1: 
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'item';
$post = $logname.' got a DB Error buying item '.$_POST['itemid'];
$logger = $logname;
$direction = 'error';
$stmt->execute();
$stmt->close();
alert("Error processing your request. We will get our smartest veeps on the issue right away.");
        break;

    case 2:
        //Inventory is full
alert("Inventory is full. Make some room before buying more items.");
        break;

    case 3:
        //Not enough tokens
alert("Not enough veep coin. Check out the games section to win some more!");
        break;
}
	   }

// Friends system
       if(isset($_POST['friend'])) {
if($_POST['friend'] == 1) {
$stmt = $link->prepare($addfriend);
$stmt->bind_param("ii", $user, $loguser);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'friend';
$post = $logname.' added '.$username.' as a friend';
$logger = $logname;
$direction = $username;
$stmt->execute();
$stmt->close();
        }
if($_POST['friend'] == 2) {
            mysqli_query($link, $delfriend);
        }
if($_POST['friend'] == 3) {
            mysqli_query($link, $confirmfriend);
        }
}

// Selling your Veep
       if(isset($_POST['sell'])) {
if($veepid != 21){
$stmt = $link->prepare($veepsellsql);
$stmt->bind_param("i", $veepid);
$stmt->execute();
$stmt->close();
}
mysqli_query($link, $soldsql);
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'sell';
$post = $logname.' sold their veep for '.$vtc.' Veep Coin';
$logger = $logname;
$direction = 'self';
$stmt->execute();
$stmt->close();
        }

// Adopting a Veep
       if(isset($_POST['veep'])) {
$stmt = $link->prepare($adoptresetsql);
$stmt->bind_param("iiiiissssss", $ranhealth, $ranhunger, $ranhappy, $ranhygiene, $ranenergy, $sname, $rhungermod, $rhappinessmod, $rhygienemod, $renergymod, $name);
$sname = '';
$name = $logname;
$ranhealth = rand(90,100);
$ranhunger = rand(75,100);
$ranhappy = rand(75,100);
$ranhygiene = rand(75,100);
$ranenergy = rand(75,100);
$rhungermod = rand(75, 125)/100;
$rhappinessmod = rand(75, 125)/100;
$rhygienemod = rand(75, 125)/100;
$renergymod = rand(75, 125)/100;
$stmt->execute();
$stmt->close();
mysqli_query($link, $adoptionsql);
if($adoptnumber != 21){
mysqli_query($link, $addadoptsql);
}
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'adopt';
$post = $logname.' adopted a new veep for '.$veepval.' Veep Coin';
$logger = $logname;
$direction = 'self';
$stmt->execute();
$stmt->close();
        }

// Find Veep Game
        if(isset($_POST['game'])) {
            if($_POST['game'] == 'findveep'){
                        mysqli_query($link, $wonfindsql);
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$post = $logname.' won a game of Find Veep';
$logger = $logname;
$direction = 'Find Veep';
$stmt->execute();
$stmt->close();
            }
			
// Defuse Game
			            if($_POST['game'] == 'defuse'){
                        mysqli_query($link, $wonfindsql);
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$post = $logname.' just saved the world in Defuse!';
$logger = $logname;
$direction = 'Defuse';
$stmt->execute();
$stmt->close();
            }
        }

// Coin Drop Game
        if(isset($_POST['coin'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("is", $vcscore, $loguser);
$vcscore = floor($_POST['coin']);
$stmt->execute();
$stmt->close();
			$tokensql = "SELECT tokens FROM `users`WHERE id='$loguser'";
			$tokenresult = mysqli_query($link, $tokensql);
			$tokenrow = mysqli_fetch_assoc($tokenresult);
			$_SESSION["tokens"] = floor($tokenrow["tokens"]);
			if($_POST['coin'] < 1){
			$stmt = $link->prepare($coindropupdatesql);
$stmt->bind_param("s", $game);
$game = 'Coin Drop';
$stmt->execute();
$stmt->close();
		} else {
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'casino';
$post = $logname.' Coin Drop winnings: '. $_POST['coin'];
$logger = $logname;
$direction = 'Coin Drop';
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($coindropresetsql);
$stmt->bind_param("s", $game);
$game = 'Coin Drop';
$stmt->execute();
$stmt->close();
			}
            }

// Space Defender Game
                if(isset($_POST['spacedefender'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['spacedefender']/25);
$exp = $experience + ($_POST['spacedefender']/10000);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$spacedefenderscore = $_POST['spacedefender'];
$post = $logname.' just finished a game of Space Defender';
$logger = $logname;
$direction = 'Space Defender';
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'Space Defender';
$score = $_POST['spacedefender'];
$stmt->execute();
$stmt->close();
                    }

// Asteroids Game
                if(isset($_POST['astroids'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['astroids']/12);
$exp = $experience + ($_POST['astroids']/10000);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$astroidsscore = $_POST['astroids'];
$post = $logname.' just finished a game of Asteroids';
$logger = $logname;
$direction = 'Asteroids';
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'Asteroids';
$score = $_POST['astroids'];
$stmt->execute();
$stmt->close();
                    }

// Piggy Fly Game
                                    if(isset($_POST['pigfly'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['pigfly']/15);
$exp = $experience + ($_POST['pigfly']/10000);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$astroidsscore = $_POST['pigfly'];
$post = $logname.' just finished a game of Piggy Fly';
$logger = $logname;
$direction = 'Piggy Fly';
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'Piggy Fly';
$score = $_POST['pigfly'];
$stmt->execute();
$stmt->close();
                    }

// Disco Dash Game
                                                        if(isset($_POST['discodash'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['discodash']*5);
$exp = $experience + ($_POST['discodash']/10000);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$astroidsscore = $_POST['discodash'];
$post = $logname.' just finished a game of Disco Dash';
$logger = $logname;
$direction = 'Disco Dash';
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'Disco Dash';
$score = $_POST['discodash'];
$stmt->execute();
$stmt->close();
                    }

// Invaders Game
                                                        if(isset($_POST['invaders'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['invaders']/150);
$exp = $experience + ($_POST['invaders']/10000);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$astroidsscore = $_POST['invaders'];
$post = $logname.' just finished a game of Invaders';
$logger = $logname;
$direction = 'Invaders';
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'Invaders';
$score = $_POST['invaders'];
$stmt->execute();
$stmt->close();
                    }

// Super Laser Zombie Invaders Game
                                                        if(isset($_POST['superlaserzombieinvaders'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['superlaserzombieinvaders']/25);
$exp = $experience + ($_POST['superlaserzombieinvaders']/15000);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'Super Laser Zombie Invasion';
$score = $_POST['superlaserzombieinvaders'];
$stmt->execute();
$stmt->close();
                    }

// City Bird Game
                                                        if(isset($_POST['citybird'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['citybird']/1500);
$exp = $experience + ($_POST['citybird']/100000);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'City Bird';
$score = $_POST['citybird'];
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$post = $logname.' just took flight in City Bird';
$logger = $logname;
$direction = 'City Bird';
$stmt->execute();
$stmt->close();
                    }

// Stackem Game
                                                        if(isset($_POST['stackem'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['stackem']/5);
$exp = $experience + ($_POST['stackem']/1000);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'StackEm';
$score = $_POST['stackem'];
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$post = $logname.' just stacked up in StackEm';
$logger = $logname;
$direction = 'StackEm';
$stmt->execute();
$stmt->close();
                    }

// Bob Blob Game
                                                        if(isset($_POST['bobblob'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['bobblob']);
$exp = $experience + ($_POST['bobblob']/100);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'Bob Blob';
$score = $_POST['bobblob'];
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$post = $logname.' just conquered Bob Blob';
$logger = $logname;
$direction = 'Bob Blob';
$stmt->execute();
$stmt->close();
                    }

// Bob's Desert Run Game
                                                        if(isset($_POST['bobsdesertrun'])) {
$stmt = $link->prepare($tokenscoresql);
$stmt->bind_param("iss", $vcscore, $exp, $loguser);
$vcscore = floor($_POST['bobsdesertrun']/500);
$exp = $experience + ($_POST['bobblob']/100000);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postscoresql);
$stmt->bind_param("sis", $game, $score, $logname);
$game = 'Bobs Desert Run';
$score = $_POST['bobsdesertrun'];
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'game';
$post = $logname.' just ran through Bobs Desert Run';
$logger = $logname;
$direction = 'Bobs Desert Run';
$stmt->execute();
$stmt->close();
                    }

// Spin to win
     if(isset($_POST['spintowin'])) {
$stmt = $link->prepare($coinwinsql);
$stmt->bind_param("is", $balance, $loguser);
$balance = floor($_POST['spintowin']);
$stmt->execute();
$stmt->close();
$stmt = $link->prepare($postsql);
$stmt->bind_param("ssss", $post, $logger, $direction, $type);
$type = 'casino';
$post = $logname.' Spin To Win balance: '. $balance;
$logger = $logname;
$direction = 'Spin To Win';
$stmt->execute();
$stmt->close();
	 }
?>