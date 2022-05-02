<?php	
	include 'user_details_retriver.php';
	include_once 'php_funs.php';
	if($access == 1){
		$message_id = filterInput($_GET['m_id']);
		reportPost($message_id);
	}else{
		header('Location:index.php');
	}

	function reportPost($message_id){
		// Making Connection
		include 'connection.php';
		// Retriving User Details
		include 'user_details_retriver.php';
		if(!checkReported($message_id) && !checkMYPost($message_id)){
			// Sql query to report a post
			$sql_report_post = "UPDATE messages SET reports = reports + 1 WHERE message_id = '$message_id'";
			// Sql querty to insert report
			$sql_report_insert = "INSERT INTO reports  VALUES('$message_id',$user_id)";
			// Executing query
			if($conn->query($sql_report_post) && $conn->query($sql_report_insert)){
				echo "done";
			}
		}
	}

	function checkReported($message_id){
		// Making Connection
		include 'connection.php';
		// Retriving User Details
		include 'user_details_retriver.php';
		// Sql query to check if reported
		$sql_check_reported = "SELECT * FROM reports WHERE message_id = '$message_id' AND user_id = '$user_id'";
		// Executing query
		if($conn->query($sql_check_reported)->num_rows > 0){
			return 1;
		}else{
			return 0;
		}
	}

	function checkMyPost($message_id){
		// Making Connection
		include 'connection.php';
		// Retriving User Details
		include 'user_details_retriver.php';
		// Sql query to check who posted
		$sql_check_my_post = "SELECT * FROM messages WHERE message_id = '$message_id'";
		// Executing query
		if($sql_check_my_post_result = $conn->query($sql_check_my_post)){
			while($row = $sql_check_my_post_result->fetch_assoc()){
				if($user_id == $row['user_id']){
					return 1;
				}else{
					return 0;
				}
			}
		}
	}
?>
