<?php

/**
 * @author Manuel Zarat
 */

class simplepress extends theme {

    function content() {
        echo "<div class='sp-content'>\n";
        parent::content();
        echo "</div>\n";
    }
    
    function sidebar() { 
        echo "<div class='sp-sidebar'>\n";
        parent::sidebar();
        $this->random_items();
        echo "</div>\n";
        echo "<div style=\"clear:both;\"></div>\n";
    }
    
    function footer() {
        parent::footer();
    }
    
    function random_items() {
        $posts = $this->archive( array( "select" => "*", "from" => "item", "where" => "status=1 AND type='post' order by rand() limit 5" ) );
        echo "<div class='sp-sidebar-item'>\n";
        echo "<div class='sp-sidebar-item-head'>Weiterlesen</div>\n";
        foreach( $posts as $post ) {
            echo "<div class='sp-sidebar-item-box'>\n";
            echo "<div class='sp-sidebar-item-box-head'><a href='../?type=post&id=$post[id]'>" . $post['title'] . "</a></div>\n";
            if( preg_match( "/^.{1,150}\b/s", $post['content'], $match ) ) {
                        $post['content'] = $match[0];
            }
            echo "<div class='sp-sidebar-item-box-body'>$post[content]</div>\n";
            echo "</div>\n";       
        }
        echo "</div>\n";
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
