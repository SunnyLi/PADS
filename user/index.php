<?php

if (!isset($_GET['id'])) die ('no user specified');
	$id = $_GET['id'];

if (is_numeric($id)){
    require_once('../inc/header.php');
	require_once('../sqldb/connect.php');
    
	$sql = db_connect('data', 'main');
	$sql->set_charset("utf8");
	$uploads = $sql->query("SELECT * FROM `handler` WHERE `uid`='$id' ORDER BY `date` DESC LIMIT 10");
    $userData = $sql->query("SELECT * FROM `user` WHERE `uid`='$id' LIMIT 1");
    $userData = $userData->fetch_assoc();
    $uname = $userData['name'];
    echo '<br /><h2>'.$uname.'\'s contribution</h2><br />';
    
    while($content = $uploads->fetch_row()){
        echo '<div class="content" style="background-color: 
            rgb('.rand(100,256).','.rand(180,256).','.rand(180,256).');">';
            echo !empty($content[10])? '<img class="thumb" src="'.$content[10].'"></img>'
            : '<div class="thumb">No image<br />available!</div>';
            echo '<a href="/v/sv'.$content[0].'" class="ctitle">'.$content[2].'</a><br />
            <span class="info">'. date($content[9]) .'@'.$content[5].'</span><br />
            <span class="desc">'.$content[3].'</span></div>';
    }
    
    $sql->close();

    //Footer
    require_once('inc/footer.php');
}
