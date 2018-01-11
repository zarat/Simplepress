<?php

/**
 * Simplepress Theme
 *
 * @author Manuel Zarat
 * 
 */

class simplepress extends theme {

    function nav() {
        $nav = new menu();
        $nav->config(array('id' => 1));
        $nav->html();
    }

    function render() {   
        $this->theme_functions();
        $this->html_header();
        echo "<div class='sp-main-wrapper'>\n";
        $this->header();
        $this->content();
        $this->sidebar();
        $this->footer();
        echo "</div>\n";
        $this->html_footer();    
    }
    
    function header() {
        echo "<div class='sp-main-header'>\n";
        echo "<div class='sp-main-header-logo'><h1>".$this->settings('site_title')."</h1><h4>".$this->settings('site_subtitle')."</h4></div>\n";
        echo "</div>\n";
        $this->nav();
    }
    
    function sidebar() {
            parent::sidebar();
            echo "<div style='clear:both;'></div>";
    }
    
    function footer() {
        echo "<div class='sp-footer' style='padding:10px;'>";
        parent::footer();
        echo " <a href='https://validator.w3.org/nu/?doc=https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]' target='_blank'>HTML5 Validator</a>";
        echo "</div>";
    }
}

?>
