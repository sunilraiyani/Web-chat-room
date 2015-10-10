<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Chat</title>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>
 
 <?php
session_start();
 
function loginForm(){
    echo'
    <div id="loginform">
    <form action="login.php" method="post">
        <p>Please enter your credentials to continue:</p>
        <label for="email">E-mail:</label>
        <input type="text" name="email" id="email" />
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" />
        <input type="submit" name="enter" id="enter" value="Enter" />
    </form>
    </div>
    ';
}

function loginForm2(){
    echo'
    <div id="loginform">
    <form action="login.php" method="post">
        <p>Invalid Credentials:</p>
        <label for="email">E-mail:</label>
        <input type="text" name="email" id="email" value="';
		echo $_SESSION['su']; 
		echo'"/>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" />
        <input type="submit" name="enter" id="enter" value="Enter" />
    </form>
    </div>
    ';
}

if(isset($_POST['enter'])){
    if($_POST['email'] != ""){
        $_SESSION['email'] = stripslashes(htmlspecialchars($_POST['email']));
    }
    else{
        echo '<span class="error">Please type in a email</span>';
    }
}
?>

<?php

if(!isset($_SESSION['name'])){
	if(isset($_SESSION['loginok'])){
		if($_SESSION['loginok']!=true){
		loginForm2();
		}
	}
	else{
		loginForm();
	}
}
else{
?>
<div id="wrapper">
    <div id="menu">
        <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
        <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
        <div style="clear:both"></div>
    </div>    
    <div id="chatbox"></div>
     
    <form name="message" action="">
        <input name="usermsg" type="text" id="usermsg" size="63" />
        <input name="submitmsg" type="submit"  id="submitmsg" value="Send" />
    </form>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript">
// jQuery Document
$(document).ready(function(){
	//If user submits the form
	$("#submitmsg").click(function(){	
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});				
		$("#usermsg").attr("value", "");
		return false;
	});
	
	//Load the file containing the chat log
	function loadLog(){		

		$.ajax({
			url: "log.html",
			cache: false,
			success: function(html){		
				$("#chatbox").html(html); //Insert chat log into the #chatbox div				
		  	},
		});
	}
	
	//Load the file containing the chat log
	function loadLog(){		
		var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
		$.ajax({
			url: "log.html",
			cache: false,
			success: function(html){		
				$("#chatbox").html(html); //Insert chat log into the #chatbox div	
				
				//Auto-scroll			
				var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
				if(newscrollHeight > oldscrollHeight){
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
				}				
		  	},
		});
	}
	
	setInterval (loadLog, 2500);	//Reload file every 2500 ms or x ms if you w
});
</script>
<?php
}
?>
<script type="text/javascript">
// jQuery Document
$(document).ready(function(){
	//If user wants to end session
	$("#exit").click(function(){
		var exit = confirm("Are you sure you want to end the session?");
		if(exit==true){window.location = 'index.php?logout=true';}		
	});
});
</script>
<?php 
if(isset($_GET['logout'])){ 
     
    //Simple exit message
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");
    fclose($fp);
     
    session_destroy();
    header("Location: index.php"); //Redirect the user
}
?>

</body>
</html>