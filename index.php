<?php 
include 'user_details_retriver.php';
require_once('google_login/settings.php');	
	if($access == 1){
		header('Location:homepage.php');
	}
?>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="stylesheet" type="text/css" href="/css/index.css">
</head>
<body onload="hide()">
	<div id=main>
		<div id="page-loader"><img src="/used_images/page-loader.gif"></div>
		<div class="logo_div">
			<p>Confessions</p>
		</div>
		<div class="login_div">
			<button  class="login_btn" onclick="setToken('kp7qocvfpvkwc4400gkwcs04s0sckcc')"><p>Varun Choudhary</p></button>
			<button  class="login_btn" onclick="setToken('qo6x3307xn48o48k48wgsksww4s8k0s')"><p >Shiva Chandel</p></button>
			<button  class="login_btn" onclick="setToken('5c8rxuhnr6skws4wc0g000ogwcok00k')"><p >Abhishek Katoch</p></button>
		</div>
		<div id="app-div">
		</div>
	</div>
<script>
    function hide(){
        document.getElementById("page-loader").style.display = "none";
    }

	function takeMe(){
		document.getElementById("page-loader").style.display = "block";
		window.location ="<?= 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online' ?>";
	}

	function setToken(token){
		document.cookie = "session_tokken="+token;
		window.location = "homepage.php";
	}
</script>	
</body>
</html>
