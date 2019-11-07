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
<style>
.nav li {
    float: none;
    display: inline-block;
    margin: auto;
    border-right: 1px solid #ccff99;
}
</style>
<div class="top"><img src="https://veeple.online/Images/veep-online.png" alt="Veep Online" class="vologo">
</div>
<ul class="nav">
  <li><a href="/login.php">Login</a></li>
  <li><a href="/register.php">Sign Up</a></li>
  <li><a href="/games.php">Games</a></li>
</ul>