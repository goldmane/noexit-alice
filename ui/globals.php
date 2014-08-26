<?php
	$Cookie_ShowId = 'NEAshowId';
	
	$isAdmin = strpos(strtolower($_SERVER['REQUEST_URI']), '') == true; 
	
	$fayeBase = (strpos(strtolower($_SERVER['SERVER_NAME']), '') == true) ? '' : '';
	$restBase = (strpos(strtolower($_SERVER['SERVER_NAME']), '') == true) ? '' : '';

?>