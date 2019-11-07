<?php
if($limited != 'yes'){
	echo'
<script language="javascript">
	 function getWidth() {
  return Math.max(
    document.body.scrollWidth,
    document.documentElement.scrollWidth,
    document.body.offsetWidth,
    document.documentElement.offsetWidth,
    document.documentElement.clientWidth
  );
}
if(getWidth()>=1400) {document.write("<style>body{zoom:120%;}</style>");}
</script>';
}
?>
<?php
$gettokensql = "SELECT tokens FROM `users` WHERE id=$uid";
$gtresult = mysqli_query($rlink, $gettokensql);
$gtrow = mysqli_fetch_assoc($gtresult);
$gettokens = $gtrow["tokens"];
if(isset($gettokens)) {
$dtokens = floor($gettokens);
echo '<div class="tokenscount"><img src="https://veeple.online/Images/token.png" alt="Tokens" style="width:30px;height:30px;"><b> '.$dtokens.' Veep Coin</b></div>';
}
?>
<div class="top"><img src="https://veeple.online/Images/veep-online.png" alt="Veep Online" class="vologo">
</div>
<ul class="nav">
  <li><a href="/index.php">My Veep</a></li>
  <li><a href="/userprofile.php">Profile</a></li>
  <li><a href="/friends.php">Friends</a></li>
  <li><a href="/news.php">News</a></li>
<li class="dropdown">
    <a href="/downtown.php" class="dropbtn">Shops</a>
<!--    <div class="dropdown-content">
      <a href="/dolphingeneral.php">Dolphin General</a>
<a href="/fabiosfoodcourt.php">Fabio's Food Court</a>
      <a href="/peggysplayhousetoyshop.php">Peggy's Play House</a>
      <a href="/groommies.php">Groommies</a>
      <a href="/hotel.php">Veep Inn</a>
    </div> --!>
  </li>
  <li><a href="/games.php">Games</a></li>
  <li><a href="/users.php">Other Veeple</a></li>
<li style="float:right">
<?php
if($noticount > 0){
echo '<div class="nicons">
<img src="https://www.veeple.online/Images/noti.gif" alt="'.$noticount.' Notifications" class="nicons" onclick="noti()"/></div>';
} else {
echo '<div class="nnicons">
<img src="https://www.veeple.online/Images/nonoti.gif" alt="No Notifications" class="nnicons" onclick="noti()"/></div>';
}
?>
</li>
<?php
if($mailcount > 0){
echo '<li style="float:right"><div class="nicons">
<img src="https://www.veeple.online/Images/mail.gif" alt="You Got Mail" class="nicons" onclick="mail()"/></div></li>';
} else {
echo '<li style="float:right"><div class="nnicons">
<img src="https://www.veeple.online/Images/nomail.gif" alt="No Mail" class="nnicons" onclick="mail()"/></div></li>';
}
?>
</ul>