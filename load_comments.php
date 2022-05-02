<?php
	include 'user_details_retriver.php';

	if($access == 1){
		
		include 'connection.php';
		include 'check_blocked.php';
		include 'ago.php';
		include_once 'php_funs.php';

		$message_id = filterInput($_POST["message_id"]);
		$amount = filterInput($_POST["amount"]);
		$total_amount = filterInput($_POST["total_amount"]);
		
		// Getting the total no. of comments.
		$sql_count_comments = "SELECT count(*) AS comments_count FROM comments WHERE message_id = $message_id ";
		// Exectuing the query and getting the no of comments.
		if($sql_count_comments_result = $conn->query($sql_count_comments)){
			while($row = $sql_count_comments_result->fetch_assoc()){
				$comments_count = $row['comments_count'];
			}
			// Updating the comments count in the messages table.
		    $sql_update_comments_count = "UPDATE messages SET comments = $comments_count WHERE message_id = $message_id";
		    if($conn->query($sql_update_comments_count)){
		    	if($comments_count >=5 && $amount < $comments_count){
					$offset = $comments_count - $amount ;
				}else{
					$offset = 0 ;
				}
				// Sql query to get the post owner.
				$sql_message_owner = "SELECT user_id FROM messages WHERE message_id = '$message_id'";
				// Executing query
				if($sql_message_owner_result = $conn->query($sql_message_owner)){
					while($row = $sql_message_owner_result->fetch_assoc()){
						$message_owner = $row['user_id'];
					}
				} 
				$sql_load_comments = "SELECT users_data.user_id , users_data.first_name , users_data.last_name , users_data.display_pic , comments.comment_id , comments.comment , comments.time_stamp FROM users_data INNER JOIN comments ON users_data.user_id = comments.user_id WHERE comments.message_id = '$message_id' ORDER BY comments.comment_id  LIMIT $amount OFFSET $offset ";
				// Executing SQL query
				if($sql_load_comments_result = $conn->query($sql_load_comments)){
					// New amount of comments.
					$new_amount = $amount + 5 ;
					if($amount <= $comments_count){
						echo "<p id=comments_status".$message_id." class=comments_status onclick=load_comments(".$message_id.",".$new_amount.",".$total_amount.")>show more comments</p>";
					}else{
						echo "<p id=comments_status".$message_id." class=comments_status >no more comments</p>";
					}
					while($row = $sql_load_comments_result->fetch_assoc()){
						$user_who_post=$row["user_id"];
						$comment_id=$row["comment_id"];
						// Blocked check
		                if(checkBlocked($row['user_id']) == 1 || checkBlocked($row['user_id']) == 2){
		                    continue;
		                 }
						if($user_id == $user_who_post || $user_id == $message_owner)
							echo"<div class=comment_heading>
									<div class=comment_icon onclick=open_user_profile(".$row['user_id'].")>
										<img class=comment_user src=".$row["display_pic"].">
									</div>
									<div class=comment_author>
										<p class=comment_heading_author><b>".ucfirst($row["first_name"])." ".ucfirst($row["last_name"])."</b></p>
										<p class=comment_body>".$row["comment"]."</p>
										<p class=comment_timestamp>".time_elapsed_string($row["time_stamp"])."</p>
									</div>
									<img src=used_images/delete.png id=delete_comment_button".$message_id." class=delete_comment_button onclick=delete_comment(".$message_id.",".$comment_id.",".$amount.",".$total_amount.")></img>
								</div>";
						else
							echo"<div class=comment_heading>
									<div class=comment_icon onclick=open_user_profile(".$row['user_id'].")>
										<img class=comment_user src=".$row["display_pic"].">
									</div>
									<div class=comment_author>
										<p class=comment_heading_author><b>".ucfirst($row["first_name"])." ".ucfirst($row["last_name"])."</b></p>
										<p class=comment_body>".$row["comment"]."</p>
										<p class=comment_timestamp>".time_elapsed_string($row["time_stamp"])."</p>
									</div>
								</div>";
					}
				}
		    }
		}
	}else{
		header('Location:index.php');
	}
?>