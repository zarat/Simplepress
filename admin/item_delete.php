<?php

include "../load.php";

$system = new system();
  
$system->delete(array("from"=>"object","where"=>"id=$_GET[id]"));

?>
