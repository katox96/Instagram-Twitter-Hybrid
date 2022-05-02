<?php

include 'user_details_retriver.php';
if($access == 1){

	include 'connection.php';
	include_once 'php_funs.php';

	$message_id = filterInput($_POST['message_id']);

	// SQL query to get user_id of the post auther.
	$sql_get_user_id = "SELECT * FROM messages WHERE message_id = $message_id";
	// SQL query to delete the message.
	$sql_delete_message = "DELETE FROM messages WHERE message_id = $message_id AND user_id = $user_id";
	// SQL query to delete the comments.
	$sql_delete_comments = "DELETE FROM comments WHERE message_id = $message_id";
	
	// Executing the query.
	$sql_get_user_id_result = $conn->query($sql_get_user_id);
	while($row = $sql_get_user_id_result->fetch_assoc()){
		$auther_user_id = $row['user_id'];
		$image_name = $row['imagename'];
	}

	// Image path to delete.
	$file = 'images/'.$image_name;
	// Checking if the auther wants to delete the post ?
	if($auther_user_id == $user_id){
		if($conn->query($sql_delete_message)){
			if($conn->query($sql_delete_comments))
				if(file_exists($file)){
					if(unlink($file)){
						echo "dlt";
					}else{
						echo "no";
					}
				}else{
					echo 'd';
				}
		}else{
			echo "0";
		}
	}else{
		echo "You cannot delete this message";
	}
}else{
	header('Location:index.php');
}

?>
