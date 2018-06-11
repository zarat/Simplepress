<?php

/**
 * @author Manuel Zarat
 */ 

while( $item->have_items() ) {

    $item1 = $item->the_item( $strip_tags=true, $content_length=200 );
    
    echo "<div class='sp-content-item'>\n";
    echo "<div class='sp-content-item-head'><a href='../?type=$item1[type]&id=$item1[id]'>" . $item1['title'] . "</a></div>\n";
    echo "<div class='sp-content-item-body'>" . $item1['content'] . "</div>\n";
    echo "</div>\n";
    
}

?>
