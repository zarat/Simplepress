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
        echo "<!--BeginNoIndex-->\n";
        parent::footer();
        echo "<!--EndNoIndex-->\n";
        echo "</div>";
    }
    
}

?>
