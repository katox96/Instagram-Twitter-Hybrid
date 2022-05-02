<?php
include 'user_details_retriver.php';
if($access == 1){
	include 'connection.php';
	include 'check_blocked.php';
	include_once 'php_funs.php';


	$search_string = filterInput($_POST['search_string']);
	if($search_string != ""){
		$search_string = explode(" ", $search_string);
		if(sizeof($search_string) != '2'){
			// Sql query to search.
			$sql_search_users = "SELECT * FROM users_data WHERE first_name LIKE '$search_string[0]%' AND NOT user_id='$user_id'";
		}else{
			// Sql query to search.
			$sql_search_users = "SELECT * FROM users_data WHERE first_name LIKE '$search_string[0]%' AND last_name LIKE '$search_string[1]%' AND NOT user_id='$user_id'";
		}
		// Executing the query.
		$sql_search_users_result = $conn->query($sql_search_users);
		// Fetching the results.
		if($sql_search_users_result->num_rows > 0){
			while($row = $sql_search_users_result->fetch_assoc()){
				if(checkBlocked($row['user_id']) == 2){
					continue;
				}
				echo"<div class=user_heading onclick=open_user_profile(".$row['user_id'].")>
						<div class=user_icon><img class=user src=".$row['display_pic'].">
						</div>
						<div class=user_name>
							<p class=user_name_heading>
								<b>".ucfirst($row['first_name'])." ".ucfirst($row['last_name'])."</b>
							</p>
							<p class=user_email></p>
						</div>
					</div>";
			}
		}else{
			echo "No user found";
		}
	}
}else{
	header('Location:index.php');
}
?>
