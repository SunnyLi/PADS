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

$query = 'SELECT * FROM `'.$cid.'`';
$result = $sql->query($query);

header('Content-Type:text/xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

echo "<i>";

if ($result->num_rows > 0)
    while($row = $result->fetch_row()){
        $var = array($row[1],$row[2],$row[3],$row[4],strtotime($row[5]),$row[7]);
        $property = join(',',$var);
        $message = htmlspecialchars($row[6]);
        echo '<d p=\'' . $property . '\'>' . $message . '</d>' . "\n";
    }

echo "</i>";
?>