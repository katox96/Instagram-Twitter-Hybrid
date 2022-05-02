function reload() {
    show();
    window.location = "index.php";
}

function logout(){
    show();
    document.cookie = "session_tokken=null";
    window.location = "index.php";
    //window.location = "logout.php";   
}

function likeme(likes,message_id,image){
    likes=likes+1;
    if(image == 1){
        document.getElementById("counter"+message_id).innerHTML="Likes "+likes+" | Comments "; // Increases the counter 
        document.getElementById("likebtn"+message_id).innerHTML="<img class=likebtn src=/used_images/like.png>";   // Changes likebutton text
        document.getElementById("likebtn"+message_id).setAttribute("onclick","unlikeme("+likes+","+message_id+",1)"); //Changes the functionality of likebutton
        // Changing double click to unlike attribute.
        document.getElementById("image"+message_id).setAttribute("ondblclick","unlikeme("+likes+","+message_id+",1)");
    }else{
        document.getElementById("counter"+message_id).innerHTML="Likes "+likes+" | Comments "; // Increases the counter 
        document.getElementById("likebtn"+message_id).innerHTML="<img class=likebtn src=/used_images/like.png>";   // Changes likebutton text
        document.getElementById("likebtn"+message_id).setAttribute("onclick","unlikeme("+likes+","+message_id+",0)"); //Changes the functionality of likebutton
    }
    document.getElementById("likebtn"+message_id).classList.add("likebtn_anm"); 
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "like.php?message_id="+message_id, true);
    xhttp.send();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response_Obj = JSON.parse(this.responseText);
            if(response_Obj.result=="sucess"){
                likes = response_Obj.total_likes; 
                document.getElementById("counter"+message_id).innerHTML="Likes "+likes+" | Comments "; // Increases the counter     
            }
        }
        
    };
    
} 

function unlikeme(likes,message_id,image){
    likes=likes-1;
    if(image == 1){
        document.getElementById("counter"+message_id).innerHTML="Likes "+likes+" | Comments "; // Decrease the counter
        document.getElementById("likebtn"+message_id).innerHTML="<img class=likebtn src=/used_images/unlike.png>"; // Change unlikebutton text
        document.getElementById("likebtn"+message_id).setAttribute("onclick","likeme("+likes+","+message_id+",1)"); // Change the functionality of unlikebutton
        // Changing double click to unlike attribute.
        document.getElementById("image"+message_id).setAttribute("ondblclick","likeme("+likes+","+message_id+",1)");
    }else{
        document.getElementById("counter"+message_id).innerHTML="Likes "+likes+" | Comments "; // Decrease the counter
        document.getElementById("likebtn"+message_id).innerHTML="<img class=likebtn src=/used_images/unlike.png>"; // Change unlikebutton text
        document.getElementById("likebtn"+message_id).setAttribute("onclick","likeme("+likes+","+message_id+",0)"); // Change the functionality of unlikebutton
    }
    document.getElementById("likebtn"+message_id).classList.remove("likebtn_anm"); 
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "unlike.php?message_id="+message_id, true);
    xhttp.send();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response_Obj = JSON.parse(this.responseText);
            if(response_Obj.result=="sucess"){
                likes = response_Obj.total_likes; 
                document.getElementById("counter"+message_id).innerHTML="Likes "+likes+" | Comments "; // Increases the counter     
            }
        }
    };

}

// Function to hide the comment box.
function hide_comment_box(message_id,user_id,amount,total_amount){
    document.getElementById("comment_box_button"+message_id).style.background="white";
    document.getElementById("commentbtn"+message_id).innerHTML = "";
    document.getElementById("comment_box_button"+message_id).setAttribute("onclick","comment_box("+message_id+","+user_id+","+amount+","+total_amount+")");
}

