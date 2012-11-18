<?php
require_once('../sqldb/connect.php');

isset($_GET['c']) ? $cid=$_GET['c'] : die('?');
if (!is_numeric($cid)) die();
	$data_array = explode('.', $cid);
	$cid = (int)$data_array[0];
	isset($data_array[1]) ? $part=(int)$data_array[1] : $part=1;

$sql = db_connect('danmaku', 'main');
$sql -> set_charset('utf8');
date_default_timezone_set('America/Toronto');

//Get file
$query = 'SELECT * FROM `'.$cid.'`';
$result = $sql->query($query);
//var_dump($result);

if(!$result) die('404');

header('Content-Type:text/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

//echo "<information>\n";
echo "<i>";

while($row = $result->fetch_row()){
//var_dump($row);

//Fix this horrible array thing
/*echo "<data><playTime>$row[1]</playTime>
<message fontsize='$row[3]' color='$row[4]' mode='$row[2]'>$row[6]</message>
<times>$row[5]</times></data>\n";	//date('Y-m-d H:i', $row[5])*/

$var = array($row[1],$row[2],$row[3],$row[4],strtotime($row[5]),$row[7]);
$property = join(',',$var);
$message = $row[6];
echo '<d p=\'' . $property . '\'>' . $message . '</d>' . "\n";
}

//echo "</information>";
echo "</i>";

//free memory... automatically done by end of script
//mysql_free_result($result);
?>