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
if(!empty($_POST)){
	if($_POST['type']=='folder'){
		$dir=$_POST['name'];
		if($_POST['name']==""){
			throw new exception('Invalid Name');
		}
		mkdir($dir);
		header("Location: writr.php?file=".$dir);
	}else{
		$template=$_POST['template'];
		$dir=$_POST['dir'].'/';
		if($_POST['dir']=="/"){
			$dir="";
		}
		$themename=explode('/', $_POST['template']);
		$themename=$themename[1];
		// get the theme name for the selected theme
		$filename=$dir.$_POST['name'].'.html';
		//template file name
		$html=copy($template, $filename);
		//copy the template to its new location
		$content=file_get_contents($filename);
		//add the title
		$content=str_replace('<head>', '<head><title>'.$_POST['name'].'</title>', $content);
		$href=preg_replace('#<link rel="stylesheet" type="text\/css" href="(.*)" \/>#smUi',$href,$content);
		$request=str_replace('writr_add.php', '', $_SERVER['REQUEST_URI']);
		$href='http://'.$_SERVER["HTTP_HOST"].$request.'/themes/'.$themename.'/';
		//get an http url to route through.
		$content=preg_replace('#(<link.*rel="stylesheet".* href=")(.*)(".*\/>)#smUi','$1'.$href.'$2$3',$content);
		//add meta stuff
		include 'core/helpers/events.php';
		$content=Events::fire('page_edit',array($filename,$template,$themename),$content);
		file_put_contents($filename, $content);
		//fire the event so we can manipulate the content.
		header("Location: writr_edit.php?file=".$filename);
		//redirect to writr_edit
		exit;
	}
}
if(!is_dir($_GET['file'])){
		header("Location: writr.php");
}
?>
<html><head>
	<link rel="stylesheet" href="core/css/base.css"/>
	<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>		
	<script src="core/js/base.js" type="text/javascript"></script>					
</head>
<?php 
if(is_dir($_GET['file'])){
	$dir=$_GET['file'];
	if($dir==""){
		$dir="/";
	}
	if(is_dir($_GET['file'])){
		if($_GET['new']=='folder'){
			//if we're adding a folder
			echo '<body class="file">';
			echo '<a class="submit" href="writr.php">&laquo;Back</a>';
			echo '<h2>Add New Folder</h2><hr>';
			echo '<form action="writr_add.php" method="post">';
			echo '<label for="name">Folder Name</label><br/>';
			echo '<input type="text" name="name"><br/>';
			echo '<input type="hidden" name="type" value="folder"><br/>';
			echo '<button class="submit" type="submit">Add</button><br/></form>';
			echo '</form>';
		}else{
			//add the file
			echo '<body class="file">';
			echo '<a class="submit" href="writr.php">&laquo;Back</a>';
			echo '<h2>Select a Template</h2><hr>';
			echo '<form action="writr_add.php" method="post">';
			echo '<label for="name">Page Name</label><br/>';
			echo '<input type="text" name="name"><br/>';
			echo '<table class="no"><tr><td class="header">Theme Name</td><td class="header">Layouts</td><td class="header">Preview</td></tr>';
			$handle=opendir('themes');
			//open the themes file
			if($handle) {
				$demo= false;
				while(($file = readdir($handle)) !== false) {
					if (substr($file, 0, 1) != '.' && is_dir('themes/'.$file)) {
						$layouts=opendir('themes/'.$file);
						//for each theme open it up
						echo '<tr><td>'.$file.'</td><td>';
						if($layouts) {
							while($template = readdir($layouts)) {
								$extension = end(explode(".",$template));
								if ($extension=='html') {
									if($template=='demo.html'){
										$demo=$template;
								 	}else{
								 		//if its a html file, and not the demo file add a link to add it
								 		echo '<input type="radio" name="template" value="themes/'.$file.'/'.$template.'"/>'.$template.'<br/>';
								 	}
								}
							}
						}else{
							//bad theme
							echo('Theme does not have requisite files.');
						}
						echo '</td><td class="preview">';
						if($demo!=false){
							echo '<a class="submit" href="javascript:void(0)" onclick=\'window.open("'.'themes/'.$file.'/'.$demo.'","Preview of '.$file.'","menubar=1,width=800,height=800")\'>Preview</a>';
							//open the demo link
						}
						echo '</td></tr>';
					}
				}
			}else{
				//probably an invalid dir that the user inputted
				throw new exception('Can\'t open specified dir.');
			}
			echo '</table><input type="hidden" name="dir" value="'.$_GET['file'].'"/>
			<button class="submit" type="submit">Add</button><br/></form>';
		}
	}
}?>
  <br/><button type="submit"class="submit" onclick="document.cookie='writr' + '=' + '0';window.location = '';">Logout</button>
</body></html>