// Function to create the comment box.
function comment_box(message_id,user_id,amount,total_amount){
    x = document.getElementById("comment_box_button"+message_id);
    x.setAttribute("onclick","hide_comment_box("+message_id+","+user_id+","+amount+","+total_amount+")");
    if(user_id==0){
        document.getElementById("commentbtn"+message_id).innerHTML="<div id=oldcomments"+message_id+"></div>"+"Please login to comment";
        load_comments(message_id,amount,total_amount);
    }else{

        // Making commenting form
        var comment_form = "<div class=comment_list id=oldcomments"+message_id+"><p id=comments_status"+message_id+" class=comments_status>Loading...</p></div>";  
        comment_form = comment_form+"<div class=comment_list id=newcomment"+message_id+"></div>";
        comment_form = comment_form+"<form id=post_comment"+message_id+" class=comment_form-inline> <input class=commentField onkeyup=red_to_blue(this) id=comment_field"+message_id+" type=text name=comment autocomplete=off >";
        comment_form = comment_form+"<input type=submit value=comment></form>";

         // Editing the <span id="commentbtn"+message_id>  element 
        document.getElementById("commentbtn"+message_id).innerHTML=comment_form;
    }
    load_comments(message_id,amount,total_amount);
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","count_comments.php", true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send("message_id="+message_id);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response_obj = JSON.parse(this.responseText);
            if(response_obj.result == "sucess"){
                document.getElementById("commentcounter"+message_id).innerHTML = response_obj.total_comments;
            }
        }
    }
}

// Function to post the comment
function post_comment(message_id,amount,total_amount){
    var comment = document.getElementById("comment_field"+message_id).value;

    // Checking the value of the comment.
    if(comment.trim() == ""){
        document.getElementById("comment_field"+message_id).style.border = "1px solid red";
    }else{
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "post_comments.php", true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("message_id="+message_id+"&comment="+comment+"&total_amount="+total_amount);
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response_obj = JSON.parse(this.responseText);
                if(response_obj.result == "sucess"){
                    var total_comments = response_obj.total_comments; 
                    load_comments(message_id,amount,total_comments);
                    document.getElementById("commentcounter"+message_id).innerHTML= total_comments;
                    document.getElementById("post_comment"+message_id).setAttribute("onsubmit","return post_comment("+message_id+","+amount+","+total_comments+")");
                    document.getElementById("comment_field"+message_id).value="";
                }
            }
        }
    }
    return false;
}

// Function to load comments
function load_comments(message_id,amount,total_amount){
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "load_comments.php", true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("message_id="+message_id+"&amount="+amount+"&total_amount="+total_amount);
        document.getElementById("comments_status"+message_id).innerHTML = "Loading...";
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("oldcomments"+message_id).innerHTML=this.responseText;
            document.getElementById("post_comment"+message_id).setAttribute("onsubmit","return post_comment("+message_id+","+amount+","+total_amount+")");
        }
    }
}


function checkNewMsg(){
    $.post("check_new_msg.php",{},
        function(data, status){
            if(data == 1){
                $("#msg_icon").attr("src","/used_images/new_msg.png");
            }else{
                $("#msg_icon").attr("src","https://img.icons8.com/ios/50/000000/new-post.png");
            }
        }
    );
}

function changeSrchMode(mode){
    var search_field = document.getElementById("search_field");
    if(mode == 1){
        search_field.setAttribute("onkeyup","searchUsers()");
        search_field.setAttribute("placeholder","Search Users");
        searchUsers();
    }else{
        search_field.setAttribute("onkeyup","searchPosts(2,1)");
        search_field.setAttribute("placeholder","Search Posts");
        searchPosts(2,1);
    }
}

function onlyImages() {
    var overlay = document.getElementById("options_overlay");
    var overlay_options = document.getElementById("options_list");
    overlay.style.display = "block";
    overlay_options.style.display = "block";
    overlay_options.innerHTML = "<p>Only Images are allowed</p><div class=div_option onclick=cancelOptions()><p>Ok</p></div>";
}

function change_opac(){
    document.getElementById("image_selection_icon").style.opacity = "0.3";
    setTimeout(function(){
        document.getElementById("image_selection_icon").style.opacity = "1";
    },100);
}

function hideImageMode() {
    clear_img();
    document.getElementById("image_selection_icon").classList.toggle("hide");
    document.getElementById("image_mode_selector").classList.toggle("hide");
}

function showBlockedUsers() {
    document.getElementById("options_overlay").style.display = "none";
    document.getElementById("options_list").style.display = "none";
    document.getElementById("user_profile").innerHTML =  "";
    document.getElementById("main").style.display = "none";
    document.getElementById("sidebar").style.display = "none";
    document.getElementById("messages").style.display = "none";
    document.getElementById("user_profile").style.display = "block";
    $.post("blocked_users.php",{msg:""},
        function(data, status){
            document.getElementById("user_profile").innerHTML = data;
        }
    ); 
}

