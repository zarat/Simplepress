<?php

/**
 * @author Manuel Zarat
 * 
 */

echo "<div class='content'>\n\n";

$conf = array('select' => '*','from' => 'object', 'where' => 'type="post" AND status=1 ORDER BY id DESC');
if($r = $system->archive($conf)) {
foreach($r as $item) {

echo "\t<div class='content-item'>\n";
    echo "\t\t<div class='content-item-head'><a href='../?type=$item[type]&id=$item[id]'>" . $item['title'] . "</a></div>\n";
    echo "\t\t<div class='content-item-body'>" . substr(strip_tags(html_entity_decode($item['content'])),0,150) . "\n\t\t</div>\n";
echo "\t</div>\n\n";

}
} else {
    echo "<div class ='content-item-head'>" . $system->_t('no_items_to_display') . "</div>\n";
}

echo "</div>\n\n";

?>
