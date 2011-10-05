<?php
include 'core/helpers/3rdparty/spoon/spoon.php';
include 'core/helpers/extend.php';
//if config isn't made then we need to install
if(!file_exists('config.php')){
	SpoonHTTP::redirect('writr_install.php');
}
//redirect to login if no cookie
if(!isset($_COOKIE['writr'])||$_COOKIE['writr']!=1){
	SpoonHTTP::redirect('writr_login.php');
}
?>
<html><head>
	<link rel="stylesheet" href="core/css/base.css"/>
	<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>		
	<script src="core/js/base.js" type="text/javascript"></script>					
</head>
<body class="edit">
	<button class="submit"onclick="window.location='writr.php'">&laquo; Back</button><br/><br/>';
	<table class="no">
	<tr><td class="header">Extensions</td><td class="header">Description</td><td class="header">Options</td></tr><?php
	//if the current path is a dir then we open the dir- otherwise we open the root
	$handle=opendir('extend');
	if($handle) {
		while(($file = readdir($handle)) !== false) {
			$extension=SpoonFile::getExtension($file);
			if ($extension=='php') {
				$info=Extend::getInfo($file);
				$installed=Extend::getInstalled();
				$action='install';
				$acname='Install';
				if(in_array($file, $installed)){
					//if the extension is installed
					$action='uninstall';
					$acname='Uninstall';
				}
				$name=$info['name'];
				$description=$info['description'];
				echo '<tr><td>'.$name.'</td><td>'.$description.'</a></td><td><a href="?action='.$action.'&file='.$file.'">'.$acname.'</a></td></tr>';
			}
		}
	}else{
		//probably an invalid dir that the user inputted directly
		throw new SpoonException('Can\'t open specified extend.');
	}
	echo '</table>';
}?>
  <br/><button type="submit"class="submit" onclick="document.cookie='writr' + '=' + '0';window.location = '';">Logout</button>
</body></html>