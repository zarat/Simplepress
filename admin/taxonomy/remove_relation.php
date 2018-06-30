<?php

/**
 * @author Manuel Zarat
 */
if( !$system->auth() ) header("Location: ../login.php");

$tax = $_GET['taxonomy'];
$itemid = $_GET['item_id'];
$term = $_GET['term'];

/**
 * Taxonomie aus db entfernen
 */
$id = $system->query( "delete from term_relation where object_id=$itemid and taxonomy_id=$tax and term_id=$term" );

?>
