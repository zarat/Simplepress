<?php

/**
 * Entfernt eine Taxonomie aus der Datenbank
 * Wird asynchron aufgerufen, deshalb load.php einbinden!
 *
 * @author Manuel Zarat
 */
require "../../load.php";

$system = new system();

if( !$system->auth() ) die();

if( !empty($_GET['taxonomy_id']) ) { 

    $taxonomy_id = $_GET['taxonomy_id'];
    
    /**
     * Die Taxonomie selbst entfernen
     */
    $system->query( "delete from term_taxonomy where id=$taxonomy_id" ) or die( 'error on :delete from term_taxonomy' );
    
    /**
     * Alle Relationen entfernen
     */
    $system->query( "delete from term_relation where taxonomy_id=$taxonomy_id" ) or die( 'error on :delete from term_relation' );
    
    /**
     *  Wenn alles geklappt hat, die ID fuer Callback Funktionen ausgeben falls eine definiert wurde
     */
    echo $taxonomy_id;
	
}

?>
