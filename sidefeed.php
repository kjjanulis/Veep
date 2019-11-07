<?php
//Posting things
$postsql = "INSERT INTO `log` (post, logger, type) VALUES (?, ?, ?)";
if( $_POST['submitpost'] ) {
$post_err = '';
if(empty(trim($_POST["post"]))){
        $post_err = "Please enter something to post.";     
    } elseif (preg_match('/[\^£$%&*()}{@#~><>|=+¬-]/', trim($_POST["post"])))
{
    $post_err = "Please remove special characters.";
} elseif (preg_match($filterwords, strtolower(trim($_POST["post"]))))
{
    $post_err = "Please remove offensive lanuage.";
} elseif (strlen(trim($_POST["post"])) > 50) {
$post_err = "Please shorten post.";
}
if(empty($post_err)){
$stmt = $link->prepare($postsql);
$stmt->bind_param("sss", $par_post, $par_logger, $par_type);
$par_post = $logname."'s ".$lveepname." says ".trim($_POST["post"]);
$par_logger = $_SESSION["username"];
$par_type = 'post';
$stmt->execute();
$stmt->close();
 echo "<meta http-equiv='refresh' content='0'>";
}
}

// Feed
$feedsql = "SELECT `post`, max(`id`) as max_id, DATE_FORMAT(time, '%r') FROM `log` WHERE logger != 'BillyBlob' && type = 'action' OR type = 'game' OR type = 'post' GROUP BY post ORDER BY `max_id` DESC LIMIT 0 , 12";
$feedresult = mysqli_query($rlink, $feedsql);

echo '<h1>Veep Feed</h1></br><table id="feed">';
if ($feedresult ->num_rows > 0) {
    // output data of each row
    while($feedrow = $feedresult ->fetch_assoc()) {
  echo '<tr>
<td>'.$feedrow["post"].'</td></tr>';
}
}
echo '</table></br>';

echo '<form id="submitpost" action="" method="post">
<input type="text" name="post" id="postinput" maxlength="50" value"'.trim($_POST["post"]).'"/></br>
        <input type="submit" name="submitpost" value="Post Status" class="btn btn-primary"/>
<span class="help-block">'.$post_err.'</span>
    </form>';

?>