<?php
require_once('../sqldb/connect.php');
if (isset($_POST['cid']) && isset($_POST['stime']) && isset($_POST['mode'])
	&& isset($_POST['size']) && isset($_POST['color']) && isset($_POST['message'])){

  $cid = $_POST['cid'];
  $message = $_POST['message'];
  $stime = $_POST['stime'];
  $mode = $_POST['mode'];
  $size = $_POST['size'];
  $color = $_POST['color'];
  
	if (!is_numeric($cid) && !is_numeric($stime) && !is_numeric($mode)
			&& !is_numeric($size) && !is_numeric($color)) die();
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
date_default_timezone_set('America/Toronto');

$cid = $sql->real_escape_string($cid);
$message = $sql->real_escape_string($message);
$stime = $sql->real_escape_string($stime);
$mode = $sql->real_escape_string($mode);
$size = $sql->real_escape_string($size);
$color = $sql->real_escape_string($color);

session_start();
$uid = $_SESSION['uid'];

$exec="INSERT INTO `$cid` (`stime`, `mode`, `size`, `color`, `date`, `message`, `uid`) VALUES ($stime, $mode, $size, $color, DEFAULT, '$message', $uid)";
$sql->query($exec);
$sql->close();
}
?>