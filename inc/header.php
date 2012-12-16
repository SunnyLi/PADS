<?php
/*========================
HEADER
========================*/

//Start session - required to be present on all pages that needs to use session (which is the whole site)
session_start();

//Checks if have session, Idea: Add session named login
//Maybe remove this check here altogether..
if (isset($_SESSION['uid'])&&isset($_SESSION['name'])){
	$uid = $_SESSION['uid'];
	$name = $_SESSION['name'];
}/*else{
	$uid = null;
	$name = null;
}*/

?>

<!DOCTYPE html> 
 
<!--Project Another Danmaku Site Front Page by Sunnyok (Alpha Edition)--> 

<html lang="en">

<head>
<meta charset="utf-8" /> 
<title><?php echo isset($html_title) ? $html_title.' | PADS' : 'PADS Pre-Alpha' ?></title>
<meta name="description" content="<?php echo isset($html_desc) ? $html_desc : 'A Danmaku Video Site for AMV, MAD, Vocaloid Music lovers.' ?>"/> 
<link rel="stylesheet" type="text/css" href="/style/style.css"/>

<!--<script type="text/javascript">
function showSearchBox(){
	document.getElementById('search-box').innerHTML = ': <input type="text" id="sf"/> <input type="button" id="sb" value="GO"/>';
}
function cls(id){
document.getElementById(id).innerHTML = '';
}
</script>-->

</head>

<body>
<div id="top-bar"><!--Image only--></div>
<div id="wrap">

<!-- Highlight problem <- What? -->
<div id="top-content">
<span id="name"><a href="/">PADS</a></span>
<span id="search" onmouseover="showSearchBox()"><a href="//<?php echo $_SERVER['HTTP_HOST']; ?>/search">Search</a></span>
<span id="search-box" onselect="showSearchBox()"></span>
<span id="user"><?php if (!empty($uid)){echo '<a href="/user/logout.php?s='.$uid.'">'.$name.'</a>';}else{echo '<a href = "/user/login.php">Sign in</a>';};?></span>
<?php if (!empty($uid)):?><span id="link"><a href="/acg/link.php">upload</a></span>
<span id="link"><a href="/acg/manage.php">manage</a></span><?php endif;?>
</div>

<div id="cwrap" onclick="cls('search-box')">

<div id="head">
<a href="/"><div id="logo" class="full"></div></a>

<div id="bar">
<span class="cat"><a href="/acg/?acg=">AMV</a></span>|
<span class="cat">MAD</span>|
<span class="cat">BGM</span>|
<span class="cat">Game</span>|
<span class="cat">Other</span>
</div>

</div>