<?php
/*=============================
Mysql login function
===============================
Connects to a database with the user specified.

Note:	MySQL disconnects at end of script.
*/

//Connect to database with specifed user
function get_stream($profile){
	@require_once($profile.'.php');
	
	if($stream = @mysql_connect('localhost', $user, $pass)){
		echo 'Currently using connection of '.$profile;
	}else{
		echo 'No connection to server.';
	};
}

//untested function..
function get_db($db){
	mysql_select_db($db, $stream);
}

function db_connect($mysql_db, $profile){
	require_once($profile.'.php');
	
	/*
	$db = @new mysqli('localhost', $user, $pass, $mysql_db);
	if ($db->connect_errno) {
		die('Connect Error: ' . $db->connect_errno);
	}
	*/

	//Procedural
	if (!@mysql_connect('localhost', $user, $pass) || !@mysql_select_db($mysql_db))
	die('ERROR: Cannot connect to db server!');
	
}
?>