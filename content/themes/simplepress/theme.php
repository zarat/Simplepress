<?php

/**
 * Ein Beispieltheme
 *
 * @author Manuel Zarat 1
 */

class simplepress extends theme {

    function header() {
        echo "<div class='sp-main-wrapper'>\n";
        echo "<div class='sp-main-header'>\n";
            echo "<div class='sp-main-header-logo'>\n";
                echo "<h1>".$this->settings('site_title')."</h1>\n";
                echo "<h4>".$this->settings('site_subtitle')."</h4>\n";
            echo "</div>\n";
        echo "</div>";
    }
    
    function navigation() {
        $nav = new menu();
        $nav->config( array( 
            "id" => 1, 
            "ul" => "submenu", 
            "li" => "li" 
        ) );
        echo $nav->html( array( 
            "before" => "<div class=\"nav-container\"><label class=\"responsive_menu\" for=\"responsive_menu\"><span>Menu</span></label><input id=\"responsive_menu\" type=\"checkbox\">", 
            "after" => "</div>"
        ) );
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

/**
 * Beispielfilter
 * Zeige nur Items, die die Taxonomie "type" in Verbindung mit dem Term "page" haben.
 */
global $hooks;
function post_filter( $item ) { 
    $item = " and id IN (
        select object_id from term_relation tr
        inner join term_taxonomy tt on tt.id = tr.taxonomy_id
        inner join term on term.id = tr.term_id
        where tt.taxonomy='type'
        and term.name='page'
    )";  
    return $item; 
}
/**
 * und ersetze den Inhalt auf der Homepage damit.
 */
$hooks->add_filter( 'archive_init_homepage', 'post_filter' );

?>
