<?php

/**
 * Image Upload fuer TinyMCE 
 * 
 * @author Manuel Zarat
 */
require "../load.php";

$system = new system();

if( !$system->auth() ) header("Location: ../login.php");

if( !empty( $_FILES['file'] ) ) {

    $path = "../content/uploads/";
    $path = $path . basename( $_FILES['file']['name']);
    
    if(move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
        $ret = $path;
    } else{
        $ret = "Fehler beim Upload!";
    }
}
  
echo json_encode( array( 'location' => $ret ) );
  
?>