<?php

include 'user_details_retriver.php';

if($access == 1){

    // Making the response class
    class response{
        var $total_comments;
        var $result;
    }

    // Initilising the object.
    $response_obj = new response;
    $response_obj->total_comments = '0';
    $response_obj->result = "failed";

    include 'connection.php';
    include_once 'php_funs.php';

    // Getting the message id.
    $message_id = filterInput($_POST['message_id']);

    // Sql to count the comments.
    $sql_count_comments = "SELECT count(*) AS total_comments FROM comments WHERE message_id='$message_id'";

    // Executing the query.
    $sql_count_comments_result = $conn->query($sql_count_comments);
    if($sql_count_comments_result->num_rows > 0){
        while($row = $sql_count_comments_result->fetch_assoc()){
           $total_comments = $row['total_comments'];
        }
        $response_obj->total_comments = $total_comments;
        $response_obj->result = "sucess";
        echo json_encode($response_obj);
    }else{
        echo json_encode($response_obj);
    }

}else{
    header('Location:index.php');
}
?>