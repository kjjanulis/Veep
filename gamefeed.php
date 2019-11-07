<?php
$gamesfeedsql = "SELECT `post`, max(`id`) as max_id, DATE_FORMAT(time, '%r'), `type` FROM `log` WHERE type = 'game' GROUP BY post ORDER BY `max_id` DESC LIMIT 0 , 10";
$gamesfeedresult = mysqli_query($rlink, $gamesfeedsql);

echo '<h1>Games Feed</h1></br><table id="feed">';
if ($gamesfeedresult ->num_rows > 0) {
    // output data of each row
    while($gfeedrow = $gamesfeedresult ->fetch_assoc()) {
  echo '<tr>
<td>'.$gfeedrow["post"].'</td></tr>';
}
}
echo '</table></br>';
?>