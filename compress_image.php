<?php
  function compress_image($source_file, $target_file, $nwidth, $nheight, $quality) {
    //Return an array consisting of image type, height, widh and mime type.
    $image_info = getimagesize($source_file);
    if(!($nwidth > 0)) $nwidth = $image_info[0];
    if(!($nheight > 0)) $nheight = $image_info[1];
      
    if(!empty($image_info)) {
      switch($image_info['mime']) {
        case 'image/jpeg' :
          if($quality == '' || $quality < 0 || $quality > 100) $quality = 75; //Default quality
          // Create a new image from the file or the url.
          $image = imagecreatefromjpeg($source_file);
          $thumb = imagecreatetruecolor($nwidth, $nheight);
          //Resize the $thumb image
          imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
          // Output image to the browser or file.
          return imagejpeg($thumb, $target_file, $quality); 
           
          break;
          
        case 'image/png' :
          if($quality == '' || $quality < 0 || $quality > 9) $quality = 6; //Default quality
          // Create a new image from the file or the url.
          $image = imagecreatefrompng($source_file);
          $thumb = imagecreatetruecolor($nwidth, $nheight);
          //Resize the $thumb image
          imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
          // Output image to the browser or file.
          return imagepng($thumb, $target_file, $quality);
          break;
            
        case 'image/gif' :
          if($quality == '' || $quality < 0 || $quality > 100) $quality = 75; //Default quality
          // Create a new image from the file or the url.
          $image = imagecreatefromgif($source_file);
          $thumb = imagecreatetruecolor($nwidth, $nheight);
          //Resize the $thumb image
          imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
          // Output image to the browser or file.
          return imagegif($thumb, $target_file, $quality); //$success = true;
          break;
            
        default:
          echo "<h4>Not supported file type!</h4>";
          break;
      }
    }
  }
?>