<?php
include 'user_details_retriver.php';
if($access == 1){
    include 'connection.php';
    include 'check_blocked.php';
    include 'ago.php';
    include_once 'php_funs.php';

    $search_string = filterInput($_POST['search_string']);
    $amount = filterInput($_POST['amount']);
    $offset = $amount - 2;

    $sql_count_posts = "SELECT * FROM messages WHERE message LIKE '%$search_string%'";
    // Executing query
    $sql_count_posts_result = $conn->query($sql_count_posts);
    // Sql query to search posts
    $sql_search_posts = "SELECT * FROM messages LEFT JOIN users_data ON messages.user_id = users_data.user_id WHERE message LIKE '%$search_string%' ORDER BY time_stamp DESC LIMIT 2 OFFSET $offset";
    // Executing query
    $sql_search_posts_result = $conn->query($sql_search_posts);
    if($sql_search_posts_result->num_rows > 0){
        while($row = $sql_search_posts_result->fetch_assoc()){
                            if($row["author"] != "Anonymous"){
                                if($row['user_id']  == $user_id){
                                    echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div onclick=open_user_profile(".$row['user_id'].") class=post_icon ><img class=user src=".$row["display_pic"]."></div><div onclick=open_user_profile(".$row['user_id'].") class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img onclick=show_post_options(".$row['message_id'].",1) src=/used_images/post_options.png></div></div>";  // Showing Heading;
                                    echo "<p class=confession_message>".$row["message"]."</p>";
                                }else{
                                    // Blocked check
                                    if(checkBlocked($row['user_id']) == 1 || checkBlocked($row['user_id']) == 2){
                                        continue;
                                    }
                                    echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div onclick=open_user_profile(".$row['user_id'].") class=post_icon ><img class=user src=".$row["display_pic"]."></div><div onclick=open_user_profile(".$row['user_id'].") class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img onclick=show_post_options(".$row['message_id'].",0) src=/used_images/post_options.png></div></div>";  // Showing Heading;
                                    echo "<p class=confession_message>".$row["message"]."</p>";
                                }
                            }else{
                                if($row['user_id'] == $user_id){
                                    echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div class=post_icon ></div><div class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img onclick=show_post_options(".$row['message_id'].",1) src=/used_images/post_options.png></div></div>";  // Showing Heading;
                                    echo "<p class=confession_message>".$row["message"]."</p>";
                                }else{
                                    echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div class=post_icon ></div><div class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img onclick=show_post_options(".$row['message_id'].",0) src=/used_images/post_options.png></div></div>";  // Showing Heading;
                                    echo "<p class=confession_message>".$row["message"]."</p>";
                                }
                            }
                            // Showing the message
                            // Image check
                            if($row["imagename"] != NULL ){
                                // Image exists.
                                // Checking whether post(with image) is liked ro not.
                                if(check_user($row["message_id"],$user_id)){
                                    // Post is liked
                                    echo "<div class=posts id=image".$row["message_id"]." ondblclick=unlikeme(".$row["likes"].",".$row["message_id"].",1)><img alt='*image*' src = images/".$row["imagename"]." ></div>";
                                    // Like Coutner.
                                    echo "<ul class=like_comment_counter><li><p id=counter".$row["message_id"].">Likes ".$row["likes"]." | Comments </p></li>"; // Likes Counter
                                    // Comments Counter
                                    echo "<li><p id=commentcounter".$row["message_id"].">".$row["comments"]."</p></li></ul>"; // comments counter.
                                    // Post is liked.
                                    echo "<ul class=like_comment_buttons><li><p id=likebtn".$row["message_id"]." onclick=unlikeme(".$row["likes"].",".$row["message_id"].",1)><img class=likebtn src=/used_images/like.png ></p></li>"; // Unlike Button
                                }else{
                                    // Post is not liked.
                                    echo "<div class=posts id=image".$row["message_id"]." ondblclick=likeme(".$row["likes"].",".$row["message_id"].",1)><img alt='*image*' src = images/".$row["imagename"]." ></div>";
                                    // Like Coutner.
                                    echo "<ul class=like_comment_counter><li><p id=counter".$row["message_id"].">Likes ".$row["likes"]." | Comments </p></li>"; // Likes Counter
                                    // Comments Counter
                                    echo "<li><p id=commentcounter".$row["message_id"].">".$row["comments"]."</p></li></ul>"; // comments counter.
                                    echo "<ul class=like_comment_buttons><li><p id=likebtn".$row["message_id"]." onclick=likeme(".$row["likes"].",".$row["message_id"].",1)><img class=likebtn src=/used_images/unlike.png></p></li>"; // Like Button
                                }
                                echo "<li><p id=comment_box_button".$row["message_id"]." onclick=comment_box(".$row["message_id"].",".$user_id.",5,".$row["comments"].")><img class=commentbtn src=/used_images/comment.png ></p></li></ul>"; // Comment Button
                                echo "<span id=commentbtn".$row["message_id"]."></span>";
                                echo " </div>";
                            }else{
                                // Image dosen't exist.
                                echo "<ul class=like_comment_counter><li><p id=counter".$row["message_id"].">Likes ".$row["likes"]." | Comments </p></li>"; // Likes Counter
                                echo "<li><p id=commentcounter".$row["message_id"].">".$row["comments"]."</p></li></ul>"; // comments counter.
                                // Checking User like status 
                                if(check_user($row["message_id"],$user_id)){
                                    // Post is liked.
                                    echo "<ul class=like_comment_buttons><li><p id=likebtn".$row["message_id"]." onclick=unlikeme(".$row["likes"].",".$row["message_id"].",0)><img  class=likebtn src=/used_images/like.png></p></li>"; // Unlike Button
                                }else{
                                    // Post is not liked.
                                    echo "<ul class=like_comment_buttons><li><p id=likebtn".$row["message_id"]."  onclick=likeme(".$row["likes"].",".$row["message_id"].",0)><img  class=likebtn src=/used_images/unlike.png></p></li>"; // Like Button
                                }
                                echo "<li><p id=comment_box_button".$row["message_id"]." onclick=comment_box(".$row["message_id"].",".$user_id.",5,".$row["comments"].")><img class=commentbtn src=/used_images/comment.png ></p></li></ul>"; // Comment Button
                                echo "<span id=commentbtn".$row["message_id"]."></span></div>";
                           }
        }
    }else{
        echo "No results";
    }

    if($amount < $sql_count_posts_result->num_rows){
        $amount += 2;
        echo"<div id=srch_btn_cntnr>
                <button id=srch_btn onclick=hidSearchPosts(".$amount.",0)>Show More</button>
            </div>";
    }else if($sql_count_posts_result->num_rows != 0){
        echo"<div id=srch_btn_cntnr>
                <button id=srch_btn>No More Posts</button>
            </div>";
    }
}else{
    header('Location:index.php');
}
?>