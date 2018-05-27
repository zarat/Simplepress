<?php

/*
 *
 * Asynchrones Speichern der Daten von dashboard.php
 *
 */

include("../load.php");
include("auth.php");

$system = new system();

$setting = $_GET['setting'];
$value = $_GET['value'];

if($setting == "site_theme") {
    $cfg=array("table"=>"settings","set"=>"value='$value' WHERE settings.key='$setting'");
    $system->update($cfg);
}

?>
