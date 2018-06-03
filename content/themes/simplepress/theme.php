<?php

/**
 * @author Manuel Zarat
 */

class simplepress extends theme {
    
    function sidebar() {
        echo "<div class='sp-sidebar'>\n";
        parent::sidebar();        
        if( $this->request( 'type' ) == 'post' ) { $this->random_posts(); }
        echo "</div>\n";
        echo "<div style=\"clear:both;\"></div>";
    }
    
    function random_posts() {
        $posts = $this->archive( array( "select" => "*", "from" => "item", "where" => "status=1 AND type='post' ORDER BY RAND() LIMIT 3" ) );
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
    
}

/**
 * Eine Funktion definieren..
 */
function start_session() { 
    session_start();  
}
/**
 * Und in das Theme einhaken.
 */
$this->add_action('init','start_session');

?>
