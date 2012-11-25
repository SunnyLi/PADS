<?php
//TODO: add parts
require_once('../inc/header.php');

if (!isset($_SESSION['uid'])){
echo('You are not logged in!');
die(include_once('../inc/footer.php'));
}

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
					if ($size <= 400000){ //size check
						//move_uploaded_file($temp_loc,$upload_dir.$name);
						$xml = simplexml_load_file($temp_loc);
						$sql = db_connect('danmaku', 'main');
						$sql->set_charset('utf8');

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
				}else if($extension == 'srt' && $type == 'application/octet-stream'){

					if ($size <= 200000){ //size check
					/*	SubRip Parser
						Example result:
						Array
						(
							[0] => stdClass Object
								(
									[number] => 1
									[stopTime] => 00:00:24,400
									[startTime] => 00:00:20,000
									[text] => Altocumulus clouds occur between six thousand
								)

							[1] => stdClass Object
								(
									[number] => 2
									[stopTime] => 00:00:27,800
									[startTime] => 00:00:24,600
									[text] => and twenty thousand feet above ground level.
								)

						)
					*/
						try{
							define('SRT_STATE_SUBNUMBER', 0);
							define('SRT_STATE_TIME',      1);
							define('SRT_STATE_TEXT',      2);
							define('SRT_STATE_BLANK',     3);

							$lines   = file($temp_loc);
							$subs    = array();
							$state   = SRT_STATE_SUBNUMBER;
							$subNum  = 0;
							$subText = '';
							$subTime = '';

							foreach($lines as $line) {
								switch($state) {
									case SRT_STATE_SUBNUMBER:
										$subNum = trim($line);
										$state  = SRT_STATE_TIME;
										break;

									case SRT_STATE_TIME:
										$subTime = trim($line);
										$state   = SRT_STATE_TEXT;
										break;

									case SRT_STATE_TEXT:
										if (trim($line) == '') {
											$sub = new stdClass;
											$sub->number = $subNum;
											list($sub->startTime, $sub->stopTime) = explode(' --> ', $subTime);
											$sub->text   = $subText;
											$subText     = '';
											$state       = SRT_STATE_SUBNUMBER;

											$subs[]      = $sub;
										} else {
											$subText .= $line;
										}
										break;
								}
							}
							
							$sql = db_connect('danmaku', 'main');
							$sql->set_charset('utf8');

							$mode = 4;	// bottom
							$size = 25;
							$color = 16777215;	// white

							//print_r($subs);
							foreach($subs as $sub){
								$stime = str_replace(',', '.', $sub->startTime);
								$parts = explode(':', $stime);
								$stime = 0;
								foreach ($parts as $i => $val) {
									$stime += $val * pow(60, 2 - $i);
								}
								if($stime > 0){
									$comment = $sql->real_escape_string($sub->text);
									$sql -> query("INSERT INTO `$cid` (`stime`, `mode`, `size`, `color`, `date`, `message`, `uid`) VALUES ($stime, $mode, $size, $color, DEFAULT, '$comment', $uid)");
								}
							}
							
							echo 'comments initialized';
							header('refresh:1;/');

						}catch(Exception $e){
							echo 'error occurred while parsing data.';
						}
					
					}else{
						echo 'file too big, try uploading in parts';
					}
				}else{
					echo 'Invalid'.$type;
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