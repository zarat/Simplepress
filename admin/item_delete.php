<?php

include "../load.php";

$system = new system();

//$db = new connection();

  $id = $_GET['id'];
	$ref = "index.php?page=item_list&type=" . $_GET['ref'];
  
  $cfg = array("from"=>"object","where"=>"id=$id");
	$system->delete($cfg);
  //$sql = "DELETE FROM object WHERE id = $id;";
	//$query = $db->query($sql); 
  
  echo "Inhalt wurde <b>aus der Datenbank entfernt</b>. Weiterleitung..";	

?>