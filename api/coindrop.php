<?php
session_start();
require_once "../config.php";
$jackpotsql = "SELECT * FROM `highscores` WHERE user='jackpot'";
$jackpotresult = mysqli_query($rlink, $jackpotsql);
$jackpotrow = mysqli_fetch_assoc($jackpotresult);
$jackpot = $jackpotrow["score"];
$uid = $_SESSION["id"];
$tokensql = "SELECT tokens FROM `users` WHERE id=$uid";
$tokenresult = mysqli_query($rlink, $tokensql);
$tokenrow = mysqli_fetch_assoc($tokenresult);
$jackpot = $jackpotrow["score"];
$tokens = floor($tokenrow["tokens"]);
echo '{"tokens": '.$tokens;
echo ', "jackpot": '.$jackpot;
echo '}';
?>