<?php
function db_connect($mysql_db, $profile){
	require_once($profile.'.php');
	
	/*
	//OOP?
	$db = @new mysqli('localhost', $user, $pass, $mysql_db);
	if ($db->connect_errno) {
		die('Connect Error: ' . $db->connect_errno);
	}
	*/
	
	//Procedural FTW
	if (!@mysql_connect('localhost', $user, $pass) || !@mysql_select_db($mysql_db))
	die('ERROR!');
	
}
?>