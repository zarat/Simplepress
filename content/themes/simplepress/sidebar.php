<?php

/**
 * @author Manuel Zarat
 * 
 */
 
$conf = array('select' => 'id,title','from' => 'object','where' => 'type="category"');

echo "<div class='sp-sidebar'>\n";
    echo "<div class='sp-sidebar-item'>";
        echo "<div class='sp-sidebar-item-head'>Kategorien</div>";
        foreach($system->archive($conf) as $cat) {
            echo "<div class='sp-sidebar-item-box'>\n<div class='sp-sidebar-item-box-head'><a href='../?type=category&id=$cat[id]'>$cat[title]</a></div>\n</div>\n";
        }
    echo "</div>";
echo "</div>\n";

?>
