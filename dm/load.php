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
				if ($extension == 'xml' && $type == 'text/xml'){
				//extension security - although fake extension still work but becomes harmless
					if ($size <= 3000000){ //size check // need to raise php upload limit!
						//move_uploaded_file($temp_loc,$upload_dir.$name);
						$xml = simplexml_load_file($temp_loc);
						$sql = db_connect('danmaku', 'main');
						$sql->set_charset('utf8');

                        $rootNode = $xml->getName();
                        
                        if($rootNode == 'information'){
                            foreach($xml->data as $data){
                                try{
                                    $stime = (string)$data->playTime;
                                    $stime = number_format($stime, 1);
                                    $mode = (int)$data->message['mode'];
                                    $size = (int)$data->message['fontsize'];
                                    $color = (int)$data->message['color'];
                                    $date = (string)$data->times;
                                    $date = strtotime($date);   // timestamp
                                    $comment = (string)$data->message;
                                    $comment = $sql->real_escape_string($comment);

                                    $sql -> query("INSERT INTO `$cid` (`stime`, `mode`, `size`, `color`, `date`, `message`, `uid`) VALUES ($stime, $mode, $size, $color, FROM_UNIXTIME($date), '$comment', $uid)");
                                }catch(Exception $e){
                                    echo 'error occurred while parsing data.';
                                }
                            }
                        }else if ($rootNode == 'i'){
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
						}else if($rootNode == 'packet'){
                            //nico style
                            //whitelist
                            $modeArr = array('ue', 'shita');        // naka is default
                            $sizeArr = array('big', 'small');       // medium is default
                            $colorArr = array('red', 'pink', 'orange', 'yellow', 'green', 'cyan', 'blue', 'blue', 'purple', 'black',
                                                'white2', 'niconicowhite', 'red2', 'truered', 'pink2', 'orange2', 'passionorange',
                                                'yellow2', 'madeyellow', 'green2', 'elementalgreen', 'cyan2', 'blue2', 'marineblue',
                                                'purple2', 'nobleviolet', 'black2');
                                                
                            $whitelist = array_merge($modeArr, $sizeArr, $colorArr);      // whitelist array

                            foreach($xml->chat as $comment){
                                if (isset($comment['vpos']) && isset($comment['mail'])){
                                    try{
                                        // ===== check vpos =====
                                        $stime = $comment['vpos']->__toString();    // get actual value
                                        if (!is_numeric($stime))
                                            continue;   // fail, try next case

                                        $stime = $comment['vpos'];
                                        if($stime > 30000)  // glitch fix? video_sec * 100, current: 5min
                                            $stime /= 10;
                                        $stime /= 100;
                                        $stime = number_format($stime, 2);
                                        
                                        // ===== check date =====
                                        $date = (int)$comment['date'];  // may cause unexpected failure

                                        // ===== check mail =====
                                        // Default values
                                        $mode = 1;
                                        $size = 25;
                                        $color = 16777215;

                                        // filter
                                        $mail = strtolower($comment['mail']);   // converts to string also!
                                        if (strpos($mail,'invisible') !== false)
                                            continue;

                                        preg_match('/#([0-9a-f]{6})\b/', $mail, $colorReg);
                                        
                                        $mail = explode(' ', $mail);            // original array
                                        $para = array_values(array_intersect($whitelist, $mail));

                                        if(!empty($para)){

                                            $i = 0;     // iterator

                                            if (isset($para[$i]))
                                                if (in_array($para[$i], $modeArr)){
                                                    switch ($para[$i]){
                                                        case 'ue':
                                                            $mode = 5;
                                                            break;
                                                        case 'shita':
                                                            $mode = 4;
                                                            break;
                                                    }
                                                    $i++;
                                                }

                                            if (isset($para[$i]))
                                                if (in_array($para[$i], $sizeArr)){
                                                    switch ($para[$i]){
                                                        case 'big':
                                                            $size = 36;
                                                            break;
                                                        case 'small':
                                                            $size = 16;
                                                            break;
                                                    }
                                                    $i++;
                                                }

                                            if (!isset($colorReg[1]) && isset($para[$i]))
                                                if (in_array($para[$i], $colorArr))
                                                    switch ($para[$i]){
                                                        case 'red':
                                                            $color = hexdec('FF0000');
                                                            break;
                                                        case 'pink':
                                                            $color = hexdec('FF8080');
                                                            break;
                                                        case 'orange':
                                                            $color = hexdec('FFC000');
                                                            break;
                                                        case 'yellow':
                                                            $color = hexdec('FFFF00');
                                                            break;
                                                        case 'green':
                                                            $color = hexdec('00FF00');
                                                            break;
                                                        case 'cyan':
                                                            $color = hexdec('00FFFF');
                                                            break;
                                                        case 'blue':
                                                        case 'ｂlue':
                                                            $color = hexdec('0000FF');
                                                            break;
                                                        case 'purple':
                                                            $color = hexdec('C000FF');
                                                            break;
                                                        case 'black':
                                                            $color = 0;
                                                            break;
                                                        case 'white2':
                                                        case 'niconicowhite':
                                                            $color = hexdec('CCCC99');
                                                            break;
                                                        case 'red2':
                                                        case 'truered':
                                                            $color = hexdec('CC0033');
                                                            break;
                                                        case 'pink2':
                                                            $color = hexdec('FF33CC');
                                                            break;
                                                        case 'orange2':
                                                        case 'passionorange':
                                                            $color = hexdec('FF6600');
                                                            break;
                                                        case 'yellow2':
                                                        case 'madeyellow':
                                                            $color = hexdec('999900');
                                                            break;
                                                        case 'green2':
                                                        case 'elementalgreen':
                                                            $color = hexdec('00CC66');
                                                            break;
                                                        case 'cyan2':
                                                            $color = hexdec('00CCCC');
                                                            break;
                                                        case 'blue2':
                                                        case 'marineblue':
                                                            $color = hexdec('3399FF');
                                                            break;
                                                        case 'purple2':
                                                        case 'nobleviolet':
                                                            $color = hexdec('6633CC');
                                                            break;
                                                        case 'black2':
                                                            $color = hexdec('666666');
                                                            break;
                                                    }
                                        }
                                        
                                        if(isset($colorReg[1]))
                                            $color = hexdec($colorReg[1]);
                                        
                                        $comment = $comment->__toString();
                                        $comment = $sql->real_escape_string($comment);
                                        $sql -> query("INSERT INTO `$cid` (`stime`, `mode`, `size`, `color`, `date`, `message`, `uid`) VALUES ($stime, $mode, $size, $color, FROM_UNIXTIME($date), '$comment', $uid)");
                                        
                                    }catch(Exception $e){
                                        echo 'error occurred while parsing data.';
                                    }
                                }
                            }
                        }
                        
						echo 'comments initialized';
						header('refresh:1;/');

					}else{
						echo 'file too big, try uploading in parts';
					}
				}else if($extension == 'json' && ($type == 'application/json' || $type == 'application/octet-stream')){
                    // assume new acfun format
                    $json = json_decode(file_get_contents($temp_loc), true);

                    $sql = db_connect('danmaku', 'main');
					$sql->set_charset('utf8');
                    
                        foreach($json as $comment){
                        
                            try{
                                $arr = explode(',', $comment['c']);

                                $stime = $arr[0];
                                $mode = $arr[2];
                                $size = $arr[3];
                                $color = $arr[1];
                                $date = $arr[5];
                                
                                if (is_numeric($stime) && is_numeric($mode)
                                && is_numeric($size) && is_numeric($color)){
                                    $stime = number_format($arr[0], 2);
                                    $mode = (int)$mode;
                                    $size = (int)$size;
                                    $color = (int)$color;
                                    $date = (int)$date;
                                    $comment = (string)$comment['m'];   // not an array
                                }else{
                                    echo 'invalid data found. bypassed.';
                                    continue;
                                }
                                
                                // create bili style
                                if ($mode == 7){
                                    $para = json_decode($comment, true);
                                    $comment = $para['n'];
                                    
                                    if (!isset($para['p']['x']) || !isset($para['p']['y']))
                                        continue;   // basic coordinate not set!
                                    
                                    $x = (int)($para['p']['x'] * 0.3);
                                    $y = (int)($para['p']['y'] * 0.43);
                                    
                                    $duration = 4;
                                    $opacity = 1;
                                    $shadow = false;
                                    //$oSize = $size;  // original
                                    
                                    if (isset($para['l'])){
                                        $stime += $para['l'];
                                        $stime = number_format($stime, 1);
                                    }
                                    
                                    if (isset($para['c']))
                                        if ($para['c'] > 0){    // check isset?
                                            $color = $para['c'];
                                        }
                                    
                                    if (isset($para['a']))
                                        $opacity = $para['a'] + 0;
                                    
                                    if (isset($para['b']))
                                        if ($para['b'] == true) // or is it bold??
                                            $shadow = true;

                                    /*if (isset($para['f'])){
                                        $size *= $para['f'] * 0.8;  //floor it?
                                    }*/
                                    
                                    if (isset($para['z'])){
                                        //transition
                                        $text = $comment;
                                        $toX = $x;
                                        $toY = $y;
                                        $opacity2 = $opacity;
                                        
                                        $iterations = count($para['z']);
                                        
                                        foreach($para['z'] as $trans){
                                            // ignore color change for now
                                            // idea: color2 - (color2 - color) // iterations

                                            $duration = 4;
                                            if (isset($trans['l']))
                                                if (is_numeric($trans['l']))
                                                    $duration = $trans['l'];
                                            
                                            if (isset($trans['x']))
                                                $toX = (int)($trans['x'] * 0.3);
                                            
                                            if (isset($trans['y']))
                                                $toY = (int)($trans['y'] * 0.43);
                                            
                                            if (isset($trans['t']))
                                                $opacity2 = $trans['t'] + 0;
                                            
                                            // don't know what shadow value takes..
                                            $comment = "[\"$x\",\"$y\",\"$opacity-$opacity2\",\"$duration\",\"$text\",\"0\",\"0\",\"$toX\",\"$toY\",\"$duration\",\"0\"]";
                                            
                                            // insert here
                                            $comment = $sql->real_escape_string($comment);
                                            $sql -> query("INSERT INTO `$cid` (`stime`, `mode`, `size`, `color`, `date`, `message`, `uid`) VALUES ($stime, $mode, $size, $color, FROM_UNIXTIME($date), '$comment', $uid)");
                                            
                                            // set for next interation
                                            $x = $toX;
                                            $y = $toY;
                                            $stime += $duration;
                                            $stime = number_format($stime, 1);
                                            $opacity = $opacity2;
                                            
                                            //if (isset($trans['f']))
                                            //    $size = $oSize * $trans['f'] * 0.8;
                                            
                                            // echo 'playtime = '.$stime.' mode = '.$mode.' size = '.$size.' color = '.$color.' date = '.$date.' comment = '.$comment.'<br />';
                                        }
                                        
                                    }else{
                                        // assemble basic mode 7 cmt, shade ignored
                                        $comment = "[\"$x\",\"$y\",\"$opacity-$opacity\",\"$duration\",\"$comment\",\"0\",\"0\"]";
                                        $comment = $sql->real_escape_string($comment);
                                        
                                        $sql -> query("INSERT INTO `$cid` (`stime`, `mode`, `size`, `color`, `date`, `message`, `uid`) VALUES ($stime, $mode, $size, $color, FROM_UNIXTIME($date), '$comment', $uid)");
                                        
                                        // echo 'playtime = '.$stime.' mode = '.$mode.' size = '.$size.' color = '.$color.' date = '.$date.' comment = '.$comment.'<br /><br />';
                                    }
                                }else{
                                    // default insert
                                    $comment = $sql->real_escape_string($comment);
                                    $sql -> query("INSERT INTO `$cid` (`stime`, `mode`, `size`, `color`, `date`, `message`, `uid`) VALUES ($stime, $mode, $size, $color, FROM_UNIXTIME($date), '$comment', $uid)");
                                }
                                
                            }catch(Exception $e){
                                echo 'error occurred while parsing data.';
                            }
                        }
                        
						echo 'comments initialized';
						header('refresh:1;/');
                        
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