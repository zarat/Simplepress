<?php

$menu = new menu();
$menu->config(array('id' => 1));
$menu->html();

$item = $system->single($type=$system->the_querystring('type'),$id=$system->the_querystring('id')); 

echo "<div class='content'>";

echo "<div class ='content-item-head'>" . $item['title'] . "</div>";
echo "<div class ='content-item-body'>" . html_entity_decode($item['content']) . "</div>";

echo "</div>";

include "sidebar.php";

?>
