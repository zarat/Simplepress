<?php 

/*
 * @author Manuel Zarat
 */
require_once '../../load.php';

$system = new system();

if( !$system->auth() ) header("Location: ../login.php");

function recursiveDelete( $id, $system ) {

    // Finden aller Unterpunkte..
    //$child_items = $system->select( array( "select" => "id", "from" => "menu", "where" => "parent=$id" ) );    
    $child_items = $system->fetch_all_assoc( $system->query( "select id from menu where parent=$id" ) );
    
    // Finden des Oberpunktes..
    //$get_parent_item = $system->select( array( "select" => "parent", "from" => "menu", "where" => "id=$id" ) );
    //$parent_item = $get_parent_item[0];
    $get_parent_item = $system->fetch_all_assoc( $system->query( "select parent from menu where id=$id" ) );
    $parent_item = $get_parent_item[0];
    
    // Jeden hierarchisch darunter liegenden REKURSIV!!! entfernen
    foreach( $child_items as $item ) { 

        $temp_item_id = $item['id'];
                
        //$system->delete( array( "from" => "menu", "where" => "id=$id" ) );
        $system->query( "update menu set parent=$parent_item[parent] where id=$temp_item_id" );
        recursiveDelete( $id, $system );
        
    }
    
    // Und den Menupunkt selbst entfernen
    $system->query( "delete from menu where id=$id" );
    
}

recursiveDelete( $_POST['id'], $system );

?>
