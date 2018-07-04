<?php

/**
 * @author Manuel Zarat 
 */

echo "<div class='sp-sidebar-item'>";
    echo "<div class='sp-sidebar-item-head'>Suche</div>";
    echo "<div class='sp-sidebar-item-box'>\n";
        echo "<div class='sp-sidebar-item-box-body'><div class='container'><form><input type='text' name='search'></form></div></div>\n";
    echo "</div>\n";
echo "</div>";

if( $categories = $this->terms( 'category' ) ) {
echo "<div class='sp-sidebar-item'>";
    echo "<div class='sp-sidebar-item-head'>Kategorien</div>";
    echo "<div class='sp-sidebar-item-box'>\n";
    foreach( $categories as $category ) { echo "<div class='sp-sidebar-item-box-head'><a href='../?category=$category[id]'>$category[name]</a></div>"; }
    echo "</div>\n";
echo "</div>";
}

?>
