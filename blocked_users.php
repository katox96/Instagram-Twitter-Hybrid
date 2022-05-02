<?php
	include 'connection.php';
	include 'user_details_retriver.php';

	if($access == 1){
		// Sql query to find blocked accounts
		$sql_show_blocked = "SELECT * FROM blocked LEFT JOIN users_data ON blocked.him = users_data.user_id WHERE i_blocked = '$user_id'";
		// Executing query
		$sql_show_blocked_result = $conn->query($sql_show_blocked);
		if($sql_show_blocked_result->num_rows > 0){
			while($row = $sql_show_blocked_result->fetch_assoc()){
				echo"<div>
						<div class=user_heading onclick=open_user_profile(".$row['user_id'].")>
							<div class=user_icon><img class=user src=".$row['display_pic'].">
							</div>
							<div class=user_name>
								<p class=user_name_heading>
									<b>".ucfirst($row['first_name'])." ".ucfirst($row['last_name'])."</b>
								</p>
							</div>
						</div>
					</div>";
			}
		}else{
			echo"<div id=block_list>
					<p id=block_list_p>
						Empty Block List
					</p>
				</div>";
		}
	}else{
		header('Location:index.php');
	}
?>