<?php
function shorten($string, $length){
	if(strlen($string) > $length){
		$string = wordwrap($string, $length);
		$string = substr($string, 0, strpos($string, "\n"));
	}
	return $string;
}
?>