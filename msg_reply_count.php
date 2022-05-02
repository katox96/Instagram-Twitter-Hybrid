<?php
	function msgReplyCounter($s_m_id){
	    // Making Connection
		include 'connection.php';
		// Sql query to count.
		$sql_count_reply = "SELECT count(*) AS mrc FROM secret_messages_replies WHERE secret_message_id='$s_m_id'";
		// Executing query.
		if($sql_count_reply_result = $conn->query($sql_count_reply)){
			while($row = $sql_count_reply_result->fetch_assoc()){
				$count = $row['mrc'];
			}
		}
		$conn->close();
		return $count;
	}
?>