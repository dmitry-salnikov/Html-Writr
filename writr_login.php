<?php
if(!file_exists('config.php')){
	header("Location: writr_install.php");
	exit;
}
?>
<html><head>
	<link rel="stylesheet" href="resources/base.css"/>
						
</head>
<body class="no">
	
	<h1>Login</h1><br/>
	<form action="writr_login.php" method="post">
	<label for="user">Username</label><br/>
	<input type="text" class="login" name="user" /><br/>
	<label for="user">Password</label><br/>
	<input type="password" class="login" name="pass" /><br/>
	<br/><button type="submit" id="submit">Login</button>
	</form>
<?php if(!empty($_POST)){
	include('config.php');
	$user=strip_tags($_POST['user']);
	$pass=strip_tags($_POST['pass']);
	if($user==USER && $pass==PASS){
		setcookie('WritrUserAuth', 1);
	}
}?>
</body></html>