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

/*
 * Custom functions
 *
 * Man kann innerhalb eines Themes eigene "Custon functions" definieren, die sich an verschiedenen Punkten waehrend dem Seitenaufbau
 * ausfuehren lassen.
 *
 * function custom_function( $customcontent = false ) {
 *    $content = "ordinary content";
 *    if( $customcontent ) { $content = $customcontent; }
 *    echo $content;    
 * }
 * 
 * Aufruf mit
 * $this->add_action('init', 'custom_function', 'custom content' );
 * 
 * oder ohne Parametern
 * $this->add_action('init', 'custom_function', 'custom content' );
 */
?>
