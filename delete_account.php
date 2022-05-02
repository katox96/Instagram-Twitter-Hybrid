<?php

include 'user_details_retriver.php';

if($access == 1){

	include 'connection.php';
	// Sql query to delete account
	$sql_delete_users_data = "DELETE FROM users_data WHERE user_id = '$user_id'";
	// Sql query to delete comments
	$sql_delete_comments_data = "DELETE FROM comments WHERE user_id = '$user_id'";
	// Sql query to delete likes
	$sql_delete_likes = "DELETE FROM likes WHERE user_id = '$user_id'";
	// Sql query to messages
	$sql_delete_messages = "DELETE FROM messages WHERE user_id = '$user_id'";
	// Sql query to delete block entry
	$sql_delete_block = "DELETE FROM blocked WHERE user_id = '$user_id'";
	// Sql query to delete user details
	$sql_delete_user_details = "DELETE FROM users_details WHERE user_id = '$user_id'";
	// Executing queries
	if($conn->query($sql_delete_users_data) && $conn->query($sql_delete_comments_data) && $conn->query($sql_delete_likes) && $conn->query($sql_delete_messages) &&
	$conn->query($sql_delete_user_details)){
		header('Location:https://mail.google.com/mail/logout');
	}

}else{
	header('Location:index.php');
}

?>