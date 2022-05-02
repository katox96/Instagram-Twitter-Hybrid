<?php
    include 'user_details_retriver.php';

    if($access != 1){
        header('Location:index.php');
    }

	include 'connection.php';
    include 'ago.php';
    include 'check_blocked.php';
    include_once 'php_funs.php';
?>
<html>
    <head>
        <style>
        </style>
        <style>@import url('https://fonts.googleapis.com/css?family=Dosis:800');</style>
        <script src="/js/un_like.js"></script>
        <script src="/js/comment_box.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            $(window).scroll(function() {   
               if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                   $("#show_more")[0].click();
               }
            });
        </script>
        <script>
            function show(){
                document.getElementById("page-loader").style.display = "block";
            }
            function hide(){
                document.getElementById("page-loader").style.display = "none";
            }
            jQuery(window).load(function(){
                jQuery("page-loader").fadeOut(500);
            });
        </script>
        <script src="/js/homepage.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
	    <link rel="stylesheet" type="text/css" href="/css/homepage.css">
	    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	    <title>
		  College Network
	    </title>
    </head>
    <body onload="hide()">
        <div id="page-loader"><img src="/used_images/page-loader.gif"></div>
        <div>
            <div id="options_overlay">
                <div id="options_list"></div>
            </div>
            <div id="navbar">
                <div onclick="reload()" id="logo_div"><p>Confessions</p></div>
                <p id="sgnot_btn" onclick=logout() style="float:right;margin-left:auto;font-weight:bold;">Sign Out</p>
            </div>
            <div id="sidebar">
                <div id=btns_container><div id=srch_btns><button id=btn_posts onclick='changeSrchMode(0)'>Posts</button><button id=btn_users onclick='changeSrchMode(1)'>Users</button></div></div>
                <input type="text" id="search_field" onkeyup="searchPosts(2,1)" placeholder="Search Posts">
                <p id="res"></p>
            </div>
            <div id=logout></div>
            <div id="user_profile"></div>
            <div id="messages"></div>
            <div id="main">
                <div id="top-div">
                    <form id="confession_form"  name="confession_form"  onsubmit="return submitdata()" method="post" enctype="multipart/form-data">
                        <div class="form-inline">
                            <?php
                                echo "<img id=temp class=user src=".$profile_pic.">";
                            ?>
                            <input class="confessionField" onkeyup="red_to_blue(this)" type="text" name="confession_msg" placeholder="  You Can Post Here !" autocomplete="off" >
                            <input type="submit" value="Post">
                        </div>
                        <div class="form-inline">
                            <input id="anon_checkbox" type="checkbox" name="anonymous_flag" value="1" onclick="hideImageMode(0)"><span id="checkbox_text">Anonymous</span>
                            <input id="file-upload" type="file" accept="image/*" onchange="showMyImage(this)" name="uploadImg">
                            <label id="file-upload-label" for="file-upload"><img  onclick="change_opac()" id="image_selection_icon"  src="/used_images/file-upload.png" style="width:25px; height:23px;"></label>
                            <p id="image_mode_selector" onclick="overlay_on()" >Post an image</p>
                        </div>
                    </form>
                    <progress id="progressBar" value="0" max="100" style="width:100%; display:none;"></progress>
                    <h3 id="status"></h3>
                    <p id="loaded_n_total"></p>
                </div>
                <?php
                    // For new post
                    echo"<div id=new_post class=boxshadow>
                            <div class=post_heading>
                                <div class=post_icon>
                                    <img class=user src=".$profile_pic.">
                                </div>
                                <div class=post_author>
                                    <p class=confession_no>
                                        ".ucfirst($first_name)." ".ucfirst($last_name)."
                                    </p>
                                    <p class=confession_timestamp>
                                        now
                                    </p>
                                </div>
                                <div class=post_options>
                                    <span id=clear_button></span>
                                </div>
                            </div>
                            <div class=posts>
                                <img alt=' ' id=show_my_img src=>
                            </div>
                        </div>";
                    echo "<div id='confessions'>";
                    $sql = "SELECT * FROM messages LEFT JOIN users_data ON  messages.user_id = users_data.user_id ORDER BY messages.message_id DESC LIMIT 10";
                    //<img  class=user src=".$row["display_pic"]." >
                    //$sql="SELECT * FROM messages ORDER BY message_id DESC LIMIT 10";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row  
                        while($row = $result->fetch_assoc()) {
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
                                    echo "<p class='confession_message anon_cnfs'>".$row["message"]."</p>";
                                }else{
                                    echo  "<div class=boxshadow id=post_id".$row['message_id']."><div class=post_heading><div class=post_icon ></div><div class=post_author><p class=confession_no> ".$row["author"]."</p><p class=confession_timestamp>".time_elapsed_string($row["time_stamp"])."</p></div><div class=post_options><img onclick=show_post_options(".$row['message_id'].",0) src=/used_images/post_options.png></div></div>";  // Showing Heading;
                                    echo "<p class='confession_message anon_cnfs'>".$row["message"]."</p>";
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
                        // No Results.
                        echo "0 results";
                    }
                ?>
                <button id="show_more" onclick="showMoreConfessions(20)" style="display:none;"></button>
                </div>
                <div class=cnfsn_loader id=cnfsn_loader><img class='confessions_loader' src="used_images/confessions_loading.gif"></div>
        </div>
    </div>
    <div class=footer>
        <div id=footer_btn_grp>
            <button class="footer_button" id="home" onclick="open_home()"><img class='btn_icon active' src=https://cdn-icons-png.flaticon.com/512/1946/1946488.png></button>
            <?php
                echo "<button class=footer_button id=open_user_profile onclick=open_user_profile(".$user_id.")><img class='profile_btn_icon btn_icon' src=".$profile_pic."></button>";
            ?>
            <button class="footer_button" id="open_sidebar" onclick="open_sidebar()"><img class='btn_icon' src=https://img.icons8.com/ios/50/000000/search.png></button>
            <button class="footer_button" id="open_sidebar" onclick="open_messages()">
                <?php
                    if($notification_status == 1){
                        $src = "/used_images/new_msg.png";
                    }else{
                        $src = "https://img.icons8.com/ios/50/000000/new-post.png";
                    }
                    echo "<img id=msg_icon class=btn_icon src=".$src.">";
                ?>
            </button>
        </div>
    </div>
    <script>
        // For desabling scrolling under the overlay.
        var fixed = document.getElementById('options_overlay');
        fixed.addEventListener('touchmove', function(e) {
            e.preventDefault();
        }, false);
    </script>
</body>
</html>
<?php
    $conn->close();
?>
