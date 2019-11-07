<?php
session_start();
require_once "../config.php";
$uid = $_SESSION["id"];
$veepidsql = "SELECT * FROM `users` JOIN `veeps` WHERE users.username = veeps.username AND id='$uid'";
$vidresult = mysqli_query($rlink, $veepidsql);
$vidrow = mysqli_fetch_assoc($vidresult);
$vid = $vidrow["veepid"];
echo '{"vid": '.$vid;
echo '}';
?>