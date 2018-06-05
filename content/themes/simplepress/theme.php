<?php

/**
 * @author Manuel Zarat
 */

class simplepress extends theme {

    function content() {
        echo "<div class=\"sp-content\">";
        parent::content();
        echo "</div>";
    }
    
    function sidebar() {
        echo "<div class=\"sp-sidebar\">";
        parent::sidebar();
        echo "</div>";
        echo "<div style=\"clear:both;\"></div>";
    }
    
    function footer() {
        echo "<div class=\"sp-footer\" style=\"padding:10px;\">";
        parent::footer();
        echo "</div>";
    }
    
}

/*
 * Beispiel einer Custom function
 */
function custom_function( $customcontent = false ) {
    $content = "ordinary content";
    if( $customcontent ) { $content = $customcontent; }
    echo $content;    
}
//$this->add_action('init', 'custom_function', 'custom content' );

?>
