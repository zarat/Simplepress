<?php

/**
 * @author Manuel Zarat
 */

require "../../load.php";

$system = new system();

if( !$system->auth() ) die();

$id = $_GET['id'];

/**
 * Das Item
 */
$system->delete( array( "from" => "item", "where" => "id=$id" ) );

/**
 * Die Custom fields
 */
$system->delete( array( "from" => "item_meta", "where" => "meta_item_id=$id" ) );

/**
 * Die Relationen
 */
$system->delete(array("from"=>"term_relation","where"=>"object_id=$id"));

?>
