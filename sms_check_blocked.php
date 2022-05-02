<?php
	// Function to check if already blocked or not.
	function smsCheckBlocked($s_m_id){
		// Making Connection
		include 'connection.php';
		// Retriving User Details
		include 'user_details_retriver.php';
	    // Checking whather the user is logged in or not.
	    if($user_id==0){
	        // Not Loged in.
	        header('Location:/index.php');                 
	    }
	    // Sql query for checking block status.
	    $sql_check_blocked = "SELECT * FROM secret_messages WHERE secret_message_id = '$s_m_id'";
	    // Executing query
	    if($sql_check_blocked_result = $conn->query($sql_check_blocked)){
	    	while($row = $sql_check_blocked_result->fetch_assoc()){
		    	
		    	// Getting other user's user_id
		    	if($row['from_user_id'] == $user_id)
		    		$other_user_id = $row['to_user_id'];
		    	else
		    		$other_user_id = $row['from_user_id'];
		    	
		    	// Checking block status
		    	if($row['blocked_by'] == $user_id){
		    		// I have blocked.
		    		return 1;
		    	}else{
		    		if($row['blocked_by'] == $other_user_id)
		    			// They have blocked.
		    			return 2;
		    		else 
		    			// Not blocked
		    			return 0;
		    	}
	    	}
	    }
	}
?>