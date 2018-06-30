<?php

/**
 * Liste fuer TinyMCE Image Liste
 * 
 * @author Manuel Zarat
 */
require "../load.php";

$system = new system();

if( !$system->auth() ) header("Location: ../login.php");

$filelist = false;
         
if( $files = opendir( '../content/uploads') ) { 

    $i = 0;            
    while ( false !== ( $file = readdir( $files ) ) ) {             
        if ( $file != '.' && $file != '..' ) {                
            $filelist[] = array( 'title' => $file, 'value' => '../content/uploads/' . basename($file)  ); 
            $i++;                       
        }                             
    }   
                               
    closedir($files);                           
} 

echo json_encode( $filelist );                                    

?>