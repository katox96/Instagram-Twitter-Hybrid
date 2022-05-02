<?php

include 'user_details_retriver.php';
if($access == 1){
	class response{
		var $total_comments;
		var $result;
	}

	$response_obj = new response;
	$response_obj->total_comments = "0";
	$response_obj->result = "failed";

	date_default_timezone_set("Asia/Calcutta"); 
	$date = date('Y-m-d H:i:s');

	include 'connection.php';
	include_once 'php_funs.php';

	$message_id = filterInput($_POST["message_id"]); // Receiving message_id 
	$comment = filterInput($_POST["comment"]);
	// SQL qurey for the comment 
		$sql_comment="INSERT INTO comments (user_id,message_id,comment,time_stamp) VALUES($user_id,$message_id,'$comment','$date')";
		$sql_comment_counter="UPDATE messages SET comments=comments+1 WHERE message_id='$message_id'";	
		// Getting comment count
		$sql_comments_count="SELECT * FROM messages WHERE message_id='$message_id'";
		// Executing the query
		$sql_comments_count_result = $conn->query($sql_comments_count);
		if($sql_comments_count_result->num_rows > 0){			
			// Executing the query
			$sql_comments_count_result = $conn->query($sql_comments_count);			
			// Fetching result array.
			while($row = $sql_comments_count_result->fetch_assoc()){
				$total_comments =  $row['comments'];
			}
			// Inserting comment and updating the count.
			if($conn->query($sql_comment_counter)){
				if($conn->query($sql_comment)){
					$response_obj->total_comments = $total_comments+1;
					$response_obj->result = "sucess";
					echo json_encode($response_obj);
				}else{
					echo json_encode($response_obj);
				}
			}else{
				echo json_encode($response_obj);
			}
		}else{
			echo json_encode($response_obj);
		}
}else{
	header('Location:index.php');
}

?>