<?php
require_once('../inc/header.php');
ini_set('display_errors', 'On');

if (!isset($_SESSION['uid']))
	die('you are not logged in!');
	
require_once('../sqldb/connect.php');
$sql = db_connect('data', 'main');
$sql->set_charset("utf8");	//required for escape and storing asian character
date_default_timezone_set('America/Toronto');

$uid = $_SESSION['uid'];

// delete content
if (isset($_GET['rm'])){
	$rmid = $_GET['rm'];
    
    if (!is_numeric($rmid))
        die('invalid');
    
	$result = $sql->query("SELECT * FROM data.handler WHERE uid=$uid AND id=$rmid");

	if($result->num_rows === 0){
		die('invalid request');
	}else if($result->num_rows === 1){
		$content = $result->fetch_row();
		if ($content[4] == 'vid'){
			$result = $sql->query("SELECT part FROM data.video WHERE vid=$rmid ORDER BY part DESC LIMIT 1");
			//print_r($result->fetch_row());	//test if part is the last
			$last_part = $result->fetch_row()[0];
			
			$sqld = db_connect('danmaku', 'main');
			$sqld->set_charset("utf8");	//required for escape and storing asian character
			
			$sql-> query("DELETE FROM data.handler WHERE id=$rmid");
			$sql-> query("DELETE FROM data.video WHERE vid=$rmid");
			$sqld->query("DROP TABLE IF EXISTS danmaku.$rmid");
			// up - current,	down - future
			for($i = 1; $i <= $last_part; $i++){
				//echo "$rmid.$i";
				$sqld->query("DROP TABLE IF EXISTS danmaku.$rmid.$i");
			}
		}else{
			echo 'this option is currently unimplemented..';
		}
	}else{
		echo 'database corrupt.. please msg admin';
		/*while($content = $result->fetch_row()){
			print_r($content);
		}*/
	}
}else if(isset($_GET['rmdm'])){
    $rmid = $_GET['rmdm'];
    
    if (!is_numeric($rmid))
        die('invalid');
    
	$result = $sql->query("SELECT * FROM data.handler WHERE uid=$uid AND id=$rmid");

	if($result->num_rows === 0){
		die('invalid request');
	}else if($result->num_rows === 1){
		$content = $result->fetch_row();
		if ($content[4] == 'vid'){
			$result = $sql->query("SELECT part FROM data.video WHERE vid=$rmid ORDER BY part DESC LIMIT 1");
			//print_r($result->fetch_row());	//test if part is the last
			$last_part = $result->fetch_row()[0];
			
			$sqld = db_connect('danmaku', 'main');
			$sqld->set_charset("utf8");	//required for escape and storing asian character
			
			$sqld->query("TRUNCATE TABLE danmaku.$rmid");   // table should exist since vid exist
			// up - current,	down - future
			for($i = 1; $i <= $last_part; $i++){
				//echo "$rmid.$i";
				$sqld->query("TRUNCATE TABLE danmaku.$rmid.$i");
			}
		}else{
			echo 'this option is currently unimplemented..';
		}
	}else{
		echo 'database corrupt.. please msg admin';
		/*while($content = $result->fetch_row()){
			print_r($content);
		}*/
	}
}

$result = $sql->query("SELECT * FROM data.handler WHERE uid=$uid ORDER BY id DESC LIMIT 10");

echo '<br /><h2>Your Stuff</h2>';

echo '<div class="option"><a href="/dm/load.php">danmaku manage</a></div><br />';

while($content = $result->fetch_row()){
	echo '<div class="content" style="background-color: lightgrey; width: 640px;">';
		echo !empty($content[10])? '<img class="thumb" src="'.$content[10].'"></img>'
		: '<div class="thumb">No image<br />available!</div>';
		echo '<a href="/v/sv'.$content[0].'" class="ctitle">'.$content[2].'</a>
		<div class="option"><a href="mod.php?id='.$content[0].'">modify</a><br /><a href="/dm/manage.php?c='.$content[0].'">dm manager</a><br />
        <a href="#" onClick="if(confirm(\'You are about to delete this item, you sure?\')){window.location=\'?rm='.$content[0].'\';}">delete this</a><br />
        <a href="?rmdm='.$content[0].'">clear pool</a></div><br />
		<span class="info">'. date($content[9]) .'@'.$content[5].'</span><br />
		<span class="desc">'.$content[3].'</span></div>';
}

include_once('../inc/footer.php');
?>