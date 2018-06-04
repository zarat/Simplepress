<?php

/**
 * 
 * Bearbeitet den Status eines Items.
 * Wird asynchron aufgerufen, deshalb load.php einbinden!
 * 
 */

if(!isset($_GET['id']) || !isset($_GET['status'])) { die("error: missing parameter 'id' or 'status'"); }

include("../load.php");

$system = new system();

	  $id = $_GET['id'];
		$status = $_GET['status'];
    
    $newstatus = ($status==1) ? 0 : 1;
    $var = ($status==1) ? "deaktivieren" : "aktivieren";
    
    $config = array("table"=>"item","set"=>"status=$status WHERE id=$id");
    $system->update($config);
    
    /**
     * Neuer Link fuer JS Response !
     * 
     */
    echo "<a style='cursor:pointer' onclick=\"update_status('$id','$newstatus')\">$var</a>";		

?>
