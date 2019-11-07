<?php
if($debug == 1 && $loglevel == 10){
echo '<pre>';
$arr = get_defined_vars();
print_r($arr);
var_dump($_SESSION);
echo '</pre>';
}
?>
<div class="footer">
<?php
echo $version;
echo '</br><a href="PP.pdf" target="_blank">Privacy Policy</a> | <a href="TOA.pdf" target="_blank">Terms of Agreement</a> | <a href="reportissue.php" target="_blank">Report Issue</a>';
if($loglevel >=1){
echo ' | <a href="admin.php">Admin</a>';
}
?>
</div>