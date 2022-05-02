<?php
include 'user_details_retriver.php';
if($access == 1){
    include 'connection.php';
    include 'check_blocked.php';
    include 'sms_check_blocked.php';
    include_once 'php_funs.php';
    include 'message_opened.php';

    date_default_timezone_set("Asia/Calcutta"); 
    $date = date('Y-m-d H:i:s');

    $message = filterInput($_POST['message']);
    $to_user = filterInput($_POST['to_user']);

    class response{
        var $result;
    }
    // Initilising the object.
    $response_obj = new response;
    // Checking whather the user is logged in or not.
    if($user_id == 0 || $user_id == $to_user || $message == "" || $to_user == "" || checkUsersSmsFlag($to_user) == 0){
        // Not Loged in.
        header('Location:/index.php');                 
    }else{
        // Sql query to check whether already messages or not
        $sql_check_message_exist = "SELECT * FROM secret_messages WHERE to_user_id = '$to_user' AND from_user_id = '$user_id'";
        // Executing query
        $sql_check_message_exist_result = $conn->query($sql_check_message_exist);
        if($sql_check_message_exist_result->num_rows > 0){
            while($row = $sql_check_message_exist_result->fetch_assoc()){
                $secret_message_id = $row['secret_message_id'];
            }
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
                    // Inserting in secret_messages 
                    $sql_send_message = "UPDATE secret_messages SET secret_message = '$message', opened = '0', last_user = '$user_id', time_stamp = '$date' WHERE secret_message_id = '$secret_message_id'";
                    // Executing query
                    if($conn->query($sql_send_message)){
                        // Inserting in secret_message_replies
                        $sql_send_message_reply = "INSERT INTO secret_messages_replies (secret_message_id,from_user_id,secret_reply,time_stamp) VALUES('$secret_message_id','$user_id','$message','$date')";
                        // Executing query
                        if($conn->query($sql_send_message_reply)){
                            $response_obj->result = '1';
                            $response_obj->block_code = '0';
                            echo json_encode($response_obj);
                        }
                    }
                }
            }
        }else{
            $response_obj->result = '0';
            $response_obj->block_code = '0';
            // Sql query to insert the data.
            $sql_send_message = "INSERT INTO secret_messages (opened,from_user_id,to_user_id,last_user,secret_message,time_stamp) VALUES('0','$user_id','$to_user','$user_id','$message','$date')";
            if($conn->query($sql_send_message)){
                // Getting the secret_message_id
                $sql_get_s_m_id = "SELECT secret_message_id AS s_m_id FROM secret_messages WHERE from_user_id='$user_id' AND to_user_id='$to_user' AND time_stamp='$date'";
                // Executing query
                $sql_get_s_m_id_result = $conn->query($sql_get_s_m_id);
                if($sql_get_s_m_id_result){
                    while($row = $sql_get_s_m_id_result->fetch_assoc()){
                        $s_m_id = $row['s_m_id'];
                    }
                    // Entering the message in secret_messages_replies table
                    $sql_send_message_reply = "INSERT INTO secret_messages_replies (secret_message_id,from_user_id,secret_reply,time_stamp) VALUES('$s_m_id','$user_id','$message','$date')";
                    // Executing.
                    if($conn->query($sql_send_message_reply)){
                        // Sending the notification.
                        $sql_sending_notification = "UPDATE users_data SET notification_status = '1' WHERE user_id = '$to_user'";
                        // Executing the query
                        if($conn->query($sql_sending_notification)){
                            $response_obj->result = '1';
                            $response_obj->block_code = '0';
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
                echo json_encode($response_obj);
            }
        }
    }
}else{
    header('Location:index.php');
}
?>