// For displaying dp before upload
function showMyDp(fileInput) {
    var img = document.getElementById("show_my_dp");
    var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                onlyImages();
                continue;
            }                       
            img.file = file;    
            var reader = new FileReader();
            reader.onload = (function(aImg) { 
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
        }
}

function deleteAccount() {
    window.location = 'delete_account.php'; 
}

function deleteAccountOptions() {
    var options_overlay = document.getElementById("options_overlay");
    options_overlay.style.display = "block";
    var options_overlay_list = document.getElementById("options_list");
    options_overlay_list.style.display = "block";
    options_overlay_list.innerHTML = "<p>Are you sure?</p><div class=div_option><p onclick=deleteAccount()>Yes</p></div><div class=div_option><p onclick=cancelOptions()>No</p></div>"; 
}

function smsToggle(x,sms_flag) {
    x.classList.toggle('active');
    y = document.getElementById("profile_opts_img");
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","set_sms_flag.php",true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send("sms_flag="+sms_flag);
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            y.setAttribute("onclick","myProfileOptions("+sms_flag+")");
            if(sms_flag == 0){
                x.setAttribute("onclick","smsToggle(this,1)");
            }else{
                x.setAttribute("onclick","smsToggle(this,0)");
            }
        }
    }
}

function myProfileOptions(sms_flag) {
    if(sms_flag == '1'){
        var toggle = "<div class=container><div class='toggle-btn active' onclick=smsToggle(this,0)><div class=inner-circle></div></div></div>";
    }
    else{
        var toggle = "<div class=container><div class='toggle-btn' onclick=smsToggle(this,1)><div class=inner-circle></div></div></div>";
    }
    var options_overlay = document.getElementById("options_overlay");
    options_overlay.style.display = "block";
    var options_overlay_list = document.getElementById("options_list");
    options_overlay_list.style.display = "block";
    options_overlay_list.innerHTML = "<div class=toggle_optn><div><p>Secret Message</p></div>"+toggle+"</div><div class=div_option onclick=showBlockedUsers()><p>Blocked Users</p></div><div class=div_option onclick=deleteAccountOptions()><p>Delete Account</p></div><div class=div_option onclick=cancelOptions()><p>Cancel</p></div>";     
}

function un_blockSmsUser(s_m_id,block) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","sms_un_block.php",true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send("s_m_id="+s_m_id+"&block="+block);
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            var response_Obj = JSON.parse(this.responseText);
            if(response_Obj.result=='1'){
                var block_btn = document.getElementById("block_btn"+s_m_id);
                if(block == 1){
                    block_btn.innerHTML = " (Unblock)";
                    block_btn.setAttribute("onclick","un_blockSmsUser("+s_m_id+",0)");
                }else{
                    block_btn.innerHTML = " (Block)";
                    block_btn.setAttribute("onclick","blockSmsUserOptions("+s_m_id+")");
                }
                cancelOptions();
            }else{
                cancelOptions();
            }
        }
    }
}

function blockSmsUserOptions(s_m_id) {
    var options_overlay = document.getElementById("options_overlay");
    options_overlay.style.display = "block";
    var options_overlay_list = document.getElementById("options_list");
    options_overlay_list.style.display = "block";
    options_overlay_list.innerHTML = "<p>Are You Sure ?</p><div><p onclick=un_blockSmsUser("+s_m_id+",1)>Yes</p></div><div><p onclick=cancelOptions()>No</p></div>";
}

function un_blockUser(profile_user_id,block) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","un_block.php",true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send("block_user_id="+profile_user_id+"&block="+block);
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            var response_Obj = JSON.parse(this.responseText);
            if(response_Obj.result=='1'){
                open_user_profile(profile_user_id);
                cancelOptions();
            }else{
                cancelOptions();
            }
        }
    }
}

function blockUserOptions(profile_user_id) {
    var options_overlay_list = document.getElementById("options_list");
    options_overlay_list.innerHTML = "<p>Are You Sure ?</p><div><p onclick=un_blockUser("+profile_user_id+",1)>Yes</p></div><div><p onclick=cancelOptions()>No</p></div>";
}


