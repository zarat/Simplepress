<?php

/**
 * @author Manuel Zarat
 */ 

while( $item->have_items() ) {

    $item1 = $item->the_item( );
    
    echo "<div class='sp-content-item'>\n";
    echo "<div class='sp-content-item-head'>" . $item1['title'] . "</div>\n";
    echo "<div class='sp-content-item-head-secondary'>" . $item1['date'] . "</div>\n";
    echo "<div class='sp-content-item-body'>" . $item1['content'] . "</div>\n";
    echo "</div>\n";
    
}

?>
