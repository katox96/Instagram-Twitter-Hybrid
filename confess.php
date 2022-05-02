<?php
include 'user_details_retriver.php';

if($access == 1){
  $sql = "1";
  $height="0";
  $width="0";

  include 'connection.php';
  include 'compress_image.php';
  include 'random_code.php';
  include_once 'php_funs.php';

  // Getting current date and time;
  date_default_timezone_set("Asia/Calcutta"); 
  $date = date('Y-m-d H:i:s');

  // Response class
  class response{
    var $type;
    var $size;
  }

  // Initilising object
  $response_obj = new response;
  $response_obj->type = '1';
  $response_obj->size = '1';


  $msg = filterInput($_POST['confession_msg']);
  $anonymous_flag = filterInput($_GET["anon_c"]);
  
  if($msg==""){
    header('Location:/homepage.php');
  }else{
    if($anonymous_flag == 1){
      // Post anonymously.
      $sql="INSERT INTO messages (message,user_id,time_stamp) VALUES('$msg','$user_id','$date')";
      echo json_encode($response_obj);
    }else{
      if(isset($_FILES['uploadImg']['name']) && @$_FILES['uploadImg']['name'] != "") {
        if($_FILES['uploadImg']['error'] > 0) {
          //  echo '<h4>Increase post_max_size and upload_max_filesize limit in php.ini file.</h4>';
        }else{
          $name1 = random_code(40);
          $name2 = random_code(40);
          $target_file_temp = $name1.$name2;
          if($_FILES['uploadImg']['size'] / 1024 <= 12240) { // 10MB
            if($_FILES['uploadImg']['type'] == 'image/jpeg' || 
              $_FILES['uploadImg']['type'] == 'image/pjpeg' || 
              $_FILES['uploadImg']['type'] == 'image/png'){          
              $source_file = $_FILES['uploadImg']['tmp_name'];
              $target_file = "images/".$target_file_temp;
              //$width      = $_POST['width'];
              //$height     = $_POST['height'];
              $quality    = 40;
              //$image_name = $_FILES['uploadImg']['name'];
              $success = compress_image($source_file, $target_file, $width, $height, $quality);
              if($success) {
                // Optional. The original file is uploaded to the server only for the comparison purpose.
                // copy($source_file, "uploads/original_" . $_FILES['uploadImg']['name']);
                // Post with name;
                $sql="INSERT INTO messages (message,user_id,author,imagename,time_stamp) VALUES('$msg','$user_id','$user_name','$target_file_temp','$date')";
                echo json_encode($response_obj);
              }
            }else{
              $response_obj->type = '0';
              echo json_encode($response_obj);
            }
          }else{
            $response_obj->size = '0';
            echo json_encode($response_obj);
          }
        }
      }else{
        // Post with name;
        $sql="INSERT INTO messages (message,user_id,author,time_stamp) VALUES('$msg','$user_id','$user_name','$date')";
        echo json_encode($response_obj);
      }
    }
  // Executing query
  $conn->query($sql);
  }
}else{
  Location('Location:index.php');
}
?>
