<?php
function db_connect($mysql_db){
if (!@mysql_connect('localhost','root','') || !@mysql_select_db($mysql_db))
die('ERROR!');
}

?>