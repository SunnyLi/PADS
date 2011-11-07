<?php require_once('connect.php');

function create_cid($cid){
db_connect('danmaku');

$query = "CREATE TABLE  `danmaku`.`$cid` (
`id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`stime` FLOAT NOT NULL ,
`mode` TINYINT UNSIGNED NOT NULL ,
`size` TINYINT UNSIGNED NOT NULL ,
`color` MEDIUMINT UNSIGNED NOT NULL ,
`date` TIMESTAMP NOT NULL ,
`message` VARCHAR( 255 ) NOT NULL ,
`uid` MEDIUMINT UNSIGNED
) ENGINE = MYISAM DEFAULT CHARSET=utf8;";
}
?>