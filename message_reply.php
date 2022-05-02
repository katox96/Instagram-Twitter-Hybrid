<?php

include 'user_details_retriver.php';

if($access == 1){
    // Making the response class
    class response{
        var $result;
        var $block_code;
        var $sms_enable;
    }
    // Initilising the object.
    $response_obj = new response;
    $response_obj->sms_enable = '1';
   	$response_obj->result = '0';
   	$response_obj->block_code = '0';
    // Getting current date and time;
    date_default_timezone_set("Asia/Calcutta"); 
    $date = date('Y-m-d H:i:s');

	include 'connection.php';
    include 'message_opened.php';
    include 'sms_check_blocked.php';
    include_once 'php_funs.php';

		$secret_message_id = filterInput($_POST['s_m_id']);
		$msg_reply = filterInput($_POST['msg_reply']);

	   // Initilising the object.
	    $response_obj = new response;
	    // Checking if Blocked or not
		// Sql query to verify that a user can reply or not
		$sql_verify = "SELECT * FROM secret_messages WHERE (from_user_id='$user_id' OR to_user_id='$user_id') AND secret_message_id=$secret_message_id";
		// Executing the query
		$sql_verify_result = $conn->query($sql_verify); 
		if($sql_verify_result->num_rows > 0){
			// Getting the to_user_id value.
			while($row = $sql_verify_result->fetch_assoc()){
				if($user_id != $row['to_user_id']){
					$to_user_id = $row['to_user_id'];
				}else{
					$to_user_id = $row['from_user_id'];
				}
			}
			if(checkUsersSmsFlag($to_user_id) == 1){
				// Getting block status.
			    if(smsCheckBlocked($secret_message_id) == 1){
			        $response_obj->result = '0';
			        $response_obj->block_code = '1';
			        echo json_encode($response_obj);    
			    }else{
			        if(smsCheckBlocked($secret_message_id) == 2){
			            $response_obj->result = '0';
			            $response_obj->block_code = '2';
			            echo json_encode($response_obj); 
			        }else{
						// Inserting the secret message reply.
						$sql_msg_reply = "INSERT INTO secret_messages_replies (secret_message_id,from_user_id,secret_reply,time_stamp) VALUES('$secret_message_id','$user_id','$msg_reply','$date')";
						// Executing the query.
						if($conn->query($sql_msg_reply)){
							// Updating the secret_messages table entry
							$sql_update_old_entry = "UPDATE secret_messages SET secret_message = '$msg_reply', last_user = '$user_id', opened = '0', time_stamp = '$date' WHERE secret_message_id = '$secret_message_id'";
							// Executing the query.
							if($conn->query($sql_update_old_entry)){
								// Sending the notification to other user
								$sql_send_notification = "UPDATE users_data SET notification_status = '1' WHERE user_id = '$to_user_id'";
								//Executing the query
								if($conn->query($sql_send_notification)){
				                    // Setting opened bit.
				                    if(setUnopened($secret_message_id,'0')){
										$response_obj->result = '1';
									    $response_obj->block_code = '0';	
										echo json_encode($response_obj);                        
				                    }

								}else{
									echo json_encode($response_obj);		
								}
							}
						}else{
							echo json_encode($response_obj);
						}	        	
			        }
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