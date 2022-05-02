<?php
include 'user_details_retriver.php';
if($access == 1){
	include 'connection.php';
	include_once 'ago.php';

	echo"<div>
			<input type=text id=message_search_field onkeyup=searchMessages() placeholder='  Search Messages'>
			<div id=message_list>";
			// Updating the notification status
			$sql_notification_status = "UPDATE users_data SET notification_status = '0' WHERE user_id = '$user_id'";
			// Sql query to fetch messages;
			$sql_messages = "SELECT * FROM secret_messages LEFT JOIN users_data ON secret_messages.to_user_id = users_data.user_id WHERE secret_messages.to_user_id='$user_id' OR secret_messages.from_user_id = '$user_id' ORDER BY time_stamp DESC";
			// Executing query
			$sql_messages_result = $conn->query($sql_messages);
			if($sql_messages_result->num_rows > 0){
				// Executing the notification query.
				$conn->query($sql_notification_status);
				while($row = $sql_messages_result->fetch_assoc()){
					echo"<div class=messages id=msg_thread".$row['secret_message_id'].">
							<div class=thread_head id=thread_head".$row['secret_message_id']." onclick=messageBox(".$row['secret_message_id'].")>";

					if($user_id != $row['to_user_id']){
						echo 	"<img class='user_msg' src=".$row['display_pic'].">
									<div class=thread_face>
										<p class='thread_head_name'>"
											.ucfirst($row['first_name'])." ".ucfirst($row['last_name']).
										"</p>";
					}else{
							echo"<img class=user_msg src='https://vignette.wikia.nocookie.net/marsargo/images/5/52/Unknown.jpg/revision/latest?cb=20170904102656'>
									<div class=thread_face>
										<p class='thread_head_name'>
											Unknown
										</p>";
					}
					if($row['last_user'] == $user_id)
						echo 	"<div class='msg_details'><div class='msg_direction'><img src='https://image.flaticon.com/icons/png/512/13/13653.png' style='width:10px; height:12px; opacity:0.5;'></div> ";
					else{
						if($row['opened'] == '0')
							echo 	"<span id=head_text".$row['secret_message_id']." style='font-weight:bold;' ><div class='msg_details'><div class='msg_direction' ><img src='/used_images/in.png' style='width:10px; height:12px; opacity:0.5;'></div> ";
						else
							echo 	"<div class='msg_details'><div class='msg_direction' ><img src='/used_images/in.png' style='width:10px; height:12px; opacity:0.5;'></div>";
					}
					$display_msg = displayMsg($row['secret_message']);
					echo 			"<div class='msg_detail_txt'>".$display_msg."</div><div class='msg_detail_ago'> ".time_elapsed_string($row['time_stamp'])."</div></span></div>
									</div>
								</div>
							<div id=msg_box".$row['secret_message_id']."></div>
						</div>";
				}
			}else{
				echo"<div class=messages>No Messages</div>";
			}
		echo"</div>
	</div>";
}else{
	header('Location:index.php');
}

function displayMsg($secret_message){
	if(strlen($secret_message) > 25){
		return substr($secret_message,0,25)."...";
	}else{
		return $secret_message;
	}
}
?>