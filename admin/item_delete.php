<?php

include "../load.php";

$system = new system();
  
$cfg = array("from"=>"object","where"=>"id=$id");
$system->delete($cfg);

?>