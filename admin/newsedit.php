<?php
// Initialize the session
session_start();
 // Include config file
require_once "../config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

// Set Main Var
include '../mainvar.php';

if(isset($_GET['postid'])){
$postid = $_GET['postid'];
}
$newspostsql = "SELECT * FROM news WHERE id = $postid";
$newsresult = mysqli_query($link, $newspostsql);
$postrow = mysqli_fetch_assoc($newsresult);
$title = $postrow["title"];
$post = $postrow["post"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-55212904-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-55212904-2');
</script>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
    <title>Veeple.Online - Admin</title>
    <link rel="stylesheet" href="../style.css?v=<?php echo $catch; ?>">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../presetstyle.css?<?php echo $catch; ?>">
</head>
<body>
<div id="animatedBackground">
</div>
<?php
include '../header.php';
echo '<div class="full">';
?>
</br>
<button class="btn btn-primary" onclick="window.location.href = '../news.php';">Back to News</button>
<?php
if($loglevel < 4){
die('<h1>No Access</h1></br><h3>Please contact an admin to request admin privileges</h3>');
} else {
	?>
	<h1>Edit News Post</h1>
<?php
// Post
if( $_POST['newspost'] ) {   
        // Prepare an insert statement
        $postnewssql = "UPDATE news SET title=?, post=?, postby=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $postnewssql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_title, $param_post, $logname, $postid);
            
            // Set parameters
            $param_title = trim($_POST["title"]);
            $param_post = trim($_POST["post"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                ?>
<meta http-equiv="refresh" content="0;URL='https://www.veeple.online/news.php' />
<?php
            } else{
                echo "Something went wrong. Please try again later."; 
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    

    // Close connection
    mysqli_close($link);
}
?>
<form id="newspost" name="newspost" action="" method="post">
	<div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
  <p>
    <label for="title">Title:</label>
  </p>
  <p>
    <input name="title" type="text" class="form-control" required="required" id="title" value="<?php echo $title; ?>" size="100" maxlength="255">
  </p>
		<span class="help-block"><?php echo $title_err; ?></span>
	</div>
  <p>
<div class="form-group <?php echo (!empty($post_err)) ? 'has-error' : ''; ?>">
  <label for="post">Post:</br>
  </label>
  <textarea name="post" cols="96" rows="15" required="required" wrap="soft" class="form-control" id="post"><?php echo $post; ?></textarea>
    <span class="help-block"><?php echo $post_err; ?></span>
  </div>
  </p>
  <p>
	  <div class="form-group">
    <input type="submit" name="newspost" id="newspost" value="Update" class="btn btn-primary">
	</div>
  </p>
</form>

	<?php
}
if($debug == 1){
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
}
echo '</div>';
?>
<div class="footer">
<?php
echo $version;
?>
</div>
</div>  
</body>
</html>