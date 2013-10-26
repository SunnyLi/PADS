<?php
/*=============================
Mysql login function
===============================
Connects to a database with the user specified.

Note:	MySQL disconnects at end of script.
*/

//Connect to database with specifed user
function get_stream($profile){
	require($profile.'.php');
	
	$stream = mysqli_connect('localhost', $user, $pass);

	if($stream){
		echo 'Currently using connection of '.$profile;
		return $stream;
	}else{
		echo 'No connection to server.';
	};
}

function db_connect($mysql_db, $profile){
	require($profile.'.php');

	$sql = mysqli_connect('localhost', $user, $pass, $mysql_db);

	if (!$sql)
		 die('Cannot connect to the database!<br>If this is your first time running, please run the db initializer function by setting `$init_db = true;` in index.php located in root directory');

	return $sql;
}
?>