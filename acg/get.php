<?php
//Get danmaku id
//Player do not POST when requesting file.
if (isset($_GET['c'])){
$cid=$_GET['c'];
}else{
die('?');
}

//Connect Server
$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Not connected : ' . mysql_error());
}

//Connect DB
$db_selected = mysql_select_db('danmaku', $link);
if (!$db_selected) {
    die ('No response : ' . mysql_error());
}

//Set encode?
mysql_set_charset('utf8', $link);

//Get file
$query = 'SELECT * FROM `'.$cid.'`';
$result = mysql_query($query, $link);

//Test if exist
if(!$result)
die('404'); //redirect to 404 page

//Outputting
header('Content-Type:text/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

echo "<information>\n";
//echo "<i>";

while($row = mysql_fetch_row($result)){

//Fix this horrible array thing
echo "<data><playTime>$row[2]</playTime><message fontsize='$row[4]' color='$row[5]' mode='$row[3]'>$row[7]</message><times>date('Y-m-d H:i', $row[6])</times></data>\n";

//$var = array($row[2],$row[3],$row[4],$row[5],$row[6]);
//$property = join(',',$var);
//$message = $row[7];
//echo '<d p=\'' . $property . '\'>' . $message . '</d>' . "\n";

}

echo "</information>";
//echo "</i>";

//free memory... automatically done by end of script
//mysql_free_result($result);
?>