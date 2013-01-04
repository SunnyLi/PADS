<?php
require_once('../inc/header.php');

if (!isset($_SESSION['uid']))
	die('insufficient privilege');

if (!isset($_GET['id']))
    die('?');

if (!is_numeric($_GET['id']))
    die();

$itemId = $_GET['id'];

require_once('../sqldb/connect.php');
$sql = db_connect('data', 'main');
$sql->set_charset("utf8");

$result = $sql->query("SELECT * FROM `handler` WHERE id=$itemId AND uid='$uid' LIMIT 1");
    if (!$result = $result -> fetch_assoc()){
        die('bad syntax');
    }

if (isset ($_GET['title']) && isset ($_GET['desc']) && isset ($_GET['type'])
	&& isset ($_GET['cat']) && isset ($_GET['tag']) && isset ($_GET['thumb'])){
	/*htmlspecialchars for html security*/
	$title = htmlspecialchars($_GET['title']);
	$desc = htmlspecialchars($_GET['desc']);
	$type = strtolower(htmlspecialchars($_GET['type']));
	$cat = strtolower(htmlspecialchars($_GET['cat']));
	$tag = htmlspecialchars($_GET['tag']);
	$thumb = htmlspecialchars($_GET['thumb']);

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
			if ($src === 'yt' || $src === 'sina' || $src === 'qq' || $src === 'url'){
                if ($src === 'yt')
					$thumb = 'http://img.youtube.com/vi/'.$url.'/mqdefault.jpg';
                // desc is an sql term..
                $sql->query("UPDATE `handler` SET title='$title', `desc`='$desc', cat='$cat', tag='$tag', thumb='$thumb' WHERE id=$itemId");
				$sql->query("UPDATE `video` SET title='$title', `desc`='$desc', src='$src', url='$url' WHERE id=$itemId");
				$sql->close();
				session_write_close();
				echo "Video modified.";
				//header('refresh:1;/');
			}else{
				echo 'invalid type';
			}
		}else{
			echo 'not enough parameter passed!';
		}
	}else{
		die('GJ');
	}
}else{
    $result = $sql->query("SELECT * FROM `handler` WHERE id=$itemId LIMIT 1");
    if ($result = $result -> fetch_assoc()){
        $cat = $result['cat'];
        $tag = htmlspecialchars($result['tag'], ENT_COMPAT);
        $thumb = htmlentities($result['thumb']);
    }else{
        die('invalid item');
    }
    $result = $sql->query("SELECT * FROM `video` WHERE vid=$itemId LIMIT 1");
    if ($result = $result -> fetch_assoc()){
        $src = $result['src'];
        $url = htmlspecialchars($result['url'], ENT_COMPAT);
        $title = htmlspecialchars($result['title'], ENT_COMPAT);
        $desc = htmlspecialchars($result['desc'], ENT_COMPAT);
    }else{
        die('invalid id');
    }
}
?>

<section>
<h4>Modify Video</h4>
<hr />
<form action='<?php echo $_SERVER['SCRIPT_NAME'] ?>' method='GET'>
<input type='text' name='id' value='<?= $itemId ?>' hidden>
<label>Type:</label>
	<select name='type'>
		<option>Video</option>
	</select>
	<br />
<label>Source:</label>	
	<select name='src'>
		<option value='yt' <?php if($src == 'yt'){echo'selected="selected"';} ?>>YouTube</option>
		<option value='sina' <?php if($src == 'sina'){echo'selected="selected"';} ?>>Sina</option>
		<option value='qq' <?php if($src == 'qq'){echo'selected="selected"';} ?>>QQ</option>
		<option value='url' <?php if($src == 'url'){echo'selected="selected"';} ?>>Local</option>
	</select>
	<input type='text' name='url' placeholder='video id' value="<?= $url ?>"><br />
<label>Title:</label>	<input type='text' name='title' value="<?= $title ?>"><br />
<label>Desc:</label>	<input type='text' name='desc' value="<?= $desc ?>"><br />
<label>Cat:	</label>
	<select name='cat'>
		<option <?php if($cat == 'amv'){echo'selected="selected"';} ?>>AMV</option>
		<option <?php if($cat == 'mad'){echo'selected="selected"';} ?>>MAD</option>
		<option <?php if($cat == 'bgm'){echo'selected="selected"';} ?>>BGM</option>
	</select>
	<br />
<label>Tag:	</label>	<input type='text' name='tag' value="<?= $tag ?>"><br />
<label>Thumb:</label>	<input type='text' name='thumb' placeholder='thumbnail url' value="<?= $thumb ?>"><br />
<input type='submit' value='post' />
</form>
</section>

<?php include_once('../inc/footer.php') ?>