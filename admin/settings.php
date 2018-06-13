<?php

/**
 * Asynchrones Speichern der Daten von dashboard.php
 */

require_once "../load.php";

$system = new system();

if( !$system->auth() ) { header("Location: ../login.php"); }

$setting = $_GET['setting'];
$value = $_GET['value'];

$system->update( array( "table" => "settings", "set" => "value='$value' WHERE settings.key='$setting'" ) );

?>
