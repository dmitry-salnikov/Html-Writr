<?php
if(!file_exists('config.php')){
	header("Location: writr_install.php");
	exit;
}
$exclude=array('resources');
if(!isset($_COOKIE['writr'])||$_COOKIE['writr']!=1){
	header("Location: writr_login.php");
	exit;
}
if(!isset($_COOKIE['writr'])||$_COOKIE['writr']!=1){
	
}
?>
<html><head>
	<link rel="stylesheet" href="resources/base.css"/>
	<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>					
</head>
<?php 
if(!$_GET['file']||(!is_file($_GET['file'])&&is_dir($_GET['file']))){
	echo '<body class="no">';
	if(is_dir($_GET['file'])){
		echo '<button id="submit"href="writr.php">&laquo; Back</button><br/><br/>';
	}
	?>
	<table class="no">
	<tr><td class="header">Files</td><td class="header">Options</td></tr><?php
	include('config.php');
	if(is_dir($_GET['file'])){
		$handle=opendir($_GET['file']);
	}else{
		$handle=opendir('.');
	}
	if($handle) {
		while(($file = readdir($handle)) !== false) {
			$extension = end(explode(".",$file));
			if (substr($file, 0, 1) != '.' && ($extension=='html'||is_dir($file))&&!in_array($file, $exclude)) {
				if(is_dir($file)){
					echo '<tr><td class="folder">'.$file.'</td><td><a href="?file='.$file.'">Open</a></td></tr>';
				}else{
					if(is_dir($_GET['file'])){
						$dir=$_GET['file'].'/';
					}
					echo '<tr><td class="text">'.$file.'</td><td><a href="?file='.$dir.$file.'">Edit</a> | <a href="'.$file.'">View</a></td></tr>';
				}
				
			}
		}
	}else{
		throw new exception('Can\'t open specified dir.');
	}
	echo '</table>';
}else{
	echo '<body class="file">';
	$content=file_get_contents($_GET['file']);
	$meta=get_meta_tags($_GET['file']);
	if(preg_match('#<title>(.*)<\/title>#smUi', $content,$title)>0){
		$title=str_replace('<title>', '', $title[0]);
		$title=str_replace('</title>', '', $title);
	}?>
	<button id="submit"href="writr.php">&laquo; Back</button><br/>
	<h1>Editing <?php echo $_GET['file'];?></h1><br/>
	<form action="writr.php" method="post">
	<label for="title">Page Title</label><br/>
	<input type="text" name="title" value="<?php echo $title;?>"/><br/><br/>
	<label for="description">Page Description</label><br/>
	<textarea name="description" ><?php echo $meta['description'];?></textarea><br/><br/>
	<label for="keywords">Page Keywords</label><br/>
	<textarea name="keywords" ><?php echo $meta['keywords'];?></textarea><br/><br/>
	<?php
	
	$content=preg_replace('#<meta.*(\/>|>.*</meta>)#smUi', '', $content);
	if ( preg_match_all( '#<!--start editable-->.*<!--end editable-->#smUi',$content,$editable )) {
		//print_r($editable);
		$i=1;
		foreach ( $editable[0] as $edit ) {
			$content=str_replace($edit, "EDIT:".$i, $content);
			$edit=str_replace('<!--start editable-->', '', $edit);
			$edit=str_replace('<!--end editable-->', '', $edit);
			echo '<label for="edit['.$i.']" >Editable Area '.$i.'</label><br/>';
			echo '<textarea class="advanced" name="edit['.$i.']" id="edit'.$i.'">'.$edit.'</textarea><br/>';
			$i++;
		}
	}  ?>
	<input type="hidden" value="<?php echo $_GET['file'];?>"name="file"/>
	<input type="hidden" value="<?php echo $i-1;?>" name="counter"/>
	<input type="hidden" name="content" value="<?php echo htmlspecialchars($content);?>"/>
	<br/><button type="submit" id="submit">Save</button>
	</form>
		<script type="text/javascript">
//<![CDATA[
  bkLib.onDomLoaded(function() {
  		<?php 
  			for($n=$i-1; $n>=1;$n--){
  				echo "new nicEditor().panelInstance('edit$n');";
  			}
  		?>

  });
  //]]>
  </script>
  <?php }?>
  <br/><button type="submit"id="submit" onclick="document.cookie='writr' + '=' + '0';window.location = '';">Logout</button>
</body></html>
<?php if(!empty($_POST)){
	$file=$_POST['file'];
	$description=strip_tags($_POST['description']);
	$keywords=strip_tags($_POST['keywords']);
	$title=$_POST['title'];
	$count=$_POST['counter'];
	$content=htmlspecialchars_decode($_POST['content']);
	$content=preg_replace('#<title>(.*)</title>.*#smUi','',$content);
	$content=str_replace('<head>', '<head><title>'.$title.'</title><meta name="description" content="'.$description.'" /><meta name="keywords" content="'.$keywords.'" />', $content);
	for($i=1;$i<=$count;$i++){
		$content=str_replace( "EDIT:".$i, "<!--start editable-->".$_POST['edit'][$i]."<!--end editable-->", $content);
	}
	file_put_contents($file, $content);
}?>