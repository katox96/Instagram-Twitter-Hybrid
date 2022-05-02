<?php
include 'user_details_retriver.php';
if($access == 1){
    $secret_message_id = filterInput($_POST['s_m_id']);
    $block = filterInput($_POST['block']);
    // Calling function
    SmsUn_Block($secret_message_id,$block);
}else{
	header('Location:index.php');
}

    function SmsUn_Block($secret_message_id,$block){
	    include 'user_details_retriver.php';
	    include_once 'php_funs.php';
		include 'connection.php';
		include 'sms_check_blocked.php';
	    class response{
	        var $result;
	    }
	    // Initilising the object.
	    $response_obj = new response;
	    $response_obj->result = '0';
		if($block == 1){
			// Block user
			// Checking whether already blocked or not.
			if(smsCheckBlocked($secret_message_id) == 0){
				// Sql query to block the user
				$sql_sms_block_user = "UPDATE secret_messages SET blocked_by = '$user_id' WHERE secret_message_id = '$secret_message_id'";
				// Executing query
				if($conn->query($sql_sms_block_user)){
					$response_obj->result = '1';
					echo json_encode($response_obj); 
				}else{
					echo json_encode($response_obj); 
				}
			}else{
				echo json_encode($response_obj); 					
			}
		}else{
			// Unblock user
			// Checking whether already blocked or not.
			if(smsCheckBlocked($secret_message_id) == 1){
				// Sql query to unblock user
				$sql_sms_unblock_user = "UPDATE secret_messages SET blocked_by = '0' WHERE secret_message_id = '$secret_message_id'";
				// Executing query
				if($conn->query($sql_sms_unblock_user)){
					$response_obj->result = '1';
					echo json_encode($response_obj); 
				}else{
					echo json_encode($response_obj); 
				}
			}else{
				echo json_encode($response_obj); 					
			}
		}
			
    }
?>