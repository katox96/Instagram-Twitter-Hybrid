<?php

include 'user_details_retriver.php';

if($access == 1){

	// Making the response class
	class response{
		var $total_likes;
		var $result;
	}
	// Initilising the object.
	$response_obj = new response;
	$response_obj->total_likes = "0";
	$response_obj->result = "failed";

	include 'connection.php'; 
	include_once 'php_funs.php';

	$message_id = filterInput($_GET["message_id"]); // Receiving message_id

	// Check whather message exists or not.
	$sql_check_message = "SELECT * FROM messages WHERE message_id = '$message_id'";


	// Checking whather user has liked the pic or not.
	$sql_check_liked = "SELECT * FROM likes WHERE message_id='$message_id' && user_id='$user_id'";

	// Executing queries.
	$sql_check_message_result = $conn->query($sql_check_message);
	$sql_check_liked_result = $conn->query($sql_check_liked);

	if($sql_check_liked_result->num_rows <= 0 && $sql_check_message_result->num_rows > 0 ){

		$sql_like = "UPDATE messages SET likes=likes+1 WHERE message_id=$message_id"; // Sql query to update likes

		$sql_user_liked ="INSERT INTO likes VALUES($message_id,$user_id)";

		if($conn->query($sql_like) && $conn->query($sql_user_liked)){
			
			// Getting the amount of total likes.
			$sql_like_count = "SELECT likes AS total_likes FROM messages WHERE message_id=$message_id";

			// Executin query.
			if($sql_like_count_result = $conn->query($sql_like_count)){

				// Fetching the total_likes value.
				while($row = $sql_like_count_result->fetch_assoc()){
					$total_likes = $row['total_likes'];
				}
				$response_obj->total_likes = $total_likes;
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
