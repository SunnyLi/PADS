<?php
if (!isset($_GET['acg'])) die ('404');
	$acg = $_GET['acg'];

if (is_numeric($acg)){
	$data_array = explode('.', $acg);
	$id = (int)$data_array[0];
	isset($data_array[1]) ? $part=(int)$data_array[1] : $part=1;

	require_once('../sqldb/connect.php');
	db_connect('data', 'main');//change user 4 security!!
	//mysql_set_charset('utf8');
	$query = mysql_query("SELECT * FROM `Handler` WHERE `id`='$id'");
	$handle = mysql_fetch_row($query);

	
	if (!empty($handle)){
		$type = $handle[4];

		switch ($type){
			case 'vid':
				$query = mysql_query("SELECT * FROM `video` WHERE `vid`='$id'");
			break;

			case 'text':
				$query = mysql_query("SELECT * FROM `txt` WHERE `txt`='$id' AND `part`=$part"); //txt -> text
			break;

			case 'code':
			break;

			case 'page':
				if (!@include_once('page.php'))
					$error[] = 'internal failure';
			break;

			default:
				$error[] = 'invalid file type';
			break;
		}
		
		while ($data = mysql_fetch_row($query)){
		/*uses an array to catch parts
		$current variable allows perfect tracking of parts
		so part after a nulled part can still work
		although I already bypassed this issue.*/
			$current = $data[2];
			$titles[$current] = $data[3];
			if ($current == $part){
				if($type == 'vid'){
					$source[$current] = $data[5];
					$file[$current] = $data[6];
					$desc[$current] = $data[4];
					$up_time[$current] = $data[7];
				}
				if($type == 'text'){
					$text[$current] = $data[4];
					$up_time[$current] = $data[5];
				}
				if($type == 'code'){
					$source[$current] = $data[5];
					$desc[$current] = $data[4];
					$up_time[$current] = $data[6];
				}
			}
		}
		
		if (isset($titles)){
			$parts = @count($titles);
			@include_once('../inc/func.php');
			
			$html_title = $handle[2];
			$title = $html_title;
			//Theratically html_title will clone $titles[$part] in db value if hitori so this is logical?
			$html_desc = shorten($handle[3], 100);
			//$uploader_id = $handle[1];
			$category = $handle[5]; //add category() function
		}else{
			$error[] = 'secondary failure';
		}
		
	}else{
	$error[] = 'non existant';
	}

}else{
$error[] = 'url error';
}



include_once('../inc/header.php');

if(!isset($error)){
	echo '<div id="cat">'.$category.'</div><br />';
	echo '<h2>'.$title.'</h2>';
	echo isset($up_time[$part]) ? $up_time[$part] : $handle[9] ;
	//just in case
	//author stuff float right
	echo '<br /><hr /><br />';

	switch ($type){
		case 'vid':
			?><embed id="play" src="player.swf" height="445" width="950" rel="nofollow" flashvars=<?php
			echo '"id='.$id.'.'.$part;
			switch($source[$part]){
				case 'yt':
					echo '&type=youtube';
				break;
			}
			echo '&file='.$file[$part].'"/>';
		break;

		case 'text':
			?>
			<?php
		break;
	}

	?>
	Share: Like Tweet +1<br />
	<?php
	if ($type=='vid'||$type=='code'){
		echo 'Description:<br />';
		echo isset($desc) ? $desc : 'not available';
	}

}else{
	echo 'Error';
	echo isset($error[0]) ? ': '.$error[0] : '!' ;
};

include_once('../inc/footer.php');
?>