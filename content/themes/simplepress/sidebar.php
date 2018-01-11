<?php

/**
 * @author Manuel Zarat
 * 
 */
 
//echo "<form method='get' action=''><input type='hidden' name='type' value='search'><input type='text' name='id'></form>";
 
$conf = array('select' => 'id,title','from' => 'object','where' => 'type="category"');
echo "<div class='sidebar'>\n";
    echo "<div class='sidebar-item'>";
        echo "<div class='sidebar-item-head'>Kategorien</div>";
        foreach($system->archive($conf) as $cat) {
            echo "<div class='sidebar-item-box'>\n<div class='sidebar-item-box-head'><a href='../?type=category&id=$cat[id]'>$cat[title]</a></div>\n</div>\n";
        }
    echo "</div>";
echo "</div>\n";

?>
