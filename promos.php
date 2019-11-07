<?php
if(isset($_SESSION["promo"])){
if($_SESSION["promo"] = 'procard'){
$newtokens = 1000;
}
} elseif($_SESSION["promo"] = 'newveep'){
$newtokens = 1000;
} else {
$newtokens = 500;
}
?>