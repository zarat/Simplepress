<?php
 
include "load.php";

$system = new system();
 
$system->logout(); 

header("Location: ../login.php");

?>
