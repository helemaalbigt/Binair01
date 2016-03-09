<?php

    include_once './inc/db.inc.php';
	include_once './inc/functions.inc.php';
	
	
	$subject = getPlaylistIFrame();

	$interm = preg_replace('/(<*[^>]*height=)"[^>]+"([^>]*>)/', '\1"90"\2', $subject);
	$result = preg_replace('/(<*[^>]*width=)"[^>]+"([^>]*>)/', '\1"100%"\2', $interm);
	
	echo $result;

?>