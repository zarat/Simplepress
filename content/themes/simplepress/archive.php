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

echo "<div class='content'>\n";

$config = array('select' => '*','from' => 'object','where' => "type='post' AND ".$system->request('type') . "=" . $system->request('id') . " ORDER BY id DESC");

if($system->archive($config)) {
    foreach($system->archive($config) as $item) {
        echo "<div class='content-item'>\n";
        echo "<div class='content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title'] . "</a></div>\n";
        echo "<div class='content-item-body'>" . substr(strip_tags(html_entity_decode($item['content'])),0,150) . "</div>\n";
        echo "</div>\n";
    }
} else {
    echo "<div class ='content-item-head'>Sorry, but there is no such item</div>\n";
}

echo "</div>\n";

include "sidebar.php";

?>
