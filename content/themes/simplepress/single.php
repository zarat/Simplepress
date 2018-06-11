<?php

/**
 * @author Manuel Zarat
 */ 

echo "<div class ='sp-content-item'>\n";
    echo "<div class ='sp-content-item-head'>" . $item['title'] . "</div>\n";
    echo "<div class ='sp-content-item-head-secondary'>";
    echo date("d.m.Y",$item['date']);
    if( $this->auth() ) {
        echo " - <a href=\"../admin/?page=item_modify&id=$item[id]\">bearbeiten</a>";
    }
    echo "</div>\n";
    echo "<div class ='sp-content-item-body'>" . $item['content'] . "</div>\n";
echo "</div>\n";

?>
