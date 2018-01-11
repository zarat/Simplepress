<?php

include "../load.php";

$system = new system();

  $id = $_GET['id'];
	$ref = "index.php?page=item_list&type=" . $_GET['ref'];
  
  $cfg = array("from"=>"object","where"=>"id=$id");
	$system->delete($cfg);
  
  echo "Inhalt wurde <b>aus der Datenbank entfernt</b>. Weiterleitung..";	

?>