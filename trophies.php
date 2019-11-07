<?php
require_once('config.php');


// Reset All Auto Trophys
$resetautotrophys = "DELETE FROM inventory WHERE item_ID = 92 OR item_ID = 93 OR item_ID = 94 OR item_ID = 60 OR item_ID = 95 OR item_ID = 96";
        if($stmt = mysqli_prepare($link, $resetautotrophys)){
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
}

// Popularity Trophy
$getpopularity = "SELECT id FROM `users` ORDER BY `users`.`popularity` DESC LIMIT 1";
        if($stmt = mysqli_prepare($link, $getpopularity)){

           // mysqli_stmt_bind_param($stmt, "s", $param_promocode);

mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$popwinner);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$givepopularity = "INSERT INTO inventory (user_ID, item_ID, current_dur, consumable)
VALUES (?, 92, 1, 0);";
        if($stmt = mysqli_prepare($link, $givepopularity)){
mysqli_stmt_bind_param($stmt, "i", $popwinner);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
}

// Gamer Trophy
$getscore = "SELECT USER, SUM(score) AS 'Total Score' FROM `highscores` GROUP BY user ORDER BY `Total Score` DESC LIMIT 1";
        if($stmt = mysqli_prepare($link, $getscore)){
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topscoreuser,$discard);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$getidscore = "SELECT id FROM `users` WHERE username = ? LIMIT 1";
        if($stmt = mysqli_prepare($link, $getidscore)){
mysqli_stmt_bind_param($stmt, "s", $topscoreuser);
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topscoreid);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$givegamer = "INSERT INTO inventory (user_ID, item_ID, current_dur, consumable)
VALUES (?, 93, 1, 0);";
        if($stmt = mysqli_prepare($link, $givegamer)){
mysqli_stmt_bind_param($stmt, "i", $topscoreid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
}

// Gobbler Trophy
$getfed = "SELECT logger, COUNT(post) AS 'Total' FROM `log` WHERE post LIKE '%fed%' GROUP BY logger ORDER BY `Total` DESC LIMIT 1";
        if($stmt = mysqli_prepare($link, $getfed)){
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topfeduser,$discard);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$getidfed = "SELECT id FROM `users` WHERE username = ? LIMIT 1";
        if($stmt = mysqli_prepare($link, $getidscore)){
mysqli_stmt_bind_param($stmt, "s", $topfeduser);
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topfedid);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$givefed = "INSERT INTO inventory (user_ID, item_ID, current_dur, consumable)
VALUES (?, 94, 1, 0);";
        if($stmt = mysqli_prepare($link, $givefed)){
mysqli_stmt_bind_param($stmt, "i", $topfedid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
}

// Savor Trophy
$getsavior = "SELECT logger, COUNT(post) AS 'Total' FROM `log` WHERE post LIKE '%healed%' OR post LIKE '%vet%' GROUP BY logger ORDER BY `Total` DESC LIMIT 1";
        if($stmt = mysqli_prepare($link, $getsavior)){
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topsaveuser,$discard);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$getidsave = "SELECT id FROM `users` WHERE username = ? LIMIT 1";
        if($stmt = mysqli_prepare($link, $getidsave)){
mysqli_stmt_bind_param($stmt, "s", $topsaveuser);
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topsaveid);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$givesave = "INSERT INTO inventory (user_ID, item_ID, current_dur, consumable)
VALUES (?, 60, 1, 0);";
        if($stmt = mysqli_prepare($link, $givesave)){
mysqli_stmt_bind_param($stmt, "i", $topsaveid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
}

// Grooming Trophy
$getgroom = "SELECT logger, COUNT(post) AS 'Total' FROM `log` WHERE post LIKE '%groomed%' OR post LIKE '%bath%' GROUP BY logger ORDER BY `Total` DESC LIMIT 1";
        if($stmt = mysqli_prepare($link, $getgroom)){
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topgroomuser,$discard);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$getidgroom = "SELECT id FROM `users` WHERE username = ? LIMIT 1";
        if($stmt = mysqli_prepare($link, $getidgroom)){
mysqli_stmt_bind_param($stmt, "s", $topgroomuser);
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topgroomid);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$givegroom = "INSERT INTO inventory (user_ID, item_ID, current_dur, consumable)
VALUES (?, 95, 1, 0);";
        if($stmt = mysqli_prepare($link, $givegroom)){
mysqli_stmt_bind_param($stmt, "i", $topgroomid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
}

// Jester Trophy
$getjester = "SELECT logger, COUNT(post) AS 'Total' FROM `log` WHERE post LIKE '%played%' GROUP BY logger ORDER BY `Total` DESC LIMIT 1";
        if($stmt = mysqli_prepare($link, $getjester)){
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topjesteruser,$discard);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$getidjester = "SELECT id FROM `users` WHERE username = ? LIMIT 1";
        if($stmt = mysqli_prepare($link, $getidjester)){
mysqli_stmt_bind_param($stmt, "s", $topjesteruser);
mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt,$topjesterid);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
}
$givejester = "INSERT INTO inventory (user_ID, item_ID, current_dur, consumable)
VALUES (?, 96, 1, 0);";
        if($stmt = mysqli_prepare($link, $givejester)){
mysqli_stmt_bind_param($stmt, "i", $topjesterid);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
}
?>