function showProfileOptions(profile_user_id) {
    var options_overlay = document.getElementById("options_overlay");
    options_overlay.style.display = "block";
    var options_overlay_list = document.getElementById("options_list");
    options_overlay_list.style.display = "block";
    options_overlay_list.innerHTML = "<div class=div_option><p onclick=blockUserOptions("+profile_user_id+")>Block</p></div><div class=div_option><p onclick=cancelOptions()>Cancel</p></div>"
}

function loadOldMsgs(s_m_id,amount,scroll) {
    var x = document.getElementById("reply_form"+s_m_id);
    var msg_reply = x.reply.value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","old_messages.php",true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send("s_m_id="+s_m_id+"&amount="+amount);
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("old_msgs"+s_m_id).innerHTML = this.responseText;
            if(scroll == 1){
                    $('#old_msgs'+s_m_id).scrollTop(10*($('#old_msgs'+s_m_id).height()));
            }
        }
    }
}

function sendReply(s_m_id) {
    var x = document.getElementById("reply_form"+s_m_id);
    var msg_reply = x.reply.value;
    if(msg_reply != ""){
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST","message_reply.php",true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("s_m_id="+s_m_id+"&msg_reply="+msg_reply);
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200){
                var response_Obj = JSON.parse(this.responseText);
                if(response_Obj.result == '1' && response_Obj.block_code == '0'){
                    x.reply.value = "";  
                }else{
                    if(response_Obj.block_code == '1'){
                        x.reply.value = "Unblock the user to reply.";
                    }else{
                        x.reply.value = "Can't reply to this conversation";
                    }
                }
                loadOldMsgs(s_m_id,10,1);
            }
        }
    }   
    return false;
}

function messageBox(s_m_id) {
    $('#head_text'+s_m_id).css("font-weight", "normal");
    $('.show').show(); // Shows
    $('.hide').hide(); // hides
    $('.show').removeClass('show');
    $('.hide').removeClass('hide');
    $('#thread_head'+s_m_id).addClass('show');
    $('#msg_box'+s_m_id).addClass('hide');
    $('#msg_box'+s_m_id).show();
    var x = document.getElementById("msg_box"+s_m_id);
    var onsubmit = "return sendReply("+s_m_id+")";
    reply_form = "<div class=old_msgs_list id=old_msgs"+s_m_id+"></div><form class=msg_form-inline id=reply_form"+s_m_id+" onsubmit='"+onsubmit+"'>";
    reply_form += "<input type=text name=reply autocomplete=off>";
    reply_form += "<input type=submit value=send>";
    reply_form += "</form>";
    x.innerHTML = reply_form ;
    document.getElementById("thread_head"+s_m_id).style.display = "none";
    loadOldMsgs(s_m_id,10,1);
}

function searchMessages() {
    var search_string = document.getElementById("message_search_field").value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","search_messages.php?search_string="+search_string,true);
    xhttp.send();
    xhttp.onreadystatechange = function() {
        document.getElementById("message_list").innerHTML = this.responseText;
    }    
}

function open_messages(){
    document.getElementById("main").style.display = "none";
    document.getElementById("sidebar").style.display = "none";
    document.getElementById("user_profile").style.display = "none";
    document.getElementById("messages").style.display = "block";
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","messages.php",true);
    xhttp.send();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("messages").innerHTML = this.responseText;
        }
    }
    checkNewMsg();
}

function sendMessage() {
    var x = document.getElementById("secret_message_form");
    var message = x.secret_message.value;
    var to_user = x.to_user.value;
    if(message!=""){
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST","send_message.php",true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("message="+message+"&to_user="+to_user);
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200){
                var response_Obj = JSON.parse(this.responseText);
                if(response_Obj.result == '1' && response_Obj.block_code == '0'){
                    x.secret_message.value = "";
                    var y = document.getElementById("secret_msg_fld");
                    y.setAttribute("placeholder","Sent");
                    setTimeout(function(){
                        y.setAttribute("placeholder","Secret Message");
                    },900);
                }else{
                     x.secret_message.value = "";
                    var y = document.getElementById("secret_msg_fld");
                    y.setAttribute("placeholder","Failed");
                    setTimeout(function(){
                        y.setAttribute("placeholder","Secret Message");
                    },900);                
                }
            }
        }
    }
    return false;
}

