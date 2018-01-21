<?php

/**
 * @author Manuel Zarat
 * 
 */

echo "<div class='sp-content'>\n\n";

while( $latest->have_posts() ) {

    $item = $latest->the_post();
    
    echo "<div class='sp-content-item'>\n";
    echo "<div class='sp-content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title'] . "</a></div>\n";
    echo "<div class='sp-content-item-body'>" . substr(strip_tags(html_entity_decode($item['content'])),0,150) . "</div>\n";
    echo "</div>\n";
    
}

$latest->pagination();

echo "</div>\n";

?>
