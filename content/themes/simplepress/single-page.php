<?php

/**
 * @author Manuel Zarat
 */ 

while( $archive->have_items() ) {

    $item = $archive->the_item( );
    
    echo "<div class ='sp-content-item'>\n";
        echo "<div class ='sp-content-item-head'>" . $item['title'] . "</div>\n";
        echo "<div class ='sp-content-item-body'>" . $item['content'] . "</div>\n";
    echo "</div>\n";
    
}

?>
