<?php
if (!isset($_GET['acg'])) die ('404');
	$acg = $_GET['acg'];

if (is_numeric($acg)){
	$data_array = explode('.', $acg);
	$id = (int)$data_array[0];
	isset($data_array[1]) ? $part=(int)$data_array[1] : $part=1;

	require_once('../sqldb/connect.php');
	db_connect('data');
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
			
		}
		
		while ($data = mysql_fetch_row($query)){
		/*uses an array to catch parts
		$current variable allows perfect tracking of parts
		so part after a nulled part can still work
		although I already bypassed this issue.*/
			$current = $data[2];
			$title[$current] = $data[3];
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
		$parts = count($title);
		
		$html_title = $handle[2];
		//$uploader_id = $handle[1];
		$html_desc = $handle[3];
		$category = $handle[5]; //add category() function

	}else{
	$error[] = 'non existant';
	}

}else{
$error[] = 'url error';
}




include_once('../inc/header.php');

if(!isset($error)){

echo $category;
echo '<h2>'.$title.'</h2>';
echo $up_time[$part].'<br /><br />';

switch ($type){
case 'vid':
?>
<embed src="player.swf" height="452" width="950" rel="nofollow" flashvars=<?php
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
LOVE: FB G Y!<br />
Description:
<?php
echo isset($desc) ? $desc : 'not available';

}else{
echo 'Error';
echo isset($error[0]) ? ': '.$error[0] : '!' ;
};

include_once('../inc/footer.php');
?>