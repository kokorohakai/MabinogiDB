<?php
function sanitize($str){
	$str = addslashes($str);
	return $str;
}

function desanitize($str){
	$str = stripslashes($str);
	return $str;
}

function cleanFilename($str){
	$str = str_replace("/", "",$str);
	return $str;
}