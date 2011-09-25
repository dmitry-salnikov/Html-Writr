<?php 
if(file_exists('config.php')){
	header("Location: writr.php");
	exit;
} ?>
<html>
<head>
	<link rel="stylesheet" href="resources/base.css"/>
</head>
<body>
	<form method="post" action="writr_install.php">
		<h1>Writr</h1>
		<hr/>
		<h2><i>Settings</i></h2>
		<label for="user">Username</label><br/>
		<input type="text" name="user"/>
		<label for="pass">Password</label><br/>
		<input type="password" name="pass"/>
		<button type="submit" id="submit">Install</button>
	</form>
</body>
</html>
<?php if(!empty($_POST)){
	foreach($_POST as $post){
		if(!$post||$post==""){
			throw new exception('Please fill out all information.');
		}
	}
	$configuration = "<?php\n";
	$pass=md5($_POST['pass'].'html-writr');
	$configuration .= "define('USER', '" . addslashes($_POST['user']) . "');\n";
	$configuration .= "define('PASS', '" . addslashes($pass) . "');\n";
	//create the file
	file_put_contents('config.php', $configuration);
	setcookie('writr', 1);
	header("Location: writr.php");
	exit;
}?>