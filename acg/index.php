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
	$handle = mysql_fetch_assoc($query);


	if (!empty($handle)){
		$type = $handle['type'];

		switch ($type){
		
			case 'vid':
			$query = mysql_query("SELECT * FROM `video` WHERE `id`='$id'");
			$data = @mysql_fetch_assoc($query);
			if ($data){
				$provider = $data['type'];
				$file = $data['code'];
			}
			break;

			case 'text':
			$query = mysql_query("SELECT * FROM `txt` WHERE `tid`='$id' AND `part`=$part");
			break;

			case 'code':
			break;

			case 'page':
			if (!@include_once('page.php'))
				$error[] = 'internal failure';
			break;

			default:
			//invalid data type... db error
			$error[] = 1;
			
		}
		
		if($type == 'vid' || $type == 'text' || $type == 'code'){

		}

		$title = $handle['title'];
		//$uploader_id = $handle[1];
		isset($handle['desc']) ? $desc=$handle['desc'] : $desc=null ;
		$category = $handle['category']; //category function
		$upload_date = $handle['date'];
		$allow = true;

	}else{
	//id does not exist in database
	$error[] = 2;
	}

}else{
//invalid data entered... id not numeric
$error[] = 3;
}





include_once('../inc/header.php');

if(!isset($error)){

echo $category;
echo '<h2>'.$title.'</h2>';
echo $upload_date.'<br /><br />';

switch ($type){
case 'vid':
?>
<embed src="player.swf" height="450" width="950" rel="nofollow" flashvars=<?php
echo '"id='.$id.'.'.$part;
switch($provider){
case 'link';
break;

case 'yt':
echo '&type=youtube';
break;
}
echo '&file='.$file.'"/>';
break;

case 'text':
?>
<?php
break;

default:
//echo $type;
}

?>
LOVE: FB G Y!<br />
Description:
<?php
echo isset($desc) ? $desc : 'not available';

}else{
echo 'Error ';
echo isset($error[0]) ? 'code #'.$error[0] : null ;
};

include_once('../inc/footer.php');
?>