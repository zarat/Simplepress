<?php

/**
 * @author Manuel Zarat
 * @license http://opensource.org/licenses/MIT
 * 
 */ 

echo "<div class='content'>\n";

if($item = $system->single(array('type'=>$system->request('type'),'id'=>$system->request('id')))) { 
    echo "<div class ='content-item-head'>" . $item['title'] . "</div>\n";
    echo "<div class ='content-item-body'>" . html_entity_decode($item['content']) . "</div>\n";
} else {
    echo "<div class ='content-item-head'>" . $system->_t('no_items_to_display') . "</div>\n";
}

if(isset($item['attachment'])) { echo "<img src='" . $item['attachment'] . "'>"; }

echo "</div>\n";

//include "sidebar.php";

?>
