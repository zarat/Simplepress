<?php

/**
 * Ein Beispieltheme
 *
 * @author Manuel Zarat
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

/*
 * Beispiel von Hooks
 */
global $hooks;

function example_action() { 
    echo "example_action"; 
}
$hooks->add_action('archive_init','example_action');

function example_filter( $item ) { 
    $item['title'] = "xxx";  
    return $item; 
}
$hooks->add_filter( 'get_current_item', 'example_filter' );

?>
