<?php

/**
 * Die Datei wird asynchron aufgerufen deshalb ist $system noch nicht definiert!
 * Asynchron eingebundene Dateien muessen load.php einbinden und auch $system deklarieren!
 * 
 * @author Manuel Zarat
 */
require "../../load.php";

$system = new system();

if( !$system->auth() ) header("Location: ../login.php");

$id = $_GET['id'];

/**
 * Das Item
 */
$system->query( "delete from item where id=$id" );

/**
 * Die Custom fields
 */
$system->query( "delete from item_meta where meta_item_id=$id" );

/**
 * Die Relationen
 */
$system->query( "delete from term_relation where object_id=$id" );

?>
