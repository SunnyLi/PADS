<?php

/**
 * Default Homepage
 *
 * Basically connects to the database and produce the default layout
 *
 * @package    PADS
 * @author     Sunnyok
 * @copyright  2013
 * @license    MIT
 */

require_once('sqldb/connect.php');
require_once('inc/header.php');
ini_set('display_errors', 'On');

//Auto DB Initializer
$init_db = false;
if ($init_db)
	require_once('sqldb/initializer.php');

//Connects to database 'data' with 'main' user
$sql = db_connect('data', 'main');
$sql->set_charset("utf8");	// for displaying asian characters
$result = $sql->query("SELECT * FROM `handler` ORDER BY `date` DESC LIMIT 10");

// this appears at multiple places!
date_default_timezone_set('America/Toronto');

echo '<br /><h2>Recently Added</h2><br />';

while($content = $result->fetch_row()){
	echo '<div class="content" style="background-color: 
		rgb('.rand(100,256).','.rand(180,256).','.rand(180,256).');">';
		echo !empty($content[10])? '<img class="thumb" src="'.$content[10].'"></img>'
		: '<div class="thumb">No image<br />available!</div>';
		echo '<a href="acg/?acg='.$content[0].'" class="ctitle">'.$content[2].'</a><br />
		<span class="info">'. date($content[9]) .'@'.$content[5].'</span><br />
		<span class="desc">'.$content[3].'</span></div>';
}

$sql->close();

//Footer
require_once('inc/footer.php');
?>