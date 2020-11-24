<?php
session_start();	//provide access to the current session

$_SESSION['validUser']=false;
session_unset();	//remove all session variables related to current session
session_destroy();	//remove current session
?>









<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Presenting Information Technology</title>
    <link rel="stylesheet" href="css/events.css"> 
</head>

<body>

<h2>You were logged out.</h2>
<p><a href="login.php">Login</a></p> 

</body>
  