<?php
include 'core/helpers/3rdparty/spoon/spoon.php';
if(!file_exists('config.php')){
	SpoonHTTP::redirect('writr_install.php');
}
 if(!empty($_POST)){
	include('config.php');
	include 'core/helpers/events.php';
	include 'core/helpers/visitor.php';
	$user=strip_tags($_POST['user']);
	$pass=strip_tags($_POST['pass']);
	$pass=md5($pass.'html-writr');
	if($user==USER && $pass==PASS){
		$args=Events::fireEvent('on_user_valid_login',array($user));//can't be edited
		setcookie("writr", "1", time() + 3600);
		SpoonHTTP::redirect('writr.php');
	}else{
		$args=Events::fireEvent('on_user_invalid_login',array($user,Visitor::getIP()));//can't be edited
		echo 'Invalid username or password<br/>';
	}
}?>
<html><head>
	<link rel="stylesheet" href="core/css/base.css"/>
						
</head>
<body class="no">
	<h1>Login</h1><br/>
	<hr>
	<form action="writr_login.php" method="post">
	<label for="user">Username</label><br/>
	<input type="text" class="login" name="user" /><br/>
	<label for="user">Password</label><br/>
	<input type="password" class="login" name="pass" /><br/>
	<br/><button type="submit" class="submit">Login</button>
	</form>
</body></html>