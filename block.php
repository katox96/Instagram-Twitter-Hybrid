<?php
	include_once 'php_funs.php';
	include 'connection.php';
	include 'user_details_retriver.php';
	include 'check_blocked.php';
	
    // Getting parameters
    $block_user_id = filterInput($_GET['block_user_id']);
    $block = filterInput($_GET['block']);

    // Block unblock funtion
    function Un_Block($block_user_id,$block){
	    // Making the response class
	    class response{
	        var $result;
	    }
	    // Initilising the object.
	    $response_obj = new response;
	    $response_obj->result = '0';
	    // Checking whather the user is logged in or not.
	    if($user_id == 0 || $user_id == $block_user_id){
	        // Not Loged in.
	        header('Location:/index.php');                 
	    }
	    // Checking whether already blocked or not
	    if(checkBlocked($block_user_id) == 0){
	    	// Sql query to block the user
	    	$sql_block_user = "INSERT INTO blocked (i_blocked,him) VALUES('$user_id','$block_user_id')";
	    	// Executing the query
	    	if($conn->query($sql_block_user)){
	    		$response_obj->result = '1';
				echo json_encode($response_obj); 
	    	}else{
				echo json_encode($response_obj); 
	    	}
	    }else{
			echo json_encode($response_obj); 
	    }
    }
?>