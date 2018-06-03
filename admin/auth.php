<?php

/**
 * @author Manuel Zarat
 */
require_once "../config.php";
require_once "../load.php";

$system = new system();

if( !$system->auth() ) {
	header("Location: ../login.php"); /* Redirect browser */  
	exit; 
}

?>
