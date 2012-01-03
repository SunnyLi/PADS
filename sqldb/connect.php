<?php
/*=============================
Mysql login function
===============================
Connects to a database with the user specified.

Note:	It only connects to database but doesn't return a value
*/

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