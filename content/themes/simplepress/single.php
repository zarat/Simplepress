<?php

/**
 * @author Manuel Zarat
 */ 

echo "<div class ='sp-content-item'>\n";
    echo "<div class ='sp-content-item-head'>" . $item['title'] . "</div>\n";
    echo "<div class ='sp-content-item-head-secondary'>" . date("d.m.y",$item['date']) . "</div>\n";
    echo "<div class ='sp-content-item-body'>" . $item['content'] . "</div>\n";
echo "</div>\n";

?>
