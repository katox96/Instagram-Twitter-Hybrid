<?php
include 'user_details_retriver.php';
if($access == 1){
	echo $notification_status;
}else{
	header('Location:index.php');
}
?>