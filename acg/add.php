<?php

function escape($var){
	if(get_magic_quote_gpc())
	$var = stripslashes($var); //htmlentities?
	return mysql_real_escape_string($var);
}

if (isset($_POST['cid']) && isset($_POST['stime']) && isset($_POST['mode']) && isset($_POST['size']) && isset($_POST['color']) && isset($_POST['message'])){ //MYSQL_REAL_ESCAPE_STRING HTML_ENTITIES!!!
  
  $cid = $_POST['cid'];
  $message = $_POST['message'];
  $stime = $_POST['stime'];
  $mode = $_POST['mode'];
  $size = $_POST['size'];
  $color = $_POST['color'];
  
  /*
  $cid = (int)$_POST['cid'];
  $message = escape($_POST['message']);
  $stime = (int)$_POST['stime'];
  $mode = (int)$_POST['mode'];
  $size = (int)$_POST['size'];
  $color = (int)$_POST['color'];
  */
  
if (!@mysql_connect("localhost","root","") || !@mysql_select_db("danmaku"))
die("error!");

mysql_set_charset('utf8', $link);
$exec="INSERT INTO `$cid` (`id`, `stime`, `mode`, `size`, `color`, `postdate`, `message`) VALUES ('', $stime, $mode, $size, $color, ".time().", '$message')";  //date("F j, Y, g:i a")
$result=mysql_query($exec);
}
?>