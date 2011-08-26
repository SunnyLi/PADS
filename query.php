<?php require('sqldb/connect.php');
db_connect('data');
$exe = mysql_query("SELECT MAX(id) FROM `handler`");
$high = mysql_fetch_array($exe);
echo $high[0];
?>