<?php

/**
 * @author Manuel Zarat
 */

echo "<!--BeginNoIndex-->\n";

while( $archive->have_items() ) {

    $item = $archive->the_item( array( "content_length" => 240 ) );
    
    echo "<div class='sp-content-item'>\n";
    echo "<div class='sp-content-item-head'><a href='../?id=$item[id]'>" . $item['title'] . "</a></div>\n";
    echo "<div class='sp-content-item-body'>" . $item['content'] . "</div>\n";
    echo "</div>\n";
    
}

$archive->pagination();

echo "<!--EndNoIndex-->\n";

?>
