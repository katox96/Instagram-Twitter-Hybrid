<?php
	// Function to check if already blocked or not.
	function checkBlocked($block_user_id){
		include 'connection.php';
		include 'user_details_retriver.php';

		if($access == 1){
			// Sql query for checking block status.
		    $sql_check_blocked = "SELECT * FROM blocked WHERE i_blocked = '$user_id' AND him = '$block_user_id'";
		    // Executing query
		    $sql_check_blocked_result = $conn->query($sql_check_blocked);
		    if($sql_check_blocked_result->num_rows > 0){
		    	return 1;
		    }else{
		    	// Checking if they has blocked.
		    	$sql_check_they_blocked = "SELECT * FROM blocked WHERE i_blocked = '$block_user_id' AND him = '$user_id'";
		    	// Executing query
		    	$sql_check_they_blocked_result = $conn->query($sql_check_they_blocked);
		    	if($sql_check_they_blocked_result->num_rows > 0){
		    		return 2;
		    	}else{
		    		return 0;
		    	}
		    }
		}else{
			header('Location:index.php');
		}
	}
?>