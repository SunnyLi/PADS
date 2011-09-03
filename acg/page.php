<?php
$query = mysql_query("SELECT `file` FROM `page` WHERE `page`=$id");
@$file = mysql_result($query, 0);

if (!empty($file)){
//query string don't work unless specify full url
//include_once('http://localhost/pads/pages/'.$file);

if (@include_once('../pages/'.$file)){
exit;
}else{
$error[] = '404';
}

}else{
$error[] = '???';
}
?>