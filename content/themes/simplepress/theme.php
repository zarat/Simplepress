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
        echo "<div class='sp-sidebar'>\n";
        parent::sidebar();        
        if( $this->request( 'type' ) == 'post' ) { $this->random_posts(); }
        echo "</div>\n";
        echo "<div style=\"clear:both;\"></div>";
    }
    
    function random_posts() {
        $posts = $this->archive( array( "select" => "*", "from" => "object", "where" => "status=1 AND type='post' ORDER BY RAND() LIMIT 3" ) );
        echo "<div class='sp-sidebar-item'>";
        echo "<div class='sp-sidebar-item-head'>Weiterlesen</div>";
        foreach( $posts as $post) {
            echo "<div class='sp-sidebar-item-box'>";
            echo "<div class='sp-sidebar-item-box-head'><a href='../?type=post&id=$post[id]'>" . $post['title'] . "</a></div>";
            echo "</div>";       
        }
        echo "</div>";
    }
    
    function footer() {
        echo "<div class='sp-footer' style='padding:10px;'>";
        parent::footer();
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
