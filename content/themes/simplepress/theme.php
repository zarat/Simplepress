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
    
    function before_header() {
        echo "<div class='sp-main-wrapper'>\n";
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
        echo "</div>";
    }
    
    function before_content() {
        if(!$this->request('type')) {
            //echo "<iframe width=\"100%\" height=\"315\" src=\"https://www.youtube.com/embed/VWIQmgApIZI\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>";
        }
    }
    
}

?>
