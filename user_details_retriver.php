<?php

include 'connection.php';
include_once 'php_funs.php';


$user_id="";
$first_name="";
$last_name="";
$user_name="";
$notification_status="";
$access = 0;

if(isset($_COOKIE["session_tokken"])){
	$session_tokken=filterInput($_COOKIE["session_tokken"]);
}else{
	$session_tokken="";
}
if($session_tokken!=""){
	// Retriving User_name
	$sql_user_name="SELECT * FROM users_data WHERE session_tokken='$session_tokken'";
	// Executing the query
	$user_name_result=$conn->query($sql_user_name);
	if($user_name_result && $user_name_result->num_rows >0){
		$row = $user_name_result->fetch_assoc();
		$user_id=$row["user_id"];
		$first_name=ucfirst($row["first_name"]);
		$last_name=ucfirst($row["last_name"]);
		$user_name=$first_name." ".$last_name;
		$profile_pic=$row["display_pic"];
		$notification_status=$row['notification_status'];
	}
}
if($user_id != ""){
	$access = 1;
}
?>

