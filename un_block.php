<?php
include 'user_details_retriver.php';
if($access == 1){
	include_once 'php_funs.php';
	$block_user_id = filterInput($_POST['block_user_id']);
    $block = filterInput($_POST['block']);
    Un_Block($block_user_id,$block);
}else{
	header('Location:index.php');
}

    function Un_Block($block_user_id,$block){
	  	include 'connection.php';
		include 'check_blocked.php';
		include 'user_details_retriver.php';
	    
	    class response{
	        var $result;
	    }
	    // Initilising the object.
	    $response_obj = new response;
	    $response_obj->result = '0';
	    if($block == 1){
	    	// Block user
		    // Checking whether already blocked or not
		    if(checkBlocked($block_user_id) == 0){
		    	// Sql query to block the user
		    	$sql_block_user = "INSERT INTO blocked (i_blocked,him) VALUES('$user_id','$block_user_id')";
		    	// Executing the query
		    	if($conn->query($sql_block_user)){
		    		$response_obj->result = '1';
					echo json_encode($response_obj); 
		    	}else{;
					echo json_encode($response_obj); 
		    	}

		    }else{
				echo json_encode($response_obj); 
		    }
	    }else{
		    // Checking whether already blocked or not
		    if(checkBlocked($block_user_id) == 1){
		    	// Sql query to block the user
		    	$sql_unblock_user = "DELETE FROM blocked WHERE i_blocked = '$user_id' AND him = '$block_user_id'";
		    	// Executing the query
		    	if($conn->query($sql_unblock_user)){
		    		$response_obj->result = '1';
					echo json_encode($response_obj); 
		    	}else{
					echo json_encode($response_obj); 
		    	}

		    }else{
				echo json_encode($response_obj); 
		    }
	    }
    }
?>