<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * @license http://opensource.org/licenses/MIT
 * 
 */

$menu = new menu();
$menu->config(array('id' => 1));
$menu->html();

echo "<div class='content'>\n\n";

$conf = array('select' => '*','from' => 'object', 'where' => 'type="post" ORDER BY id DESC');

foreach($system->archive($conf) as $item) {

echo "\t<div class='content-item'>\n";
    echo "\t\t<div class='content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title'] . "</a></div>\n";
    echo "\t\t<div class='content-item-body'>" . substr(strip_tags(html_entity_decode($item['content'])),0,150) . "\n\t\t</div>\n";
echo "\t</div>\n\n";

}

echo "</div>";

include "sidebar.php";

?>
