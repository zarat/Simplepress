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
$delete_item = $system->db->prepare( "delete from item where id=?" );    
$delete_item->bind_param( "i" , $id );
$delete_item->execute();
    
/**
 * Die Custom fields
 */
$delete_custom_fields = $system->db->prepare( "delete from item_meta where meta_item_id=?" );    
$delete_custom_fields->bind_param( "i" , $id );
$delete_custom_fields->execute();

/**
 * Die Relationen
 */
$delete_relations = $system->db->prepare( "delete from term_relation where object_id=?" );    
$delete_relations->bind_param( "i" , $id );
$delete_relations->execute();

?>
