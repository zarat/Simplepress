<?php

/**
 * @author Manuel Zarat
 * 
 */ 

echo "<div class='sp-content'>\n";

echo "<div class ='sp-content-item'>\n";
echo "<div class ='sp-content-item-head'>" . $item['title'] . "</div>\n";
echo "<div class ='sp-content-item-body'>" . $item['content'] . "</div>\n";
echo "</div>\n";

if(isset($item['attachment'])) { echo "<img src='" . $item['attachment'] . "'>"; }

echo "</div>\n";

?>
