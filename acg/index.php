<?php
//check query string var entered
if (!isset($_GET['acg'])) die ('404');
$acg = $_GET['acg'];

//check query string is a number
if (is_numeric($acg)){
//explode the numeric string (if no decimal no second value will return)
$data_array = explode('.', $acg);
//set data id
$id = (int)$data_array[0];
//set part
isset($data_array[1]) ? $part=(int)$data_array[1] : $part=1;

//query
require_once('../sqldb/connect.php');
db_connect('data');
//mysql_set_charset('utf8');
$query = mysql_query("SELECT * FROM `Handler` WHERE `id`='$id'");
$handle = mysql_fetch_row($query);

if (!empty($handle)){
$type = $handle[3];

switch ($type){ //detect type

case 'vid':
$query = mysql_query("SELECT * FROM `video` WHERE `vid`='$id' AND `part`=$part");
$type = 'video';
$desc = null;
break;

case 'txt':
$query = mysql_query("SELECT * FROM `txt` WHERE `tid`='$id' AND `part`=$part");
$type = 'text';
break;

/* //works but pointless...
case 'redir':
exit(header('Location: '.$addr));
break;
*/

case 'code':
$query = mysql_query("SELECT `file` FROM `code` WHERE `id`=$id");
$file = '../';
$file .= 'index.php';
//$file .= mysql_result($query, 0);
@require_once($file);
exit;

default:
//invalid data type... db error
$error[] = '1';
}

}else{
//id does not exist in database
$error[] = '2';
}

//$uid = $handle[1];
$title = $handle[2];
$category = $handle[4];

}else{
//invalid data entered...
$error[] = '3';
}


include_once('../header.php');

if(!isset($error)){

switch ($type){
case 'video':
echo 'category';
echo '<h2>'.$title.'</h2>';
?>
<?php
break;

case 'text':
?>
132
<?php
break;

default:
//echo $type;
}

}else{
echo 'Error code #'.$error[0];
};

include_once('../footer.php');
?>