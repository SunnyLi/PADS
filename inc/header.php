<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
 
<!--Project Another Danmaku Site Front Page by Sunnyok (Alpha Ed.)--> 

<html xmlns = "http://www.w3.org/1999/xhtml" lang = "en-US" xml:lang = "en-US">

<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/> 
<title><?php echo isset($html_title) ? $html_title.'|PADS' : 'PADS Pre-Alpha' ?></title>
<meta name="description" content="<?php echo isset($html_desc) ? $html_desc : 'A Danmaku Video Site for AMV, MAD, Vocaloid Music lovers.' ?>"/> 
<link rel="stylesheet" type="text/css" href="../../PADS/style/sunnyok.css"/> 

<script type="text/javascript">
function showSearchBox(){
	document.getElementById('search-box').innerHTML = ': <input type="text" id="sf"/> <input type="button" id="sb" value="GO"/>';
}
function cls(id){
document.getElementById(id).innerHTML = '';
}
</script>

</head>

<body>
<div id="top-bar"></div>
<div id="wrap">

<!-- Highlight problem-->
<div id="top-content">
<span id="name"><a href="http://localhost/PADS">PADS</a></span>
<span id="search" onmouseover="showSearchBox()"><a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PADS/search">Search</a></span>
<span id="search-box" onselect="showSearchBox()"></span>
<span id="user"><?php if (!empty($uid)){echo '<a href="http://localhost/PADS/?u='.$uid.'">'.@$name.'</a>';}else{echo '<a href = "http://localhost/PADS/Login">Sign in</a>';};?></span>
</div>

<div id="cwrap" onclick="cls('search-box')">

<div id="head">
<a href="http://localhost/pads"><img id="logo" class="full"/></a>

<div id="bar">
<span class="cat"><a href="http://localhost/pads/acg/?acg=">AMV</a></span>|
<span class="cat">MAD</span>|
<span class="cat">Vocaloid</span>|
<span class="cat">Game</span>|
<span class="cat">Other</span>
</div>

</div>