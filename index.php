<?php session_start();

//$_SESSION['uid'] = 1;
$_SESSION['name'] = 'Sunny';
//session_unset('name');
//session_unset('uid');

if (isset($_SESSION['uid'])&&isset($_SESSION['name'])){
$uid = $_SESSION['uid'];
$name = $_SESSION['name'];
}else{
$uid = null;
$name = null;
};

require_once('header.html');
?>

Stuff here!<br />
Stuff here!<br />
Stuff here!<br />
Stuff here!<br />
Stuff here!<br />
Stuff here!<br />
Stuff here!<br />
Stuff here!<br />
Stuff here!<br />
Stuff here!<br />
</div>

<div id="footer">

<table width="940" cellspacing="0" cellpadding="0" border="0" align="center">
<tbody>
<tr>
<td align="center">
Notice: NO video files are stored on this server!<br />
Error Reporting: namedoesntfi#hotmail.com (Change # to @ )<br />
Copyright
<a href="http://sunny.hifast.ca/">Sunnyok</a>
Some Rights Reserved
</td>
</tr>
</tbody>
</table>

</div>

</div>

<div id="top-bar">
<span id="title"><a href="http://localhost/PADS">PADS</a></span>
<span id="search"><a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PADS/search">Search</a>: <input type="text" id="sf"/> <input type="button" id="sb" value="GO"/></span>
<span id="user"><?php if (!empty($uid)){echo '<a href="http://localhost/PADS/?u='.$uid.'">'.$name.'</a>';}else{echo '<a href = "http://localhost/PADS/Login">Sign in</a>';};?></span>
</div>

</div>

</body>

</html>