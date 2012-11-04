<?php
require_once('../sqldb/connect.php');
ini_set('display_errors', 'On');
if (isset($_POST['cid']) && isset($_POST['stime']) && isset($_POST['mode'])
	&& isset($_POST['size']) && isset($_POST['color']) && isset($_POST['message'])){
	//MYSQL_REAL_ESCAPE_STRING HTML_ENTITIES!!!

  $cid = $_POST['cid'];
  $message = $_POST['message'];
  $stime = $_POST['stime'];
  $mode = $_POST['mode'];
  $size = $_POST['size'];
  $color = $_POST['color'];
  
	if (!is_numeric($cid)) die();
	$data_array = explode('.', $cid);
	$cid = (int)$data_array[0];
	isset($data_array[1]) ? $part=(int)$data_array[1] : $part=1;
  
  /*
  $cid = (int)$_POST['cid'];
  $message = escape($_POST['message']);
  $stime = (int)$_POST['stime'];
  $mode = (int)$_POST['mode'];
  $size = (int)$_POST['size'];
  $color = (int)$_POST['color'];
  */

$sql = db_connect('danmaku', 'main');
$sql->set_charset('utf8');

$exec="INSERT INTO `$cid` (`id`, `stime`, `mode`, `size`, `color`, `date`, `message`) VALUES ('', $stime, $mode, $size, $color, DEFAULT, '$message')";  //date("F j, Y, g:i a")
$sql->query($exec);
$sql->close();
}
?>