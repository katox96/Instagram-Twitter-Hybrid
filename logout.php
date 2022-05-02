<?php
include 'connection.php';
include 'random_code.php';
// Session Tokken Generation
$pre_session_tokken = $_COOKIE["session_tokken"];
$session_tokken=random_code(40);
// SQL Inserting Tokken To The Table
$sql_session_tokken="UPDATE users_data  SET session_tokken='$session_tokken' WHERE session_tokken='$pre_session_tokken'";
// Executing the Tokken Saving Query
if($conn->query($sql_session_tokken)){
	setcookie('session_tokken',$session_tokken,time() - (86400 * 30),'/');
	header('Location:https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost');
	}
?>
