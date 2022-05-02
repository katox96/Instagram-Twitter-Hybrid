<?php

include 'user_details_retriver.php';

if($access == 1){
	include 'connection.php';

	$time_now = time();
	$time_last = $time_now - 86400;
	$i=0;

	$sql_last_joinded = "SELECT * FROM users_data WHERE join_time_stamp BETWEEN '$time_last' AND '$time_now' AND NOT user_id = '$user_id'";
	$sql_last_joinded_result = $conn->query($sql_last_joinded);

	if($sql_last_joinded_result->num_rows > 0){

		echo "<label class='lst_joind_lvl'>Users joined in last 24 hrs</label>";
		while($row = $sql_last_joinded_result->fetch_assoc()){
			echo"<div class=user_heading onclick=open_user_profile(".$row['user_id'].")>
				<div class=user_icon>
					<img class=user src=".$row['display_pic'].">
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
		echo "<lable class='lst_joind_lvl'>Nobody joined in last 24 hrs</label>";
	}

}else{
	header('Location:index.php');
}

?>
