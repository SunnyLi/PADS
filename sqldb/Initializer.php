<?php
/*======================
ACG Initializer
========================
Set-up database for the site to function.

Note: Only one SQL Statement is allowed at a time.

#SUNNY'S DATABASE AUTO-GENERATE SCRIPT
#Last updated on Nov 3, 2012
*/

//Connection Script
require_once('connect.php');

//Get admin privellage
$sql = get_stream('main');

//Notice..
echo '<br /><br />ACG Auto-Generation Script<hr />';


//===============================================

if ($sql->query('
CREATE DATABASE  `ppl`
')){
	echo '+DATABASE CREATED: User<br />';
}else{
	echo $sql->error;
	echo '<br />';
}

if ($sql->query(
'#USER
CREATE TABLE `ppl`.`user` (
`uid` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
`user` VARCHAR(32) NOT NULL,
`pass` VARCHAR(32) NOT NULL,
`salt` VARCHAR(16) NOT NULL,
`mail` VARCHAR(64) NOT NULL,
`name` VARCHAR(64),
`bday` DATE,
`bio` TEXT,
`share` VARCHAR(128),
`perm` TINYINT UNSIGNED NOT NULL DEFAULT 0,
`reg_key` VARCHAR(16),
`last` DATE
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

#SECURITY WARNING: split access data from login info..
')){
	echo '&nbsp;&nbsp;-Table created: User<br />';
}else{
	echo $sql->error;
	echo '<br />';
}


//===============================================

if ($sql->query('
CREATE DATABASE  `data`
')){
	echo '+DATABASE CREATED: Content<br />';
}else{
	echo $sql->error;
	echo '<br />';
}

if ($sql->query('
#MEDIA HANDLER
CREATE TABLE  `data`.`handler` (
`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
`uid` INT UNSIGNED NOT NULL,
`title` VARCHAR(64) NOT NULL,
`desc` VARCHAR(128),
`type` VARCHAR(4) NOT NULL,
`cat` VARCHAR(16) NOT NULL,
`tag` VARCHAR(255),
`point` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
`perm` TINYINT UNSIGNED NOT NULL DEFAULT 0,
`date` TIMESTAMP NOT NULL,
`thumb` VARCHAR(255),
`log` VARCHAR(128)
) ENGINE = MYISAM DEFAULT CHARSET=utf8;
')){
	echo '&nbsp;&nbsp;-Table created: AI<br />';
}else{
	echo $sql->error;
	echo '<br />';
}

if ($sql->query('
#VIDEO
CREATE TABLE  `data`.`video` (
`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`vid` BIGINT UNSIGNED NOT NULL ,
`part` TINYINT UNSIGNED DEFAULT 1 ,
`title` VARCHAR(128) ,
`desc` TEXT ,
`src` VARCHAR(16) NOT NULL ,
`url` VARCHAR(255) NOT NULL ,
`date` TIMESTAMP NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;
')){
	echo '&nbsp;&nbsp;-Table created: VID<br />';
}else{
	echo $sql->error;
	echo '<br />';
}

if ($sql->query('
#TEXTS
CREATE TABLE  `data`.`text` (
`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`txt` BIGINT UNSIGNED NOT NULL ,
`part` TINYINT UNSIGNED DEFAULT 1 ,
`title` VARCHAR(128) ,
`text` TEXT NOT NULL ,
`date` TIMESTAMP NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;
')){
	echo '&nbsp;&nbsp;-Table created: TXT<br />';
}else{
	echo $sql->error;
	echo '<br />';
}

if ($sql->query('
#CODE
CREATE TABLE `data`.`code` (
`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`code` BIGINT UNSIGNED NOT NULL ,
`part` TINYINT UNSIGNED DEFAULT 1 ,
`title` VARCHAR(128) ,
`desc` VARCHAR(255) ,
`src` TEXT NOT NULL ,
`date` TIMESTAMP NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;
')){
	echo '&nbsp;&nbsp;-Table created CODE<br />';
}else{
	echo $sql->error;
	echo '<br />';
}

if ($sql->query('
#SCRIPT PAGE
CREATE TABLE `data`.`page` (
`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`page` BIGINT UNSIGNED NOT NULL ,
`file` VARCHAR(64) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;
')){
	echo '&nbsp;&nbsp;-Table created: PG<br />';
}else{
	echo $sql->error;
	echo '<br />';
}

if ($sql->query('
#COMMENTS
CREATE TABLE  `data`.`comments` (
`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`cid` BIGINT UNSIGNED NOT NULL ,
`uid` INT UNSIGNED NOT NULL ,
`time` TIMESTAMP NOT NULL ,
`comment` VARCHAR(255) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

#cid = id of source
')){
	echo '&nbsp;&nbsp;-Table created: CMT<br />';
}else{
	echo $sql->error;
	echo '<br />';
}


//===============================================

if ($sql->query('
CREATE DATABASE  `danmaku`
')){
	echo '+DATABASE CREATED: Danmaku<br />';
}else{
	echo $sql->error;
	echo '<br />';
}

if ($sql->query('
#DANMAKKU
CREATE TABLE  `danmaku`.`cmt#` (
`id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`stime` FLOAT UNSIGNED NOT NULL ,
`mode` TINYINT UNSIGNED NOT NULL ,
`size` TINYINT UNSIGNED NOT NULL , #OR maybe char(3)
`color` MEDIUMINT UNSIGNED NOT NULL ,
`date` TIMESTAMP NOT NULL ,
`message` VARCHAR(1023) NOT NULL , #255?
`uid` INT UNSIGNED DEFAULT 0
) ENGINE = MYISAM DEFAULT CHARSET=utf8;'
)){
	echo '&nbsp;&nbsp;-Table Created: DMK<br />';
}

echo '===== Configuration Complete! =====<br /><br />';
$sql->close();
?>