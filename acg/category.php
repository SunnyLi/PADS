<?php
//echo cat('MAD')?'true':'false' ;
function cat($cat){
$cats = array(
'Douga' => array('AMV', 'MAD', 'MMD'),
'Music' => array('Vocaloid', 'Animated_MV', '3DMV'),
'Games' => array('Touhou', 'PlayStation'),
'Other' => array()
);
foreach ($cats as $cats => $subcat)
	foreach ($subcat as $subcat)
		if ($cat == $subcat)
		return $cats.'+'.$subcat;

//print_r($cats);
return false;
}
?>