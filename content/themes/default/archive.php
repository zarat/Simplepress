<?php

$menu = new menu();
$menu->config(array('id' => 1));
$menu->html();

echo "<div class='content'>";

foreach($system->archive($what="*",$from="object",$where="type='post' AND ".$system->the_querystring('type') . "=" . $system->the_querystring('id') . " ORDER BY id DESC") as $item) {

    echo "<div class='content-item'>";
    echo "<div class='content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title'] . "</a></div>";
    echo "<div class='content-item-body'>" . substr(strip_tags(html_entity_decode($item['content'])),0,150) . "</div>";
    echo "</div>";
}

echo "</div>";

include "sidebar.php";

?>
