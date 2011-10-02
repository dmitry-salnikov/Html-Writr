<?php
//if config isn't made then we need to install
if(!file_exists('config.php')){
	header("Location: writr_install.php");
	exit;
}
//add files and folders that you don't want edited to this array
$exclude=array('themes','core','examples');
//redirect to login if no cookie
if(!isset($_COOKIE['writr'])||$_COOKIE['writr']!=1){
	header("Location: writr_login.php");
	exit;
}
?>
<html><head>
	<link rel="stylesheet" href="core/css/base.css"/>
	<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>		
	<script src="core/js/base.js" type="text/javascript"></script>					
</head>
<?php 
if(isset($_GET['delete'])&&$_GET['delete']==true){
	if(is_dir($_GET['rmfile'])){
		require('core/helpers/file.php');
		fileHelper::rrmdir($_GET['rmfile']);
	}else{
		unlink($_GET['rmfile']);	
	}
}
if(isset($_GET['file'])&&is_file($_GET['file'])){
	//redirect to edit
}elseif(isset($_GET['add'])&&$_GET['add']&&$_GET['add']=='true'){
	//redirect to add
}else{
	//if we're browsing a folders
	echo '<body class="no">';
	if(isset($_GET['file'])&&is_dir($_GET['file'])){
		echo '<button class="submit"onclick="window.location=\'writr.php\'">&laquo; Back</button><br/><br/>';
	}
	?>
	<table class="no">
	<tr><td class="header">Files</td><td class="header">Options</td></tr><?php
	include('config.php');
	//if the current path is a dir then we open the dir- otherwise we open the root
	if(isset($_GET['file'])&&is_dir($_GET['file'])){
		$handle=opendir($_GET['file']);
	}else{
		$handle=opendir('.');
	}
	if($handle) {
		while(($file = readdir($handle)) !== false) {
			$dots=explode(".",$file);
			$extension = end($dots);
			if (substr($file, 0, 1) != '.' && ($extension=='html'||is_dir($file))&&!in_array($file, $exclude)) {
				if(is_dir($file)){
					echo '<tr><td class="folder">'.$file.'</td><td><a href="?file='.$file.'">Open</a> | <a href="?delete=true&rmfile='.$file.'">Delete</a></td></tr>';
				}else{
					if(is_dir($_GET['file'])){
						$dir=$_GET['file'].'/';
					}
					echo '<tr><td class="text">'.$file.'</td><td><a href="writr_edit.php?file='.$dir.$file.'">Edit</a> | <a href="'.$file.'">View</a> | <a href="?delete=true&rmfile='.$file.'">Delete</a></td></tr>';
				}

			}
		}
	}else{
		//probably an invalid dir that the user inputted directly
		throw new exception('Can\'t open specified dir.');
	}
	echo '</table>';
	if(isset($_GET['file'])){
		$dir=$_GET['file'];
		if($dir==''){
			$dir='/';
		}
	}else{
		$dir='/';
	}
	echo '<br/><button type="submit"class="submit half" onclick="window.location = \'writr_add.php?file='.$dir.'\';">Add Page</button><button type="submit"class="submit half right" onclick="window.location = \'writr_add.php?new=folder&file='.$dir.'\';">Add Folder</button><br/>';
}?>
  <br/><button type="submit"class="submit" onclick="document.cookie='writr' + '=' + '0';window.location = '';">Logout</button>
</body></html>