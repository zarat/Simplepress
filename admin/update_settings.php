<?php

/**
 * Asynchrones Speichern der Daten von dashboard.php
 */

require_once "../load.php";
require_once "auth.php";

$system = new system();

$setting = $_GET['setting'];
$value = $_GET['value'];

$system->update( array( "table" => "settings", "set" => "value='$value' WHERE settings.key='$setting'" ) );

?>
