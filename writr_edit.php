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
	$file=$_POST['file'];
	$description=strip_tags($_POST['description']);
	$keywords=strip_tags($_POST['keywords']);
	$title=$_POST['title'];
	$count=$_POST['counter'];
	$content=htmlspecialchars_decode($_POST['content']);
	//add meta stuff and title
	foreach($_POST['edit'] as $key=>$edit){
		//replace the placeholders with content
		$start=$_POST['start'][$key];
		$content=str_replace( "EDIT:".$key, $start.$edit.'<!--end editable-->', $content);
	}
	include 'core/helpers/events.php';
	$args=Events::fire('page_edit',array($content,$title,$description,$keywords,$file));
	$content=$args[1];
	$title=$args[2];
	$description=$args[3];
	$keywords=$args[4];
	//format the data we get back from the event
	$content=preg_replace('#<title>(.*)</title>.*#smUi','',$content);
	$content=str_replace('<head>', '<head><title>'.$title.'</title><meta name="description" content="'.$description.'" /><meta name="keywords" content="'.$keywords.'" />', $content);
	//fire the event so others can manipulate the content
	file_put_contents($file, $content);
	header("Location: writr.php");
}
?>
<html><head>
	<link rel="stylesheet" href="core/css/base.css"/>
	<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>		
	<script src="core/js/base.js" type="text/javascript"></script>					
</head>
<?php
if(is_file($_GET['file'])){
	//if we're editing a file
	echo '<body class="file">';
	$content=file_get_contents($_GET['file']);
	$meta=get_meta_tags($_GET['file']);
	if(preg_match('#<title>(.*)<\/title>#smUi', $content,$title)>0){
		$title=str_replace('<title>', '', $title[0]);
		$title=str_replace('</title>', '', $title);
		//$title now has the page titles
	}
	echo '<button class="submit"onclick="window.location=\'writr.php\'">&laquo; Back</button><br/><br/>';?>
	<h1>Editing <?php echo $_GET['file'];?></h1><br/>
	<form action="writr_edit.php" method="post">
	<label for="title">Page Title</label><br/>
	<input type="text" name="title" value="<?php echo $title;?>"/><br/><br/>
	<label for="description">Page Description</label><br/>
	<textarea name="description" ><?php echo $meta['description'];?></textarea><br/><br/>
	<label for="keywords">Page Keywords</label><br/>
	<textarea name="keywords" ><?php echo $meta['keywords'];?></textarea><br/><br/>
	<?php

	$content=preg_replace('#<meta.*(\/>|>.*</meta>)#smUi', '', $content);//removes the meta info from the file
	if ( preg_match_all( '#<!--start editable.*-->.*<!--end editable-->#smUi',$content,$editable )) {
		//gets all the editable areas
		$i=1;
		$n=0;
		$advanced=array();
		foreach ( $editable[0] as $edit ) {
			$content=str_replace($edit, 'EDIT:'.$n, $content);
			$start=preg_replace('#(<!--start editable.*-->).*<!--end editable-->#smUi', '$1', $edit);//get the formatted comment
			$name=preg_replace('#<!--start editable(.*)-->#smUi', '$1', $start);//get the area name
			$edit=preg_replace('#<!--start editable.*-->(.*)<!--end editable-->#smUi', '$1', $edit);//get the content
			//replace the tags
			$type='';
			if(strpos($name, '[type:')){
				//get the type of the editable area.
				$type=preg_replace('#.*\[type:(.*)\]#smUi', '$1', $name);
				$name=preg_replace('#\[type:.*\]#smUi', '', $name);
			}
			if(!$name||$name==""){
				$name=$i;
			}
			$type=trim($type);
			if($type=='simple'){
			    echo '<label for="edit['.$n.']" >Editable Area '.$name.'</label><br/>';
			    echo '<input type="text" name="edit['.$n.']" value="'.$edit.'"><br/>';
			    echo '<input type="hidden" name="start['.$n.']" value="'.$start.'"/>';
			}elseif($type=='large'){
			    echo '<label for="edit['.$n.']" >Editable Area '.$name.'</label><br/>';
			    echo '<textarea class="advanced" name="edit['.$n.']" id="edit'.$n.'">'.$edit.'</textarea><br/>';
			    echo '<input type="hidden" name="start['.$n.']" value="'.$start.'"/>';
			}else{
				array_push($advanced, $n);
			    echo '<label for="edit['.$n.']" >Editable Area '.$name.'</label><br/>';
			    echo '<textarea class="advanced" name="edit['.$n.']" id="edit'.$n.'">'.$edit.'</textarea><br/>';
			    echo '<input type="hidden" name="start['.$n.']" value="'.$start.'"/>';
			}
			$i++;
			$n++;
		}
	}  ?>
	<input type="hidden" value="<?php echo $_GET['file'];?>"name="file"/>
	<input type="hidden" value="<?php echo $n-1;?>" name="counter"/>
	<input type="hidden" name="content" value="<?php echo htmlspecialchars($content);?>"/>
	<br/><button type="submit" class="submit">Save</button>
	</form>
		<script type="text/javascript">
//<![CDATA[
  bkLib.onDomLoaded(function() {
  		<?php 
  			if(is_array($advanced)){
  				foreach($advanced as $n){
  					echo "new nicEditor().panelInstance('edit$n');";
  					//makes the content editors advancde
  				}
  			}
  		?>

  });
  //]]>
  </script>
  <?php }else{
  	header("Location: writr.php");
  }?>
	<br/>
	<button type="submit"class="submit" onclick="document.cookie='writr' + '=' + '0';window.location = '';">Logout</button>
</body></html>