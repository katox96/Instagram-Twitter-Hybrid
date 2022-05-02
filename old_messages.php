<?php
include 'user_details_retriver.php';
if($access == 1){
	include 'connection.php';
    include 'message_opened.php';
    include 'msg_reply_count.php';
    include 'ago.php';
	include 'sms_check_blocked.php'; 
	include_once 'php_funs.php';
    $seen_flag = 0;


	$secret_message_id = filterInput($_POST['s_m_id']);
	$amount = filterInput($_POST['amount']);

	// Calculating the block string
	// Sql query for getting user_id of other user
	$sql_other_user = "SELECT * FROM secret_messages WHERE secret_message_id = '$secret_message_id' AND (to_user_id = '$user_id' OR from_user_id = '$user_id')";
	// Executing the query
	$sql_other_user_result = $conn->query($sql_other_user);
	if($sql_other_user_result->num_rows > 0){
		while($row = $sql_other_user_result->fetch_assoc()){
			if($row['from_user_id'] == $user_id)
				$other_user_id = $row['to_user_id'];
			else
				$other_user_id = $row['from_user_id'];
		}
	}
	if(smsCheckBlocked($secret_message_id) == 0)
		$block_string = "<font color=red id=block_btn".$secret_message_id." onclick=blockSmsUserOptions(".$secret_message_id.")> (Block)</font>";
	else{
		if(smsCheckBlocked($secret_message_id) == 1)
			$block_string = "<font color=red id=block_btn".$secret_message_id." onclick=un_blockSmsUser(".$secret_message_id.",0)> (Unblock)</font>";
		else
			$block_string = "<font color=black id=block_btn".$secret_message_id."> (Blocked)</font>";
	}
	// Sql query to verify that a user can reply or not
	$sql_verify = "SELECT * FROM secret_messages WHERE secret_message_id='$secret_message_id' AND (from_user_id='$user_id' OR to_user_id='$user_id')";
	// Executing the query
	$sql_verify_result = $conn->query($sql_verify); 
	if($sql_verify_result->num_rows > 0){
		while($row = $sql_verify_result->fetch_assoc()){
			if($row['last_user'] != $user_id){
				// Setting opened bit.
				setUnopened($secret_message_id,'1');
			}else{
				// checking seen status.
				if($row['opened'] == 1){
					$seen_flag = 1;
				}
			}
		}
		// Getting total replies
		$total_replies = msgReplyCounter($secret_message_id);
		// Calculating offset
		if($total_replies > $amount)
			$offset = $total_replies - $amount ;
		else
			$offset = 0 ;
		// Sql query to get messages
		$sql_get_messages = "SELECT * FROM secret_messages_replies WHERE secret_message_id='$secret_message_id' ORDER BY secret_reply_id LIMIT $amount OFFSET $offset";

		// More messages status.
		if($total_replies < $amount){
			echo "<p class='show_more_replies'>No more messages".$block_string."</p>";
		}else{
			$amount = $amount + 5;
			echo "<p class='show_more_replies' onclick=loadOldMsgs(".$secret_message_id.",".$amount.")>Show more".$block_string."</p>";
		}

		// Executing the query.
		$sql_get_messages_result = $conn->query($sql_get_messages);
		if($sql_get_messages_result->num_rows > 0){
			while($row = $sql_get_messages_result->fetch_assoc()){
				if($row['from_user_id'] == $user_id){
					// Seen or not
					echo"<div class=msg_container>
							<div class='speech-bubble-dsr tooltip'>
								<p class='reply_details tooltiptext'>".time_elapsed_string($row['time_stamp'])."</p>
								<p class=msg_text>".$row['secret_reply']."</p>
							</div>
						</div>";
					$seen_flag_final = 1;
				}else{
					echo"<div class=msg_container>
							<div class='speech-bubble-ds tooltip'>
								<p class='reply_details tooltiptext'>".time_elapsed_string($row['time_stamp'])."</p>
								<p class=msg_text>".$row['secret_reply']."</p>
							</div>
						</div>";
					$seen_flag_final = 0;
				}
			}
		}
		if($seen_flag == 1 && $seen_flag_final == 1){
			echo "<p class=seen>(Seen)</p>";
		}
	}
}else{
	header('Location:index.php');
}
?>