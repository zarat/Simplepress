<?php

/**
 * @author Manuel Zarat
 */
if( !$system->auth() ) header("Location: ../login.php"); 
 
$tax = $_GET['taxonomy'];
$itemid = $_GET['item_id'];
$term = $_GET['term'];

/**
 * Neue Taxonomie speichern
 */
$id = $system->query( "insert into term_relation (object_id,taxonomy_id,term_id) values ($itemid, $tax, $term)" );

?>
