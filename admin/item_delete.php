<?php

/**
 * 
 * Entfernt ein Item aus der Datenbank.
 * Wird asynchron aufgerufen, deshalb load.php eingebunden.
 * 
 */

include "../load.php";

$system = new system();
  
$system->delete(array("from"=>"item","where"=>"id=$_GET[id]"));

?>
