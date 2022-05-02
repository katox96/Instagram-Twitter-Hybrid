<?php
include 'user_details_retriver.php';
if($access == 1){

    include 'connection.php';
    include 'check_blocked.php';
    include_once 'php_funs.php';
    include 'ago.php';
    
    $amount = filterInput($_GET["amount"]);
    $offset = $amount - 10;

    $sql = "SELECT * FROM messages LEFT JOIN users_data ON  messages.user_id = users_data.user_id ORDER BY messages.message_id DESC LIMIT 10 OFFSET $offset";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Output data of each row  
        while($row = $result->fetch_assoc()) {
            // Blocked check
            if(checkBlocked($row['user_id']) == 1 || checkBlocked($row['user_id']) == 2){
                continue;
            }
            if($row["author"] != "Anonymous"){
                if($row['user_id']  == $user_id){
                    echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div onclick=open_user_profile(".$row['user_id'].") class=post_icon ><img class=user src=".$row["display_pic"]."></div><div onclick=open_user_profile(".$row['user_id'].") class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img onclick=show_post_options(".$row['message_id'].",1) src=/used_images/post_options.png></div></div>";  // Showing Heading;
                    echo "<p class=confession_message>".$row["message"]."</p>";
                }else{
                    echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div onclick=open_user_profile(".$row['user_id'].") class=post_icon ><img class=user src=".$row["display_pic"]."></div><div onclick=open_user_profile(".$row['user_id'].") class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img onclick=show_post_options(".$row['message_id'].",0) src=/used_images/post_options.png></div></div>";  // Showing Heading;
                    echo "<p class=confession_message>".$row["message"]."</p>";
                }
            }else{
                if($row['user_id'] == $user_id){
                    echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div class=post_icon></div><div class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img onclick=show_post_options(".$row['message_id'].",1) src=/used_images/post_options.png></div></div>";  // Showing Heading;
                    echo "<p class='confession_message anon_cnfsn'>".$row["message"]."</p>";
                }else{
                    echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div class=post_icon></div><div class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img onclick=show_post_options(".$row['message_id'].",0) src=/used_images/post_options.png></div></div>";  // Showing Heading;
                    echo "<p class='confession_message anon_cnfsn'>".$row["message"]."</p>";
                }
            }
            // Showing the message
            // Image check
            if($row["imagename"]!=NULL){
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
                echo "<li><p id=comment_box_button".$row["message_id"]."  onclick=comment_box(".$row["message_id"].",".$user_id.",5,".$row["comments"].")><img class=commentbtn src=/used_images/comment.png></p></li></ul>"; // Comment Button
                echo "<span id=commentbtn".$row["message_id"]."></span>";
                echo " </div>";
            }else{
                // Image dosen't exist.
                echo "<ul class=like_comment_counter><li><p id=counter".$row["message_id"].">Likes ".$row["likes"]." | Comments </p></li>"; // Likes Counter
                echo "<li><p id=commentcounter".$row["message_id"].">".$row["comments"]."</p></li></ul>"; // comments counter.
                // Checking User like status 
                if(check_user($row["message_id"],$user_id)){
                    // Post is liked.
                    echo "<ul class=like_comment_buttons><li><p id=likebtn".$row["message_id"]." onclick=unlikeme(".$row["likes"].",".$row["message_id"].",0)><img class=likebtn src=/used_images/like.png></p></li>"; // Unlike Button
                }else{
                    // Post is not liked.
                    echo "<ul class=like_comment_buttons><li><p id=likebtn".$row["message_id"]." onclick=likeme(".$row["likes"].",".$row["message_id"].",0)><img class=likebtn src=/used_images/unlike.png></p></li>"; // Like Button
                }
                echo "<li><p id=comment_box_button".$row["message_id"]."  onclick=comment_box(".$row["message_id"].",".$user_id.",5,".$row["comments"].")><img class=commentbtn src=/used_images/comment.png></p></li></ul>"; // Comment Button
                echo "<span id=commentbtn".$row["message_id"]."></span>";
                echo " </div>";
            }
        }
        // Checking if total post reached or not.
        $sql_total_posts = "SELECT COUNT(message_id) AS total_posts FROM messages";
        // Executing query
        $sql_total_posts_result = $conn->query($sql_total_posts);
        // Fetching row count value.
        while($row = $sql_total_posts_result->fetch_assoc()){
            $total_posts = $row["total_posts"];
        }
        if($amount < $total_posts ){
            // For loading more posts.
            $new_amount = $amount + 10 ;
            echo"<button id=show_more onclick=showMoreConfessions(".$new_amount.") style=display:none;></button>
                <div id=cnfsn_loader class=cnfsn_loader>
                    <img class='confessions_loader' src='used_images/confessions_loading.gif'>
                </div>";
        }else{
            echo "<div id=srch_btn_cntnr>
                    <button id=srch_btn>No More Posts</button>
                </div>";
        }
    }else{
        echo "0 results";
    }
}else{
    header('Location:index.php');
}
?>
