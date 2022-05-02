<?php
include 'user_details_retriver.php';
if($access == 1){
    include 'connection.php';
    include 'compress_image.php';
    include'random_code.php';
    include_once 'php_funs.php';
    // Response class
    class response{
        var $line1;
        var $line2;
        var $line3;
        var $line4;
        var $dp_update;
    }
    $response_obj = new response;
    $response_obj->dp_update = '0';
    // Checking whather the user is logged in or not.
    if($user_id==0){
        // Not Loged in.
        header('Location:/index.php');                 
    }

    // Getting the data.
    $line1 =  filterInput($_POST['line1']);
    $line2 =  filterInput($_POST['line2']);
    $line3 =  filterInput($_POST['line3']);
    $line4 =  filterInput($_POST['line4']);

    $response_obj->line1 = $line1;
    $response_obj->line2 = $line2;
    $response_obj->line3 = $line3;
    $response_obj->line4 = $line4;
    // Sql query to check if details are filled or not.
    $sql_check_user_details = "SELECT * FROM users_details WHERE user_id = '$user_id'";

    $sql_check_user_details_result = $conn->query($sql_check_user_details);

    if($sql_check_user_details_result->num_rows == 0){
        // Query to insert the data.
        $sql_insert_user_details = "INSERT INTO users_details (user_id,line1,line2,line3,line4) VALUES('$user_id','$line1','$line2','$line3','$line4')";

        if($conn->query($sql_insert_user_details)){
            uploadDp($response_obj);
        }
    }else{
        $sql_user_details = "UPDATE users_details SET line1='$line1', line2='$line2', line3='$line3' , line4='$line4' WHERE user_id='$user_id'";

        if($conn->query($sql_user_details)){
            uploadDp($response_obj);
        }
    }
}else{
    header('Location:index.php');
}

function uploadDp($response_obj){
    // Making Connection
    include 'connection.php';
    // Retriving User Details
    include 'user_details_retriver.php';
    // Defining variables
    $height = "0";
    $width = "0";
    $quality = 40;
    if(isset($_FILES['dp']['name']) && @$_FILES['dp']['name'] != ""){
        if($_FILES['dp']['error'] == 0){
            if($_FILES['dp']['size'] / 1024 <= 5120) { // 5MB
                if($_FILES['dp']['type'] == 'image/jpeg' || $_FILES['dp']['type'] == 'image/pjpeg' || $_FILES['dp']['type'] == 'image/png'){
                    $source_file = $_FILES['dp']['tmp_name'];
                    $target_file = "images/".random_code(40).random_code(40);
                    $quality = 40;
                    // Compressing and uploading file.
                    if(compress_image($source_file,$target_file,$width,$height,$quality,)){
                        // Inserting the image name in the table
                        if($conn->query("UPDATE users_data SET display_pic = '$target_file' WHERE user_id = $user_id")){
                            $response_obj->dp_update = '1';
                        }
                    }
                }
            }
        }
    }
    echo json_encode($response_obj);
}
?>
