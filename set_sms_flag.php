<?php
include 'user_details_retriver.php';
if($access == 1){
	include 'connection.php';
	include_once 'php_funs.php';

	$sms_flag = filterInput($_POST['sms_flag']);

	$sql_update_sms_flag = "UPDATE users_data SET sms_flag = '$sms_flag' WHERE user_id = '$user_id'";
	// Executing query
	if($conn->query($sql_update_sms_flag)){
		echo "done";
	}
}else{
	header('Location:index.php');
}
?>