function show_edit_profile_form(){
    document.getElementById("show_my_dp").style = "opacity: 0.5;";
    document.getElementById("user_info").style.display = "none";
    document.getElementById("edit_profile_form").style.display = "block";
    document.getElementById("profile_pic").setAttribute("for","dp-update");
    document.getElementById("edit_button").setAttribute("onclick","hide_edit_profile_form()");
}

function hide_edit_profile_form(){
    $('#updt_prfl_btn').val("Loading...");
    document.getElementById("show_my_dp").style = "opacity: 1;";
    document.getElementById("user_info").style.display = "block";
    document.getElementById("profile_pic").setAttribute("for","");
    document.getElementById("edit_profile_form").style.display = "none";
    document.getElementById("edit_button").setAttribute("onclick","show_edit_profile_form()");
}

// For loading more posts of user in profile.
function show_more_user_posts(amount,profile_user_id){
    $("#user_posts").load("load_user_posts.php?amount="+amount+"&profile_user_id="+profile_user_id).show();
}

// Clicking the show moure button in user profile page.
$(window).scroll(function() {
   if($(window).scrollTop() + $(window).height() == $(document).height()) {
       $("#show_more_user_posts")[0].click();
    }
});

function open_user_profile(profile_user_id) {
    document.getElementById("user_profile").innerHTML =  "";
    document.getElementById("main").style.display = "none";
    document.getElementById("sidebar").style.display = "none";
    document.getElementById("messages").style.display = "none";
    document.getElementById("user_profile").style.display = "block";
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","profile.php",true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send("profile_user_id="+profile_user_id);
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("user_profile").innerHTML = this.responseText;
        }
    }
    checkNewMsg();
}

function open_home() {
    checkNewMsg();
    document.getElementById("user_profile").innerHTML = "";
    document.getElementById("user_profile").style.display = "none";
    document.getElementById("sidebar").style.display = "none";
    document.getElementById("messages").style.display = "none";
    document.getElementById("main").style.display = "block";
    $("#confessions").empty();
    $("#confessions").load("load_confessions.php?amount=10").show();
}

function searchUsers(){
    var search_string = document.getElementById("search_field").value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","search_users.php?",true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send("search_string="+search_string);
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("res").innerHTML = this.responseText; 
        }
    }
}

// For showing more confessions.
function showMoreConfessions(amount){
    $("#show_more").remove();
    $("#cnfsn_loader").remove();
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","load_confessions.php?amount="+amount,true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            $('#confessions').append(this.responseText);
        }
    }
}

function searchPosts(amount,type) {
    var search_string = document.getElementById("search_field").value;
    var result = document.getElementById("res");
    if(type == 1)
        result.innerHTML = "";
    if(search_string != ""){
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST","search_posts.php",true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("search_string="+search_string+"&amount="+amount);
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200){
                if(this.responseText == "No results"){
                    $('#res').html(this.responseText);
                }else{
                    $('#res').append(this.responseText);
                }
            }
        }
    }else{
        result.innerHTML = "";
    }    
}

function hidSearchPosts(amount,type){
    document.getElementById('srch_btn_cntnr').remove();
    searchPosts(amount,type);
}

function open_sidebar(){
    document.getElementById("main").style.display = "none";
    document.getElementById("user_profile").style.display = "none";
    document.getElementById("user_profile").innerHTML = "";
    document.getElementById("messages").style.display = "none";
    document.getElementById("sidebar").style.display = "block";
    checkNewMsg();
    $('#res').load('last_joined.php');
}

function report_post(message_id){
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","report_post.php?m_id="+message_id,true);
    xhttp.send();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            cancelOptions();
        }
    }   
}


function delete_post(message_id){
    var options_overlay = document.getElementById("options_overlay");
    var post = document.getElementById("post_id"+message_id);
    post.style.display = "none";
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "delete_messages.php", true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send("message_id="+message_id);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          //  $("#confessions").load("load_confessions.php?amount=10").show();
        }
    }
    options_overlay.style.display = "none";
}

