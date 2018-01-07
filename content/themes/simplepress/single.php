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

$item = $system->single($system->request('type'),$system->request('id')); 

echo "<div class='content'>\n";

if($item) { 
    echo "<div class ='content-item-head'>" . $item['title'] . "</div>\n";
    echo "<div class ='content-item-body'>" . html_entity_decode($item['content']) . "</div>\n";
} else {
    echo "<div class ='content-item-head'>Sorry, but there is no such item</div>\n";
}

echo "</div>\n";

include "sidebar.php";

?>
