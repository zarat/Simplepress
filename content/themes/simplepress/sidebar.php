<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * @license http://opensource.org/licenses/MIT
 * 
 */

echo "<div class='sidebar'>";

$conf = array('select' => 'id,title','from' => 'object','where' => 'type="category"');

echo "<ul>";
foreach($system->archive($conf) as $cat) {
    echo "<li><a href='../?type=category&id=$cat[id]'>$cat[title]</a></li>";
}
echo "</ul>";

echo "</div>";

?>
