<?php
	function setUnopened($s_m_id,$opened){
		include 'connection.php';
		
		// Sql query to update the bit.
		$sql_set_unopen = "UPDATE secret_messages SET opened = $opened WHERE secret_message_id = '$s_m_id'";
		// Executing query.
		if($conn->query($sql_set_unopen))
			return '1';
		else
			return '0';
	}
?>