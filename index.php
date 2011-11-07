<?php require_once('sqldb/connect.php');

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

@db_connect('data', 'main');
$contents = @mysql_query("SELECT * FROM `video` WHERE `part`='1' ORDER BY `date` DESC LIMIT 10");

require_once('inc/header.php');

while($content = mysql_fetch_row($contents)){
echo '<div class="content" style="background-color: rgb('.rand(150,256).','.rand(150,256).','.rand(150,256).');">
<span class="ctitle">'.$content[3].'</span><br />
'//<span class="info">'. date('Y-m-d H:m:s' ,$content[7]) .'@'.$content[6].'</span><br />
.'<span class="info">'. date($content[7]) .'@'.$content[6].'</span><br />
<span class="desc">'.$content[4].'</span>
</div>
';
}

include_once('inc/footer.php');
?>