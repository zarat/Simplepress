<?php

$menu = new menu();
$menu->config(array('id' => 1));
$menu->html();

$page = $system->single($type=$system->the_querystring('type'),$id=$system->the_querystring('id'));

echo "<div class='content'>";

if($page) { 
    echo "<div class ='content-item-head'>" . $page['title'] . "</div>";
    echo "<div class ='content-item-body'>" . html_entity_decode($page['content']) . "</div>";
} else {
    echo "<div class ='content-item-head'>Sorry, but there is " . $page['error'] . "</div>";
}

echo "</div>";

include "sidebar.php";

?>
