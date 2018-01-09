<?php

function timestamp2datime($timestamp){
	$datetime = NULL;
	$year = substr($timestamp, 0, 4);
	$month = substr($timestamp, 4, 2);
	$day = substr($timestamp, 6, 2);
	$hour = substr($timestamp, 8, 2);
	$min = substr($timestamp, 10, 2);
	$datetime .= "$month/$day/$year {$hour}:$min";

	return $datetime;
}

function timestamp2date($timestamp){
	$date = NULL;
	$year = substr($timestamp, 0, 4);
	$month = substr($timestamp, 4, 2);
	$day = substr($timestamp, 6, 2);		
	$date .= "$month/$day/$year";
	return $date;
}

function valid($string) {
	$string = str_replace("&","&#38;",$string);
	$string = str_replace("?","&#63;",$string);
	$string = str_replace("'","&#039;",$string);
	return $string;	
}

?>
