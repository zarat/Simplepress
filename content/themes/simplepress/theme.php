<?php

/**
 * @author Manuel Zarat
 */

class simplepress extends theme {

    function content() {
        $content = "<div class=\"sp-content\">";
        $content .= parent::content();
        $content .= "</div>";
        return $content;
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

function custom_function( $customcontent = false ) {
    $content = "ordinary content";
    if( $customcontent ) { $content = $customcontent . $content; }
    echo $content;    
}

$this->add_action('init', 'custom_function', 'extra' );

?>
