<?php

/**
 * Die Datei wird asynchron aufgerufen deshalb ist $system noch nicht definiert!
 * Asynchron eingebundene Dateien muessen load.php einbinden und auch $system deklarieren!
 *
 * @author Manuel Zarat
 */
require_once "../../load.php";

$system = new system();

if( !$system->auth() ) header("Location: ../login.php");

$id = $_GET['id'];
$status = $_GET['status'];   
$newstatus = ($status==1) ? 0 : 1;
$response = ($status==1) ? "deaktivieren" : "aktivieren";
    
$system->query( "update item set status=$status WHERE id=$id" );

/**
 * Weil die Datei asynchron aufgerufen wird, wird hier ein Rueckgabewert ausgegeben.
 */
echo "<a style='cursor:pointer' onclick=\"update_status('$id','$newstatus')\">$response</a>";		

?>
