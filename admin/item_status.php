<?php

include("../load.php");

$system = new system();

	if(!isset($_GET['id']) || !isset($_GET['status'])) {
		echo "error";
		exit;
	}


	  $id = $_GET['id'];
		$status = $_GET['status'];
    
    if($status == '0') {
        $var = "<b>deaktiviert</b>";
    } else {
        $var = "<b>aktiviert</b>";
    }
    
		//$update = "UPDATE object SET status=$status WHERE id=$id"; 
    
    ($status==1)?$newstatus=0:$newstatus=1;
    ($status==1)?$v="deaktivieren":$v="aktivieren";
    
		//$db->query($update); 
    
    $config = array("table"=>"object","set"=>"status=$status WHERE id=$id");
    $system->update_object($config);
    
    echo "<a style='cursor:pointer' onclick=\"update_status('$id','$newstatus')\">$v</a>";		

?>