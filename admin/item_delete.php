<?php

/**
 * 
 * Entfernt ein Item aus der Datenbank.
 * Die Datei load.php ist eingebunden, weil die Datei asynchron aufgerufen wird.
 * 
 */

include "../load.php";

$system = new system();
  
$system->delete(array("from"=>"object","where"=>"id=$_GET[id]"));

?>
