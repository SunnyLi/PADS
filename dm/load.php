<?php
//TODO: add parts
require_once('../inc/header.php');

if (!isset($_SESSION['uid'])){
echo('You are not logged in!');
die(include_once('../inc/footer.php'));
}

/*
danmu format
parse
load
redirect
*/

if (isset($_POST['vid']) && isset($_POST['send'])){
	$vid = (int)$_POST['vid'];	//current vid should have no part, add a part box later
	$cid = $vid;
	$uid = $_SESSION['uid'];
	if (is_numeric($vid)){

		require_once('../sqldb/connect.php');
		$sql = db_connect('data', 'main');
		$result = $sql->query("SELECT 1 FROM handler WHERE id='$vid' AND uid='$uid'");
		$sql->close();
		if ($result->num_rows > 0){	// user uploaded the video

			//print_r($_FILES['file']);
			$name = $_FILES['file']['name'];
			$type = $_FILES['file']['type'];
			$size = $_FILES['file']['size'];
			//$error = $_FILES['file']['error'];
			$temp_loc = $_FILES['file']['tmp_name'];
			$extension = substr($name, strpos($name, '.')+1);

			if (!empty($name)){
				if (($extension == 'xml' || $extension == 'json') && $type == 'text/xml'){
				//extension security - although fake extension still work but becomes harmless
					if ($size <= 300000){ //size check
						//move_uploaded_file($temp_loc,$upload_dir.$name);
						$xml = simplexml_load_file($temp_loc);

						//bili style
						foreach($xml -> d as $comment){
							try{
								$arr = explode(',', $comment['p']);

								$stime = $arr[0];
								$mode = $arr[1];
								$size = $arr[2];
								$color = $arr[3];
								$date = $arr[4];
								
								if (is_numeric($stime) && is_numeric($mode)
								&& is_numeric($size) && is_numeric($color)){
									$stime = number_format($arr[0], 2);
									$mode = (int)$mode;
									$size = (int)$size;
									$color = (int)$color;
									$date = (int)$date;
									
									$sql = db_connect('danmaku', 'main');
									$sql->set_charset('utf8');
									$comment = $sql->real_escape_string($comment);
									
									$sql -> query("INSERT INTO `$cid` (`stime`, `mode`, `size`, `color`, `date`, `message`, `uid`) VALUES ($stime, $mode, $size, $color, FROM_UNIXTIME($date), '$comment', $uid)");
								}else{
									echo 'invalid data found. bypassed.';
								}
								
								/*echo 'playtime = '.$stime.' mode = '.$mode.
									' size = '.$size.' color = '.$color.
									' date = '.$date.' comment = '.$comment.'<br />';
								*/
							}catch(Exception $e){
								echo 'error occurred while parsing data.';
							}
						}
						
						echo 'comments initialized';
						header('refresh:1;/');

					}else{
						echo 'file too big, try uploading in parts';
					}
				}else{
					echo 'Invalid';
				}
			}else{
				echo 'No file chosen';
			}
		}else{
			echo 'Insufficient Privilege/Video don\'t exist';
		}
	}else{
		echo 'Invalid video id format!';
	}
}
?>

<form action='<?php echo $_SERVER['SCRIPT_NAME']?>' method='POST' enctype='multipart/form-data'>
<label>Vid (Uploaded by you):</label><br />
<input type='number' name='vid'><br />
<label>Comment File (XML):</label><br />
<input type='file' name='file'><br />
<input type='submit' name='send' value='Submit'><br />
</form>

<?php include_once('../inc/footer.php'); ?>