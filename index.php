<?php
/*=============================
Default Homepage
===============================
Basically connects to the database and produce the default layout

Security Note: Do not connect using main(root?) account.
*/

//error_reporting(E_ALL);
ini_set('display_errors', 'On');

//required moduel fo later use
require_once('sqldb/connect.php');
require_once('inc/header.php');

//Auto DB Initializer
//to initialize database set variable to true
$init_db = false;
if ($init_db){
	require_once('sqldb/initializer.php');
}

//Session_start included in header
//Random setters for testing purposes
//$_SESSION['uid'] = 1;
//$_SESSION['name'] = "Sunny";
//session_unset('name');
//session_unset('uid');

//Connects to database 'data' with 'main' user
$sql = @db_connect('data', 'main');

//$contents = @mysql_query("SELECT * FROM `video` WHERE `part`='1' ORDER BY `date` DESC LIMIT 10");
$result = $sql->query("SELECT * FROM `handler` ORDER BY `date` DESC LIMIT 10");

date_default_timezone_set('America/Toronto');

while($content = $result->fetch_row()){
	echo '<div class="content" style="background-color: 
		rgb('.rand(150,256).','.rand(150,256).','.rand(150,256).');">
		<a href="acg/?acg='.$content[0].'" class="ctitle">'.$content[2].'</a><br />
		<span class="info">'. date($content[9]) .'@'.$content[5].'</span><br />
		<span class="desc">'.$content[3].'</span></div>';
}

$sql->close();

//Footer
require_once('inc/footer.php');
?>