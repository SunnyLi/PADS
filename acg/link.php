<?php
require_once('../inc/header.php');

if (!isset($_SESSION['uid']))
	die('insufficient privilege');

// form processing
if (isset ($_GET['title']) && isset ($_GET['desc']) && isset ($_GET['type'])
	&& isset ($_GET['cat']) && isset ($_GET['tag']) && isset ($_GET['thumb'])){
	/*htmlspecialchars for html security*/
	$title = htmlspecialchars($_GET['title']);
	$desc = htmlspecialchars($_GET['desc']);
	$type = strtolower(htmlspecialchars($_GET['type']));
	$cat = strtolower(htmlspecialchars($_GET['cat']));
	$tag = htmlspecialchars($_GET['tag']);
	$thumb = htmlspecialchars($_GET['thumb']);

	require_once('../sqldb/connect.php');
	$sql = db_connect('data', 'main');
	// escape string
	$sql->set_charset("utf8");	//required for escape and storing asian character
	$title = $sql->real_escape_string($title);
	$desc = $sql->real_escape_string($desc);
	$tag = $sql->real_escape_string($tag);
	$thumb = $sql->real_escape_string($thumb);

	if ($type == 'video'){
		$src = htmlspecialchars($_GET['src']);
		$url = htmlspecialchars($_GET['url']);
		
		$src = $sql->real_escape_string($src);
		$url = $sql->real_escape_string($url);
		
		// video required
		if (!empty($title) && !empty($type) && !empty($cat)
				&& !empty($src) && !empty($url)){
			
			if ($src === 'yt' || $src === 'sina' || $src === 'url'){
				$uid = $_SESSION['uid'];
				if ($src === 'yt')
					$thumb = 'http://img.youtube.com/vi/'.$url.'/default.jpg';
				$sql->query("INSERT INTO `handler` (`uid`, `title`, `desc`, `type`, `cat`, `tag`, `thumb`) VALUES ($uid, '$title', '$desc', 'vid', '$cat', '$tag', '$thumb')");
				$inc = $sql->insert_id;
				$sql->query("INSERT INTO `video` (`vid`, `part`, `title`, `desc`, `src`, `url`) VALUES ($inc, DEFAULT, '$title', '$desc', '$src', '$url')");
				$sql->query("CREATE TABLE `danmaku`.`$inc` LIKE `danmaku`.`cmt#`");
				$sql->close();
				session_write_close();
				echo "Video added.";
				header('refresh:1;/');
			}else{
				echo 'invalid type';
			}
		}else{
			echo 'not enough parameter passed!';
		}
	}else if($type == 'article'){
		$txt = htmlspecialchars($_GET['txt']);
	}else if($type == 'code'){
		$src = htmlspecialchars($_GET['src']);
	}else{
		die('GJ');
	}
}
?>

<section>
<h4>Linking to a video</h4>
<hr />
<form action='<?php echo $_SERVER['SCRIPT_NAME'] ?>' method='GET'>
<label>Type:</label>
	<select name='type'>
		<option>Video</option>
		<option>Article</option>
		<option>Code</option>
	</select>
	<br />
<label>Source:</label>	
	<select name='src'>
		<option value='yt'>YouTube</option>
		<option value='sina'>Sina</option>
		<option value='url'>Local</option>
		<option>Dailymotion</option>
	</select>
	<input type='text' name='url' placeholder='video id'><br />
<label>Title:</label>	<input type='text' name='title'><br />
<label>Desc:</label>	<input type='text' name='desc'><br />
<label>Cat:	</label>
	<select name='cat'>
		<option>AMV</option>
		<option>MAD</option>
		<option>BGM</option>
	</select>
	<br />
<label>Tag:	</label>	<input type='text' name='tag'><br />
<label>Thumb:</label>	<input type='text' name='thumb' placeholder='url'><br />
<input type='submit' value='post' />
</form>
</section>

<?php include_once('../inc/footer.php') ?>