function cancelOptions(){
    var options_overlay = document.getElementById("options_overlay");
    options_overlay.style.display = "none";
}

function delete_post_confirm(message_id){
    var options_overlay_list =  document.getElementById("options_list");
    var options_overlay = document.getElementById("options_overlay");
    options_overlay_list.innerHTML = "<p> Are Your Sure ?</p><div class=div_option><p onclick=delete_post("+message_id+")> Yes </p></div><div class=div_option><p onclick=cancelOptions()> No </p></div>";

}

function show_post_options(message_id,delete_flag){
    var options_overlay = document.getElementById("options_overlay");
    options_overlay.style.display = "block";
    var options_overlay_list =  document.getElementById("options_list");
    options_overlay_list.style.display = "block";
    if(delete_flag == 1){
        options_overlay_list.innerHTML = "<div class=div_option><p onclick=showDetails("+message_id+")>Details</p></div><div class=div_option><p onclick=delete_post_confirm("+message_id+")>Delete Post</p></div><div class=div_option><p onclick=cancelOptions()>Cancel</p></div>";
    }else{
        options_overlay_list.innerHTML = "<div class=div_option><p onclick=showDetails("+message_id+")>Details</p></div><div class=div_option><p onclick=report_post("+message_id+")>Report Post</p></div><div class=div_option><p onclick=cancelOptions()>Cancel</p></div>";
    }
}

function showDetails(message_id) {
    var options_overlay_list = document.getElementById("options_list");
    options_overlay_list.innerHTML = "";
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","show_details.php",true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send("m_id="+message_id);
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            var response_Obj = JSON.parse(this.responseText);
            options_overlay_list.innerHTML = "<p>Posted on: "+response_Obj.date+"</p><p> likes: "+response_Obj.likes+"</p><p> comments: "+response_Obj.comments+"</p><p> reports: "+response_Obj.reports+"</p><div class=div_option><p onclick=cancelOptions()>Close</p></div>"; 
        }
    }
}

// Saving profile details
function update_profile_details() {
    $('#updt_prfl_btn').val("Loading...");
    var x = document.getElementById("edit_details_form");
    var fd = new FormData(x);
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST","save_details.php",true);
    xhttp.send(fd);
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            var response_Obj = JSON.parse(this.responseText);
            document.getElementById("user_info").innerHTML="<p class=info_row>"+response_Obj.line1+"</p><p class=info_row>"+response_Obj.line2+"</p><p class=info_row>"+response_Obj.line3+"</p><p class=info_row>"+response_Obj.line4+"</p>";
            hide_edit_profile_form();
            $('#updt_prfl_btn').val("Save");
            if(response_Obj.dp_update == '1'){
                document.getElementById("temp").setAttribute("src",$('#show_my_dp').attr('src'));
            }
        }
    }
    return false;
}

function _(el){
    return document.getElementById(el);
}
    // Function to post without refreshing the page.
    function submitdata(){ 
        var confession_msg = document.forms["confession_form"]["confession_msg"].value;
        // Checking the value of the confession_msg;
        if(confession_msg.trim() == ""){
            document.forms["confession_form"]["confession_msg"].style.border = "1px solid red";
        }else{
            document.getElementById("new_post").style.display = "none";
            document.getElementById("show_my_img").style.display = "none";
            document.getElementById("clear_button").innerHTML = "<img src=used_images/upload_load.gif width=20px height=20px>";
            var fd = new FormData(document.getElementById('confession_form')); 
            var xhttp = new XMLHttpRequest();
            var pic = document.getElementById("file-upload");
            if(document.getElementById("anon_checkbox").checked){
                xhttp.open("POST", "confess.php?anon_c=1", true);
            }else{
                xhttp.open("POST", "confess.php?anon_c=0", true);
            }
            if('files' in pic){
                if(pic.files.length != 0){
                    xhttp.upload.addEventListener("progress", progressHandler, false);
                    xhttp.addEventListener("load", completeHandler, false);
                    xhttp.addEventListener("error", errorHandler, false);
                    xhttp.addEventListener("abort", abortHandler, false);
                }
            }
            xhttp.send(fd);
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response_Obj = JSON.parse(this.responseText);
                    document.forms["confession_form"]["confession_msg"].value = "";
                    var overlay = document.getElementById("options_overlay");
                    var options_overlay_list = document.getElementById("options_list");
                    if(response_Obj.size == '0'){
                        overlay.style.display = "block";
                        options_list.style.display = "block";
                        options_overlay_list.innerHTML = "<p>Size limit exceeded</p><div class=div_option onclick=cancelOptions()><p>ok</p></div>";
                    }else if(response_Obj.type == '0'){
                        overlay.style.display = "block";
                        options_list.style.display = "block";
                        options_overlay_list.innerHTML = "<p>Only Images are allowed</p><div class=div_option onclick=cancelOptions()><p>ok</p></div>";
                    }else{
                        $("#confessions").load("load_confessions.php?amount=10").show();
                    }
                }
            }
        }
        return false;
    }

