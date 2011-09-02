<?php
//check query string var entered
if (!isset($_GET['acg'])) die ('404');
$acg = $_GET['acg'];

if (is_numeric($acg)){ //this statement enclose all inner function
$data_array = explode('.', $acg);
$id = (int)$data_array[0];
isset($data_array[1]) ? $part=(int)$data_array[1] : $part=1;

require_once('../sqldb/connect.php');
db_connect('data');
//mysql_set_charset('utf8');
$query = mysql_query("SELECT * FROM `Handler` WHERE `id`='$id'");
$handle = mysql_fetch_row($query);

if (!empty($handle)){
$permission  = $handle[8];

switch ($permission){
case 2:
$error[] = 'high permission';
break;

case 1:
$type = $handle[4];

switch ($type){

case 'vid':
$query = mysql_query("SELECT * FROM `video` WHERE `vid`='$id' AND `part`=$part");
break;

case 'text':
$query = mysql_query("SELECT * FROM `txt` WHERE `tid`='$id' AND `part`=$part");
break;

case 'code':
break;


case 'page':
//this part won't be used as often so include codes below... check if its included else give error...
$query = mysql_query("SELECT `file` FROM `code` WHERE `id`=$id");
@$file = mysql_result($query, 0);
if (!empty($file)){

//query string don't work unless specify full url
//include_once('http://localhost/pads/pages/'.$file);
if (@include_once('../pages/'.$file)){
exit;
}else{
//invalid URL
$error[] = 5;
}

}else{
//no file specified
$error[] = 4;
}
break;


default:
//invalid data type... db error
$error[] = 1;
}

//end permission 1 regular
break;

case 0:
$error[] = 'pending request';
break;

default:
//unknown permission
$error[] = 6;
}

}else{
//id does not exist in database
$error[] = 2;
}

$title = $handle[2];
if ($permission == 1){ //permission OK
//$uploader_id = $handle[1];
isset($handle[3]) ? $desc=$handle[3] : $desc=null ;
$category = $handle[5]; //category function
$upload_date = $handle[9];
}

}else{
//invalid data entered...
$error[] = 3;
}


include_once('../inc/header.php');

if(!isset($error)){

echo $category;
echo '<h2>'.$title.'</h2>';
echo $upload_date.'<br /><br />';

switch ($type){
case 'vid':
?>
<?php
break;

case 'text':
?>
<?php
break;

default:
//echo $type;
}

?>
LOVE: FB G Y!<br />
Description:
<?php
echo $desc;

}else{
echo 'Error code #'.$error[0];
};

include_once('../inc/footer.php');
?>