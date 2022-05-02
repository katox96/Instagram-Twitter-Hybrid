<?php
function filterInput($string){
	include 'connection.php';
	return mysqli_real_escape_string($conn, htmlentities(trim($string), ENT_QUOTES)); 
}

function checkUsersSmsFlag($user_id){
	// Making Connection
	include 'connection.php';
	// Sql query to check Sms flag
	$sql_check_sms_flag = "SELECT sms_flag FROM users_data WHERE user_id = '$user_id'";
	// Executing query
	$sql_check_sms_flag_result = $conn->query($sql_check_sms_flag);
	while($row = $sql_check_sms_flag_result->fetch_assoc())
		return $row['sms_flag'];
}

function lastProfileVisit($profile_user_id) {
	// Making Connection
	include 'connection.php';
	// Retriving User Details
	include 'user_details_retriver.php';
	// Getting current date and time;
	date_default_timezone_set("Asia/Calcutta"); 
	$now = date('Y-m-d H:i:s');
	// Sql query to check the last visit
	$sql_check_last_visit = "SELECT time_stamp AS last_time FROM profile_visits WHERE i_visited = '$user_id' AND him = '$profile_user_id'";
	$sql_check_last_visit_result = $conn->query($sql_check_last_visit);
	if($sql_check_last_visit_result->num_rows >0){
		while($row = $sql_check_last_visit_result->fetch_assoc())
			$last_time = $row['last_time'];
		$last_time = strtotime($now) - strtotime($last_time);

		return ($last_time/3600);
	}else{
		return 0;
	}
}

function makeProfileVisit($profile_user_id) {
	// Making Connection
	include 'connection.php';
	// Retriving User Details
	include 'user_details_retriver.php';
	// Getting current date and time;
	date_default_timezone_set("Asia/Calcutta"); 
	$now = date('Y-m-d H:i:s');
	// Updating the profiel_visits count in users_data table
	$sql_update_count = "UPDATE users_data SET profile_visits = profile_visits + 1 WHERE user_id = '$profile_user_id'";
	// Checking last visit
	if(lastProfileVisit($profile_user_id) > 24){
		// Updating the time stamp in profile_visits table
		$sql_update_time_stamp = "UPDATE profile_visits SET time_stamp = '$now' WHERE i_visited = '$user_id' AND him = '$profile_user_id'";
		// Executing queries
		if($conn->query($sql_update_time_stamp)){
			if($conn->query($sql_update_count)){
			}
		}
	}else{
		if(lastProfileVisit($profile_user_id) == 0){
			// Inserting new row in profile_visits
			$sql_insert_visit = "INSERT INTO profile_visits (i_visited,him,time_stamp) VALUES ('$user_id','$profile_user_id','$now')";
			// Executing query
			if($conn->query($sql_insert_visit)){
				if($conn->query($sql_update_count)){

				}
			}
		}
	}
}

function sumMessages($user_id){
	// Making Connection
	include 'connection.php';
	// Sql query to count messages
	$sql_count_messages = "SELECT count(*) AS total FROM messages WHERE user_id = '$user_id'";
	// Executing query
	$sql_count_messages_result = $conn->query($sql_count_messages);
	while($row = $sql_count_messages_result->fetch_assoc())
		return $row['total'];
}

// PHP function to check whether the user have liked the post or not ?
function check_user($message_id,$user_id){
    include 'connection.php';
    if($user_id!="" && $user_id!=0){
        // Sql to check whether liked or not
        $sql_check_user_like="SELECT * FROM likes WHERE user_id=$user_id && message_id=$message_id";
        // Checking results of the Query
        if($conn->query($sql_check_user_like)->num_rows > 0){
            return 1 ;
        }else{
            return 0 ;
        }
    }else{
        return 0 ;
    }
}
?>
