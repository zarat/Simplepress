<?php 

/*
 *
 * Wird asynchron aufgerufen, deshalb load.php einbinden.
 * 
 */

require_once '../../load.php';

$system = new system();

function recursiveDelete($id,$system) {

    // Alle unterpunkte finden
    $get_child_items = $system->select( array( "select" => "id", "from" => "menu", "where" => "parent=$id" ) );
    
    foreach($get_child_items as $k => $v) { 
        $system->delete( array( "from" => "menu", "where" => "id=$v[0]" ) );
        //echo $v[0] . "<br>";
        recursiveDelete($v[0], $system);
    }
    
    // Menupunkt selbst entfernen
    $system->delete( array( "from" => "menu", "where" => "id=$id" ) );
}

recursiveDelete($_POST['id'],$system);

?>
