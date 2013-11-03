<?php
//ini_set('display_errors', 'On');

if (!isset($_GET['acg'])) die ('404');
	$acg = $_GET['acg'];

if (is_numeric($acg)){
	$data_array = explode('.', $acg);
	$id = (int)$data_array[0];
	isset($data_array[1]) ? $part=(int)$data_array[1] : $part=1;

	require_once('../sqldb/connect.php');
	$sql = db_connect('data', 'main');
	$sql->set_charset("utf8");
	$query = $sql->query("SELECT * FROM `handler` WHERE `id`='$id'");
	//var_dump($query);
	$handle = $query->fetch_row();
	
	if (!empty($handle)){
        $upid = $handle[1];
        $query = $sql->query("SELECT * FROM `user` WHERE `uid`='$upid'");
        $upInfo = $query->fetch_row();
        $up_name = 'undefined';
        if (!empty($upInfo))
            $up_name = $upInfo[1];
		$type = $handle[4];

		switch ($type){
			case 'vid':
				$query = $sql->query("SELECT * FROM `video` WHERE `vid`='$id'");
			break;

			case 'text':
				$query = $sql->query("SELECT * FROM `txt` WHERE `txt`='$id' AND `part`=$part"); //txt -> text
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
		
		while ($data = $query->fetch_row()){
			//print_r($data);
			$current = $data[2];
			$titles[$current] = $data[3];
			if ($current == $part){
				if($type == 'vid'){
					$source[$current] = $data[6];
					$file[$current] = $data[7];
					$desc[$current] = $data[4];
					$up_time[$current] = $data[8];
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

// functions copied from category.php
$cats = array(
  'Douga' => array('AMV', 'MAD', 'MMD'),
  'Music' => array('Vocaloid', 'OP/ED', 'BGM'),
  'Games' => array('Touhou', 'Doujin', 'Console'),
  'Other' => array()
);

function cat($cat){
    global $cats;
    
    foreach ($cats as $kats => $subcat){
        foreach ($subcat as $subcat){
            if (strtolower($cat) == strtolower($subcat))
                return $kats.' - '.$subcat;
        }
    }
    return false;
}

if(!isset($error)){

	echo '<div id="cat">';
    echo cat($category);
    echo '</div><br />';
	echo '<h2>'.$title.'</h2>';
    echo '<span style="float: right">up: <a href="/user/'.$upid.'">'.$up_name.'</a></span>';
	echo isset($up_time[$part]) ? $up_time[$part] : $handle[9] ;
	//just in case
	//author stuff float right
	echo '<br /><hr /><br />';

	switch ($type){
		case 'vid':
			?><embed id="play" src="/player/MukioPlayerPlus.swf" height="445" width="950" rel="nofollow" allowfullscreen="true" flashvars=<?php
			echo '"id='.$id.'.'.$part;
			switch($source[$part]){
				case 'yt':
					echo '&type=youtube';
				case 'url':
					echo '&file='.$file[$part];
					break;
				case 'sina':
					echo '&type=sina&vid='.$file[$part];
					break;
				case 'qq':
					echo '&type=qq&vid='.$file[$part];
					break;
			}
			echo '&autostart=false"/>';
		break;

		case 'text':
			?>
			<?php
		break;
	}

	?>
	Share: unavailable in alpha<br /><br />
	<?php
	if ($type=='vid'||$type=='code'){
		echo 'Description:<br />';
		echo isset($desc[$part]) ? $desc[$part] : 'not available';
	}

}else{
	echo 'Error';
	echo isset($error[0]) ? ': '.$error[0] : '!' ;
};

include_once('../inc/footer.php');
?>