<?php

include 'user_details_retriver.php';
if($access != 1){
    goto redirect;
}

include 'connection.php';
include 'check_blocked.php';
include_once 'php_funs.php';
include 'ago.php';


$blocked_flag = 0;
$profile_user_id = filterInput($_POST['profile_user_id']);

if($user_id != $profile_user_id){
    // Checking if already blocked or not
    if(checkBlocked($profile_user_id) == 1){
        $blocked_flag = 1;
        goto i_blocked_him;
    }else{
        if(checkBlocked($profile_user_id) == 2){
            $blocked_flag = 2;
            goto they_blocked_me;
        }
    }
}
// Getting the profile info.
$sql_fetch_profile_info = "SELECT * FROM users_details WHERE user_id='$profile_user_id'";
$sql_fetch_profile_info_result = $conn->query($sql_fetch_profile_info);
if($sql_fetch_profile_info_result->num_rows > 0){
    while($row = $sql_fetch_profile_info_result->fetch_assoc()){
        $line1 = $row['line1'];
        $line2 = $row['line2'];
        $line3 = $row['line3'];
        $line4 = $row['line4'];
        $info_flag = '1';
    }
}else{
    $line1 = "";
    $line2 = "";
    $line3 = "";
    $line4 = "";
    $info_flag = '0';
}
if($user_id == $profile_user_id){
	// Sql query to get data from database.
	$sql_profile_data = "SELECT * FROM  users_data WHERE user_id='$profile_user_id'";
	// Executing the query.
	$sql_profile_data_result = $conn->query($sql_profile_data);
	// Extracting the data.
	while($row = $sql_profile_data_result->fetch_assoc()){
		$end = strlen($row['display_pic'])-6;
		echo"<div id=first_div>
                <label id=profile_pic>
                    <img id=show_my_dp class=profile_user src=".$row['display_pic'].">
                </label>
                <div id=third_div>
                    <p id=profile_user_name>".ucfirst($row['first_name'])." ".ucfirst($row['last_name'])."</p>
                    <p class='stats'>Profile Visits :".$row['profile_visits']."</p>
                    <p class='stats'>Total Posts :".sumMessages($profile_user_id)."</p>
                    <button id=edit_button onclick=show_edit_profile_form() class=profile_button>Edit Profile</button>
                </div>
                <div class=profile_opts><img id=profile_opts_img onclick=myProfileOptions(".$row['sms_flag'].") src=/used_images/settings.png></div>
            </div>";
        echo "<input type=hidden id=sms_enb>";
        echo"<div id=edit_profile_form>
                <label id=edit_details_lbl>Write anything about you</label><br>
                <form id=edit_details_form onsubmit='return update_profile_details()' method='post'>";
                    echo"<input type=file id=dp-update onChange=showMyDp(this) name=dp>";
                    echo'<input type=text name=line1 autocomplete=off value="'.$line1.'"><br>';
                    echo'<input type=text name=line2 autocomplete=off value="'.$line2.'"><br>';
                    echo'<input type=text name=line3 autocomplete=off value="'.$line3.'"><br>';
                    echo'<input type=text name=line4 autocomplete=off value="'.$line4.'"><br>';
                    echo"<input id='updt_prfl_btn' type=submit value=Save></form></div>";
	}
}else{
    // Making profile visit
    makeProfileVisit($profile_user_id);
	// Sql query to get data from database.
	$sql_profile_data = "SELECT * FROM  users_data WHERE user_id='$profile_user_id'";
	// Executing the query.
	$sql_profile_data_result = $conn->query($sql_profile_data);
	// Extracting the data.
	while($row = $sql_profile_data_result->fetch_assoc()){
		$end = strlen($row['display_pic'])-6;
		echo "<div id=first_div>
                <img class=profile_user src=".$row['display_pic'].">
                <div id=third_div>
                    <div id=forth_div>
                        <p id=profile_user_name>".ucfirst($row['first_name'])." ".ucfirst($row['last_name'])."</p>
                        <div class=profile_opts>
                            <img onclick=showProfileOptions(".$profile_user_id.") src=/used_images/post_options.png>
                        </div>
                    </div>
                    <p class='stats'>Total Posts :".sumMessages($profile_user_id)."</p>";
                    if(checkUsersSmsFlag($profile_user_id)){
                        echo"<form onsubmit='return sendMessage()' id=secret_message_form>
                            <input type=hidden name=to_user value=".$profile_user_id.">
                            <input placeholder='Secret Message' name=secret_message type=text autocomplete=off id=secret_msg_fld>
                            <input value=send type=submit>
                        </form>";
                    }
                echo"</div>
            </div>
            <div id=edit_profile_form></div>";
	}	
}
if( $line1 != "" || $line2 != "" || $line3 != "" || $line4 != ""){
    echo"<div id=user_info>
            <p class='info_row'>".$line1."</p>
            <p class='info_row'>".$line2."</p>
            <p class='info_row'>".$line3."</p>
            <p class='info_row'>".$line4."</p>
        </div>";
}else{
    echo"<div id=user_info>
            <p id=edit_details_lbl>No details are added</p>
        </div>";
}
    // Counting total posts by user
    $sql_user_posts_count = "SELECT COUNT(*) AS total_posts FROM messages WHERE user_id= '$profile_user_id'";
    // Executing the query.
    $sql_user_posts_count_result = $conn->query($sql_user_posts_count);
    while($row = $sql_user_posts_count_result->fetch_assoc()){
        $user_total_posts = $row['total_posts'];
    }

