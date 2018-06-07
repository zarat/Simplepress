<?php

/**
 * @author Manuel Zarat
 */

require_once "../load.php";
require_once "auth.php";

function get_all_fields( $item_id ) {
    $system = new system();
    $all_fields = $system->single_meta( $item_id, $index = true );
    if($all_fields) {
        foreach( $all_fields as $k => $v) {
            echo "<form>";
            echo "<p>" . $v[0] . ": " . $v[1] . "</p>";
            echo "<a style=\"cursor:pointer;\" onclick=\"deletecustomfield('" . $k . "',$item_id)\">entfernen</a>";
            echo "</form";
        }
    }
}

function add_field( $item_id, $field_key, $field_value ) {
    $system = new system();
    $config['insert'] = "item_meta (`meta_item_id`, `meta_key`, `meta_value`)";
    $config['values'] = " ($item_id, '$field_key', '$field_value')";
    $system->insert( $config );
}

function delete_field( $key, $item_id ) {
    $system = new system();
    $system->delete( array( 'from' => 'item_meta', 'where' => 'meta_id=' . $key ) );
    echo $item_id;
} 
    
if( isset( $_POST['item_id'] ) ) {

    $item_id = @$_POST['item_id'];
    $customfield_key = @$_POST['field_key'];
    $customfield_value = @$_POST['field_value'];
    $system = new system();
    $config = array( "insert" => "item_meta (`meta_item_id`, `meta_key`, `meta_value`)", "values" => "($item_id, '$customfield_key', '$customfield_value')");
    $last_id = $system->insert($config);
    echo $item_id;

} elseif( isset($_GET['action']) ) { 

    $action = $_GET['action'];    
    switch ( $action ) {   
        case "get":
            $item_id = @$_GET['item_id'];
            get_all_fields( $item_id );
            break;            
        case"add":
            $item_id = @$_GET['item_id'];
            $field_key = @$_GET['field_key'];
            $field_value = @$_GET['field_value'];
            add_field( $item_id, $field_key, $field_value );
            break;            
        case "delete":
            $key = @$_GET['key'];
            $item_id = @$_GET['item_id'];
            delete_field( $key, $item_id );
            break;            
        default:
            break;    
    }

} 

?>
