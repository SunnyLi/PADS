<?php
require_once('../sqldb/connect.php');
require_once('../inc/header.php');

$cats = array(
'Douga' => array('AMV', 'MAD', 'MMD'),
'Music' => array('Vocaloid', 'OP/ED', 'BGM'),
'Games' => array('Touhou', 'Doujin', 'Console'),
'Other' => array()
);

// check if parameter is a valid category
function get_cat($cat){
    global $cats;
	foreach ($cats as $cats2 => $subcat)
		foreach ($subcat as $subcat)
			if (strtolower($cat) == strtolower($subcat))
				return $subcat;
	return false;
}


$sql = db_connect('data', 'main');
$sql->set_charset("utf8");
date_default_timezone_set('America/Toronto');

if (isset($_GET['cat'])){
	$cat = htmlspecialchars($_GET['cat']);
	if ($cat = get_cat($cat)){
        $result = $sql->query("SELECT * FROM `handler` WHERE `cat` = '$cat' ORDER BY `date` DESC LIMIT 10");

        echo "<div class='category'><h4>$cat</h4><hr />";
        while($content = $result->fetch_row()){
            echo '<div class="content" style="background-color: 
                rgb('.rand(100,256).','.rand(180,256).','.rand(180,256).');">';
                echo !empty($content[10])? '<img class="thumb" src="'.$content[10].'"></img>'
                : '<div class="thumb">No image<br />available!</div>';
                echo '<a href="acg/?acg='.$content[0].'" class="ctitle">'.$content[2].'</a><br />
                <span class="info">'. date($content[9]) .'@'.$content[5].'</span><br />
                <span class="desc">'.$content[3].'</span></div>';
        }
        echo '<br /><div style="clear: both;"></div></div>';
	}else{
		echo 'not found..';
	}
}else{
	//default page
	echo '<h2>Categories</h2><hr />';

	foreach ($cats as $cats => $subcat){
		echo "<div class='category'><h3>$cats</h3><hr />";
		foreach ($subcat as $subcat){
			$result = $sql->query("SELECT * FROM `handler` WHERE `cat` = '$subcat' ORDER BY `date` DESC LIMIT 10");

			echo "<div class='category'><h4>$subcat</h4><hr />";
			while($content = $result->fetch_row()){
				echo '<div class="content" style="background-color: 
					rgb('.rand(100,256).','.rand(180,256).','.rand(180,256).');">';
					echo !empty($content[10])? '<img class="thumb" src="'.$content[10].'"></img>'
					: '<div class="thumb">No image<br />available!</div>';
					echo '<a href="acg/?acg='.$content[0].'" class="ctitle">'.$content[2].'</a><br />
					<span class="info">'. date($content[9]) .'@'.$content[5].'</span><br />
					<span class="desc">'.$content[3].'</span></div>';
			}
			echo '<br /><div style="clear: both;"></div></div>';
		}
		echo "</div>";
	}
}

include_once('../inc/footer.php');
?>