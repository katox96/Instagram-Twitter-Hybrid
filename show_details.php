<?php
include 'user_details_retriver.php';
if($access == 1){
	include 'connection.php';
    include 'check_blocked.php';
    include_once 'php_funs.php';

    $message_id = filterInput($_POST['m_id']);

	class response{
	    var $date;
	    var $likes;
	    var $comments;
	    var $reports;
	}
	// Initilising the object
	$response_obj = new response;
	// Sql query to reterive details
	$sql_get_details = "SELECT time_stamp,likes,comments,reports FROM messages WHERE message_id = '$message_id'";
	// Executing query
	$sql_get_details_result = $conn->query($sql_get_details);
	if($sql_get_details_result->num_rows > 0){
		while($row = $sql_get_details_result->fetch_assoc()){
			$response_obj->date = $row['time_stamp'];
			$response_obj->likes = $row['likes'];
			$response_obj->comments = $row['comments'];
			$response_obj->reports = $row['reports'];
		}
		echo json_encode($response_obj);
	}
}else{
	header('Location:index.php');
}
?>