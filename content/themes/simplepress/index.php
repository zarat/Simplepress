<?php

/**
 * @author Manuel Zarat
 */

echo "<!--BeginNoIndex-->\n";

while( $latest->have_posts() ) {

    $item = $latest->the_post( $strip_tags=true, $content_length=200 );
    
    echo "<div class='sp-content-item'>\n";
    echo "<div class='sp-content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title'] . "</a></div>\n";
    echo "<div class='sp-content-item-body'>" . $item['content'] . "</div>\n";
    echo "</div>\n";
    
}

$latest->pagination();

echo "<!--EndNoIndex-->\n";

?>
