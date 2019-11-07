<?php
$veepsellsql = "UPDATE `aval_veeps` SET rare = rare - 1, owned = owned - 1 WHERE veepid = ?";


if($firstlog == 1) {
echo '<h2>Welcome, it is time to adopt your first veep!</h2></br>';
echo '<form id="adoptveep" action="" method="post">
<input id="adoptveep" name="adoptveep" type="hidden" value="adoptveep">
        <input type="submit" class="btn btn-primary" name="adoptveep" value="Adopt a veep"/>
    </form>';
echo '</br></br><div class="embed-responsive embed-responsive-16by9">
<video width="320" height="240" controls autoplay>
  <source src="Images/Veep Tutorial.mp4" type="video/mp4">
Your browser does not support the video tag.
</video>
</div>';
} else {
if($friendid == '' && $firstlog == 0) {
	if($veepid != 21 && $veepid != 0){
$stmt = $link->prepare($veepsellsql);
$stmt->bind_param("i", $veepid);
$stmt->execute();
$stmt->close();
}
	$veepid = 0;
mysqli_query($link, $diedsql);
echo '<h1>Unfortunately, it looks like you no longer have a veep.</h1></br>';
echo '<form id="adoptveep" action="" method="post">
<input id="adoptveep" name="adoptveep" type="hidden" value="adoptveep">
        <input type="submit" class="btn btn-primary" name="adoptveep" value="Adopt a veep"/>
    </form>';
} else {
	if($veepid != 21 && $veepid != 0){
$stmt = $link->prepare($veepsellsql);
$stmt->bind_param("i", $veepid);
$stmt->execute();
$stmt->close();
}
		$veepid = 0;
mysqli_query($link, $diedsql);
echo '<h1>Unfortunately, this veep no longer exist.</h1></br>';
}
}

?>