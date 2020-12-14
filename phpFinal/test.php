<?php
session_cache_limiter('none');  //This prevents a Chrome error when using the back button to return to this page.
session_start();
echo "<h1>Session variable ".$_SESSION['validUser']."</h1>";
if (!$_SESSION['validUser']) {
	header('Location: recipeIndex.php');
}
?>