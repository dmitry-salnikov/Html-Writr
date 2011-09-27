<?php
if(!file_exists('config.php')){
	header("Location: writr_install.php");
	exit;
}
?>
<html><head>
	<link rel="stylesheet" href="core/css/base.css"/>
						
</head>
<body class="no">
	<h1>Login</h1><br/>
<?php if(!empty($_POST)){
	include('config.php');
	$user=strip_tags($_POST['user']);
	$pass=strip_tags($_POST['pass']);
	$pass=md5($pass.'html-writr');
	if($user==USER && $pass==PASS){
		setcookie("writr", "1", time() + 3600);
		header("Location: writr.php");
		exit;
	}else{
		echo 'Invalid username or password<br/>';
	}
}?>
	<form action="writr_login.php" method="post">
	<label for="user">Username</label><br/>
	<input type="text" class="login" name="user" /><br/>
	<label for="user">Password</label><br/>
	<input type="password" class="login" name="pass" /><br/>
	<br/><button type="submit" id="submit">Login</button>
	</form>
</body></html>