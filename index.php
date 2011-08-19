<?php require_once('connect.php');

session_start();

//$_SESSION['uid'] = 1;
//$_SESSION['name'] = 'Sunny';
//session_unset('name');
//session_unset('uid');

if (isset($_SESSION['uid'])&&isset($_SESSION['name'])){
$uid = $_SESSION['uid'];
$name = $_SESSION['name'];
}else{
$uid = null;
$name = null;
};

db_connect('videos');
$contents = @mysql_query("SELECT * FROM `vid` ORDER BY `upload time` DESC LIMIT 10");

require_once('header.html');

while($content = mysql_fetch_row($contents)){
echo '<div class="content" style="background-color: rgb('.rand(150,256).','.rand(150,256).','.rand(150,256).');">
<span class="ctitle">'.$content[1].'</span><br />
<span class="info">'. date('Y-m-d H:m:s' ,$content[4]) .'@'.$content[3].'</span><br />
<span class="desc">'.$content[2].'</span>
</div>
';
}

include_once('footer.html');
?>