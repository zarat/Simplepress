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

$cat_query = "select * from term WHERE taxonomy_id=(SELECT id FROM term_taxonomy WHERE taxonomy='category')";
$cats = $this->fetch_all_assoc( $this->query( $cat_query ) );
echo "<div class='sp-sidebar-item'>";
    echo "<div class='sp-sidebar-item-head'>Kategorien</div>";
    echo "<div class='sp-sidebar-item-box'>\n";
    foreach($cats as $cat) {
        echo "<div class='sp-sidebar-item-box-body'><a href='../?category=$cat[id]'>$cat[name]</a></div>";
    }
    echo "</div>\n";
echo "</div>";

?>
