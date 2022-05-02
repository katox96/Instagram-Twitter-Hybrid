<?php

include 'user_details_retriver.php';

if($access == 1){

	include 'connection.php';
	include_once 'php_funs.php';
	
	// Getting needed Variables;
	$message_id = filterInput($_POST["message_id"]);
	$comment_id = filterInput($_POST["comment_id"]);

	// Checking whether the user can delete the comment or not
	// For the user who posted the comment
	$sql_for_comment_author = "SELECT user_id FROM comments WHERE comment_id = '$comment_id'";
	// For the user who posted the post
	$sql_for_post_author = "SELECT user_id FROM messages WHERE message_id = '$message_id'";

	$sql_for_comment_author_result = $conn->query($sql_for_comment_author);

	while($row = $sql_for_comment_author_result->fetch_assoc())
		$comment_author = $row['user_id'];

	if($user_id == $comment_author){
		deleteComment($comment_id,$message_id);
	}else{

		$sql_for_post_author_result = $conn->query($sql_for_post_author);

		while($row = $sql_for_post_author_result->fetch_assoc())
			$post_author = $row['user_id'];

		if($user_id == $post_author){
			deleteComment($comment_id,$message_id);
		}
	}



}else{
	header('Location:index.php');
}

function deleteComment($comment_id, $message_id){
	include 'connection.php';
	class response{
		var $result;
	}

	$response_obj = new response;
	$response_obj->result = 0;
	// Sql query for deleting comment.
	$sql_delete_comment = "DELETE FROM comments WHERE comment_id='$comment_id'";
	// Sql query to decrease the counter.
	$sql_reduce_comment_counter = "UPDATE messages SET comments=comments-1 WHERE message_id='$message_id'";
	// Executing the sql query.
	if($conn->query($sql_delete_comment)){
		$conn->query($sql_reduce_comment_counter);
		$response_obj->result =1;
	    echo json_encode($response_obj);
	}else{
	    echo json_encode($response_obj);
	}
}

?>
