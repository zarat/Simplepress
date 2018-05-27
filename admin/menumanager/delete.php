<?php 

/*
 *
 * Wird asynchron aufgerufen, deshalb load.php einbinden.
 * 
 */

require_once '../../load.php';

$system = new system();

function recursiveDelete($id,$system) {

    // Finden aller Unterpunkte..
    $get_child_items = $system->select( array( "select" => "id", "from" => "menu", "where" => "parent=$id" ) );
    
    // Jeden hierarchisch darunter liegenden REKURSIV!!! entfernen
    foreach($get_child_items as $k => $v) { 
        $system->delete( array( "from" => "menu", "where" => "id=$v[0]" ) );
        recursiveDelete($v[0], $system);
    }
    
    // Und den Menupunkt selbst entfernen
    $system->delete( array( "from" => "menu", "where" => "id=$id" ) );
}

recursiveDelete($_POST['id'],$system);

?>
