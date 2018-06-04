<?php 

/*
 * @author Manuel Zarat
 */

require_once '../../load.php';
require_once "auth.php";

$system = new system();

function recursiveDelete( $id, $system ) {

    // Finden aller Unterpunkte..
    $get_child_items = $system->select( array( "select" => "id", "from" => "menu", "where" => "parent=$id" ) );
    
    // Jeden hierarchisch darunter liegenden REKURSIV!!! entfernen
    foreach( $get_child_items as $item => $values ) { 
        $system->delete( array( "from" => "menu", "where" => "id=$values[0]" ) );
        recursiveDelete( $values[0], $system );
    }
    
    // Und den Menupunkt selbst entfernen
    $system->delete( array( "from" => "menu", "where" => "id=$id" ) );
}

recursiveDelete( $_POST['id'], $system );

?>
