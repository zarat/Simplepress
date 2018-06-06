<?php

/**
 * @author Manuel Zarat
 */

class simplepress extends theme {

    function header() {
        echo "<div class=\"sp-main-wrapper\">\n";
        parent::header();
    }
    
    function content() {
        echo "<div class=\"sp-content\">";
        parent::content();
        echo "</div>";
    }
    
    function sidebar() {
        echo "<div class=\"sp-sidebar\">";
        parent::sidebar();
        echo "</div>\n";
        echo "<div style=\"clear:both;\"></div>\n";
    }
    
    function footer() {
        echo "\n<div class=\"sp-footer\" style=\"padding:10px;\">";
        parent::footer();
        echo "</div>\n";
    }
    
    function html_footer() {
        echo "</div>\n";
        parent::html_footer();
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
