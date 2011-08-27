<?php
//check query string var entered
if (!isset($_GET['acg'])) die ('404');
$acg = $_GET['acg'];

try{
//check query string is a number
if (is_numeric($acg)){
//explode the numeric string (if no decimal no second value will return)
$data_array = explode('.', $acg);
//set data id
$id = (int)$data_array[0];
//set part if specified
if (isset($data_array[1])){
$part = (int)$data_array[1];
}

//query
require_once('../sqldb/connect.php');
db_connect('data');
//mysql_set_charset('utf8');
$query = mysql_query("SELECT * FROM `Handler` WHERE `id`='$id'");
$handle = mysql_fetch_row($query);

if (!empty($handle)){
switch ($handle[2]){

case 'vid':
$query = mysql_query("SELECT * FROM `video` WHERE `vid`='$id' AND `part`=$part");
break;

case 'txt':
if (isset($part)){
throw new exception('did not expect part data for text');
break;
};
$query = mysql_query("SELECT * FROM `txt` WHERE `tid`='$id'");
break;

default:
throw new exception('type not specified');
}

}else{
throw new exception ('does not exist');
}

}else{
throw new exception ('invalid');
}

} catch (exception $invalid){}

include_once('../header.html');

if(!isset($invalid)){
echo 'video';
}else{
echo 'Error: '.$invalid->getMessage();
};

include_once('../footer.html');

?>