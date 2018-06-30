<?php

/**
 * Entfernt einen Term aus der Datenbank
 * Wird asynchron aufgerufen, deshalb load.php einbinden!
 *
 * @author Manuel Zarat
 */
require "../../load.php";

$system = new system();

if( !$system->auth() ) die();

if( !empty($_GET['term_id']) ) { 

    $term_id = $_GET['term_id'];
    
    /**
     * Den Term selbst entfernen
     */
    $system->query( "delete from term where id=$term_id" ) or die( 'error on :delete from term' );
    
    /**
     * Alle Relationen entfernen
     */
    $system->query( "delete from term_relation where term_id=$term_id" ) or die( 'error on :delete from term_relation' );
    
    /**
     *  Wenn alles geklappt hat, die ID fuer Callback Funktionen ausgeben falls eine definiert wurde
     */
    echo $term_id;
	
}

?>
