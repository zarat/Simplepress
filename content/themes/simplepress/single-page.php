<?php

/**
 * @author Manuel Zarat
 * @date 05.01.2018
 * @license http://opensource.org/licenses/MIT
 * 
 */

$item = $system->single(array('type'=>$system->request('type'),'id'=>$system->request('id'))); 

echo "<div class='content'>\n";

if($item) { 
    echo "<div class ='content-item-head'>" . $item['title'] . "</div>\n";
    echo "<div class ='content-item-body'>" . html_entity_decode($item['content']) . "</div>\n";
} else {
    echo "<div class ='content-item-head'>" . $system->_t('no_items_to_display') . "</div>\n";
}

echo "</div>\n";

//include "sidebar.php";

?>