?>
    <div id="user_posts">
        <?php
            // Sql query for fetching the users posts.
            $sql_user_posts = "SELECT * FROM messages INNER JOIN users_data ON  messages.user_id = users_data.user_id AND messages.user_id='$profile_user_id' WHERE NOT messages.author = 'anonymous' ORDER BY messages.message_id DESC LIMIT 10";

            $sql_user_posts_result = $conn->query($sql_user_posts);

            if($sql_user_posts_result->num_rows > 0){
        while($row = $sql_user_posts_result->fetch_assoc()){
                            if($row['user_id']  == $user_id){
                                echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div class=post_icon ><img class=user src=".$row["display_pic"]."></div><div class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img src=/used_images/post_options.png onclick=show_post_options(".$row['message_id'].",1)></div></div>";  // Showing Heading;
                                echo "<p class=confession_message>".$row["message"]."</p>";
                            }else{
                                echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div class=post_icon ><img class=user src=".$row["display_pic"]."></div><div class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img src=/used_images/post_options.png onclick=show_post_options(".$row['message_id'].",0)></div></div>";  // Showing Heading;
                                echo "<p class=confession_message>".$row["message"]."</p>";
                            }
                            // Showing the message
                            // Image check
                            if($row["imagename"]!=NULL){
                                // Image exists.
                                // Checking whether post(with image) is liked ro not.
                                if(check_user($row["message_id"],$user_id)){
                                    echo "<div class=posts id=image".$row["message_id"]." ondblclick=unlikeme(".$row["likes"].",".$row["message_id"].",1)><img  alt='*image*' src = images/".$row["imagename"]." ></div>";
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
            }

        if($user_total_posts > 10){
            echo"<button onclick='show_more_user_posts(20,".$profile_user_id.")' id='show_more' style='display: none;'></button>
                <div>
                    <img class='confessions_loader' src='used_images/confessions_loading.gif'></div>
                </div>";
        }else{
            echo"<div id=srch_btn_cntnr>
                <button id=srch_btn>No More Posts</button>
            </div>";
        }

        ?>
    </div>
<?php
// user aleready blocked
i_blocked_him:
    if($blocked_flag == 1){
        // Sql query to get data from database.
        $sql_profile_data = "SELECT * FROM  users_data WHERE user_id='$profile_user_id'";
        // Executing the query.
        $sql_profile_data_result = $conn->query($sql_profile_data);
        // Extracting the data.
        while($row = $sql_profile_data_result->fetch_assoc()){
            $end = strlen($row['display_pic'])-6;
            echo "<div id=first_div>
                    <img class=profile_user src=".$row['display_pic'].">
                    <div id=third_div>
                        <p id=profile_user_name>".ucfirst($row['first_name'])." ".ucfirst($row['last_name'])."</p>
                        <button class='unblock_btn' onclick=un_blockUser(".$profile_user_id.",0)>Unblock</button>
                    </div>
                </div>";
        }
    }

they_blocked_me:
    if($blocked_flag == 2){
        echo "You are blocked by him";
    }

redirect:
    if($access != 1)
        header('Location:index.php');
?>