function progressHandler(event){
    _("progressBar").style.display = "block";
//  _("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
    var percent = (event.loaded / event.total) * 100;
    _("progressBar").value = Math.round(percent);
    _("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
}

function completeHandler(event){
    _("progressBar").style.display = "none";
    _("clear_button").innerHTML = "<img src=used_images/upload_done.png width=20px height=20px>";
    _("status").innerHTML = "";
    _("progressBar").value = 0;
    document.getElementById("show_my_img").setAttribute("src","");
    document.getElementById('file-upload').value="";
    setTimeout(function(){
    	_("clear_button").innerHTML = "";
    },1000);
}

function errorHandler(event){
    _("status").innerHTML = "Upload Failed";
}

function abortHandler(event){
    _("status").innerHTML = "Upload Aborted";
}

// Functon to change the field color if mis-submit.
function red_to_blue(x){
    x.style.border = "1px solid rgb(29, 161, 242) ";
}

// For displaying image befor upload  in the overlay id=myNav.
 function showMyImage(fileInput) {
    var img=document.getElementById("show_my_img");
    var files = fileInput.files;
    var flag=0
        for (var i = 0; i < files.length; i++) {           
            var file = files[i];
            var imageType = /image.*/;     
            if (!file.type.match(imageType)) {
                onlyImages();
                continue;
            }                       
            img.file = file;    
            var reader = new FileReader();
            reader.onload = (function(aImg) {
                return function(e) { 
                    aImg.src = e.target.result; 
                }; 
            })(img);
            reader.readAsDataURL(file);
            flag = 1 ;
        }
        if(flag){
            document.getElementById("new_post").style.display = "block";
            img.style.display = "block";
            document.getElementById("clear_button").innerHTML = "<button id=clear_img_button onclick=clear_img() >Clear</button>";
        }
    }

// Function to clear selected image.
function clear_img(){
    $("#file-upload").val("");
    var img = document.getElementById("show_my_img");
    img.style.display = "none";
    document.getElementById("new_post").style.display = "none";
    img.setAttribute("src","");
    document.getElementById("clear_button").innerHTML = "";
}


// Changing the post mode from text to image and wise wersa.

function image_mode(){
    var but = document.getElementById("file_selection_button");
    but.style.display = "block";
    document.getElementById("image_mode_selector").innerHTML = "Only Text ?";
    document.getElementById("image_mode_selector").setAttribute("onclick","text_mode()");
}

function text_mode(){
    var but = document.getElementById("file_selection_button");
    but.style.display = "none"
    document.getElementById("image_mode_selector").innerHTML = "Post an image ?";
    document.getElementById("image_mode_selector").setAttribute("onclick","image_mode()");
}

// Function to delete comment posted by the user.

function delete_comment(message_id,comment_id,amount,total_amount){
    document.getElementById("delete_comment_button"+message_id).setAttribute("onclick","");
    var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "delete_comments.php", true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhttp.send("message_id="+message_id+"&comment_id="+comment_id);
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response_Obj = JSON.parse(this.responseText);
                if(response_Obj.result == '1'){
                    total_amount=total_amount-1;
                    load_comments(message_id,amount,total_amount);
                    document.getElementById("commentcounter"+message_id).innerHTML=
                    Number(document.getElementById("commentcounter"+message_id).innerHTML)-1;
                    //document.getElementById("post_comment"+message_id).setAttribute("onclick","post_comment("+message_id+","+total_amount+")");
                }
            }
        }
}
