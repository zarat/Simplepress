<?php

/**
 * Simplepress Logout Script
 * 
 * @author Manuel Zarat
 */
 
include "load.php";

$system = new system();
 
$system->logout(); 

/**
 * Funktioniert nicht, wenn irgendwo davor etwas ausgegeben wird! Auch blank lines
 */
header("Location: ../login.php");

?>
